<?php

namespace Qck;

/**
 * Bootstrap4Page
 *
 * @author muellerm
 */
class Bootstrap4Page implements \Qck\Interfaces\Snippet, \Qck\Interfaces\HttpContent
{

    function __construct ( $Title, Interfaces\Html\Snippet $Contents )
    {
        $this->Title = $Title;
        $this->Contents = $Contents;
    }

    function setLanguage ( $Language )
    {
        $this->Language = $Language;
    }

    function setHeaderContents ( Interfaces\Html\Snippet $HeaderContents )
    {
        $this->HeaderContents = $HeaderContents;
    }

    function setFooterContents ( Interfaces\Html\Snippet $FooterContents )
    {
        $this->FooterContents = $FooterContents;
    }

    public function getCharset ()
    {
        return Interfaces\Output::CHARSET_UTF_8;
    }

    public function getContentType ()
    {
        return Interfaces\Output::CONTENT_TYPE_TEXT_HTML;
    }

    function setUseJs ( $UseJs )
    {
        $this->UseJs = $UseJs;
    }

    public function __toString ()
    {
        ob_start ();
        ?>
        <!doctype html>
        <html lang="<?= $this->Language ?>">
            <head>
                <!-- Required meta tags -->
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

                <!-- Bootstrap CSS -->
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

                <?= $this->HeaderContents ? $this->HeaderContents : "" ?>
                <title><?= $this->Title ?></title>
            </head>
            <body>
                <?= $this->Contents; ?>

                <?php if ($this->UseJs): ?>
                    <!-- Optional JavaScript -->
                    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
                    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
                    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
                <?php endif; ?>                
                <?= $this->FooterContents ? $this->FooterContents : "" ?>
            </body>
        </html>
        <?php
        return ob_get_clean ();
    }

    /**
     *
     * @var string
     */
    protected $Language = "en";

    /**
     *
     * @var bool
     */
    protected $UseJs = true;

    /**
     *
     * @var string
     */
    protected $Title;

    /**
     *
     * @var Interfaces\Html\Snippet
     */
    protected $HeaderContents;

    /**
     *
     * @var Interfaces\Html\Snippet
     */
    protected $FooterContents;

    /**
     *
     * @var Interfaces\Html\Snippet
     */
    protected $Contents;

}
