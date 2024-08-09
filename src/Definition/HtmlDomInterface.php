<?php declare(strict_types=1);

namespace AlanVdb\Html\Definition;

interface HtmlDomInterface
{
    /**
     * Retrieves an element by its ID.
     *
     * @param string $id The ID of the element to retrieve.
     * @return HtmlDomElementInterface The element with the specified ID.
     */
    public function getElementById(string $id) : HtmlDomElementInterface;

    /**
     * Retrieves all elements with the specified class name.
     *
     * @param string $className The class name of the elements to retrieve.
     * @return HtmlDomElementInterface[] An array of elements with the specified class name.
     */
    public function getElementsByClassName(string $className) : array;

    /**
     * Retrieves all elements with the specified tag name.
     *
     * @param string $tagName The tag name of the elements to retrieve.
     * @return HtmlDomElementInterface[] An array of elements with the specified tag name.
     */
    public function getElementsByTagName(string $tagName) : array;

    /**
     * Retrieves the first element that matches the specified CSS selector.
     *
     * @param string $selector The CSS selector to match elements against.
     * @return HtmlDomElementInterface The first element that matches the selector.
     */
    public function querySelector(string $selector) : HtmlDomElementInterface;

    /**
     * Retrieves all elements that match the specified CSS selector.
     *
     * @param string $selector The CSS selector to match elements against.
     * @return HtmlDomElementInterface[] An array of elements that match the selector.
     */
    public function querySelectorAll(string $selector) : array;

    /**
     * Creates a new element with the specified tag name.
     *
     * @param string $tagName The tag name of the element to create.
     * @return HtmlDomElementInterface The newly created element.
     */
    public function createElement(string $tagName) : HtmlDomElementInterface; 
}
