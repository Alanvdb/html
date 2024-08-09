<?php

namespace AlanVdb\Html;

use AlanVdb\Html\Definition\HtmlDomInterface;
use AlanVdb\Html\Definition\HtmlDomElementInterface;
use Exception;
use DOMDocument;
use DOMXPath;

class HtmlDom implements HtmlDomInterface
{
    protected DOMDocument $dom;
    protected DOMXPath $xpath;

    /**
     * Initializes the DOMDocument and loads the provided HTML.
     *
     * @param string $html The HTML content to load.
     * @throws ValueError If the HTML content is empty or invalid.
     */
    public function __construct(string $html)
    {
        $this->dom = new DOMDocument();

        libxml_use_internal_errors(true);

        $this->dom->loadHTML($html);

        libxml_use_internal_errors(false);
        $this->xpath = new DOMXPath($this->dom);
    }

    /**
     * Retrieves an element by its ID.
     *
     * @param string $id The ID of the element to retrieve.
     * @return HtmlDomElementInterface The element with the specified ID.
     * @throws Exception If no element with the given ID is found.
     */
    public function getElementById(string $id): HtmlDomElementInterface
    {
        $element = $this->dom->getElementById($id);
        if ($element) {
            return new HtmlDomElement($element);
        }
        throw new Exception("Element with ID '$id' not found.");
    }

    /**
     * Retrieves all elements with the specified class name.
     *
     * @param string $className The class name of the elements to retrieve.
     * @return HtmlDomElementInterface[] An array of elements with the specified class name.
     */
    public function getElementsByClassName(string $className): array
    {
        $elements = $this->xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $className ')]");
        $result = [];
        foreach ($elements as $element) {
            $result[] = new HtmlDomElement($element);
        }
        return $result;
    }

    /**
     * Retrieves all elements with the specified tag name.
     *
     * @param string $tagName The tag name of the elements to retrieve.
     * @return HtmlDomElementInterface[] An array of elements with the specified tag name.
     */
    public function getElementsByTagName(string $tagName): array
    {
        $elements = $this->dom->getElementsByTagName($tagName);
        $result = [];
        foreach ($elements as $element) {
            $result[] = new HtmlDomElement($element);
        }
        return $result;
    }

    /**
     * Retrieves the first element that matches the specified CSS selector.
     *
     * @param string $selector The CSS selector to match elements against.
     * @return HtmlDomElementInterface The first element that matches the selector.
     * @throws Exception If no element matching the selector is found.
     */
    public function querySelector(string $selector): HtmlDomElementInterface
    {
        $xpathQuery = $this->cssToXPath($selector);
        $elements = $this->xpath->query($xpathQuery);
        if ($elements === false || $elements->length === 0) {
            throw new Exception("No element matching selector '$selector' found.");
        }
        return new HtmlDomElement($elements->item(0));
    }

    /**
     * Retrieves all elements that match the specified CSS selector.
     *
     * @param string $selector The CSS selector to match elements against.
     * @return HtmlDomElementInterface[] An array of elements that match the selector.
     */
    public function querySelectorAll(string $selector): array
    {
        $xpathQuery = $this->cssToXPath($selector);
        $elements = $this->xpath->query($xpathQuery);
        if ($elements === false) {
            return [];
        }
        $result = [];
        foreach ($elements as $element) {
            $result[] = new HtmlDomElement($element);
        }
        return $result;
    }

    /**
     * Creates a new element with the specified tag name.
     *
     * @param string $tagName The tag name of the element to create.
     * @return HtmlDomElementInterface The newly created element.
     */
    public function createElement(string $tagName): HtmlDomElementInterface
    {
        $element = $this->dom->createElement($tagName);
        return new HtmlDomElement($element);
    }

    /**
     * Converts a CSS selector into an XPath query.
     *
     * @param string $selector The CSS selector to convert.
     * @return string The corresponding XPath query.
     * @throws Exception If the selector format is unsupported.
     */
    protected function cssToXPath(string $selector): string
    {
        if (preg_match('/^#([\w\-]+)$/', $selector, $matches)) {
            return "//*[@id='{$matches[1]}']";
        } elseif (preg_match('/^\.([\w\-]+)$/', $selector, $matches)) {
            return "//*[contains(concat(' ', normalize-space(@class), ' '), ' {$matches[1]} ')]";
        } elseif (preg_match('/^[\w\-]+$/', $selector)) {
            return "//{$selector}";
        } else {
            throw new Exception("Unsupported selector format: $selector");
        }
    }
}
