<?php

namespace Qck\TestApp\Controller;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class TestDriver implements \Qck\Interfaces\TestDriver
{

  function __construct( \Qck\Interfaces\ServiceRepo $ServiceRepo )
  {
    $this->ServiceRepo = $ServiceRepo;
  }

  public function run()
  {
    // Check if we have a specific testsuite
    /* @var $Request \Qck\Apps\TestApp\TestSuite[] */
    $Request = $this->ServiceRepo->getOptional( \Qck\Interfaces\App\Request::class );
    if ( $Request )
    {
      $TestSuiteFqcn = $Request->get( "suite" );
      if ( !class_exists( $TestSuiteFqcn ) )
        throw new \InvalidArgumentException( "TestSuite class '" . $TestSuiteFqcn . "' not found. Did you set the --suite parameter correctly?" );
      $TestSuite = new $TestSuiteFqcn;
      $this->runSuite( $TestSuite, $Request );
    }
    else
    {

      $TestSuites = $this->ServiceRepo->getAll( \Qck\Interfaces\TestSuite::class );
      foreach ( $TestSuites as $TestSuite )
        $this->runSuite( $TestSuite );
    }
  }

  protected function runSuite( \Qck\Interfaces\TestSuite $TestSuite,
                               \Qck\Interfaces\App\Request $Request = null )
  {
    /* @var $config \Qck\Apps\TestApp\AppConfig */
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
        $this->runTest( $TestClass, $TestClass, $TestsRun, $TestsFailed );
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

    $ExitCode = count( $TestsFailed ) > 0 ? \Qck\Interfaces\Response::EXIT_CODE_INTERNAL_ERROR : \Qck\Interfaces\Response::EXIT_CODE_OK;

    // Check if we have a specific testsuite
    /* @var $ResponseFactory \Qck\Interfaces\App\ResponseFactory */
    $ResponseFactory = $this->ServiceRepo->getOptional( \Qck\Interfaces\App\ResponseFactory::class );
    return $ResponseFactory ? $ResponseFactory->create( null, $ExitCode ) : $ExitCode;
  }

  protected function runTest( $TestClass, $RootTestClass, array &$TestsRun,
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

        $this->runTest( $RequiredTest, $RootTestClass, $TestsRun, $TestsFailed );
      }
    }

    print PHP_EOL . "********** Running test class " . $TestClass . PHP_EOL;
    $TestObj->run();
    print "********** PASSED: " . $TestClass . PHP_EOL;
    $TestsRun[] = $TestClass;
  }

  /**
   *
   * @var \Qck\Interfaces\ServiceRepo
   */
  protected $ServiceRepo;

}
