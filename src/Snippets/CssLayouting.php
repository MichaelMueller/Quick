<?php

namespace Qck\Snippets;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class CssLayouting implements \Qck\Interfaces\Snippet
{

    function __construct( $BaseSize = "10px + 1.0vw" )
    {
        $this->BaseSize = $BaseSize;
    }

    public function __toString()
    {

        // Boxes
        ob_start();
        ?>
        <style>
            body {
                width: 100%;
                min-height: 100vh;
                overflow: visible;    
                display: flex;
                flex-direction: column;
                align-items: stretch;
            }

            img
            {
                width: 100%;
                height: auto;
            }            

            /* Textsize */
            .Capitalized
            {
                text-transform: capitalize;
            }

            .Uppercase
            {
                text-transform: uppercase;
            }

            .BgImgCover
            {
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;    
            }

            .BgImgContain
            {
                background-position: center;
                background-repeat: no-repeat;
                background-size: contain;                
            }

            .TextCenter
            {
                text-align: center;
            }

            .Col
            {
                display: flex;      
                flex-direction: column;
            }

            .Row
            {
                display: flex;      
                flex-direction: row;
            }

            .WrapRow
            {
                display: flex;      
                flex-direction: row;
                flex-wrap: wrap;
            }

            .Cell
            {
                flex: 1;
            }

            .CrossStart
            {
                align-items: flex-start;
            }


            .CrossEnd
            {
                align-items: flex-start;
            }

            .CrossCenter 
            {
                align-items: center;
            }

            .CrossStretch 
            {
                align-items: stretch;
            }

            .SelfLeft 
            {
                align-self: flex-start;
            }

            .SelfEnd
            {
                align-self: flex-end;
            }

            .SelfCenter 
            {
                align-self: center;
            }

            .SelfStretch 
            {
                align-self: stretch;
            }

            .Center
            {
                justify-content: center;
            }

            .SpaceBetween
            {
                justify-content: space-between;
            }

            .End
            {
                justify-content: flex-end;
            }

            .Flex1 
            {
                flex: 1;
            }

            .Flex2
            {
                flex: 2;
            }

            .Flex3 
            {
                flex: 3;
            }

            .Flex100
            {
                flex: 100;
            }
            .FlexStretch
            {
                flex: 100;
            }


            .Hidden
            {
                display: none;
            }
            
            .Rounded 
            {
                border-radius: 50%;
            }

        </style>
        <?php

        return ob_get_clean();
    }

    protected function getSize( $Multiplier = 1.0 )
    {
        return sprintf( "calc(%f*(%s))", $Multiplier, $this->BaseSize );
    }

    protected $BaseSize;

}
