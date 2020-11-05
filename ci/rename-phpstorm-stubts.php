<?php

use Rector\Compiler\Renaming\JetbrainsStubsRenamer;
use Symplify\SmartFileSystem\SmartFileSystem;

require __DIR__ . '/../vendor/autoload.php';

$symfonyStyleFactory = new \Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory();
$symfonyStyle = $symfonyStyleFactory->create();

$jetbrainsStubsRenamer = new JetbrainsStubsRenamer($symfonyStyle, new SmartFileSystem());
$jetbrainsStubsRenamer->renamePhpStormStubs(__DIR__ . '/../vendor/jetbrains/phpstorm-stubs');
