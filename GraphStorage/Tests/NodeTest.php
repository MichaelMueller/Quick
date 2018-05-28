<?php

namespace qck\GraphStorage\Tests;

/**
 *
 * @author muellerm
 */
class NodeTest extends \qck\core\abstracts\Test
{

  public function run( \qck\core\interfaces\AppConfig $config )
  {
    $this->Dir = $this->getTempDir( "GraphStorage", true, true );
    $this->testCreateAndSave();
    $this->testRead();
    $this->testReadModfiySave();
  }

  function testCreateAndSave()
  {
    $Db = new \qck\GraphStorage\GraphDb( $this->Dir );
    $MyUniversity = University::create( "My University" );
    $ProfSteinberg = Teacher::create( "Prof. Steinberg" );
    $ProfPipen = Teacher::create( "Prof. Pipen" );
    $Sally = Student::create( "Sally Miller" );
    $John = Student::create( "Jon Smith" );
    $Michael = Student::create( "Michael Jordan" );

    $MyUniversity->Decane = $ProfPipen;
    $MyUniversity->Teachers->add( $ProfPipen );
    $MyUniversity->Teachers->add( $ProfSteinberg );
    $MyUniversity->Students->add( $Sally );
    $MyUniversity->Students->add( $John );
    $MyUniversity->Students->add( $Michael );
    $ProfSteinberg->addStudent( $Sally );
    $ProfPipen->addStudent( $Sally );
    $ProfPipen->addStudent( $John );
    $ProfPipen->addStudent( $Michael );

    $Db->register( $MyUniversity );
    $Db->commit();
  }

  function testRead()
  {
    $Db = new \qck\GraphStorage\GraphDb( $this->Dir );
    $MyUniversity = $Db->load( University::UUID );

    $this->assert( $MyUniversity->Decane->Name == "Prof. Pipen" );

    $Michael = $MyUniversity->Students->findValue( $this->createStudentMatcher( "Michael Jordan" ) );
    $this->assert( $Michael );
  }

  function testReadModfiySave()
  {
    $Db = new \qck\GraphStorage\GraphDb( $this->Dir );
    $University = $Db->load( University::UUID );

    $Michael = $University->Students->findValue( $this->createStudentMatcher( "Michael Jordan" ) );
    $this->assert( $Michael );
    /* @var $Michael Student */
    $Michael->Name = "Michael Air Jordan";
    $Db->commit();

    $Db2 = new \qck\GraphStorage\GraphDb( $this->Dir );
    $University2 = $Db2->load( University::UUID );
    $Michael2 = $University2->Students->findValue( $this->createStudentMatcher( "Michael Air Jordan" ) );
    $this->assert( $Michael2 );

    $this->assert( $Michael->Name == $Michael2->Name, $Michael->Name . "is not equals " . $Michael2->Name );
  }

  function createStudentMatcher( $name )
  {
    return function($value) use($name)
    {
      return $value instanceof Student && $value->Name == $name;
    };
  }

  public function getRequiredTests()
  {
    return array ();
  }

  protected $Dir;

}
