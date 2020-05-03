<?php

namespace Qck\Snippets;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class MasterPage implements \Qck\Interfaces\Snippet, \Qck\Interfaces\HttpContent
{

    function __construct( \Qck\Interfaces\Language $Language, $Title, array $BodySnippets, array $HeaderSnippets = [] )
    {
        $this->Language       = $Language;
        $this->Title          = $Title;
        $this->HeaderSnippets = $HeaderSnippets;
        $this->BodySnippets   = $BodySnippets;
    }

    public function getCharset()
    {
        return \Qck\Interfaces\HttpContent::CHARSET_UTF_8;
    }

    public function getContentType()
    {
        return \Qck\Interfaces\HttpContent::CONTENT_TYPE_TEXT_HTML;
    }

    public function __toString()
    {
        $Lang = $this->Language->get();
        ob_start();
        ?>
        <!doctype html>
        <html lang="<?= $Lang ?>">
            <head>
                <!-- Required meta tags -->
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

                <title><?= $this->Title ?></title>
                <?php
                foreach ( $this->HeaderSnippets as $Snippet )
                {
                    echo $Snippet;
                }
                ?>
            </head>
            <body>
                <?php
                foreach ( $this->BodySnippets as $Snippet )
                {
                    echo $Snippet;
                }
                ?>
            </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     *
     * @var \Qck\Interfaces\Language
     */
    protected $Language;

    /**
     *
     * @var string
     */
    protected $Title;

    /**
     *
     * @var \Qck\Interfaces\Snippet[]
     */
    protected $HeaderSnippets;

    /**
     *
     * @var \Qck\Interfaces\Snippet[]
     */
    protected $BodySnippets;

}
