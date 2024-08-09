<?php declare(strict_types=1);

namespace AlanVdb\Html\Definition;

interface HtmlDomElementInterface
{
    public function appendChild(HtmlDomElementInterface $child) : self;

    public function insertBefore(HtmlDomElementInterface $newNode, HtmlDomElementInterface $referenceNode) : self;

    public function getInnerHtml() : string;

    public function insertAdjacentHTML(string $position, string $html) : self;

    public function setAttribute(string $name, string $value) : self;

    public function getAttribute(string $name) : string;

    public function removeAttribute(string $name) : self;

    public function addClass(string $className) : self;

    public function removeClass(string $className) : self;

    public function toggleClass(string $className) : self;

    public function remove(): void;

    public function removeChild(HtmlDomElementInterface $child): void;

    public function getParent() : ?HtmlDomElementInterface;

    public function getChildNodes() : array;

    public function getFirstChild() : ?HtmlDomElementInterface;

    public function getLastChild() : ?HtmlDomElementInterface;

    public function getNextSibling() : ?HtmlDomElementInterface;

    public function getPreviousSibling() : ?HtmlDomElementInterface;

    /**
     * Gets the underlying DOMElement.
     *
     * @return \DOMNode The DOMNode wrapped by this class.
     */
    public function getElement(): \DOMNode;
}
