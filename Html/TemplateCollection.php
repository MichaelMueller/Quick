<?php

namespace Qck\Html;

/**
 * Description of BootstrapPage
 *
 * @author muellerm
 */
class TemplateCollection implements \Qck\Interfaces\Template
{

  /**
   * 
   * @param \Qck\Interfaces\Template $Template
   */
  public function addTemplate( \Qck\Interfaces\Template $Template )
  {
    $this->templates[] = $Template;
  }

  public function render()
  {
    $output = "";
    foreach ( $this->templates as $template )
      $output = $template->render();
    return $output;
  }

  /**
   *
   * @var \Qck\Interfaces\Template[]
   */
  protected $templates;

}
