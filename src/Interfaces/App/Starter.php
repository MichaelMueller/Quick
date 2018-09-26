<?php

namespace Qck\Interfaces\App;

/**
 * An interface for the FrontController / Start Controller / Framework
 * 
 * @author muellerm
 */
interface Starter extends Service
{

  function run();
}
