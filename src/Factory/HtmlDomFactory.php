<?php declare(strict_types=1);

namespace AlanVdb\Html\Factory;

use AlanVdb\Html\Definition\HtmlDomInterface;
use AlanVdb\Html\HtmlDom;

class HtmlDomFactory
{
    /**
     * Creates a new instance of HtmlDom with the provided HTML content.
     *
     * @param string $htmlContent The HTML content to load into the HtmlDom instance.
     * @return HtmlDomInterface An instance of HtmlDom containing the loaded HTML.
     */
    public function createHtmlDom(string $htmlContent) : HtmlDomInterface
    {
        return new HtmlDom($htmlContent);
    }
}
