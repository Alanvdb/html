<?php declare(strict_types=1);

namespace AlanVdb\Html\Definition;

interface HtmlDomElementInterface
{
    /**
     * Appends a child element to the current element.
     *
     * @param HtmlDomElementInterface $child The child element to append.
     * @return self The current element.
     */
    public function appendChild(HtmlDomElementInterface $child) : self;

    /**
     * Inserts a new node before the reference node.
     *
     * @param HtmlDomElementInterface $newNode The new node to insert.
     * @param HtmlDomElementInterface $referenceNode The reference node before which the new node is inserted.
     * @return self The current element.
     */
    public function insertBefore(HtmlDomElementInterface $newNode, HtmlDomElementInterface $referenceNode) : self;

    /**
     * Gets the inner HTML of the element.
     *
     * @return string The inner HTML content.
     */
    public function getInnerHtml() : string;

    /**
     * Inserts HTML at the specified position relative to the current element.
     *
     * @param string $position The position where the HTML should be inserted. Possible values: 'beforebegin', 'afterbegin', 'beforeend', 'afterend'.
     * @param string $html The HTML string to insert.
     * @return self The current element.
     */
    public function insertAdjacentHTML(string $position, string $html) : self;

    /**
     * Sets an attribute on the element.
     *
     * @param string $name The name of the attribute.
     * @param string $value The value of the attribute.
     * @return self The current element.
     */
    public function setAttribute(string $name, string $value) : self;

    /**
     * Gets the value of an attribute on the element.
     *
     * @param string $name The name of the attribute.
     * @return string The value of the attribute.
     */
    public function getAttribute(string $name) : string;

    /**
     * Removes an attribute from the element.
     *
     * @param string $name The name of the attribute to remove.
     * @return self The current element.
     */
    public function removeAttribute(string $name) : self;

    /**
     * Adds a class to the element.
     *
     * @param string $className The class name to add.
     * @return self The current element.
     */
    public function addClass(string $className) : self;

    /**
     * Removes a class from the element.
     *
     * @param string $className The class name to remove.
     * @return self The current element.
     */
    public function removeClass(string $className) : self;

    /**
     * Toggles a class on the element.
     *
     * @param string $className The class name to toggle.
     * @return self The current element.
     */
    public function toggleClass(string $className) : self;

    /**
     * Removes the current element from the DOM.
     */
    public function remove();

    /**
     * Removes a child element from the current element.
     *
     * @param HtmlDomElementInterface $child The child element to remove.
     */
    public function removeChild(HtmlDomElementInterface $child);

    /**
     * Gets the parent element of the current element.
     *
     * @return HtmlDomElementInterface|null The parent element, or null if there is no parent.
     */
    public function getParent() : ?HtmlDomElementInterface;

    /**
     * Gets the child nodes of the current element.
     *
     * @return HtmlDomElementInterface[] An array of child nodes.
     */
    public function getChildNodes() : array;

    /**
     * Gets the first child of the current element.
     *
     * @return HtmlDomElementInterface|null The first child element, or null if there is no child.
     */
    public function getFirstChild() : ?HtmlDomElementInterface;

    /**
     * Gets the last child of the current element.
     *
     * @return HtmlDomElementInterface|null The last child element, or null if there is no child.
     */
    public function getLastChild() : ?HtmlDomElementInterface;

    /**
     * Gets the next sibling of the current element.
     *
     * @return HtmlDomElementInterface|null The next sibling element, or null if there is no next sibling.
     */
    public function getNextSibling() : ?HtmlDomElementInterface;

    /**
     * Gets the previous sibling of the current element.
     *
     * @return HtmlDomElementInterface|null The previous sibling element, or null if there is no previous sibling.
     */
    public function getPreviousSibling() : ?HtmlDomElementInterface;
}
