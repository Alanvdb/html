<?php

namespace AlanVdb\Html;

use AlanVdb\Html\Definition\HtmlDomElementInterface;
use AlanVdb\Html\Common\HtmlDomQueryTrait;
use DOMNode;

class HtmlDomElement implements HtmlDomElementInterface
{
    use HtmlDomQueryTrait;

    protected DOMNode $element;
    protected ?HtmlDomElementInterface $parent;

    public function __construct(DOMNode $element, HtmlDomElementInterface $parent = null)
    {
        $this->element = $element;
        $this->parent = $parent;
        $this->setXPath(new \DOMXPath($element->ownerDocument));
    }

    public function appendChild(HtmlDomElementInterface $child) : self
    {
        $this->element->appendChild($child->getElement());
        $child->setParent($this);
        return $this;
    }

    public function insertBefore(HtmlDomElementInterface $newNode, HtmlDomElementInterface $referenceNode) : self
    {
        $this->element->insertBefore($newNode->getElement(), $referenceNode->getElement());
        $newNode->setParent($this);
        return $this;
    }

    public function getInnerHtml() : string
    {
        $innerHTML = "";
        foreach ($this->element->childNodes as $child) {
            $innerHTML .= $this->element->ownerDocument->saveHTML($child);
        }
        return $innerHTML;
    }

    public function insertAdjacentHTML(string $position, string $html) : self
    {
        $fragment = $this->element->ownerDocument->createDocumentFragment();
        $fragment->appendXML($html);

        switch ($position) {
            case 'beforebegin':
                $this->element->parentNode->insertBefore($fragment, $this->element);
                break;
            case 'afterbegin':
                $this->element->insertBefore($fragment, $this->element->firstChild);
                break;
            case 'beforeend':
                $this->element->appendChild($fragment);
                break;
            case 'afterend':
                $this->element->parentNode->insertBefore($fragment, $this->element->nextSibling);
                break;
        }

        return $this;
    }

    public function setAttribute(string $name, string $value) : self
    {
        $this->element->setAttribute($name, $value);
        return $this;
    }

    public function getAttribute(string $name) : string
    {
        return $this->element->getAttribute($name);
    }

    public function removeAttribute(string $name) : self
    {
        $this->element->removeAttribute($name);
        return $this;
    }

    public function addClass(string $className) : self
    {
        $currentClass = $this->getAttribute('class');
        if (!preg_match('/\b' . preg_quote($className, '/') . '\b/', $currentClass)) {
            $this->setAttribute('class', trim("$currentClass $className"));
        }
        return $this;
    }

    public function removeClass(string $className) : self
    {
        $currentClass = $this->getAttribute('class');
        $updatedClass = preg_replace('/\b' . preg_quote($className, '/') . '\b/', '', $currentClass);
        $this->setAttribute('class', trim($updatedClass));
        return $this;
    }

    public function toggleClass(string $className) : self
    {
        if (preg_match('/\b' . preg_quote($className, '/') . '\b/', $this->getAttribute('class'))) {
            $this->removeClass($className);
        } else {
            $this->addClass($className);
        }
        return $this;
    }

    public function remove(): void
    {
        $this->element->parentNode->removeChild($this->element);
    }

    public function removeChild(HtmlDomElementInterface $child): void
    {
        $this->element->removeChild($child->getElement());
        $child->setParent(null);
    }

    public function getParent() : ?HtmlDomElementInterface
    {
        return $this->parent;
    }

    protected function setParent(?HtmlDomElementInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getChildNodes() : array
    {
        $children = [];
        foreach ($this->element->childNodes as $child) {
            if ($child instanceof DOMNode) {
                $children[] = new HtmlDomElement($child, $this);
            }
        }
        return $children;
    }

    public function getFirstChild() : ?HtmlDomElementInterface
    {
        $firstChild = $this->element->firstChild;
        return $firstChild instanceof DOMNode ? new HtmlDomElement($firstChild, $this) : null;
    }

    public function getLastChild() : ?HtmlDomElementInterface
    {
        $lastChild = $this->element->lastChild;
        return $lastChild instanceof DOMNode ? new HtmlDomElement($lastChild, $this) : null;
    }

    public function getNextSibling() : ?HtmlDomElementInterface
    {
        $nextSibling = $this->element->nextSibling;
        return $nextSibling instanceof DOMNode ? new HtmlDomElement($nextSibling, $this->parent) : null;
    }

    public function getPreviousSibling() : ?HtmlDomElementInterface
    {
        $previousSibling = $this->element->previousSibling;
        return $previousSibling instanceof DOMNode ? new HtmlDomElement($previousSibling, $this->parent) : null;
    }

    public function getElement(): DOMNode
    {
        return $this->element;
    }
}
