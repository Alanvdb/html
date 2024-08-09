<?php

use AlanVdb\Html\HtmlDomElement;
use PHPUnit\Framework\TestCase;

class HtmlDomElementTest extends TestCase
{
    protected $element;

    protected function setUp(): void
    {
        $doc = new DOMDocument();
        $doc->loadHTML('<div id="test" class="initial"><p>Hello</p></div>');
        $this->element = new HtmlDomElement($doc->getElementById('test'));
    }

    public function testAppendChild()
    {
        $newElement = $this->element->getElement()->ownerDocument->createElement('span', 'New Child');
        $wrappedElement = new HtmlDomElement($newElement);

        $this->element->appendChild($wrappedElement);

        $this->assertStringContainsString('<span>New Child</span>', $this->element->getInnerHtml());
    }

    public function testSetAttribute()
    {
        $this->element->setAttribute('data-test', 'value');
        $this->assertEquals('value', $this->element->getAttribute('data-test'));
    }

    public function testAddClass()
    {
        $this->element->addClass('new-class');
        $this->assertEquals('initial new-class', $this->element->getAttribute('class'));
    }

    public function testRemoveClass()
    {
        $this->element->removeClass('initial');
        $this->assertEquals('', $this->element->getAttribute('class'));
    }

    public function testToggleClass()
    {
        // Test to remove the class "initial"
        $this->element->toggleClass('initial');
        $this->assertEquals('', $this->element->getAttribute('class'));

        // Test to add the class "new-class"
        $this->element->toggleClass('new-class');
        $this->assertEquals('new-class', $this->element->getAttribute('class'));
    }

    public function testGetParent()
    {
        $doc = new DOMDocument();

        $parentElement = $doc->createElement('div');
        $childElement = $doc->createElement('span');

        $parentDomElement = new HtmlDomElement($parentElement);
        $childDomElement = new HtmlDomElement($childElement);

        $parentDomElement->appendChild($childDomElement);

        $this->assertSame($parentDomElement, $childDomElement->getParent());
    }

    public function testGetChildNodes()
    {
        $children = $this->element->getChildNodes();
        $this->assertCount(1, $children);
        $this->assertEquals('Hello', $children[0]->getInnerHtml());
    }

    public function testInsertBefore()
    {
        $doc = new DOMDocument();
        $doc->loadHTML('<div><p>First</p><p id="second">Second</p></div>');
        $parentElement = new HtmlDomElement($doc->getElementsByTagName('div')->item(0));
        $newElement = new HtmlDomElement($doc->createElement('p', 'Inserted'));

        $referenceElement = new HtmlDomElement($doc->getElementById('second'));

        $parentElement->insertBefore($newElement, $referenceElement);

        $this->assertStringContainsString('<p>Inserted</p><p id="second">Second</p>', $parentElement->getInnerHtml());
    }

    public function testInsertAdjacentHTML()
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

    public function testRemoveAttribute()
    {
        $this->element->setAttribute('data-test', 'value');
        $this->element->removeAttribute('data-test');
        $this->assertEquals('', $this->element->getAttribute('data-test'));
    }

    public function testRemoveChild()
    {
        $child = $this->element->getChildNodes()[0];
        $this->element->removeChild($child);

        $this->assertStringNotContainsString('<p>Hello</p>', $this->element->getInnerHtml());
    }

    public function testGetFirstChild()
    {
        $firstChild = $this->element->getFirstChild();
        $this->assertInstanceOf(HtmlDomElement::class, $firstChild);
        $this->assertEquals('Hello', $firstChild->getInnerHtml());
    }

    public function testGetLastChild()
    {
        $lastChild = $this->element->getLastChild();
        $this->assertInstanceOf(HtmlDomElement::class, $lastChild);
        $this->assertEquals('Hello', $lastChild->getInnerHtml());
    }

    public function testGetNextSibling()
    {
        $doc = new DOMDocument();
        $doc->loadHTML('<div><p id="first">First</p><p id="second">Second</p></div>');
        $firstElement = new HtmlDomElement($doc->getElementById('first'));
        $nextSibling = $firstElement->getNextSibling();

        $this->assertInstanceOf(HtmlDomElement::class, $nextSibling);
        $this->assertEquals('Second', $nextSibling->getInnerHtml());
    }

    public function testGetPreviousSibling()
    {
        $doc = new DOMDocument();
        $doc->loadHTML('<div><p id="first">First</p><p id="second">Second</p></div>');
        $secondElement = new HtmlDomElement($doc->getElementById('second'));
        $previousSibling = $secondElement->getPreviousSibling();

        $this->assertInstanceOf(HtmlDomElement::class, $previousSibling);
        $this->assertEquals('First', $previousSibling->getInnerHtml());
    }

    public function testRemove()
    {
        $this->element->remove();
        $this->assertStringNotContainsString('<div id="test"', $this->element->getElement()->ownerDocument->saveHTML());
    }

    public function testGetElement()
    {
        $this->assertInstanceOf(DOMElement::class, $this->element->getElement());
    }
}
