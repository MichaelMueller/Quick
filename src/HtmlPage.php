<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class HtmlPage implements \Qck\Interfaces\HttpContent
{

    function __construct( $Title, \Qck\Interfaces\HtmlSnippet $ContentSnippet )
    {
        $this->Title = $Title;
        $this->ContentSnippet = $ContentSnippet;
    }

    function setLanguageCode( $LanguageCode )
    {
        $this->LanguageCode = $LanguageCode;
    }

    function setHeaderSnippet( \Qck\Interfaces\HtmlSnippet $HeaderSnippet )
    {
        $this->HeaderSnippet = $HeaderSnippet;
    }

    public function getCharset()
    {
        return \Qck\Interfaces\HttpContent::CHARSET_UTF_8;
    }

    public function getContentType()
    {
        return \Qck\Interfaces\HttpContent::CONTENT_TYPE_TEXT_HTML;
    }

    public function getContents()
    {
        ob_start();
        ?>
        <!doctype html>
        <html lang="<?= $this->LanguageCode ?>">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <?= $this->HeaderSnippet ? $this->HeaderSnippet->renderHtml() : "" ?>
            </head>
            <body>                
                <?= $this->ContentSnippet->renderHtml() ?>
            </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     *
     * @var string
     */
    protected $Title;

    /**
     *
     * @var \Qck\Interfaces\HtmlSnippet
     */
    protected $ContentSnippet;

    /**
     *
     * @var string
     */
    protected $LanguageCode = "en";

    /**
     *
     * @var \Qck\Interfaces\HtmlSnippet
     */
    protected $HeaderSnippet;

    /**
     *
     * @var mixed
     */
    protected $AdditionalHeaders = [];

}
