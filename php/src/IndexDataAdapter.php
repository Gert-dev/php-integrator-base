<?php

namespace PhpIntegrator;

/**
 * Adapts and resolves data from the index as needed to receive an appropriate output data format.
 */
class IndexDataAdapter
{
    /**
     * The storage to use for accessing index data.
     *
     * @var IndexDataAdapter\ProviderInterface
     */
    protected $storage;

    /**
     * Constructor.
     *
     * @param IndexDataAdapter\ProviderInterface $storage
     */
    public function __construct(IndexDataAdapter\ProviderInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Retrieves information about the specified structural element.
     *
     * @param int $id
     *
     * @return array
     */
    public function getStructuralElementInfo($id)
    {
        return $this->resolveStructuralElement(
            $this->storage->getStructuralElementRawInfo($id),
            $this->storage->getParentFqsens($id),
            $this->storage->getStructuralElementRawInterfaces($id),
            $this->storage->getStructuralElementRawTraits($id),
            $this->storage->getStructuralElementRawConstants($id),
            $this->storage->getStructuralElementRawProperties($id),
            $this->storage->getStructuralElementRawMethods($id)
        );
    }

    /**
     * Resolves structural element information from the specified raw data.
     *
     * @param array|\Traversable $element
     * @param array|\Traversable $parentFqsens
     * @param array|\Traversable $interfaces
     * @param array|\Traversable $traits
     * @param array|\Traversable $constants
     * @param array|\Traversable $properties
     * @param array|\Traversable $methods
     *
     * @return array
     */
    public function resolveStructuralElement(
        $element,
        $parentFqsens,
        $interfaces,
        $traits,
        $constants,
        $properties,
        $methods
    ) {
        $result = [
            'class'        => $element['fqsen'],
            'wasFound'     => true,
            'startLine'    => $element['start_line'],
            'name'         => $element['fqsen'],
            'shortName'    => $element['name'],
            'filename'     => $element['path'],
            'isTrait'      => ($element['type_name'] === 'trait'),
            'isClass'      => ($element['type_name'] === 'class'),
            'isInterface'  => ($element['type_name'] === 'interface'),
            'isAbstract'   => !!$element['is_abstract'],
            'parents'      => array_values($parentFqsens),
            'isDeprecated' => !!$element['is_deprecated'],
            'descriptions' => [
                'short' => $element['short_description'],
                'long'  => $element['long_description']
            ],
            'constants'    => [],
            'properties'   => [],
            'methods'      => []
        ];

        // Take all members from the base class as a starting point.
        $baseClassInfo = !empty($parentFqsens) ? $this->getStructuralElementInfo(array_keys($parentFqsens)[0]) : null;

        if ($baseClassInfo) {
            $result['constants']  = $baseClassInfo['constants'];
            $result['properties'] = $baseClassInfo['properties'];
            $result['methods']    = $baseClassInfo['methods'];
        }

        // Append members from direct interfaces to the pool of members. These only supply additional members, but will
        // never overwrite any existing members as they have a lower priority than inherited members.
        foreach ($interfaces as $interface) {
            $interface = $this->getStructuralElementInfo($interface['id']);

            foreach ($interface['constants'] as $constant) {
                if (!isset($result['constants'][$constant['name']])) {
                    $result['constants'][$constant['name']] = $constant;
                }
            }

            foreach ($interface['properties'] as $property) {
                if (!isset($result['properties'][$property['name']])) {
                    $result['properties'][$property['name']] = $property;
                }
            }

            foreach ($interface['methods'] as $method) {
                if (!isset($result['methods'][$method['name']])) {
                    $result['methods'][$method['name']] = $method;
                }
            }
        }

        foreach ($traits as $trait) {
            $trait = $this->getStructuralElementInfo($trait['id']);

            foreach ($trait['constants'] as $constant) {
                $result['constants'][$constant['name']] = array_merge($constant, [
                    'declaringClass' => [
                        'name'            => $element['fqsen'],
                        'filename'        => $element['path'],
                        'startLine'       => $element['start_line'],
                        'isTrait'         => ($element['type_name'] === 'trait'),
                        'isClass'         => ($element['type_name'] === 'class'),
                        'isInterface'     => ($element['type_name'] === 'interface')
                    ]
                ]);
            }

            foreach ($trait['properties'] as $property) {
                $inheritedData = [];
                $existingProperty = null;

                if (isset($result['properties'][$property['name']])) {
                    $existingProperty = $result['properties'][$property['name']];

                    if ($this->isInheritingDocumentation($property)) {
                        $inheritedData = $this->extractInheritedPropertyInfo($existingProperty);
                    }
                }

                $resultingProperty = array_merge($property, $inheritedData, [
                    'declaringClass' => [
                        'name'            => $element['fqsen'],
                        'filename'        => $element['path'],
                        'startLine'       => $element['start_line'],
                        'isTrait'         => ($element['type_name'] === 'trait'),
                        'isClass'         => ($element['type_name'] === 'class'),
                        'isInterface'     => ($element['type_name'] === 'interface')
                    ]
                ]);

                if ($existingProperty) {
                    $resultingProperty['descriptions']['long'] = $this->resolveInheritDoc(
                        $resultingProperty['descriptions']['long'],
                        $existingProperty['descriptions']['long']
                    );
                }

                $result['properties'][$property['name']] = $resultingProperty;
            }

            foreach ($trait['methods'] as $method) {
                $inheritedData = [];
                $existingMethod = null;

                if (isset($result['methods'][$method['name']])) {
                    $existingMethod = $result['methods'][$method['name']];

                    if ($this->isInheritingDocumentation($method)) {
                        $inheritedData = $this->extractInheritedMethodInfo($existingMethod);
                    }
                }

                $resultingMethod = array_merge($method, $inheritedData, [
                    'declaringClass' => [
                        'name'            => $element['fqsen'],
                        'filename'        => $element['path'],
                        'startLine'       => $element['start_line'],
                        'isTrait'         => ($element['type_name'] === 'trait'),
                        'isClass'         => ($element['type_name'] === 'class'),
                        'isInterface'     => ($element['type_name'] === 'interface')
                    ]
                ]);

                if ($existingMethod) {
                    $resultingMethod['descriptions']['long'] = $this->resolveInheritDoc(
                        $resultingMethod['descriptions']['long'],
                        $existingMethod['descriptions']['long']
                    );
                }

                $result['methods'][$method['name']] = $resultingMethod;
            }
        }

        foreach ($constants as $rawConstantData) {
            $result['constants'][$rawConstantData['name']] = array_merge($this->getConstantInfo($rawConstantData), [
                'declaringClass' => [
                    'name'            => $element['fqsen'],
                    'filename'        => $element['path'],
                    'startLine'       => $element['start_line'],
                    'isTrait'         => ($element['type_name'] === 'trait'),
                    'isClass'         => ($element['type_name'] === 'class'),
                    'isInterface'     => ($element['type_name'] === 'interface')
                ],

                'declaringStructure' => [
                    'name'            => $element['fqsen'],
                    'filename'        => $element['path'],
                    'startLine'       => $element['start_line'],
                    'isTrait'         => ($element['type_name'] === 'trait'),
                    'isClass'         => ($element['type_name'] === 'class'),
                    'isInterface'     => ($element['type_name'] === 'interface'),
                    'startLineMember' => $rawConstantData['start_line']
                ]
            ]);
        }

        foreach ($properties as $rawPropertyData) {
            $inheritedData = [];
            $existingProperty = null;
            $overriddenPropertyData = null;

            $property = $this->getPropertyInfo($rawPropertyData);

            if (isset($result['properties'][$property['name']])) {
                $existingProperty = $result['properties'][$property['name']];

                $overriddenPropertyData = [
                    'declaringClass'     => $existingProperty['declaringClass'],
                    'declaringStructure' => $existingProperty['declaringStructure'],
                    'startLine'          => $existingProperty['startLine']
                ];

                if ($this->isInheritingDocumentation($property)) {
                    $inheritedData = $this->extractInheritedPropertyInfo($existingProperty);
                }
            }

            $resultingProperty = array_merge($property, $inheritedData, [
                'override'       => $overriddenPropertyData,
                'implementation' => null,

                'declaringClass' => [
                    'name'            => $element['fqsen'],
                    'filename'        => $element['path'],
                    'startLine'       => $element['start_line'],
                    'isTrait'         => ($element['type_name'] === 'trait'),
                    'isClass'         => ($element['type_name'] === 'class'),
                    'isInterface'     => ($element['type_name'] === 'interface')
                ],

                'declaringStructure' => [
                    'name'            => $element['fqsen'],
                    'filename'        => $element['path'],
                    'startLine'       => $element['start_line'],
                    'isTrait'         => ($element['type_name'] === 'trait'),
                    'isClass'         => ($element['type_name'] === 'class'),
                    'isInterface'     => ($element['type_name'] === 'interface'),
                    'startLineMember' => $rawPropertyData['start_line']
                ]
            ]);

            if ($resultingProperty['return']['type'] === 'self') {
                $resultingProperty['return']['resolvedType'] = $element['fqsen'];
            }

            if ($existingProperty) {
                $resultingProperty['descriptions']['long'] = $this->resolveInheritDoc(
                    $resultingProperty['descriptions']['long'],
                    $existingProperty['descriptions']['long']
                );
            }

            $result['properties'][$property['name']] = $resultingProperty;
        }

        foreach ($methods as $rawMethodData) {
            $inheritedData = [];
            $existingMethod = null;
            $overriddenMethodData = null;
            $implementedMethodData = null;

            $method = $this->getMethodInfo($rawMethodData);

            if (isset($result['methods'][$method['name']])) {
                $existingMethod = $result['methods'][$method['name']];

                if ($existingMethod['declaringStructure']['isInterface']) {
                    $implementedMethodData = [
                        'declaringClass'     => $existingMethod['declaringClass'],
                        'declaringStructure' => $existingMethod['declaringStructure'],
                        'startLine'          => $existingMethod['startLine']
                    ];
                } else {
                    $overriddenMethodData = [
                        'declaringClass'     => $existingMethod['declaringClass'],
                        'declaringStructure' => $existingMethod['declaringStructure'],
                        'startLine'          => $existingMethod['startLine']
                    ];
                }

                if ($this->isInheritingDocumentation($method)) {
                    $inheritedData = $this->extractInheritedMethodInfo($existingMethod);
                }
            }

            $resultingMethod = array_merge($method, $inheritedData, [
                'override'       => $overriddenMethodData,
                'implementation' => $implementedMethodData,

                'declaringClass' => [
                    'name'            => $element['fqsen'],
                    'filename'        => $element['path'],
                    'startLine'       => $element['start_line'],
                    'isTrait'         => ($element['type_name'] === 'trait'),
                    'isClass'         => ($element['type_name'] === 'class'),
                    'isInterface'     => ($element['type_name'] === 'interface')
                ],

                'declaringStructure' => [
                    'name'            => $element['fqsen'],
                    'filename'        => $element['path'],
                    'startLine'       => $element['start_line'],
                    'isTrait'         => ($element['type_name'] === 'trait'),
                    'isClass'         => ($element['type_name'] === 'class'),
                    'isInterface'     => ($element['type_name'] === 'interface'),
                    'startLineMember' => $rawMethodData['start_line']
                ]
            ]);

            if ($resultingMethod['return']['type'] === 'self') {
                $resultingMethod['return']['resolvedType'] = $element['fqsen'];
            }

            if ($existingMethod) {
                $resultingMethod['descriptions']['long'] = $this->resolveInheritDoc(
                    $resultingMethod['descriptions']['long'],
                    $existingMethod['descriptions']['long']
                );
            }

            $result['methods'][$method['name']] = $resultingMethod;
        }

        // Resolve return types.
        foreach ($result['methods'] as $name => &$method) {
            if ($method['return']['type'] === '$this' || $method['return']['type'] === 'static') {
                $method['return']['resolvedType'] = $element['fqsen'];
            } elseif (!isset($method['return']['resolvedType'])) {
                $method['return']['resolvedType'] = $method['return']['type'];
            }
        }

        foreach ($result['properties'] as $name => &$property) {
            if ($property['return']['type'] === '$this' || $property['return']['type'] === 'static') {
                $property['return']['resolvedType'] = $element['fqsen'];
            } elseif (!isset($property['return']['resolvedType'])) {
                $property['return']['resolvedType'] = $property['return']['type'];
            }
        }

        return $result;
    }

    /**
     * @param array $rawInfo
     *
     * @return array
     */
    public function getMethodInfo(array $rawInfo)
    {
        return array_merge($this->getFunctionInfo($rawInfo), [
            'isMethod'           => true,
            'isMagic'            => !!$rawInfo['is_magic'],
            'isPublic'           => ($rawInfo['access_modifier'] === 'public'),
            'isProtected'        => ($rawInfo['access_modifier'] === 'protected'),
            'isPrivate'          => ($rawInfo['access_modifier'] === 'private'),
            'isStatic'           => !!$rawInfo['is_static'],

            'override'           => null,
            'implementation'     => null,

            'declaringClass'     => null,
            'declaringStructure' => null
        ]);
    }

    /**
     * @param array $rawInfo
     *
     * @return array
     */
    public function getFunctionInfo(array $rawInfo)
    {
        $rawParameters = $this->storage->getFunctionParameters($rawInfo['id']);

        $optionals = [];
        $parameters = [];

        foreach ($rawParameters as $rawParameter) {
            $name = '';

            if ($rawParameter['is_reference']) {
                $name .= '&';
            }

            $name .= '$' . $rawParameter['name'];

            if ($rawParameter['is_variadic']) {
                $name .= '...';
            }

            if ($rawParameter['is_optional']) {
                $optionals[] = $name;
            } else {
                $parameters[] = $name;
            }
        }

        $throws = $this->storage->getFunctionThrows($rawInfo['id']);

        $throwsAssoc = [];

        foreach ($throws as $throws) {
            $throwsAssoc[$throws['type']] = $throws['description'];
        }

        return [
            'name'          => $rawInfo['name'],
            'isBuiltin'     => false,
            'startLine'     => $rawInfo['start_line'],
            'filename'      => $rawInfo['path'],

            'parameters'    => $parameters,
            'optionals'     => $optionals,
            'throws'        => $throwsAssoc,
            'isDeprecated'  => !!$rawInfo['is_deprecated'],
            'hasDocblock'   => !!$rawInfo['has_docblock'],

            'descriptions'  => [
                'short' => $rawInfo['short_description'],
                'long'  => $rawInfo['long_description']
            ],

            'return'        => [
                'type'         => $rawInfo['return_type'],
                'description'  => $rawInfo['return_description']
            ]
        ];
    }

    /**
     * @param array $rawInfo
     *
     * @return array
     */
    public function getPropertyInfo(array $rawInfo)
    {
        return [
            'name'               => $rawInfo['name'],
            'isProperty'         => true,
            'startLine'          => $rawInfo['start_line'],
            'isMagic'            => !!$rawInfo['is_magic'],
            'isPublic'           => ($rawInfo['access_modifier'] === 'public'),
            'isProtected'        => ($rawInfo['access_modifier'] === 'protected'),
            'isPrivate'          => ($rawInfo['access_modifier'] === 'private'),
            'isStatic'           => !!$rawInfo['is_static'],
            'isDeprecated'       => !!$rawInfo['is_deprecated'],
            'hasDocblock'        => !!$rawInfo['has_docblock'],

            'descriptions'  => [
                'short' => $rawInfo['short_description'],
                'long'  => $rawInfo['long_description']
            ],

            'return'        => [
                'type'         => $rawInfo['return_type'],
                'description'  => $rawInfo['return_description']
            ],

            'override'           => null,
            'declaringClass'     => null,
            'declaringStructure' => null
        ];
    }

    /**
     * @param array $rawInfo
     *
     * @return array
     */
    public function getConstantInfo(array $rawInfo)
    {
        return [
            'name'         => $rawInfo['name'],
            'isBuiltin'    => !!$rawInfo['is_builtin'],
            'isPublic'     => true,
            'isProtected'  => false,
            'isPrivate'    => false,
            'isStatic'     => true,
            'isDeprecated' => !!$rawInfo['is_deprecated'],
            'hasDocblock'  => !!$rawInfo['has_docblock'],

            'descriptions'  => [
                'short' => $rawInfo['short_description'],
                'long'  => $rawInfo['long_description']
            ],

            'return'        => [
                'type'         => $rawInfo['return_type'],
                'description'  => $rawInfo['return_description']
            ],
        ];
    }

    /**
     * Returns a boolean indicating whether the specified item will inherit documentation from a parent item (if
     * present).
     *
     * @param array $processedData
     *
     * @return bool
     */
    protected function isInheritingDocumentation(array $processedData)
    {
        // Ticket #86 - Add support for inheriting the entire docblock from the parent if the current docblock contains
        // nothing but these tags. Note that, according to draft PSR-5 and phpDocumentor's implementation, this is
        // incorrect. However, some large frameworks (such as Symfony) use this and it thus makes life easier for many
        // developers, hence this workaround.
        return !$processedData['hasDocblock'] || in_array($processedData['descriptions']['short'], [
            '{@inheritdoc}', '{@inheritDoc}'
        ]);
    }

    /**
     * Resolves the inheritDoc tag for the specified description.
     *
     * Note that according to phpDocumentor this only works for the long description (not the so-called 'summary' or
     * short description).
     *
     * @param string $description
     * @param string $parentDescription
     *
     * @return string
     */
    protected function resolveInheritDoc($description, $parentDescription)
    {
        return str_replace(DocParser::INHERITDOC, $parentDescription, $description);
    }

    /**
     * Extracts data from the specified (processed, i.e. already in the output format) property that is inheritable.
     *
     * @param array $processedData
     *
     * @return array
     */
    protected function extractInheritedPropertyInfo(array $processedData)
    {
        return array_filter($processedData, function ($key) {
            return in_array($key, [
                'isDeprecated',
                'descriptions',
                'return'
            ]);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Extracts data from the specified (processed, i.e. already in the output format) method that is inheritable.
     *
     * @param array $processedData
     *
     * @return array
     */
    protected function extractInheritedMethodInfo(array $processedData)
    {
        return array_filter($processedData, function ($key) {
            return in_array($key, [
                'isDeprecated',
                'descriptions',
                'return',
                'parameters',
                'optionals',
                'throws'
            ]);
        }, ARRAY_FILTER_USE_KEY);
    }
}
