<?php

namespace qck\ext;

/**
 * implementation of a system cmd
 *
 * @author micha
 */
class WindowsShellCmd implements interfaces\Cmd
{

  function __construct( interfaces\Cmd $Cmd, $ShellExe = null )
  {
    $this->Cmd = $Cmd;
    $this->ShellExe = $ShellExe;
  }

  function run( &$output = false )
  {
    $this->guessShellExe();

    $Cmd = new Cmd( ( string ) $this );
    return $Cmd->run( $output );
  }

  public function guessShellExe( $preferMsysGit = true )
  {
    if ( $this->ShellExe )
      return;
    $possiblePaths = [ "C:/Program Files/Git/bin/sh.exe", "C:/Program Files (x86)/Git/bin/sh.exe", "C:/cygwin/bin/sh.exe", "C:/cygwin64/Git/bin/sh.exe" ];
    if ( !$preferMsysGit )
      $possiblePaths = array_reverse( $possiblePaths );

    foreach ( $possiblePaths as $possiblePath )
    {
      if ( file_exists( $possiblePath ) )
      {
        $this->ShellExe = '"' . $possiblePath . '"';
        break;
      }
    }
  }

  public function __toString()
  {
    $this->guessShellExe();
    $Cmd = $this->ShellExe;
    if ( $this->Cmd->getStartDirectory() )
      $Cmd .= " --cd=" . $this->Cmd->getStartDirectory();;
    $Cmd .= ' -c "' . (( string ) $this->Cmd) . '"';
    return $Cmd;
  }

  public function getStartDirectory()
  {
    return $this->Cmd->getStartDirectory();
  }

  /**
   *
   * @var interfaces\Cmd
   */
  protected $Cmd;
  protected $ShellExe;

}
