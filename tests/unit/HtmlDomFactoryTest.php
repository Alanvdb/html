<?php declare(strict_types=1);

namespace AlanVdb\Html\Tests\Factory;

use AlanVdb\Html\Factory\HtmlDomFactory;
use AlanVdb\Html\Definition\HtmlDomInterface;
use AlanVdb\Html\HtmlDom;
use PHPUnit\Framework\TestCase;
use ValueError;

class HtmlDomFactoryTest extends TestCase
{
    private HtmlDomFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new HtmlDomFactory();
    }

    public function testCreateHtmlDomWithValidHtml(): void
    {
        $htmlContent = '<div><p>Hello World</p></div>';
        $htmlDom = $this->factory->createHtmlDom($htmlContent);

        $this->assertInstanceOf(HtmlDomInterface::class, $htmlDom);
        $this->assertInstanceOf(HtmlDom::class, $htmlDom);

        $paragraph = $htmlDom->querySelector('p');
        $this->assertNotNull($paragraph);
        $this->assertEquals('Hello World', $paragraph->getInnerHtml());
    }

    public function testCreateHtmlDomWithEmptyString(): void
    {
        $this->expectException(ValueError::class);
        $this->expectExceptionMessageMatches('/Argument #1 \(\$source\) must not be empty/');

        $this->factory->createHtmlDom('');
    }
}
