<?php

namespace AlanVdb\Html\Common;

use AlanVdb\Html\Definition\HtmlDomElementInterface;
use AlanVdb\Html\HtmlDomElement;
use DOMXPath;
use DOMNode;
use Exception;

/**
 * Trait HtmlDomQueryTrait
 *
 * Provides common query functionalities for HTML DOM elements using XPath.
 */
trait HtmlDomQueryTrait
{
    protected DOMXPath $xpath;

    /**
     * Sets the DOMXPath instance used for querying the DOM.
     *
     * @param DOMXPath $xpath The DOMXPath instance.
     */
    public function setXPath(DOMXPath $xpath): void
    {
        $this->xpath = $xpath;
    }

    /**
     * Converts a CSS selector into an XPath query.
     *
     * @param string $selector The CSS selector.
     * @return string The corresponding XPath query.
     * @throws Exception If the selector format is not supported.
     */
    protected function cssToXPath(string $selector): string
    {
        if (preg_match('/^#([\w\-]+)$/', $selector, $matches)) {
            return ".//*[@id='{$matches[1]}']";
        } elseif (preg_match('/^\.([\w\-]+)$/', $selector, $matches)) {
            return ".//*[contains(concat(' ', normalize-space(@class), ' '), ' {$matches[1]} ')]";
        } elseif (preg_match('/^[\w\-]+$/', $selector)) {
            return ".//{$selector}";
        } else {
            throw new Exception("Unsupported selector format: $selector");
        }
    }

    /**
     * Queries the DOM for the first element matching the specified CSS selector.
     *
     * @param string $selector The CSS selector.
     * @return HtmlDomElementInterface|null The matching element, or null if not found.
     */
    public function querySelector(string $selector): ?HtmlDomElementInterface
    {
        $xpathQuery = $this->cssToXPath($selector);
        $elements = $this->xpath->query($xpathQuery, $this->getElement());
        if ($elements === false || $elements->length === 0) {
            return null;
        }
        return new HtmlDomElement($elements->item(0));
    }

    /**
     * Queries the DOM for all elements matching the specified CSS selector.
     *
     * @param string $selector The CSS selector.
     * @return HtmlDomElementInterface[] An array of matching elements.
     */
    public function querySelectorAll(string $selector): array
    {
        $xpathQuery = $this->cssToXPath($selector);
        $elements = $this->xpath->query($xpathQuery, $this->getElement());
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
     * Must be implemented by the class using this trait.
     * Should return the DOMNode associated with the current object.
     *
     * @return DOMNode The associated DOMNode.
     */
    abstract protected function getElement(): DOMNode;
}
