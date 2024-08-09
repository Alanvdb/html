<?php

namespace AlanVdb\Html\Common;

use AlanVdb\Html\Definition\HtmlDomElementInterface;
use AlanVdb\Html\HtmlDomElement;
use DOMXPath;
use DOMNode;
use Exception;

trait HtmlDomQueryTrait
{
    protected DOMXPath $xpath;

    public function setXPath(DOMXPath $xpath)
    {
        $this->xpath = $xpath;
    }

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

    public function querySelector(string $selector): ?HtmlDomElementInterface
    {
        $xpathQuery = $this->cssToXPath($selector);
        $elements = $this->xpath->query($xpathQuery, $this->getElement());
        if ($elements === false || $elements->length === 0) {
            throw new Exception("No element matching selector '$selector' found.");
        }
        return new HtmlDomElement($elements->item(0));
    }

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
     * Cette méthode doit être implémentée par les classes utilisant ce trait.
     * Elle doit retourner le nœud DOM (DOMNode) associé à l'objet courant.
     *
     * @return DOMNode
     */
    abstract protected function getElement(): DOMNode;
}
