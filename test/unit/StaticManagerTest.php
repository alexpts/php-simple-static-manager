<?php
namespace PTS\StaticManager;

use PTS\Tools\Collection;
use PTS\Tools\CollectionInterface;

class StaticManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var StaticManager */
    protected $manager;

    protected function setUp()
    {
        $this->manager = new StaticManager(new Collection);
    }

    public function testGetCssSet()
    {
        $css = $this->manager->getCssSet();
        self::assertInstanceOf(CollectionInterface::class, $css);
    }

    public function testGetJsSet()
    {
        $footerJs = $this->manager->getJsFooterSet();
        $headerJs = $this->manager->getJsHeaderSet();

        self::assertInstanceOf(CollectionInterface::class, $headerJs);
        self::assertInstanceOf(CollectionInterface::class, $footerJs);
    }

    /**
     * @param array $resources
     * @param string $expected
     *
     * @dataProvider dataProviderJs
     */
    public function testDrawFooterScripts(array $resources, string $expected)
    {
        $set = $this->manager->getJsFooterSet();
        foreach ($resources as $name => $url) {
            $set->addItem($name, $url);
        }

        $html = $this->manager->drawFooterScripts();
        self::assertEquals($expected, $html);
    }


    /**
     * @param array $resources
     * @param string $expected
     *
     * @dataProvider dataProviderJs
     */
    public function testDrawHeaderScripts(array $resources, string $expected)
    {
        $set = $this->manager->getJsHeaderSet();
        foreach ($resources as $name => $url) {
            $set->addItem($name, $url);
        }

        $html = $this->manager->drawHeaderScripts();
        self::assertEquals($expected, $html);
    }

    /**
     * @param array $resources
     * @param string $expected
     *
     * @dataProvider dataProviderCss
     */
    public function testDrawStyles(array $resources, string $expected)
    {
        $set = $this->manager->getCssSet();
        foreach ($resources as $name => $url) {
            $set->addItem($name, $url);
        }

        $html = $this->manager->drawStyles();
        self::assertEquals($expected, $html);
    }

    public function dataProviderJs()
    {
        return [
            [
                ['jquery' => '/jquery.js'],
                "<script src='/jquery.js'></script>\n"
            ],
            [
                ['jquery' => '/jquery.js', 'all' => '/all.js'],
                "<script src='/jquery.js'></script>\n<script src='/all.js'></script>\n"
            ]
        ];
    }

    public function dataProviderCss()
    {
        return [
            [
                ['jquery' => '/jquery.css'],
                "<link rel='stylesheet' href='/jquery.css' />\n"
            ],
            [
                ['jquery' => '/jquery.css', 'all' => '/all.css'],
                "<link rel='stylesheet' href='/jquery.css' />\n<link rel='stylesheet' href='/all.css' />\n"
            ]
        ];
    }
}