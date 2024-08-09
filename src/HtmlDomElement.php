<?php

namespace AlanVdb\Html;

use AlanVdb\Html\Definition\HtmlDomElementInterface;
use DOMElement;

class HtmlDomElement implements HtmlDomElementInterface
{
    protected DOMElement $element;
    protected ?HtmlDomElementInterface $parent;

    /**
     * Initializes the HtmlDomElement with a DOMElement and an optional parent.
     *
     * @param DOMElement $element The DOM element to wrap.
     * @param HtmlDomElementInterface|null $parent The parent element, if any.
     */
    public function __construct(DOMElement $element, HtmlDomElementInterface $parent = null)
    {
        $this->element = $element;
        $this->parent = $parent;
    }

    /**
     * Appends a child element to the current element.
     *
     * @param HtmlDomElementInterface $child The child element to append.
     * @return self The current element.
     */
    public function appendChild(HtmlDomElementInterface $child) : self
    {
        $this->element->appendChild($child->getElement());
        $child->setParent($this);
        return $this;
    }

    /**
     * Inserts a new node before the reference node.
     *
     * @param HtmlDomElementInterface $newNode The new node to insert.
     * @param HtmlDomElementInterface $referenceNode The reference node before which the new node is inserted.
     * @return self The current element.
     */
    public function insertBefore(HtmlDomElementInterface $newNode, HtmlDomElementInterface $referenceNode) : self
    {
        $this->element->insertBefore($newNode->getElement(), $referenceNode->getElement());
        $newNode->setParent($this);
        return $this;
    }

    /**
     * Gets the inner HTML of the element.
     *
     * @return string The inner HTML content.
     */
    public function getInnerHtml() : string
    {
        $innerHTML = "";
        foreach ($this->element->childNodes as $child) {
            $innerHTML .= $this->element->ownerDocument->saveHTML($child);
        }
        return $innerHTML;
    }

    /**
     * Inserts HTML at the specified position relative to the current element.
     *
     * @param string $position The position where the HTML should be inserted. Possible values: 'beforebegin', 'afterbegin', 'beforeend', 'afterend'.
     * @param string $html The HTML string to insert.
     * @return self The current element.
     */
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

    /**
     * Sets an attribute on the element.
     *
     * @param string $name The name of the attribute.
     * @param string $value The value of the attribute.
     * @return self The current element.
     */
    public function setAttribute(string $name, string $value) : self
    {
        $this->element->setAttribute($name, $value);
        return $this;
    }

    /**
     * Gets the value of an attribute on the element.
     *
     * @param string $name The name of the attribute.
     * @return string The value of the attribute.
     */
    public function getAttribute(string $name) : string
    {
        return $this->element->getAttribute($name);
    }

    /**
     * Removes an attribute from the element.
     *
     * @param string $name The name of the attribute to remove.
     * @return self The current element.
     */
    public function removeAttribute(string $name) : self
    {
        $this->element->removeAttribute($name);
        return $this;
    }

    /**
     * Adds a class to the element.
     *
     * @param string $className The class name to add.
     * @return self The current element.
     */
    public function addClass(string $className) : self
    {
        $currentClass = $this->getAttribute('class');
        if (!preg_match('/\b' . preg_quote($className, '/') . '\b/', $currentClass)) {
            $this->setAttribute('class', trim("$currentClass $className"));
        }
        return $this;
    }

    /**
     * Removes a class from the element.
     *
     * @param string $className The class name to remove.
     * @return self The current element.
     */
    public function removeClass(string $className) : self
    {
        $currentClass = $this->getAttribute('class');
        $updatedClass = preg_replace('/\b' . preg_quote($className, '/') . '\b/', '', $currentClass);
        $this->setAttribute('class', trim($updatedClass));
        return $this;
    }

    /**
     * Toggles a class on the element.
     *
     * @param string $className The class name to toggle.
     * @return self The current element.
     */
    public function toggleClass(string $className) : self
    {
        if (preg_match('/\b' . preg_quote($className, '/') . '\b/', $this->getAttribute('class'))) {
            $this->removeClass($className);
        } else {
            $this->addClass($className);
        }
        return $this;
    }

    /**
     * Removes the current element from the DOM.
     */
    public function remove()
    {
        $this->element->parentNode->removeChild($this->element);
    }

    /**
     * Removes a child element from the current element.
     *
     * @param HtmlDomElementInterface $child The child element to remove.
     */
    public function removeChild(HtmlDomElementInterface $child)
    {
        $this->element->removeChild($child->getElement());
        $child->setParent(null); // Clear parent reference
    }

    /**
     * Gets the parent element of the current element.
     *
     * @return HtmlDomElementInterface|null The parent element, or null if there is no parent.
     */
    public function getParent() : ?HtmlDomElementInterface
    {
        return $this->parent;
    }

    /**
     * Sets the parent element of the current element.
     *
     * @param HtmlDomElementInterface|null $parent The parent element to set.
     */
    protected function setParent(?HtmlDomElementInterface $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * Gets the child nodes of the current element.
     *
     * @return HtmlDomElementInterface[] An array of child nodes.
     */
    public function getChildNodes() : array
    {
        $children = [];
        foreach ($this->element->childNodes as $child) {
            if ($child instanceof DOMElement) {
                $children[] = new HtmlDomElement($child, $this);
            }
        }
        return $children;
    }

    /**
     * Gets the first child of the current element.
     *
     * @return HtmlDomElementInterface|null The first child element, or null if there is no child.
     */
    public function getFirstChild() : ?HtmlDomElementInterface
    {
        $firstChild = $this->element->firstChild;
        return $firstChild instanceof DOMElement ? new HtmlDomElement($firstChild, $this) : null;
    }

    /**
     * Gets the last child of the current element.
     *
     * @return HtmlDomElementInterface|null The last child element, or null if there is no child.
     */
    public function getLastChild() : ?HtmlDomElementInterface
    {
        $lastChild = $this->element->lastChild;
        return $lastChild instanceof DOMElement ? new HtmlDomElement($lastChild, $this) : null;
    }

    /**
     * Gets the next sibling of the current element.
     *
     * @return HtmlDomElementInterface|null The next sibling element, or null if there is no next sibling.
     */
    public function getNextSibling() : ?HtmlDomElementInterface
    {
        $nextSibling = $this->element->nextSibling;
        return $nextSibling instanceof DOMElement ? new HtmlDomElement($nextSibling, $this->parent) : null;
    }

    /**
     * Gets the previous sibling of the current element.
     *
     * @return HtmlDomElementInterface|null The previous sibling element, or null if there is no previous sibling.
     */
    public function getPreviousSibling() : ?HtmlDomElementInterface
    {
        $previousSibling = $this->element->previousSibling;
        return $previousSibling instanceof DOMElement ? new HtmlDomElement($previousSibling, $this->parent) : null;
    }

    /**
     * Gets the underlying DOMElement.
     *
     * @return DOMElement The DOMElement wrapped by this class.
     */
    public function getElement(): DOMElement
    {
        return $this->element;
    }
}
