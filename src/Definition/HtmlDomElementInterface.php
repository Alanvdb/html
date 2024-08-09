<?php declare(strict_types=1);

namespace AlanVdb\Html\Definition;

use DOMElement;

/**
 * Interface HtmlDomElementInterface
 *
 * Defines the methods for interacting with HTML DOM elements.
 */
interface HtmlDomElementInterface
{
    /**
     * Appends a child element to the current element.
     *
     * @param HtmlDomElementInterface $child The child element to append.
     * @return self
     */
    public function appendChild(HtmlDomElementInterface $child): self;

    /**
     * Inserts a new node before a reference node within the current element.
     *
     * @param HtmlDomElementInterface $newNode The new node to insert.
     * @param HtmlDomElementInterface $referenceNode The reference node before which the new node will be inserted.
     * @return self
     */
    public function insertBefore(HtmlDomElementInterface $newNode, HtmlDomElementInterface $referenceNode): self;

    /**
     * Gets the inner HTML content of the current element.
     *
     * @return string The inner HTML content.
     */
    public function getInnerHtml(): string;

    /**
     * Inserts HTML at a specified position relative to the current element.
     *
     * @param string $position The position relative to the element (e.g., 'beforebegin', 'afterbegin', 'beforeend', 'afterend').
     * @param string $html The HTML content to insert.
     * @return self
     */
    public function insertAdjacentHTML(string $position, string $html): self;

    /**
     * Sets an attribute on the current element.
     *
     * @param string $name The name of the attribute.
     * @param string $value The value of the attribute.
     * @return self
     */
    public function setAttribute(string $name, string $value): self;

    /**
     * Gets the value of an attribute from the current element.
     *
     * @param string $name The name of the attribute.
     * @return string The value of the attribute.
     */
    public function getAttribute(string $name): string;

    /**
     * Removes an attribute from the current element.
     *
     * @param string $name The name of the attribute.
     * @return self
     */
    public function removeAttribute(string $name): self;

    /**
     * Adds a class to the current element's class list.
     *
     * @param string $className The class name to add.
     * @return self
     */
    public function addClass(string $className): self;

    /**
     * Removes a class from the current element's class list.
     *
     * @param string $className The class name to remove.
     * @return self
     */
    public function removeClass(string $className): self;

    /**
     * Toggles a class in the current element's class list.
     *
     * @param string $className The class name to toggle.
     * @return self
     */
    public function toggleClass(string $className): self;

    /**
     * Removes the current element from the DOM.
     */
    public function remove(): void;

    /**
     * Removes a child element from the current element.
     *
     * @param HtmlDomElementInterface $child The child element to remove.
     */
    public function removeChild(HtmlDomElementInterface $child): void;

    /**
     * Gets the parent element of the current element.
     *
     * @return HtmlDomElementInterface|null The parent element, or null if there is no parent.
     */
    public function getParent(): ?HtmlDomElementInterface;

    /**
     * Gets an array of child elements of the current element.
     *
     * @return HtmlDomElementInterface[] An array of child elements.
     */
    public function getChildNodes(): array;

    /**
     * Gets the first child element of the current element.
     *
     * @return HtmlDomElementInterface|null The first child element, or null if there are no children.
     */
    public function getFirstChild(): ?HtmlDomElementInterface;

    /**
     * Gets the last child element of the current element.
     *
     * @return HtmlDomElementInterface|null The last child element, or null if there are no children.
     */
    public function getLastChild(): ?HtmlDomElementInterface;

    /**
     * Gets the next sibling element of the current element.
     *
     * @return HtmlDomElementInterface|null The next sibling element, or null if there is no next sibling.
     */
    public function getNextSibling(): ?HtmlDomElementInterface;

    /**
     * Gets the previous sibling element of the current element.
     *
     * @return HtmlDomElementInterface|null The previous sibling element, or null if there is no previous sibling.
     */
    public function getPreviousSibling(): ?HtmlDomElementInterface;

    /**
     * Gets the underlying DOMElement.
     *
     * @return DOMElement The underlying DOMElement.
     */
    public function getElement(): DOMElement;
}
