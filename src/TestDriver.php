<?php

namespace Qck;

use Qck\Interfaces\HttpResponder;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class TestDriver implements \Qck\Interfaces\TestDriver, \Qck\Interfaces\Controller
{

  function __construct( \Qck\Interfaces\ServiceRepo $ServiceRepo )
  {
    $this->ServiceRepo = $ServiceRepo;
  }

  public function run( \Qck\Interfaces\ServiceRepo $ServiceRepo )
  {
    $ExitCode = $this->runInternal( $ServiceRepo );
    /* @var $ResponseFactory Qck\Interfaces\ResponseFactory */
    $ResponseFactory = $ServiceRepo->get( \Qck\Interfaces\ResponseGuard::class );
    return $ResponseFactory->getResponse( null, $ExitCode );
  }

  public function exec()
  {
    $this->runInternal( $this->ServiceRepo );
  }

  protected function msg( $Msg, $Append = false )
  {
    $date = \DateTime::createFromFormat( 'U.u', microtime( TRUE ) );
    $datetime = $date->format( 'Y-m-d H:i:s.u' );
    if ( $Append )
      print $Msg;
    else
      print PHP_EOL . self::class . ", " . $datetime . ": " . $Msg;
  }

  protected function runInternal( \Qck\Interfaces\ServiceRepo $ServiceRepo )
  {
    $ExitCode = HttpResponder::EXIT_CODE_OK;
    // Check if we have a specific testsuite
    /* @var $Request \Qck\Interfaces\Request */
    $Request = $ServiceRepo->getOptional( \Qck\Interfaces\App\Request::class );
    if ( $Request && !$Request->wasRunFromCommandLine() )
      print "<html><head><title>" . self::class . "</title></head><body><pre>";

    $TestsRun = array ();
    $TestsFailed = array ();
    if ( $Request )
    {
      $TestSuiteFqcn = $Request->get( "suite" );
      if ( !class_exists( $TestSuiteFqcn ) )
        throw new \InvalidArgumentException( "TestSuite class '" . $TestSuiteFqcn . "' not found. Did you set the --suite parameter correctly?" );
      $TestSuite = new $TestSuiteFqcn;
      $this->runSuite( $TestSuite, $ServiceRepo, $TestsRun, $TestsFailed );
    }
    else
    {
      $TestSuites = $ServiceRepo->getAll( \Qck\Interfaces\TestSuite::class );
      foreach ( $TestSuites as $TestSuite )
        $this->runSuite( $TestSuite, $ServiceRepo, $TestsRun, $TestsFailed );
      if ( count( $TestSuites ) == 0 )
        $this->msg( ": No TestSuites found!!!" );
    }

    $Text = "All tests ok!";
    if ( count( $TestsFailed ) > 0 )
      $Text = count( $TestsFailed ) . " tests failed!";
    $this->msg( "******** RESULTS: " . count( $TestsRun ) . " tests run. " . $Text . " ********" );

    if ( $Request && !$Request->wasRunFromCommandLine() )
      print "</pre></body></html>";
    /* @var $Cleaner \Qck\Interfaces\Cleaner */
    $Cleaner = $ServiceRepo->getOptional( \Qck\Interfaces\Cleaner::class );
    if ( $Cleaner )
      $Cleaner->tidyUp();
    return $ExitCode;
  }

  protected function runSuite( \Qck\Interfaces\TestSuite $TestSuite,
                               \Qck\Interfaces\ServiceRepo $ServiceRepo, array &$TestsRun,
                               array &$TestsFailed )
  {
    $this->msg( "Start of Test Suite " . get_class( $TestSuite ) );

    // RUN THE TEST TREE
    $TestClasses = $TestSuite->getTests();

    foreach ( $TestClasses as $TestClass )
    {
      try
      {
        $this->runTest( $ServiceRepo, $TestClass, $TestClass, $TestsRun, $TestsFailed );
      }
      catch ( \Exception $ex )
      {
        $this->msg( "FAILED. Reason: " . strval( $ex ) . ")", true );
        $TestsFailed[] = $TestClass;
      }
    }

    $this->msg( "End of Test Suite " . get_class( $TestSuite ) );
  }

  protected function runTest(
  \Qck\Interfaces\ServiceRepo $ServiceRepo, $TestClass, $RootTestClass, array &$TestsRun,
  array &$TestsFailed )
  {
    if ( in_array( $TestClass, $TestsRun ) )
      return;

    /* @var $TestObj \Qck\Interfaces\Test */
    $TestObj = new $TestClass;
    $RequiredTests = $TestObj->getRequiredTests();

    if ( is_array( $RequiredTests ) )
    {
      foreach ( $RequiredTests as $RequiredTest )
      {
        if ( in_array( $RootTestClass, $RequiredTests ) )
          throw new \LogicException( "Cyclic Test Dependency for test Class: " . $RootTestClass );

        $this->runTest( $ServiceRepo, $RequiredTest, $RootTestClass, $TestsRun, $TestsFailed );
      }
    }

    $this->msg( "Running test class " . $TestClass . ": " );
    $TestObj->exec( $ServiceRepo );
    $this->msg( "PASSED", true );
    $TestsRun[] = $TestClass;
  }

  /**
   *
   * @var \Qck\Interfaces\ServiceRepo
   */
  protected $ServiceRepo;

}
