<?php

namespace Qck;

use Qck\App\Interfaces\Response;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class TestDriver implements \Qck\Interfaces\TestDriver, \Qck\App\Interfaces\Controller
{

  function __construct( \Qck\Interfaces\ServiceRepo $ServiceRepo )
  {
    $this->ServiceRepo = $ServiceRepo;
  }

  public function run( \Qck\Interfaces\ServiceRepo $ServiceRepo )
  {
    $ExitCode = $this->runInternal( $ServiceRepo );
    /* @var $ResponseFactory Qck\App\Interfaces\ResponseFactory */
    $ResponseFactory = $ServiceRepo->get( \Qck\App\Interfaces\ResponseFactory::class );
    return $ResponseFactory->create( null, $ExitCode );
  }

  public function exec()
  {
    $this->runInternal( $this->ServiceRepo );
  }

  protected function runInternal( \Qck\Interfaces\ServiceRepo $ServiceRepo )
  {
    $ExitCode = Response::EXIT_CODE_OK;
    // Check if we have a specific testsuite
    /* @var $Request \Qck\App\Interfaces\Request */
    $Request = $ServiceRepo->getOptional( \Qck\Interfaces\App\Request::class );
    if ( $Request )
    {
      $TestSuiteFqcn = $Request->get( "suite" );
      if ( !class_exists( $TestSuiteFqcn ) )
        throw new \InvalidArgumentException( "TestSuite class '" . $TestSuiteFqcn . "' not found. Did you set the --suite parameter correctly?" );
      $TestSuite = new $TestSuiteFqcn;
      $ExitCode = $this->runSuite( $TestSuite, $ServiceRepo, $Request );
    }
    else
    {
      $TestSuites = $ServiceRepo->getAll( \Qck\Interfaces\TestSuite::class );
      foreach ( $TestSuites as $TestSuite )
        if ( $this->runSuite( $TestSuite, $ServiceRepo, null ) != Response::EXIT_CODE_OK )
          $ExitCode = Response::EXIT_CODE_INTERNAL_ERROR;
    }

    /* @var $Cleaner \Qck\Interfaces\Cleaner */
    $Cleaner = $ServiceRepo->getOptional( \Qck\Interfaces\Cleaner::class );
    if ( $Cleaner )
      $Cleaner->tidyUp();
    return $ExitCode;
  }

  protected function runSuite( \Qck\Interfaces\TestSuite $TestSuite,
                               \Qck\Interfaces\ServiceRepo $ServiceRepo,
                               \Qck\Interfaces\App\Request $Request = null )
  {
    if ( $Request && !$Request->isCli() )
      print "<html><head><title>" . self::class . "</title></head><body><pre>";
    print PHP_EOL . "********** START of Test Suite " . get_class( $TestSuite ) . PHP_EOL;

    // RUN THE TEST TREE
    $TestClasses = $TestSuite->getTests();

    $TestsRun = array ();
    $TestsFailed = array ();
    foreach ( $TestClasses as $TestClass )
    {
      try
      {
        $this->runTest( $ServiceRepo, $TestClass, $TestClass, $TestsRun, $TestsFailed );
      }
      catch ( \Exception $ex )
      {
        print "********** FAILED: test case " . $TestClass . " (Reason: " . strval( $ex ) . ")" . PHP_EOL;
        $TestsFailed[] = $TestClass;
      }
    }

    $Text = "All tests ok!";
    if ( count( $TestsFailed ) > 0 )
      $Text = count( $TestsFailed ) . " tests failed!";
    print PHP_EOL . PHP_EOL . "********** RESULTS: " . count( $TestClasses ) . " tests run. " . $Text . PHP_EOL;
    if ( $Request && !$Request->isCli() )
      print "</pre></body></html>";

    $ExitCode = count( $TestsFailed ) > 0 ? Response::EXIT_CODE_INTERNAL_ERROR : Response::EXIT_CODE_OK;
    return $ExitCode;
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

    print PHP_EOL . "********** Running test class " . $TestClass . PHP_EOL;
    $TestObj->exec( $ServiceRepo );
    print "********** PASSED: " . $TestClass . PHP_EOL;
    $TestsRun[] = $TestClass;
  }

  /**
   *
   * @var \Qck\Interfaces\ServiceRepo
   */
  protected $ServiceRepo;

}
