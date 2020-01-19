<?php

namespace Qck\Interfaces;

/**
 * array of scalars (! no complex hierarchies) serializer
 * @author muellerm
 */
interface Serializer
{

  /**
   * @param \mbits\DataObject $DataObject
   * @return string a string representing the DataObject
   */
  function serialize( array $Array );

  /**
   * @param string $DataString
   * @param string $FqcnClassName for creating the actual dataobject class - if null a plain DataObject is created
   * @return DataObject 
   */
  function unserialize( $DataString );

  /**
   * 
   * @return string an extension for the file to be written WITHOUT DOT!
   */
  function getFileExtension();
}
