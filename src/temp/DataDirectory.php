<?php

namespace Qck;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class DataDirectory implements \Qck\Interfaces\DataDirectory, Interfaces\ObjectIdGenerator
{

  function __construct( $DataDir, \Qck\Interfaces\FileFactory $FileFactory, $FileExtension )
  {
    $this->DataDir       = $DataDir;
    $this->FileFactory   = $FileFactory;
    $this->FileExtension = $FileExtension;
  }

  /**
   * 
   * @param mixed $Id
   * @return \Qck\Interfaces\File
   */
  public function getFile( $Id )
  {
    static $Zerofill = 15;
    $FileName        = is_int( $Id ) ? str_pad( $Id, $Zerofill, '0', STR_PAD_LEFT ) : $Id;
    $Path            = $this->DataDir . DIRECTORY_SEPARATOR . $FileName . "." . $this->FileExtension;
    return $this->FileFactory->createFileObjectFromPath( $Path );
  }

  public function generateNextId()
  {
    $Id       = 0;
    $DataFile = null;
    do
    {
      ++$Id;
      $DataFile = $this->getFile( $Id );
    }
    while ( $DataFile->exists() );
    $DataFile->touch();
    return $Id;
  }

  /**
   *
   * @var string
   */
  protected $DataDir;

  /**
   *
   * @var \Qck\Interfaces\FileFactory
   */
  protected $FileFactory;

  /**
   *
   * @var string
   */
  protected $FileExtension;

}
