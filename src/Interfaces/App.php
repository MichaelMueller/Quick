<?php

namespace Qck\Interfaces;

/**
 * An interface for the FrontController / Start Controller / Framework
 * 
 * @author muellerm
 */
interface App extends Service
{
  function run();
}
