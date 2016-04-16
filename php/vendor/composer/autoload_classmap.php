<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'A\\AncestorClass' => $baseDir . '/tests/Application/Command/ClassInfoTest/ClassInheritance.php',
    'A\\AnotherChildClass' => $baseDir . '/tests/Application/Command/ClassInfoTest/ClassDocblockInheritance.php',
    'A\\B' => $baseDir . '/tests/Application/Command/GlobalConstantsTest/GlobalConstants.php',
    'A\\Base' => $baseDir . '/tests/Application/Command/SemanticLintTest/DocblockCorrectnessMissingDocumentation.php',
    'A\\BaseClass' => $baseDir . '/tests/Application/Command/ClassInfoTest/ClassInheritance.php',
    'A\\BaseInterface' => $baseDir . '/tests/Application/Command/ClassInfoTest/InterfaceImplementation.php',
    'A\\BaseTrait' => $baseDir . '/tests/Application/Command/ClassInfoTest/TraitUsage.php',
    'A\\C' => $baseDir . '/tests/Application/Command/SemanticLintTest/DocblockCorrectnessMissingDocumentation.php',
    'A\\ChildClass' => $baseDir . '/tests/Application/Command/ClassInfoTest/ClassDocblockInheritance.php',
    'A\\FirstClass' => $baseDir . '/tests/Application/Command/ClassListTest/ClassList.php',
    'A\\FirstInterface' => $baseDir . '/tests/Application/Command/ClassInfoTest/InterfaceImplementation.php',
    'A\\FirstTrait' => $baseDir . '/tests/Application/Command/ClassInfoTest/TraitUsage.php',
    'A\\Foo' => $baseDir . '/tests/Application/Command/AvailableVariablesTest/ClassMethodScope.php',
    'A\\MissingDocumentation' => $baseDir . '/tests/Application/Command/SemanticLintTest/DocblockCorrectnessMissingDocumentation.php',
    'A\\ParentClass' => $baseDir . '/tests/Application/Command/ClassInfoTest/ClassDocblockInheritance.php',
    'A\\ParentInterface' => $baseDir . '/tests/Application/Command/ClassInfoTest/MethodImplementation.php',
    'A\\ParentInterface1' => $baseDir . '/tests/Application/Command/ClassInfoTest/InterfaceMultipleInheritance.php',
    'A\\ParentInterface2' => $baseDir . '/tests/Application/Command/ClassInfoTest/InterfaceMultipleInheritance.php',
    'A\\ParentTrait' => $baseDir . '/tests/Application/Command/ClassInfoTest/MethodOverride.php',
    'A\\SecondClass' => $baseDir . '/tests/Application/Command/ClassListTest/ClassList.php',
    'A\\SecondInterface' => $baseDir . '/tests/Application/Command/ClassInfoTest/InterfaceImplementation.php',
    'A\\SecondTrait' => $baseDir . '/tests/Application/Command/ClassInfoTest/TraitUsage.php',
    'A\\SimpleClass' => $baseDir . '/tests/Application/Command/ClassInfoTest/SimpleClass.php',
    'A\\Test' => $baseDir . '/tests/Application/Command/SemanticLintTest/UnknownClassesSingleNamespace.php',
    'A\\TestClass' => $baseDir . '/tests/Application/Command/ClassInfoTest/ClassConstant.php',
    'A\\TestInterface' => $baseDir . '/tests/Application/Command/ClassInfoTest/InterfaceMultipleInheritance.php',
    'A\\TestTrait' => $baseDir . '/tests/Application/Command/ClassInfoTest/MethodDocblockInheritance.php',
    'A\\childClass' => $baseDir . '/tests/Application/Command/ClassInfoTest/ResolveSpecialTypes.php',
    'B' => $baseDir . '/tests/Application/Command/DeduceTypeTest/GlobalFunction.php',
    'Bar' => $baseDir . '/tests/Application/Command/DeduceTypeTest/Clone.php',
    'ParentClass' => $baseDir . '/tests/Application/Command/DeduceTypeTest/Parent.php',
    'PhpIntegrator\\Application' => $baseDir . '/src/Application.php',
    'PhpIntegrator\\Application\\Command' => $baseDir . '/src/Application/Command.php',
    'PhpIntegrator\\Application\\CommandInterface' => $baseDir . '/src/Application/CommandInterface.php',
    'PhpIntegrator\\Application\\Command\\AvailableVariables' => $baseDir . '/src/Application/Command/AvailableVariables.php',
    'PhpIntegrator\\Application\\Command\\AvailableVariablesTest' => $baseDir . '/tests/Application/Command/AvailableVariablesTest.php',
    'PhpIntegrator\\Application\\Command\\AvailableVariables\\QueryingVisitor' => $baseDir . '/src/Application/Command/AvailableVariables/QueryingVisitor.php',
    'PhpIntegrator\\Application\\Command\\ClassInfo' => $baseDir . '/src/Application/Command/ClassInfo.php',
    'PhpIntegrator\\Application\\Command\\ClassInfoTest' => $baseDir . '/tests/Application/Command/ClassInfoTest.php',
    'PhpIntegrator\\Application\\Command\\ClassList' => $baseDir . '/src/Application/Command/ClassList.php',
    'PhpIntegrator\\Application\\Command\\ClassListTest' => $baseDir . '/tests/Application/Command/ClassListTest.php',
    'PhpIntegrator\\Application\\Command\\ClassList\\ProxyProvider' => $baseDir . '/src/Application/Command/ClassList/ProxyProvider.php',
    'PhpIntegrator\\Application\\Command\\DeduceType' => $baseDir . '/src/Application/Command/DeduceType.php',
    'PhpIntegrator\\Application\\Command\\DeduceTypeTest' => $baseDir . '/tests/Application/Command/DeduceTypeTest.php',
    'PhpIntegrator\\Application\\Command\\DeduceType\\IndexDataAdapterProvider' => $baseDir . '/src/Application/Command/DeduceType/IndexDataAdapterProvider.php',
    'PhpIntegrator\\Application\\Command\\GlobalConstants' => $baseDir . '/src/Application/Command/GlobalConstants.php',
    'PhpIntegrator\\Application\\Command\\GlobalConstantsTest' => $baseDir . '/tests/Application/Command/GlobalConstantsTest.php',
    'PhpIntegrator\\Application\\Command\\GlobalFunctions' => $baseDir . '/src/Application/Command/GlobalFunctions.php',
    'PhpIntegrator\\Application\\Command\\GlobalFunctionsTest' => $baseDir . '/tests/Application/Command/GlobalFunctionsTest.php',
    'PhpIntegrator\\Application\\Command\\Reindex' => $baseDir . '/src/Application/Command/Reindex.php',
    'PhpIntegrator\\Application\\Command\\ResolveType' => $baseDir . '/src/Application/Command/ResolveType.php',
    'PhpIntegrator\\Application\\Command\\ResolveTypeTest' => $baseDir . '/tests/Application/Command/ResolveTypeTest.php',
    'PhpIntegrator\\Application\\Command\\SemanticLint' => $baseDir . '/src/Application/Command/SemanticLint.php',
    'PhpIntegrator\\Application\\Command\\SemanticLintTest' => $baseDir . '/tests/Application/Command/SemanticLintTest.php',
    'PhpIntegrator\\Application\\Command\\SemanticLint\\AnalyzerInterface' => $baseDir . '/src/Application/Command/SemanticLint/AnalyzerInterface.php',
    'PhpIntegrator\\Application\\Command\\SemanticLint\\DocblockCorrectnessAnalyzer' => $baseDir . '/src/Application/Command/SemanticLint/DocblockCorrectnessAnalyzer.php',
    'PhpIntegrator\\Application\\Command\\SemanticLint\\UnknownClassAnalyzer' => $baseDir . '/src/Application/Command/SemanticLint/UnknownClassAnalyzer.php',
    'PhpIntegrator\\Application\\Command\\SemanticLint\\UnusedUseStatementAnalyzer' => $baseDir . '/src/Application/Command/SemanticLint/UnusedUseStatementAnalyzer.php',
    'PhpIntegrator\\Application\\Command\\SemanticLint\\Visitor\\ClassUsageFetchingVisitor' => $baseDir . '/src/Application/Command/SemanticLint/Visitor/ClassUsageFetchingVisitor.php',
    'PhpIntegrator\\Application\\Command\\SemanticLint\\Visitor\\DocblockClassUsageFetchingVisitor' => $baseDir . '/src/Application/Command/SemanticLint/Visitor/DocblockClassUsageFetchingVisitor.php',
    'PhpIntegrator\\Application\\Command\\SemanticLint\\Visitor\\UseStatementFetchingVisitor' => $baseDir . '/src/Application/Command/SemanticLint/Visitor/UseStatementFetchingVisitor.php',
    'PhpIntegrator\\Application\\Command\\VariableType' => $baseDir . '/src/Application/Command/VariableType.php',
    'PhpIntegrator\\Application\\Command\\VariableTypeTest' => $baseDir . '/tests/Application/Command/VariableTypeTest.php',
    'PhpIntegrator\\Application\\Command\\VariableType\\QueryingVisitor' => $baseDir . '/src/Application/Command/VariableType/QueryingVisitor.php',
    'PhpIntegrator\\Application\\Command\\Visitor\\ScopeLimitingVisitor' => $baseDir . '/src/Application/Command/Visitor/ScopeLimitingVisitor.php',
    'PhpIntegrator\\DocParser' => $baseDir . '/src/DocParser.php',
    'PhpIntegrator\\DocblockAnalyzer' => $baseDir . '/src/DocblockAnalyzer.php',
    'PhpIntegrator\\IndexDataAdapter' => $baseDir . '/src/IndexDataAdapter.php',
    'PhpIntegrator\\IndexDataAdapter\\ProviderInterface' => $baseDir . '/src/IndexDataAdapter/ProviderInterface.php',
    'PhpIntegrator\\IndexDatabase' => $baseDir . '/src/IndexDatabase.php',
    'PhpIntegrator\\IndexStorageItemEnum' => $baseDir . '/src/IndexStorageItemEnum.php',
    'PhpIntegrator\\IndexedTest' => $baseDir . '/tests/IndexedTest.php',
    'PhpIntegrator\\Indexer' => $baseDir . '/src/Indexer.php',
    'PhpIntegrator\\Indexer\\DependencyFetchingVisitor' => $baseDir . '/src/Indexer/DependencyFetchingVisitor.php',
    'PhpIntegrator\\Indexer\\IndexingFailedException' => $baseDir . '/src/Indexer/IndexingFailedException.php',
    'PhpIntegrator\\Indexer\\OutlineIndexingVisitor' => $baseDir . '/src/Indexer/OutlineIndexingVisitor.php',
    'PhpIntegrator\\Indexer\\StorageInterface' => $baseDir . '/src/Indexer/StorageInterface.php',
    'PhpIntegrator\\Indexer\\UseStatementFetchingVisitor' => $baseDir . '/src/Indexer/UseStatementFetchingVisitor.php',
    'PhpIntegrator\\TypeAnalyzer' => $baseDir . '/src/TypeAnalyzer.php',
    'PhpIntegrator\\TypeResolver' => $baseDir . '/src/TypeResolver.php',
    'PhpIntegrator\\TypeResolverTest' => $baseDir . '/tests/TypeResolverTest.php',
);
