<?php declare(strict_types=1);

use AlanVdb\Html\HtmlDom;
use AlanVdb\Html\HtmlDomElement;
use PHPUnit\Framework\TestCase;

class HtmlDomTest extends TestCase
{
    protected string $html;
    protected HtmlDom $dom;

    protected function setUp(): void
    {
        $this->html = '<div id="container" class="main"><p class="text">Hello World</p><p class="text">Another Text</p></div>';
        $this->dom = new HtmlDom($this->html);
    }

    public function testConstructorHtmlParsingWithEmptyStringCausesValueError(): void
    {
        $this->expectException(ValueError::class);
        $this->expectExceptionMessage("DOMDocument::loadHTML(): Argument #1 (\$source) must not be empty");

        new HtmlDom('');  // Tenter de créer un HtmlDom avec une chaîne vide
    }

    public function testQuerySelectorAllWithXPathError(): void
    {
        $mockXPath = $this->getMockBuilder(\DOMXPath::class)
                          ->disableOriginalConstructor()
                          ->onlyMethods(['query'])
                          ->getMock();
    
        $mockXPath->method('query')->willReturn(false);
    
        $reflection = new \ReflectionClass($this->dom);
        $xpathProperty = $reflection->getProperty('xpath');
        $xpathProperty->setAccessible(true);
        $xpathProperty->setValue($this->dom, $mockXPath);
    
        $result = $this->dom->querySelectorAll('.nonexistent-class');
    
        $this->assertIsArray($result, 'Expected result to be an array.');
        $this->assertCount(0, $result, 'Expected empty array when XPath query fails.');
    }

    public function testGetElementById(): void
    {
        $element = $this->dom->getElementById('container');
        $this->assertInstanceOf(HtmlDomElement::class, $element);
        $this->assertEquals('container', $element->getAttribute('id'));
    }

    public function testGetElementByIdNotFound(): void
    {
        $this->assertNull($this->dom->getElementById('unknown'));
    }

    public function testGetElementsByClassName(): void
    {
        $elements = $this->dom->getElementsByClassName('text');
        $this->assertCount(2, $elements);
        $this->assertEquals('Hello World', $elements[0]->getInnerHtml());
    }

    public function testGetElementsByClassNameNotFound(): void
    {
        $elements = $this->dom->getElementsByClassName('nonexistent');
        $this->assertCount(0, $elements);
    }

    public function testGetElementsByTagName(): void
    {
        $elements = $this->dom->getElementsByTagName('p');
        $this->assertCount(2, $elements);
        $this->assertEquals('Hello World', $elements[0]->getInnerHtml());
    }

    public function testGetElementsByTagNameNotFound(): void
    {
        $elements = $this->dom->getElementsByTagName('nonexistent');
        $this->assertCount(0, $elements);
    }

    public function testQuerySelector(): void
    {
        $element = $this->dom->querySelector('#container');
        $this->assertInstanceOf(HtmlDomElement::class, $element);
        $this->assertEquals('main', $element->getAttribute('class'));
    }

    public function testQuerySelectorInvalidSelector(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Unsupported selector format: @invalid");
        $this->dom->querySelector('@invalid');
    }

    public function testQuerySelectorNotFound(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No element matching selector '.nonexistent' found.");
        $this->dom->querySelector('.nonexistent');
    }

    public function testQuerySelectorAll(): void
    {
        $elements = $this->dom->querySelectorAll('.text');
        $this->assertCount(2, $elements);
        $this->assertEquals('Another Text', $elements[1]->getInnerHtml());
    }

    public function testQuerySelectorAllNotFound(): void
    {
        $elements = $this->dom->querySelectorAll('.nonexistent');
        $this->assertCount(0, $elements);
    }

    public function testQuerySelectorAllReturnsEmptyArrayOnXPathError(): void
    {
        // Injecter un sélecteur qui se convertit en XPath mal formé
        $result = $this->dom->querySelectorAll('invalid-selector');  // Sélecteur invalide qui provoque un XPath incorrect
    
        $this->assertIsArray($result, 'Expected result to be an array.');
        $this->assertCount(0, $result, 'Expected empty array when XPath query fails.');
    }

    public function testQuerySelectorWithInvalidXPath(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Unsupported selector format: //*[");
    
        $this->dom->querySelector('//*[');
    }

    public function testCreateElement(): void
    {
        $newElement = $this->dom->createElement('span');
        $this->assertInstanceOf(HtmlDomElement::class, $newElement);
        $newElement->setAttribute('class', 'highlight');
        $this->assertEquals('highlight', $newElement->getAttribute('class'));
    }

    public function testCreateElementWithText(): void
    {
        $newElement = $this->dom->createElement('span');
        $newElement->getElement()->textContent = 'Text content';
        $this->assertEquals('Text content', $newElement->getInnerHtml());
    }

    public function testCreateElementAndInsertIntoDom(): void
    {
        $newElement = $this->dom->createElement('span');
        $newElement->setAttribute('class', 'highlight');
        $container = $this->dom->getElementById('container');
        $container->appendChild($newElement);

        $this->assertStringContainsString('<span class="highlight"></span>', $container->getInnerHtml());
    }

    public function testCssToXPathTagSelector(): void
    {
        $element = $this->dom->querySelector('p');
        $this->assertInstanceOf(HtmlDomElement::class, $element);
        $this->assertEquals('Hello World', $element->getInnerHtml());
    }
}
