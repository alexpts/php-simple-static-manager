<?php
namespace PTS\StaticManager;

use PHPUnit\Framework\TestCase;
use PTS\Tools\Collection;
use PTS\Tools\CollectionInterface;
use PTS\Tools\NotFoundKeyException;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class StaticManagerTest extends TestCase
{
    /** @var StaticManager */
    protected $manager;

    protected function setUp()
    {
        $this->manager = new StaticManager(new Collection);
    }

    public function testGetCssSet(): void
    {
        $css = $this->manager->getCssSet();
        self::assertInstanceOf(CollectionInterface::class, $css);
    }

    public function testGetJsSet(): void
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
    public function testDrawFooterScripts(array $resources, string $expected): void
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
    public function testDrawHeaderScripts(array $resources, string $expected): void
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
    public function testDrawStyles(array $resources, string $expected): void
    {
        $set = $this->manager->getCssSet();
        foreach ($resources as $name => $url) {
            $set->addItem($name, $url);
        }

        $html = $this->manager->drawStyles();
        self::assertEquals($expected, $html);
    }

    public function dataProviderJs(): array
    {
        return [
            [
                ['jquery' => '/jquery.js'],
                "<script src='/jquery.js'></script>\n"
            ],
            [
                ['jquery' => '/jquery.js', 'all' => '/all.js'],
                "<script src='/jquery.js'></script>\n<script src='/all.js'></script>\n"
            ],
            [
                ['jquery' => ['src' => '/jquery.js', 'type' => 'module']],
                "<script src='/jquery.js' type='module'></script>\n"
            ],
        ];
    }

    public function dataProviderCss(): array
    {
        return [
            [
                ['jquery' => '/jquery.css'],
                "<link href='/jquery.css' rel='stylesheet'/>\n"
            ],
            [
                ['jquery' => '/jquery.css', 'all' => '/all.css'],
                "<link href='/jquery.css' rel='stylesheet'/>\n<link href='/all.css' rel='stylesheet'/>\n"
            ],
            [
                ['jquery' => ['href' => '/jquery.css'], 'all' => ['href' => '/all.css', 'rel' => 'less']],
                "<link href='/jquery.css' rel='stylesheet'/>\n<link href='/all.css' rel='less'/>\n"
            ],
        ];
    }

    public function testGetUnknownPackage(): void
    {
        $this->expectException(NotFoundKeyException::class);
        $this->manager->getPackage('badName');
    }

    public function testSePackage(): void
    {
        $package = new Package(new EmptyVersionStrategy);
        $this->manager->setPackage('empty', $package);

        $result = $this->manager->getPackage('empty');

        self::assertEquals($result, $package);
    }
}