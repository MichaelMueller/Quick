<?php

namespace Qck;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class DataDirectory implements \Qck\Interfaces\DataDirectory, Interfaces\ObjectIdGenerator
{

  function __construct( $DataDir, \Qck\Interfaces\PathFactory $PathFactory, $FileExtension )
  {
    $this->DataDir       = $DataDir;
    $this->PathFactory   = $PathFactory;
    $this->FileExtension = $FileExtension;
  }

  /**
   * 
   * @param mixed $Id
   * @return \Qck\Interfaces\Path
   */
  public function getFile( $Id )
  {
    static $Zerofill = 15;
    $FileName        = is_int( $Id ) ? str_pad( $Id, $Zerofill, '0', STR_PAD_LEFT ) : $Id;
    $Path            = $this->DataDir . DIRECTORY_SEPARATOR . $FileName . "." . $this->FileExtension;
    return $this->PathFactory->createPathFromPath( $Path );
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
   * @var \Qck\Interfaces\PathFactory
   */
  protected $PathFactory;

  /**
   *
   * @var string
   */
  protected $FileExtension;

}
