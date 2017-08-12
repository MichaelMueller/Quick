<?php

namespace qck\apps\testapp\controller;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Start implements \qck\interfaces\Controller
{

  public function run( \qck\interfaces\AppConfig $config )
  {
    /* @var $config \qck\apps\testapp\AppConfig */
    // COLLECT TEST FILES AND RUN
    header( \qck\interfaces\Response::CT_TEXTPLAIN_UTF8 );

    print PHP_EOL . "********** START of test suite" . PHP_EOL;

    // RUN THE TEST TREE
    $testClasses = $config->getTests();
    $testsRun = array ();
    $testsFailed = array ();
    foreach ( $testClasses as $testClass )
    {
      try
      {
        $this->runTest( $config, $testClass, $testClass, $testsRun, $testsFailed );
      }
      catch ( \Exception $ex )
      {
        print "********** FAILED: test case " . $testClass . " (Reason: " . $ex->getMessage() . ")" . PHP_EOL;
        $testsFailed[] = $testClass;
      }
    }

    $text = "All tests ok!";
    if ( count( $testsFailed ) > 0 )
      $text = count( $testsFailed ) . " tests failed!";
    print PHP_EOL . PHP_EOL . "********** RESULTS: " . count( $testClasses ) . " tests run. " . $text . PHP_EOL;
  }

  protected function runTest( $config, $testClass, $rootTestClass, &$testsRun, &$testsFailed )
  {
    if ( in_array( $testClass, $testsRun ) )
      return;

    $testObj = new $testClass;
    $requiredTests = $testObj->getRequiredTests();

    if ( is_array( $requiredTests ) )
    {
      foreach ( $requiredTests as $requiredTest )
      {
        if ( in_array( $rootTestClass, $requiredTests ) )
          throw new \Exception( "Cyclic Test Dependency for test Class: " . $rootTestClass );

        if ( !class_exists( $requiredTest, true ) )
          throw new \Exception( $requiredTest . " not found as required Test for ".$testClass );
        $this->runTest( $config, $requiredTest, $rootTestClass, $testsRun, $testsFailed );
      }
    }

    print PHP_EOL . "********** Running test class " . $testClass . PHP_EOL;
    $testObj->run( $config );
    print "********** PASSED: " . $testClass . PHP_EOL;
    $testsRun[] = $testClass;
  }
}
