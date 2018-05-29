<?php

namespace qck\StructuredData\Interfaces;

/**
 *
 * @author muellerm
 */
interface GraphSchema
{

  /**
   * @return array of strings
   */
  function getNodeSchemaNames();

  /**
   * @return NodeSchema
   */
  function getNodeSchema( $Name );
}
