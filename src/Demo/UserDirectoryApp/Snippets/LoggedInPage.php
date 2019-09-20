<?php

namespace Qck\Demo\UserDirectoryApp\Snippets;

/**
 * Description of LoginForm
 *
 * @author muellerm
 */
class LoginForm implements \Qck\Interfaces\HtmlSnippet
{

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
