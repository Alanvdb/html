<?php declare(strict_types=1);

use AlanVdb\Html\HtmlDom;
use AlanVdb\Html\HtmlDomElement;
use PHPUnit\Framework\TestCase;

class HtmlDomElementTest extends TestCase
{
    protected HtmlDomElement $element;

    protected function setUp(): void
    {
        $doc = new DOMDocument();
        $doc->loadHTML('<div id="test" class="initial"><p>Hello</p></div>');
        $this->element = new HtmlDomElement($doc->getElementById('test'));
    }

    public function testAppendChild(): void
    {
        $newElement = $this->element->getElement()->ownerDocument->createElement('span', 'New Child');
        $wrappedElement = new HtmlDomElement($newElement);

        $this->element->appendChild($wrappedElement);

        $this->assertStringContainsString('<span>New Child</span>', $this->element->getInnerHtml());
        $this->assertSame($this->element, $wrappedElement->getParent());
    }

    public function testSetAttribute(): void
    {
        $this->element->setAttribute('data-test', 'value');
        $this->assertEquals('value', $this->element->getAttribute('data-test'));
    }

    public function testAddClass(): void
    {
        $this->element->addClass('new-class');
        $this->assertEquals('initial new-class', $this->element->getAttribute('class'));
    }

    public function testRemoveClass(): void
    {
        $this->element->removeClass('initial');
        $this->assertEquals('', $this->element->getAttribute('class'));
    }

    public function testToggleClass(): void
    {
        // Test to remove the class "initial"
        $this->element->toggleClass('initial');
        $this->assertEquals('', $this->element->getAttribute('class'));

        // Test to add the class "new-class"
        $this->element->toggleClass('new-class');
        $this->assertEquals('new-class', $this->element->getAttribute('class'));

        // Test to remove the class "new-class" again
        $this->element->toggleClass('new-class');
        $this->assertEquals('', $this->element->getAttribute('class'));
    }

    public function testGetParent(): void
    {
        $doc = new DOMDocument();

        $parentElement = $doc->createElement('div');
        $childElement = $doc->createElement('span');

        $parentDomElement = new HtmlDomElement($parentElement);
        $childDomElement = new HtmlDomElement($childElement);

        $parentDomElement->appendChild($childDomElement);

        $this->assertSame($parentDomElement, $childDomElement->getParent());
    }

    public function testGetChildNodes(): void
    {
        $children = $this->element->getChildNodes();
        $this->assertCount(1, $children);
        $this->assertEquals('Hello', $children[0]->getInnerHtml());
    }

    public function testGetInnerText()
    {
        $html = '<div><p>Hello <span>World</span></p><p>Another paragraph</p></div>';

        $dom = new HtmlDom($html);
        $element = $dom->getElementsByTagName('div')[0];
        
        $innerText = $element->getInnerText();
        
        $expectedText = "Hello WorldAnother paragraph";
        $this->assertEquals($expectedText, $innerText);
    }

    public function testInsertBefore(): void
    {
        $doc = new DOMDocument();
        $doc->loadHTML('<div><p>First</p><p id="second">Second</p></div>');
        $parentElement = new HtmlDomElement($doc->getElementsByTagName('div')->item(0));
        $newElement = new HtmlDomElement($doc->createElement('p', 'Inserted'));

        $referenceElement = new HtmlDomElement($doc->getElementById('second'));

        $parentElement->insertBefore($newElement, $referenceElement);

        $this->assertStringContainsString('<p>Inserted</p><p id="second">Second</p>', $parentElement->getInnerHtml());
        $this->assertSame($parentElement, $newElement->getParent());
    }

    public function testInsertAdjacentHTML(): void
    {
        $this->element->insertAdjacentHTML('beforeend', '<span>Adjacent</span>');
        $this->assertStringContainsString('<p>Hello</p><span>Adjacent</span>', $this->element->getInnerHtml());

        $this->element->insertAdjacentHTML('afterbegin', '<span>Start</span>');
        $this->assertStringContainsString('<span>Start</span><p>Hello</p>', $this->element->getInnerHtml());

        $this->element->insertAdjacentHTML('beforebegin', '<span>Before</span>');
        $this->assertStringContainsString('<span>Before</span><div id="test"', $this->element->getElement()->ownerDocument->saveHTML());

        $this->element->insertAdjacentHTML('afterend', '<span>After</span>');
        $this->assertStringContainsString('</div><span>After</span>', $this->element->getElement()->ownerDocument->saveHTML());
    }

    public function testRemoveAttribute(): void
    {
        $this->element->setAttribute('data-test', 'value');
        $this->element->removeAttribute('data-test');
        $this->assertEquals('', $this->element->getAttribute('data-test'));
    }

    public function testRemoveChild(): void
    {
        $child = $this->element->getChildNodes()[0];
        $this->element->removeChild($child);

        $this->assertStringNotContainsString('<p>Hello</p>', $this->element->getInnerHtml());
        $this->assertNull($child->getParent());
    }

    public function testGetFirstChild(): void
    {
        $firstChild = $this->element->getFirstChild();
        $this->assertInstanceOf(HtmlDomElement::class, $firstChild);
        $this->assertEquals('Hello', $firstChild->getInnerHtml());
    }

    public function testGetLastChild(): void
    {
        $lastChild = $this->element->getLastChild();
        $this->assertInstanceOf(HtmlDomElement::class, $lastChild);
        $this->assertEquals('Hello', $lastChild->getInnerHtml());
    }

    public function testGetNextSibling(): void
    {
        $doc = new DOMDocument();
        $doc->loadHTML('<div><p id="first">First</p><p id="second">Second</p></div>');
        $firstElement = new HtmlDomElement($doc->getElementById('first'));
        $nextSibling = $firstElement->getNextSibling();

        $this->assertInstanceOf(HtmlDomElement::class, $nextSibling);
        $this->assertEquals('Second', $nextSibling->getInnerHtml());
    }

    public function testGetPreviousSibling(): void
    {
        $doc = new DOMDocument();
        $doc->loadHTML('<div><p id="first">First</p><p id="second">Second</p></div>');
        $secondElement = new HtmlDomElement($doc->getElementById('second'));
        $previousSibling = $secondElement->getPreviousSibling();

        $this->assertInstanceOf(HtmlDomElement::class, $previousSibling);
        $this->assertEquals('First', $previousSibling->getInnerHtml());
    }

    public function testRemove(): void
    {
        $this->element->remove();
        $this->assertStringNotContainsString('<div id="test"', $this->element->getElement()->ownerDocument->saveHTML());
    }

    public function testGetElement(): void
    {
        $this->assertInstanceOf(DOMElement::class, $this->element->getElement());
    }
}
