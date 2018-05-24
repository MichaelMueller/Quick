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
    $this->Dir = $this->getTempDir( "GraphStorage" );
    $this->testCreate();
    $this->testRead();
  }

  function testCreate()
  {
    $MyUniversity = University::create( $this->Dir, "My University" );
    $ProfSteinberg = Teacher::create( "Prof. Steinberg" );
    $ProfPipen = Teacher::create( "Prof. Pipen" );
    $Sally = Student::create( "Sally Miller" );
    $John = Student::create( "Jon Smith" );
    $Michael = Student::create( "Michael Jordan" );

    $MyUniversity->Decane = $ProfSteinberg;
    $MyUniversity->Teachers->add( $ProfPipen );
    $MyUniversity->Teachers->add( $ProfSteinberg );
    $MyUniversity->Students->add( $Sally );
    $MyUniversity->Students->add( $John );
    $MyUniversity->Students->add( $Michael );
    $ProfSteinberg->addStudent( $Sally );
    $ProfPipen->addStudent( $Sally );
    $ProfPipen->addStudent( $John );
    $ProfPipen->addStudent( $Michael );

    $MyUniversity->save();
  }
  
  function testRead()
  {
    $MyUniversity = University::create( $this->Dir, "My University" );
    
    $this->assert( $MyUniversity->Decane->Name == "Prof. Pipen" );

    $Michael = $MyUniversity->Students->findFirst( $this->createStudentMatcher( "Michael Jordan" ) );
    $this->assert( $Michael );
  }

  function testModify()
  {
    $University = University::create( $this->Dir, "My University" );
    
    $Michael = $University->Students->findFirst( $this->createStudentMatcher( "Michael Jordan" ) );
    $this->assert( $Michael );
    /* @var $Michael Student */
    $Michael->Name = "Michael Air Jordan";
    $University->save();

    $University2 = University::create( $this->Dir, "My University" );
    $Michael2 = $University2->Students->findFirst( $this->createStudentMatcher( "Michael Air Jordan" ) );

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
