<?php

use AlanVdb\Html\HtmlDom;
use AlanVdb\Html\HtmlDomElement;
use PHPUnit\Framework\TestCase;
use ValueError;

class HtmlDomTest extends TestCase
{
    protected $html;
    protected $dom;

    protected function setUp(): void
    {
        $this->html = '<div id="container" class="main"><p class="text">Hello World</p><p class="text">Another Text</p></div>';
        $this->dom = new HtmlDom($this->html);
    }




public function testConstructorHtmlParsingWithEmptyStringCausesValueError()
{
    $this->expectException(ValueError::class);
    $this->expectExceptionMessage("DOMDocument::loadHTML(): Argument #1 (\$source) must not be empty");

    // Tenter de créer un HtmlDom avec une chaîne vide
    new HtmlDom('');
}





    
    public function testQuerySelectorAllWithXPathError()
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
    
        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

        
    public function testGetElementById()
    {
        $element = $this->dom->getElementById('container');
        $this->assertInstanceOf(HtmlDomElement::class, $element);
        $this->assertEquals('container', $element->getAttribute('id'));
    }

    public function testGetElementByIdNotFound()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Element with ID 'unknown' not found.");
        $this->dom->getElementById('unknown');
    }

    public function testGetElementsByClassName()
    {
        $elements = $this->dom->getElementsByClassName('text');
        $this->assertCount(2, $elements);
        $this->assertEquals('Hello World', $elements[0]->getInnerHtml());
    }

    public function testGetElementsByClassNameNotFound()
    {
        $elements = $this->dom->getElementsByClassName('nonexistent');
        $this->assertCount(0, $elements);
    }

    public function testGetElementsByTagName()
    {
        $elements = $this->dom->getElementsByTagName('p');
        $this->assertCount(2, $elements);
        $this->assertEquals('Hello World', $elements[0]->getInnerHtml());
    }

    public function testGetElementsByTagNameNotFound()
    {
        $elements = $this->dom->getElementsByTagName('nonexistent');
        $this->assertCount(0, $elements);
    }

    public function testQuerySelector()
    {
        $element = $this->dom->querySelector('#container');
        $this->assertInstanceOf(HtmlDomElement::class, $element);
        $this->assertEquals('main', $element->getAttribute('class'));
    }

    public function testQuerySelectorInvalidSelector()
{
    $this->expectException(Exception::class);
    $this->expectExceptionMessage("Unsupported selector format: @invalid");
    $this->dom->querySelector('@invalid');
}


    public function testQuerySelectorNotFound()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No element matching selector '.nonexistent' found.");
        $this->dom->querySelector('.nonexistent');
    }

    public function testQuerySelectorAll()
    {
        $elements = $this->dom->querySelectorAll('.text');
        $this->assertCount(2, $elements);
        $this->assertEquals('Another Text', $elements[1]->getInnerHtml());
    }

    public function testQuerySelectorAllNotFound()
    {
        $elements = $this->dom->querySelectorAll('.nonexistent');
        $this->assertCount(0, $elements);
    }

    public function testQuerySelectorAllReturnsEmptyArrayOnXPathError()
    {
        // Injecter un sélecteur qui se convertit en XPath mal formé
        $result = $this->dom->querySelectorAll('invalid-selector'); // Sélecteur invalide qui provoque un XPath incorrect
    
        // Vérifiez que le résultat est bien un tableau vide
        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }
    

    public function testQuerySelectorWithInvalidXPath()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Unsupported selector format: //*[");
    
        $this->dom->querySelector('//*[');
    }    

    public function testCreateElement()
    {
        $newElement = $this->dom->createElement('span');
        $this->assertInstanceOf(HtmlDomElement::class, $newElement);
        $newElement->setAttribute('class', 'highlight');
        $this->assertEquals('highlight', $newElement->getAttribute('class'));
    }

    public function testCreateElementWithText()
    {
        $newElement = $this->dom->createElement('span');
        $newElement->getElement()->textContent = 'Text content';
        $this->assertEquals('Text content', $newElement->getInnerHtml());
    }

    public function testCreateElementAndInsertIntoDom()
    {
        $newElement = $this->dom->createElement('span');
        $newElement->setAttribute('class', 'highlight');
        $container = $this->dom->getElementById('container');
        $container->appendChild($newElement);

        $this->assertStringContainsString('<span class="highlight"></span>', $container->getInnerHtml());
    }

    public function testCssToXPathTagSelector()
    {
        $element = $this->dom->querySelector('p');
        $this->assertInstanceOf(HtmlDomElement::class, $element);
        $this->assertEquals('Hello World', $element->getInnerHtml());
    }
}
