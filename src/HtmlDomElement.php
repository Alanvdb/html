<?php

namespace AlanVdb\Html;

use AlanVdb\Html\Definition\HtmlDomElementInterface;
use AlanVdb\Html\Common\HtmlDomQueryTrait;
use DOMXPath;
use DOMElement;

/**
 * Class HtmlDomElement
 *
 * Represents an element in the HTML DOM and provides methods for interacting with it.
 */
class HtmlDomElement implements HtmlDomElementInterface
{
    use HtmlDomQueryTrait;

    protected DOMElement $element;
    protected ?HtmlDomElementInterface $parent;

    /**
     * HtmlDomElement constructor.
     *
     * @param DOMElement $element The DOMElement instance.
     * @param HtmlDomElementInterface|null $parent The parent element, if any.
     */
    public function __construct(DOMElement $element, HtmlDomElementInterface $parent = null)
    {
        $this->element = $element;
        $this->parent = $parent;
        $this->setXPath(new DOMXPath($element->ownerDocument));
    }

    /**
     * Appends a child element to this element.
     *
     * @param HtmlDomElementInterface $child The child element to append.
     * @return self
     */
    public function appendChild(HtmlDomElementInterface $child): self
    {
        $this->element->appendChild($child->getElement());
        $child->setParent($this);
        return $this;
    }

    /**
     * Inserts a new element before a reference element.
     *
     * @param HtmlDomElementInterface $newNode The new element to insert.
     * @param HtmlDomElementInterface $referenceNode The reference element before which the new element will be inserted.
     * @return self
     */
    public function insertBefore(HtmlDomElementInterface $newNode, HtmlDomElementInterface $referenceNode): self
    {
        $this->element->insertBefore($newNode->getElement(), $referenceNode->getElement());
        $newNode->setParent($this);
        return $this;
    }

    /**
     * Returns the inner HTML of this element.
     *
     * @return string The inner HTML of this element.
     */
    public function getInnerHtml(): string
    {
        $innerHTML = "";
        foreach ($this->element->childNodes as $child) {
            $innerHTML .= $this->element->ownerDocument->saveHTML($child);
        }
        return $innerHTML;
    }

    /**
     * Inserts HTML content at a specified position relative to this element.
     *
     * @param string $position The position where the HTML should be inserted. 
     *                         Can be 'beforebegin', 'afterbegin', 'beforeend', or 'afterend'.
     * @param string $html The HTML content to insert.
     * @return self
     */
    public function insertAdjacentHTML(string $position, string $html): self
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
     * Sets an attribute for this element.
     *
     * @param string $name The name of the attribute.
     * @param string $value The value of the attribute.
     * @return self
     */
    public function setAttribute(string $name, string $value): self
    {
        $this->element->setAttribute($name, $value);
        return $this;
    }

    /**
     * Retrieves the value of an attribute for this element.
     *
     * @param string $name The name of the attribute.
     * @return string The value of the attribute.
     */
    public function getAttribute(string $name): string
    {
        return $this->element->getAttribute($name);
    }

    /**
     * Removes an attribute from this element.
     *
     * @param string $name The name of the attribute to remove.
     * @return self
     */
    public function removeAttribute(string $name): self
    {
        $this->element->removeAttribute($name);
        return $this;
    }

    /**
     * Adds a class to this element.
     *
     * @param string $className The class name to add.
     * @return self
     */
    public function addClass(string $className): self
    {
        $currentClass = $this->getAttribute('class');
        if (!preg_match('/\b' . preg_quote($className, '/') . '\b/', $currentClass)) {
            $this->setAttribute('class', trim("$currentClass $className"));
        }
        return $this;
    }

    /**
     * Removes a class from this element.
     *
     * @param string $className The class name to remove.
     * @return self
     */
    public function removeClass(string $className): self
    {
        $currentClass = $this->getAttribute('class');
        $updatedClass = preg_replace('/\b' . preg_quote($className, '/') . '\b/', '', $currentClass);
        $this->setAttribute('class', trim($updatedClass));
        return $this;
    }

    /**
     * Toggles a class on this element.
     *
     * @param string $className The class name to toggle.
     * @return self
     */
    public function toggleClass(string $className): self
    {
        if (preg_match('/\b' . preg_quote($className, '/') . '\b/', $this->getAttribute('class'))) {
            $this->removeClass($className);
        } else {
            $this->addClass($className);
        }
        return $this;
    }

    /**
     * Removes this element from the DOM.
     */
    public function remove(): void
    {
        $this->element->parentNode->removeChild($this->element);
    }

    /**
     * Removes a child element from this element.
     *
     * @param HtmlDomElementInterface $child The child element to remove.
     */
    public function removeChild(HtmlDomElementInterface $child): void
    {
        $this->element->removeChild($child->getElement());
        $child->setParent(null);
    }

    /**
     * Retrieves the parent element of this element.
     *
     * @return HtmlDomElementInterface|null The parent element, or null if there is none.
     */
    public function getParent(): ?HtmlDomElementInterface
    {
        return $this->parent;
    }

    /**
     * Sets the parent element of this element.
     *
     * @param HtmlDomElementInterface|null $parent The parent element to set.
     */
    protected function setParent(?HtmlDomElementInterface $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * Retrieves all child nodes of this element.
     *
     * @return HtmlDomElementInterface[] An array of child elements.
     */
    public function getChildNodes(): array
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
     * Retrieves the first child element of this element.
     *
     * @return HtmlDomElementInterface|null The first child element, or null if there is none.
     */
    public function getFirstChild(): ?HtmlDomElementInterface
    {
        $firstChild = $this->element->firstElementChild;
        return $firstChild instanceof DOMElement ? new HtmlDomElement($firstChild, $this) : null;
    }

    /**
     * Retrieves the last child element of this element.
     *
     * @return HtmlDomElementInterface|null The last child element, or null if there is none.
     */
    public function getLastChild(): ?HtmlDomElementInterface
    {
        $lastChild = $this->element->lastElementChild;
        return $lastChild instanceof DOMElement ? new HtmlDomElement($lastChild, $this) : null;
    }

    /**
     * Retrieves the next sibling element of this element.
     *
     * @return HtmlDomElementInterface|null The next sibling element, or null if there is none.
     */
    public function getNextSibling(): ?HtmlDomElementInterface
    {
        $nextSibling = $this->element->nextElementSibling;
        return $nextSibling instanceof DOMElement ? new HtmlDomElement($nextSibling, $this->parent) : null;
    }

    /**
     * Retrieves the previous sibling element of this element.
     *
     * @return HtmlDomElementInterface|null The previous sibling element, or null if there is none.
     */
    public function getPreviousSibling(): ?HtmlDomElementInterface
    {
        $previousSibling = $this->element->previousElementSibling;
        return $previousSibling instanceof DOMElement ? new HtmlDomElement($previousSibling, $this->parent) : null;
    }

    /**
     * Returns the underlying DOMElement.
     *
     * @return DOMElement The underlying DOMElement instance.
     */
    public function getElement(): DOMElement
    {
        return $this->element;
    }
}
