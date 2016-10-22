<?php
declare(strict_types = 1);
namespace PTS\StaticManager;

use PTS\Tools\CollectionInterface;
use PTS\Tools\NotFoundKeyException;
use Symfony\Component\Asset\PackageInterface;

class StaticManager
{
    /** @var CollectionInterface[] */
    protected $collections;
    /** @var PackageInterface[] */
    protected $packages = [];

    public function __construct(CollectionInterface $emptyCollection)
    {
        $this->collections = [
            'css' => $emptyCollection,
            'jsHeader' => clone $emptyCollection,
            'jsFooter' => clone $emptyCollection,
        ];
    }

    public function getCssSet() : CollectionInterface
    {
        return $this->collections['css'];
    }

    public function getJsHeaderSet() : CollectionInterface
    {
        return $this->collections['jsHeader'];
    }

    public function getJsFooterSet() : CollectionInterface
    {
        return $this->collections['jsFooter'];
    }

    public function drawHeaderScripts() : string
    {
        $collection = $this->getJsHeaderSet();
        return $this->drawScripts($collection);
    }

    public function drawFooterScripts() : string
    {
        $collection = $this->getJsFooterSet();
        return $this->drawScripts($collection);
    }

    protected function drawScripts(CollectionInterface $collection) : string
    {
        $scripts = $collection->getFlatItems(true);

        $result = '';
        foreach ($scripts as $script) {
            $result .= "<script src='" . $script . "'></script>\n";
        }

        return $result;
    }

    public function drawStyles() : string
    {
        $collection = $this->getCssSet();
        $styles = $collection->getFlatItems(true);

        $result = '';
        foreach ($styles as $style) {
            $result .= "<link rel='stylesheet' href='" . $style . "' />\n";
        }

        return $result;
    }

    public function setPackage(string $name, PackageInterface $package)
    {
        $this->packages[$name] = $package;
        return $this;
    }

    /**
     * @param string $name
     * @return PackageInterface
     * @throws NotFoundKeyException
     */
    public function getPackage(string $name) : PackageInterface
    {
        if (!array_key_exists($name, $this->packages)) {
            throw new NotFoundKeyException('Package not found');
        }

        return $this->packages[$name];
    }
}
