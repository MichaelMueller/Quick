<?php

namespace Qck\Demo\UserDirectoryApp\Snippets;

/**
 * Description of LoginForm
 *
 * @author muellerm
 */
class LoginForm implements \Qck\Interfaces\HtmlSnippet
{

    function __construct( $LoginUrl )
    {
        $this->LoginUrl = $LoginUrl;
    }

    function setRedirectUrl( $RedirectUrl )
    {
        $this->RedirectUrl = $RedirectUrl;
    }

    function setMessage( $Message )
    {
        $this->Message = $Message;
    }

    function setError( $Error )
    {
        $this->Error = $Error;
    }

    public function renderHtml()
    {
        ob_start();
        ?>
        <div id="LoginForm">
            <form action="<?= $this->LoginUrl ?>" method="post">
                <p>Please login:</p>
                <p>Username: <input type="text" name="Username" placeholder="Enter Username"></p>
                <p>Password: <input type="password" name="Password" placeholder="Enter Password"></p>
                <p><input type="submit" class="button" value="Login"></p>

                <?php if ($this->RedirectUrl): ?>
                    <input type="hidden" name="redirect" value="<?= $_REQUEST["RedirectUrl"] ?>">
            <?php endif; ?>        
            </form>
            <?php if ($this->Error): ?>
                <p style="color: red"><?= $this->Error ?></p>
            <?php endif; ?>
            <?php if ($this->Message): ?>
                <p><?= $this->Message ?></p>
        <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     *
     * @var string
     */
    protected $LoginUrl;

    /**
     *
     * @var string
     */
    protected $RedirectUrl;

    /**
     *
     * @var string
     */
    protected $Message;

    /**
     *
     * @var string
     */
    protected $Error;

}
