<?php

namespace Qck\Interfaces;

/**
 * Interface for a Path object
 * @author muellerm
 */
interface Route
{

  /**
   * @return string the Path extension. no dot. if this is a dir null will be returned
   */
  function getRouteName();

  /**
   * @return string the Path basename, i.e. the Pathname without extension
   */
  function getAppFunctionFqcn();

}
