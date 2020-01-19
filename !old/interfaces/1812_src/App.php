<?php

namespace Qck\Interfaces;

/**
 * An Config Service Class
 * 
 * @author muellerm
 */
interface App extends Functor
{

  /**
   * @return bool
   */
  function wasInvokedFromCli();

  /**
   * @return Inputs
   */
  function getInputs();

  /**
   * @return Session
   */
  function getSession();

  /**
   * @return UserDb
   */
  function getUserDb();

  /**
   * @return array of method names
   */
  function getShellMethods();
}
