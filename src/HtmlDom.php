<?php

namespace AlanVdb\Html;

use AlanVdb\Html\Definition\HtmlDomInterface;
use AlanVdb\Html\Definition\HtmlDomElementInterface;
use AlanVdb\Html\Common\HtmlDomQueryTrait;
use Exception;
use DOMDocument;
use DomElement;
use DOMXPath;

class HtmlDom implements HtmlDomInterface
{
    use HtmlDomQueryTrait;

    protected DOMDocument $dom;

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
        $this->setXPath(new DOMXPath($this->dom));  // Initialisation du DOMXPath dans le trait
    }

    /**
     * Retrieves an element by its ID.
     *
     * @param string $id The ID of the element to retrieve.
     * @return HtmlDomElementInterface|null The element with the specified ID.
     */
    public function getElementById(string $id): ?HtmlDomElementInterface
    {
        $element = $this->dom->getElementById($id);
        if ($element) {
            return new HtmlDomElement($element);
        }
        return null;
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
     * Returns the root DOMNode (the entire document).
     *
     * @return DomElement
     */
    protected function getElement(): DomElement
    {
        return $this->dom->documentElement;
    }
}
