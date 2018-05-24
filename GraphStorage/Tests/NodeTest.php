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
    // Create a Student Register: A University has Students and Teachers. Students and Teachers are related (they teach the students)
    // with CRUD functionality
    $this->createTests( $config );
  }

  function testCreate()
  {
    $Sally = Student::create( "Sally Miller" );
    $John = Student::create( "Jon Smith" );
    $Michael = Student::create( "Michael Jordan" );

    $ProfPipen = Teacher::create( "Prof. Pipen" );
    $ProfPipen->addStudent( $Sally );
    $ProfPipen->addStudent( $John );
    $ProfPipen->addStudent( $Michael );

    $ProfSteinberg = Teacher::create( "Prof. Steinberg" );
    $ProfSteinberg->addStudent( $Sally );

    $MyUniversity = University::create( "My University", $ProfPipen );
    $MyUniversity->Teachers->add( $ProfPipen );
    $MyUniversity->Teachers->add( $ProfSteinberg );
    $MyUniversity->Students->add( $Sally );
    $MyUniversity->Students->add( $John );
    $MyUniversity->Students->add( $Michael );

    $Db = $this->getDb();
    $Db->add( $MyUniversity );
    $Db->sync();
  }

  function createStudentMatcher( $name )
  {
    return function($value) use($name)
    {
      return $value instanceof Student && $value->Name == $name;
    };
  }

  function testRead()
  {
    $Db = $this->getDb();
    /* @var $University University */
    $University = $Db->get( University::UUID );
    $this->assert( $University->Decane->Name == "Prof. Pipen" );

    $Michael = $University->Students->findFirst( $this->createStudentMatcher( "Michael Jordan" ) );
    $this->assert( $Michael );
  }

  function testModify( $MichaelUuid )
  {
    $Db = $this->getDb();
    /* @var $University University */
    $University = $Db->get( University::UUID );
    $Michael = $University->Students->findFirst( $this->createStudentMatcher( "Michael Jordan" ) );
    $this->assert( $Michael );
    /* @var $Michael Student */
    $Michael->Name = "Michael Air Jordan";
    $Db->sync();

    $NewDatabase = $this->getDb();
    $University2 = $NewDatabase->get( University::UUID );
    $Michael2 = $University2->Students->findFirst( $this->createStudentMatcher( "Michael Air Jordan" ) );

    $this->assert( $Michael->Name == $Michael2->Name, $Michael->Name . "is not equals " . $Michael2->Name );
  }

  function testConcurrentModify()
  {
    /* @var $Uni1 University */
    $Db1 = $this->getFileDatabase();
    $Uni1 = $Db1->get( University::UUID );

    /* @var $Uni2 University */
    $Db2 = $this->getFileDatabase();
    $Uni2 = $Db2->get( University::UUID );

    $Uni1->Students->remove( function($val)
    {
      return $val instanceof Student && ($val->Name == "Sally Miller" || $val->Name == "Michael Air Jordan");
    } );

    $NewStud = Student::create( "Sally The Real Miller" );
    $Uni2->Students->add( $NewStud );
    $Db2->add( $NewStud );

    $Db1->sync();
    $Db2->sync();

    $Db3 = $this->getFileDatabase();
    $Uni3 = $Db3->get( University::UUID );
    $SallyMiller = $Uni3->Students->findFirst( $this->createStudentMatcher( "Sally The Real Miller" ) );
    $this->assert( $SallyMiller );

    $Michael = $Uni3->Students->findFirst( $this->createStudentMatcher( "Michael Air Jordan" ) );
    $this->assert( $Michael === null );
  }

  public function getRequiredTests()
  {
    return array ();
  }

  protected $Dir;

}
