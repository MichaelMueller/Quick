<?php

namespace qck\html;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Dialog extends Container
{

  function __construct( $Title )
  {
    $this->Title = $Title;
    $this->setStyle( "border-radius", "10px" );
    $this->setStyle( "border", "solid 1px gray" );
  }

  function setTitle( $Title )
  {
    $this->Title = $Title;
  }

  function proxyRender()
  {
    ob_start();
    ?>

    <div style="border-top-left-radius: 10px; 
         border-top-right-radius: 10px; width: 100%; background-color: #4a8cdb; color: #fff; font-size: 20px;"><?= $this->Title ?></div>
    <div> <?= parent::proxyRender() ?> </div>

    <?php
    return ob_get_clean();
  }

  private $Title;

}
