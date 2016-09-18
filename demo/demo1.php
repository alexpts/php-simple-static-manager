<?php
use PTS\StaticManager\StaticManager;
use PTS\Tools\Collection;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;

require_once __DIR__ . '/../vendor/autoload.php';

$emptyPackage = new Package(new EmptyVersionStrategy);
$projectPackage = new Package(new StaticVersionStrategy('v1'));

$packages = new Packages(null, [
    'empty' => $emptyPackage,
    'project' => $projectPackage,
]);

$projectAsset = $packages->getPackage('project');
$emptyPackage = $packages->getPackage('empty');

$staticManager = new StaticManager(new Collection);
$css = $staticManager->getCssSet();
$css->addItem('bootstrap', $projectAsset->getUrl('/bootstrap/3.3.6/css/bootstrap.min.css'));
$css->addItem('bootstrap2', $emptyPackage->getUrl('https://yastatic.net/bootstrap/3.3.6/css/bootstrap.min.css'));

$staticManager->drawStyles();

