<?php

namespace qck\apps\database\templates;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Style implements \qck\interfaces\Template
{

  public function render()
  {
    ob_start();
    ?>
    <style>
      /************ RULES ****************************
      content consists of layout stuff and content elements. content elements: paragraphs containing text images forms.
      
      gray (dark to bright): #1f1f1f, #303030, #808080, #fff
      colors: #FAAA40 (mbits orange), , #ff6666 (red), #f8bb00 (yellow)
      harmonic blue to mbits orange: 3170A1 7AABD0 0C4673
      */

      /************ BODY AND BLOCK ELEMENT STYLING ****************************/
      *
      {
        box-sizing: border-box;
        padding: 0;
        margin: 0;
        border: 0;
      }

      body 
      {
        background-image: url("img/texture.png");
        background-repeat: repeat;
        font-family: "Trebuchet MS", Helvetica, sans-serif;
        color: #1f1f1f;
        background-color: #fff;
        font-size: calc(8px + 0.8vw);
      }

      .form-control
      {
        border: 1px solid gray;
        border-radius: calc(8px + 0.2vw);
        font-family: "Trebuchet MS", Helvetica, sans-serif;
        font-size: calc(8px + 0.8vw);
        color: #1f1f1f;
        background-color: #fff;
        padding: calc(4px + 0.4vw);
        width: 100%;
      }
      
      .form-control:focus
      {
        outline: none;
        border-color: #5bc0de;
        box-shadow: 0 0 10px #5bc0de;        
      }
      
      input[type="checkbox"]
      {
        width: auto;
        margin-right: calc(8px + 0.4vw);
      }
      
     .btn
      {
        font-family: "Trebuchet MS", Helvetica, sans-serif;    
        font-size: calc(8px + 0.8vw);
        padding: calc(4px + 0.4vw);
        border: 1px solid rgba(27,31,35,0.2);
        background-image: linear-gradient(-180deg, #5bc0de 0%, #1786a7 90%);
        border-radius: 0.25em;   
        cursor: pointer;
        color: #fff;
      }
      
      .btn:hover
      {
        background-image: linear-gradient(-180deg, #1786a7 0%, #186b84 90%);        
      }
      
      .velem
      {
        margin-top: calc(8px + 0.8vw);
      }
      
      .centerLayout
      {
        margin-left: auto;
        margin-right: auto;
        min-height: 99vh;
        width: 50%;
        display: flex;
        flex-direction: column;
        justify-content: center;
      }

      .dialogBar
      {
        background: #1786a7;
        border: 1px solid gray;
        box-shadow: 0 2px 2px gray, inset 0 1px 0 #fff;  
        border: 1px solid gray;
        border-top-left-radius: calc(8px + 0.2vw);
        border-top-right-radius: calc(8px + 0.2vw);
        padding: calc(8px + 0.8vw);
        color: #fff; 
        text-shadow: 1px 1px #1e5799;        
      }

      .dialogBody
      {
        box-shadow: 0 2px 2px gray;  
        border-right: 1px solid gray;  
        border-left: 1px solid gray;  
        border-bottom: 1px solid gray;  
        background-color: #fff;      
        padding: calc(8px + 0.8vw);
        border-bottom-left-radius: calc(8px + 0.2vw);
        border-bottom-right-radius: calc(8px + 0.2vw);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
      }



    </style>
    <?php

    return ob_get_clean();
  }
}
