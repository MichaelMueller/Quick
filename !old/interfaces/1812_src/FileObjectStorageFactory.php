<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface FileObjectStorageFactory
{

  /**
   * @return ObjectStorage
   */
  function createFileObjectStorage( $Id, File $File, Serializer $Serializer, ObjectDatabase $ObjectDatabase );
}
