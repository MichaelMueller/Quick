<?php

namespace Qck\Snippets;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class CssColorStyles implements \Qck\Interfaces\Snippet
{

    function __construct( $FontColor, $FontColor2, $FontColor3, $BgColor )
    {
        $this->FontColor  = $FontColor;
        $this->FontColor2 = $FontColor2;
        $this->FontColor3 = $FontColor3;
        $this->BgColor    = $BgColor;
    }

    public function __toString()
    {
        // Boxes
        ob_start();
        ?>
        <style>

            body
            {
                color: <?= $this->FontColor ?>;   
            }

            /* tags > links */
            a { 
                -webkit-transition: color .4s; 
                transition: color .4s; 
                color: <?= $this->FontColor ?>;
                text-decoration: none;
            }

            a:link, a:visited 
            { 
                color: <?= $this->FontColor2 ?>;   
            }

            a:hover 
            { 
                color: <?= $this->FontColor ?>; 
            }

            a:active 
            { 
                -webkit-transition: color .3s; 
                transition: color .3s; 
                color: <?= $this->FontColor2 ?>; 
            }

            /* tags > forms */
            textarea, input, select
            {
                background: <?= $this->BgColor ?>;                
                border: 1px solid <?= $this->FontColor ?>;
                color: <?= $this->FontColor ?>;   
                outline: none;
            }

            input:placeholder, textarea:placeholder 
            {
                color: <?= $this->FontColor ?>; 
            }
            input:focus::placeholder, textarea:focus::placeholder 
            {
                color: <?= $this->FontColor2 ?>; 
            }

            /* Track */
            ::-webkit-scrollbar-track {
                background: <?= $this->BgColor ?>; 
            }

            /* Handle */
            ::-webkit-scrollbar-thumb {
                background: <?= $this->FontColor2 ?>; 
            }

            /* Handle on hover */
            ::-webkit-scrollbar-thumb:hover {
                background: <?= $this->FontColor ?>; 
            }      

            .Button
            {
                cursor: pointer;
                background-color: <?= $this->BgColor ?>;
                border-color: <?= $this->FontColor ?>;
                transition: background-color .3s, color .3s;     
                color: <?= $this->FontColor2 ?>;
                display: inline-block;
            }

            .Button:hover
            {
                background-color: <?= $this->FontColor2 ?>;
                color: <?= $this->BgColor ?>;
            }
        </style>
        <?php
        return ob_get_clean();
    }

    protected $FontColor;
    protected $FontColor2;
    protected $FontColor3;
    protected $BgColor;

}
