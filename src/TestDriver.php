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
    /* @var $TestSuites \Qck\Apps\TestApp\TestSuite[] */
    $TestSuites = $this->ServiceRepo->
  }

  protected function runSuite( $SuiteFqcn )
  {
    /* @var $config \Qck\Apps\TestApp\AppConfig */
    if ( !$AppConfig->getRequest()->isCli() )
      print "<html><head><title>" . $AppConfig->getAppName() . "</title></head><body><pre>";
    print PHP_EOL . "********** START of test suite" . PHP_EOL;

    // RUN THE TEST TREE
    $TestSuiteFqcn = $AppConfig->getRequest()->get( "suite" );
    if ( !class_exists( $TestSuiteFqcn ) )
      throw new \InvalidArgumentException( "TestSuite class '" . $TestSuiteFqcn . "' not found. Did you set the --suite parameter correctly?" );
    /* @var $TestSuite \Qck\Interfaces\TestSuite */
    $TestSuite = new $TestSuiteFqcn;
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
    if ( !$AppConfig->getRequest()->isCli() )
      print "</pre></body></html>";

    $ExitCode = count( $TestsFailed ) > 0 ? \Qck\Interfaces\Response::EXIT_CODE_INTERNAL_ERROR : \Qck\Interfaces\Response::EXIT_CODE_OK;
    return new \Qck\AppFramework\Response( null, $ExitCode );
  }

  protected function runTest( $TestClass, $RootTestClass, &$TestsRun, &$TestsFailed )
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
          throw new \Exception( "Cyclic Test Dependency for test Class: " . $RootTestClass );

        $this->runTest( $RequiredTest, $RootTestClass, $TestsRun, $TestsFailed );
      }
    }

    print PHP_EOL . "********** Running test class " . $TestClass . PHP_EOL;
    try
    {
      $TestContext = new \Qck\TestApp\TestContext();
      $TestObj->run( $TestContext );
    }
    catch ( \Exception $e )
    {
      $TestContext->deleteAllFiles();
      throw $e;
    }
    print "********** PASSED: " . $TestClass . PHP_EOL;
    $TestsRun[] = $TestClass;
  }

  /**
   *
   * @var \Qck\Interfaces\ServiceRepo
   */
  protected $ServiceRepo;

}
