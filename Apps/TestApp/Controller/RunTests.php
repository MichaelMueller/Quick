<?php

namespace Qck\Apps\TestApp\Controller;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class TestRunController implements \Qck\Interfaces\Controller
{

  public function run( \Qck\Interfaces\AppConfig $AppConfig )
  {
    /* @var $config \Qck\Apps\TestApp\AppConfig */
    if ( !$AppConfig->isCli() )
      print "<html><head><title>" . $AppConfig->getAppName() . "</title></head><body><pre>";
    print PHP_EOL . "********** START of test suite" . PHP_EOL;

    // RUN THE TEST TREE
    $TestSuiteFqcn = $AppConfig->getInputs()->get( "suite" );
    /* @var $TestSuite \Qck\Interfaces\TestSuite*/
    $TestSuite = new $TestSuiteFqcn;
    $TestClasses = $TestSuite->getTests();
    
    $TestsRun = array ();
    $TestsFailed = array ();
    foreach ( $TestClasses as $TestClass )
    {
      try
      {
        $this->runTest( $config, $testClass, $testClass, $testsRun, $testsFailed, $FilesToDelete );
      }
      catch ( \Exception $ex )
      {
        foreach ( $FilesToDelete as $FileToDelete )
          $this->rmfile( $FileToDelete );

        print "********** FAILED: test case " . $testClass . " (Reason: " . strval( $ex ) . ")" . PHP_EOL;
        $testsFailed[] = $testClass;
      }
    }

    $text = "All tests ok!";
    if ( count( $testsFailed ) > 0 )
      $text = count( $testsFailed ) . " tests failed!";
    print PHP_EOL . PHP_EOL . "********** RESULTS: " . count( $testClasses ) . " tests run. " . $text . PHP_EOL;
    if ( !$AppConfig->isCli() )
      print "</pre></body></html>";
  }

  protected function runTest( $config, $testClass, $filenodedbTestClass, &$testsRun,
                              &$testsFailed )
  {
    if ( in_array( $testClass, $testsRun ) )
      return;

    $testObj = new $testClass;
    $requiredTests = $testObj->getRequiredTests();

    if ( is_array( $requiredTests ) )
    {
      foreach ( $requiredTests as $requiredTest )
      {
        if ( in_array( $filenodedbTestClass, $requiredTests ) )
          throw new \Exception( "Cyclic Test Dependency for test Class: " . $filenodedbTestClass );

        $this->runTest( $config, $requiredTest, $filenodedbTestClass, $testsRun, $testsFailed, $FilesToDelete );
      }
    }

    print PHP_EOL . "********** Running test class " . $testClass . PHP_EOL;
    $testObj->run( $config, $FilesToDelete );
    print "********** PASSED: " . $testClass . PHP_EOL;
    $testsRun[] = $testClass;
  }
}
