<?php

namespace Qck\Apps\TestApp\Controller;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Run implements \Qck\Interfaces\Controller
{

  public function run( \Qck\Interfaces\AppConfig $AppConfig )
  {
    /* @var $config \Qck\Apps\TestApp\AppConfig */
    if ( !$AppConfig->isCli() )
      print "<html><head><title>" . $AppConfig->getAppName() . "</title></head><body><pre>";
    print PHP_EOL . "********** START of test suite" . PHP_EOL;

    // RUN THE TEST TREE
    $TestSuiteFqcn = $AppConfig->getInputs()->get( "s" );
    /* @var $TestSuite \Qck\Interfaces\TestSuite */
    $TestSuite = new $TestSuiteFqcn;
    $TestClasses = $TestSuite->getTests();

    $TestsRun = array ();
    $TestsFailed = array ();
    $FilesToBeDeleted = array ();
    foreach ( $TestClasses as $TestClass )
    {
      try
      {
        $this->runTest( $AppConfig, $TestClass, $TestClass, $TestsRun, $TestsFailed, $FilesToBeDeleted );
      }
      catch ( \Exception $ex )
      {
        foreach ( $FilesToBeDeleted as $FileToBeDeleted )
          \Qck\Core\Test::delete( $FileToBeDeleted );

        print "********** FAILED: test case " . $TestClass . " (Reason: " . strval( $ex ) . ")" . PHP_EOL;
        $TestsFailed[] = $TestClass;
      }
    }

    $Text = "All tests ok!";
    if ( count( $TestsFailed ) > 0 )
      $Text = count( $TestsFailed ) . " tests failed!";
    print PHP_EOL . PHP_EOL . "********** RESULTS: " . count( $TestClasses ) . " tests run. " . $Text . PHP_EOL;
    if ( !$AppConfig->isCli() )
      print "</pre></body></html>";
  }

  protected function runTest( \Qck\Apps\TestApp\AppConfig $AppConfig, $TestClass,
                              $RootTestClass, &$TestsRun, &$TestsFailed,
                              &$FilesToBeDeleted )
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

        $this->runTest( $AppConfig, $RequiredTest, $RootTestClass, $TestsRun, $TestsFailed, $FilesToBeDeleted );
      }
    }

    print PHP_EOL . "********** Running test class " . $TestClass . PHP_EOL;
    $TestObj->run( $AppConfig, $FilesToBeDeleted );
    print "********** PASSED: " . $TestClass . PHP_EOL;
    $TestsRun[] = $TestClass;
  }
}
