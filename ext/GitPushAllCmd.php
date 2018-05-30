<?php

namespace qck\ext;

/**
 * implementation of a system cmd
 *
 * @author micha
 */
class GitPushAllCmd extends Cmd
{

  function __construct( $StartDirectory = null )
  {
    parent::__construct( null, $StartDirectory );
  }

  public function __toString()
  {
    $commitMsg = date( '[d-M-Y H:i:s' );
    $commitMsg .= " " . date_default_timezone_get();
    $commitMsg .= ", from " . get_current_user();
    $commitMsg .= " on " . gethostname();
    $cmd = 'git add -A && git commit -am"' . $commitMsg . '" && git push origin';

    return $cmd;
  }
}
