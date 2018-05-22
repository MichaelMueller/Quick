<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
interface SchemaElement
{

  /**
   * 
   * @return string
   */
  function getId();

  /**
   * 
   * @return string
   */
  function getName();
  
  /**
   * 
   * @param \qck\db\SchemaElement $Other
   * @return bool
   */
  function hasChanged( SchemaElement $Other );
  
  /**
   * @return SqlMapper Description
   */
  function getSqlMapper();
}
