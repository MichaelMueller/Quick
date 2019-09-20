<?php

namespace Qck\Demo\AuthenticationDemo\Snippets;

/**
 * Description of LoginForm
 *
 * @author muellerm
 */
class LoggedInPage implements \Qck\Interfaces\HtmlSnippet
{

    function __construct( $LogoutUrl, $Username )
    {
        $this->LogoutUrl = $LogoutUrl;
        $this->Username = $Username;
    }

    public function renderHtml()
    {
        ob_start();
        ?>
        <p>Logged in as user <?= $this->Username ?></p>
        <p><a href="<?= $this->LogoutUrl ?>">Logout</a></p>
        <?php
        return ob_get_clean();
    }

    /**
     *
     * @var string
     */
    protected $LogoutUrl;

    /**
     *
     * @var string
     */
    protected $Username;

}
