<?php

namespace qck\apps\database\controller;

/**
 * Description of databaseController
 *
 * @author muellerm
 */
class LoginForm implements \qck\interfaces\Controller
{

  public function run( \qck\interfaces\AppConfig $config )
  {
    /* @var $config \qck\apps\database\AppConfig */
    $Page = new \qck\html\Page();
    $Page->setTitle($config->getAppName());
    
    $Container = new \qck\html\Container();
    $Container->setWidth("100%");
    $Page->setCentralWidget($Container);
    
    $ContainerLayout = new \qck\html\HorizontalCenterLayout();
    $Container->setLayout($ContainerLayout);
    
    $Form = new \qck\html\Form();
    $Form->setWidth("50%");    
    $Form->setMinHeight("100vh");    
    $ContainerLayout->setWidget($Form);
    
    $FormLayout = new \qck\html\FlexLayout();        
    $FormLayout->setDirection(\qck\html\FlexLayout::COLUMN);
    $FormLayout->setHAlign(\qck\html\FlexLayout::VALIGN_CENTER);
    
    $Form->setLayout($FormLayout);
    
    $UserNameLabel = new \qck\html\Text("Username: ");
    $FormLayout->addWidget($UserNameLabel, null, "15px");
    
    $UserNameTxt = new \qck\html\TextField("Username", "");
    $FormLayout->addWidget($UserNameTxt, null, "15px");    
    
    return new \qck\core\Response( $Page );
  }
}
