<?php

namespace qck\core\controller;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class RunTestsController implements \qck\core\interfaces\Controller
{

  public function run( \qck\core\interfaces\AppConfig $config )
  {
    /* @var $config \qck\apps\testapp\AppConfig */

    // if no tests available go back to start
    $testClasses = $config->getTests();
    if ( count( $testClasses ) == 0 )
    {
      throw new \Exception( "No tests found" );
      //header( "Location: " . $config->mkLink( $config->getControllerFactory()->getStartControllerClassName() ) );
      //return null;
    }

    // COLLECT TEST FILES AND RUN
    header( \qck\core\interfaces\Response::CT_TEXTPLAIN_UTF8 );

    print PHP_EOL . "********** START of test suite" . PHP_EOL;

    // RUN THE TEST TREE
    $testClasses = $config->getTests();
    $testsRun = array ();
    $testsFailed = array ();
    foreach ( $testClasses as $testClass )
    {
      $FilesToDelete = [];
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
  }

  protected function rmfile( $File )
  {
    if ( is_dir( $File ) )
    {
      $dir = $File;
      $objects = scandir( $dir );
      foreach ( $objects as $object )
      {
        if ( $object != "." && $object != ".." )
        {
          if ( is_dir( $dir . "/" . $object ) )
            $this->rrmdir( $dir . "/" . $object, false );
          else
            unlink( $dir . "/" . $object );
        }
      }

      rmdir( $dir );
    }
    else if ( is_file( $File ) )
      unlink( $File );
  }

  protected function runTest( $config, $testClass, $filenodedbTestClass, &$testsRun,
                              &$testsFailed, &$FilesToDelete = [] )
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

        if ( !class_exists( $requiredTest, true ) )
          throw new \Exception( $requiredTest . " not found as required Test for " . $testClass );
        $this->runTest( $config, $requiredTest, $filenodedbTestClass, $testsRun, $testsFailed, $FilesToDelete );
      }
    }

    print PHP_EOL . "********** Running test class " . $testClass . PHP_EOL;
    $testObj->run( $config, $FilesToDelete );
    print "********** PASSED: " . $testClass . PHP_EOL;
    $testsRun[] = $testClass;
  }
}
