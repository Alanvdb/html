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
        // Handle ID selectors
        if (preg_match('/^#([\w\-]+)$/', $selector, $matches)) {
            return ".//*[@id='{$matches[1]}']";
        }
        // Handle class selectors
        elseif (preg_match('/^\.([\w\-]+)$/', $selector, $matches)) {
            return ".//*[contains(concat(' ', normalize-space(@class), ' '), ' {$matches[1]} ')]";
        }
        // Handle attribute selectors with various operators
        elseif (preg_match('/^([\w\-]+)\[([^\]]+)\]$/', $selector, $matches)) {
            $tagName = $matches[1];
            $attributeSelector = $matches[2];
    
            // Handle attribute contains selector (e.g., [href*="artist"])
            if (preg_match('/([\w\-]+)\*="?([^"]*)"?/', $attributeSelector, $attrMatches)) {
                return ".//{$tagName}[contains(@{$attrMatches[1]}, '{$attrMatches[2]}')]";
            }
            // Handle attribute exact match selector (e.g., [href="artist"])
            elseif (preg_match('/([\w\-]+)="([^"]*)"/', $attributeSelector, $attrMatches)) {
                return ".//{$tagName}[@{$attrMatches[1]}='{$attrMatches[2]}']";
            }
            // Handle attribute starts with selector (e.g., [href^="artist"])
            elseif (preg_match('/([\w\-]+)\^="?([^"]*)"?/', $attributeSelector, $attrMatches)) {
                return ".//{$tagName}[starts-with(@{$attrMatches[1]}, '{$attrMatches[2]}')]";
            }
            // Handle attribute ends with selector (e.g., [href$="artist"])
            elseif (preg_match('/([\w\-]+)\$="?([^"]*)"?/', $attributeSelector, $attrMatches)) {
                return ".//{$tagName}[substring(@{$attrMatches[1]}, string-length(@{$attrMatches[1]}) - string-length('{$attrMatches[2]}') + 1) = '{$attrMatches[2]}']";
            }
            // Handle attribute exists selector (e.g., [href])
            elseif (preg_match('/([\w\-]+)$/', $attributeSelector, $attrMatches)) {
                return ".//{$tagName}[@{$attrMatches[1]}]";
            }
        }
        // Handle tag name selectors
        elseif (preg_match('/^[\w\-]+$/', $selector)) {
            return ".//{$selector}";
        }
    
        throw new Exception("Unsupported selector format: $selector");
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
