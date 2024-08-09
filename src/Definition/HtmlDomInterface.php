<?php declare(strict_types=1);

namespace AlanVdb\Html\Definition;

interface HtmlDomInterface
{
    public function getElementById(string $id) : HtmlDomElementInterface;

    public function getElementsByClassName(string $className) : array;

    public function getElementsByTagName(string $tagName) : array;

    public function querySelector(string $selector) : ?HtmlDomElementInterface;

    public function querySelectorAll(string $selector) : array;

    public function createElement(string $tagName) : HtmlDomElementInterface;
}
