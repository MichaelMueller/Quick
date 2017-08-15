<?php

namespace qck\html;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Page extends \qck\abstracts\HtmlElement
{

  public function render()
  {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
      <head>
        <meta charset="<?= $this->Charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">      
        <title><?= $this->Title ?></title>   

        <style>
          /************ BODY AND BLOCK ELEMENT STYLING ****************************/
          html, body, div, span, img, a, textarea, input, button, select
          {
            padding: 0;
            margin: 0;
            border: 0;
            box-sizing: border-box;
            min-width: 240px;
          }

          body 
          {
            font-family: Arial, Helvetica, sans-serif;
            color: #545454;
            background-color: #fff;
            font-size: calc(10px + 0.5vw);
          }

          img, video, audio
          {  
            width: 100%;
            height: auto;
          }

          /************ LINKS ****************************/
          a { 
            -webkit-transition: color .4s; 
            transition: color .4s; 
            color: #545454;
            text-decoration: none;
          }

          a:link, a:visited 
          { 
            color: #FAAA40;   
          }

          a:hover 
          { 
            color: #545454; 
          }

          a:active 
          { 
            -webkit-transition: color .3s; 
            transition: color .3s; 
            color: #3170A1; 
          }
          /************ END LINKS ****************************/


          /************ FORMS ****************************/
          textarea, input, select
          {
            background-color: #fff;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            border: 1px solid #545454;
            display: inline-block;
            color: #545454;
            font-size: calc(10px + 0.5vw);
            padding: 5px;
            outline: none;
            width: 100%;
          }
          
          input[type="checkbox"]
          {
            width: auto;
            margin-right: calc(5px + 0.0vw);
          }

          input:-webkit-input-placeholder, textarea:-webkit-input-placeholder {
            color: #545454;
          }
          input:focus::-webkit-input-placeholder, textarea:focus::-webkit-input-placeholder {
            color: #3170A1;
          }

          /* Firefox < 19 */
          input:-moz-placeholder {
            color: #545454;
          }
          input:focus:-moz-placeholder {
            color: #3170A1;
          }

          /* Firefox > 19 */
          input::-moz-placeholder {
            color: #545454;
          }
          input:focus::-moz-placeholder {
            color: #3170A1;
          }

          /* Internet Explorer 10 */
          input:-ms-input-placeholder {
            color: #545454;
          }
          input:focus:-ms-input-placeholder {
            color: #3170A1;
          }

          /************ END SPECIFIC ID STYLES: BACKGROUND, FONT-COLOR, BORDER ***************/
        </style>
      </head>
      <body<?= $this->getAttributeString() ?>>

        <?= $this->CentralWidget->render() ?>

      </body>
    </html>
    <?php
    return ob_get_clean();
  }

  protected function proxyRender()
  {
    
  }

  function setCentralWidget( \qck\interfaces\HtmlElement $CentralWidget )
  {
    $this->CentralWidget = $CentralWidget;
  }

  function setTitle( $Title )
  {
    $this->Title = $Title;
  }

  function setCharset( $Charset )
  {
    $this->Charset = $Charset;
  }

  /**
   *
   * @var \qck\interfaces\HTMLWidget
   */
  protected $CentralWidget;
  protected $Title;
  protected $Charset = "utf-8";

}
