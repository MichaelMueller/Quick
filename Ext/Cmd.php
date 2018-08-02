<?php

namespace Qck\Ext;

/**
 * implementation of a system cmd
 *
 * @author micha
 */
class Cmd implements \Qck\Interfaces\Cmd
{

  function __construct( $Cmd, $StartDirectory = null, array $Args = [] )
  {
    $this->Cmd = $Cmd;
    $this->StartDirectory = $StartDirectory;
    $this->Args = $Args;
  }

  function setStartDirectory( $StartDirectory )
  {
    $this->StartDirectory = $StartDirectory;
  }

  function run( &$output = false )
  {
    // change WorkingDir if needed ( remember old working dir )
    $cwd = null;
    if ( $this->StartDirectory )
    {
      $cwd = getcwd();
      chdir( $this->StartDirectory );
    }
    // if output is not null
    if ( $output !== false )
      ob_start();

    // run command
    $retVal = -1;
    if ( !system( ( string ) $this, $retVal ) )
      $retVal = -1;

    // get output if neessary
    if ( $output !== false )
      $output = ob_get_clean();

    // change back cwd if necessary
    if ( $cwd )
      chdir( $cwd );
    return $retVal;
  }

  public function __toString()
  {
    $cmd = $this->Cmd;
    if ( count( $this->Args ) > 0 )
      $cmd .= " " . (implode( " ", $this->Args ));
    return $cmd;
  }

  public function getStartDirectory()
  {
    return $this->StartDirectory;
  }

  /**
   *
   * @var string
   */
  protected $Cmd;

  /**
   *
   * @var string
   */
  protected $StartDirectory;

  /**
   *
   * @var array
   */
  protected $Args;

}
