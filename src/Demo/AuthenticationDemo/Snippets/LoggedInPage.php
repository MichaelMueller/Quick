<?php

namespace Qck\Demo\AuthenticationDemo\Snippets;

/**
 * Description of LoginForm
 *
 * @author muellerm
 */
class LoggedInPage implements \Qck\Interfaces\HtmlSnippet
{

    function __construct( $LogoutUrl )
    {
        $this->LogoutUrl = $LogoutUrl;
    }

    public function renderHtml()
    {
        ob_start();
        ?>
        <a href="<?= $this->LogoutUrl ?>">Logout</a>
        <?php
        return ob_get_clean();
    }

    /**
     *
     * @var string
     */
    protected $LogoutUrl;

}
