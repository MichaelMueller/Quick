<?php
namespace qck\core\interfaces;

/**
 *
 * @author muellerm
 */
interface ErrorController extends Controller
{  
  function setErrorCode($errorCode=Response::CODE_SERVER_ERROR);
}
