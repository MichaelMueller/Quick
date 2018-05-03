<?php

namespace qck\db\tests;

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

  /**
   * 
   * @return \qck\db\interfaces\NodeDb
   */
  function getFileDatabase()
  {
    $JsonSerializer = new \qck\db\JsonSerializer();
    $FileDatabase = new \qck\db\FileNodeDb( $this->Dir, $JsonSerializer );
    return $FileDatabase;
  }

  /**
   * 
   * @return \qck\db\interfaces\NodeDb
   */
  function getDb()
  {
    return $this->getFileDatabase();
  }

  const PROF_PIPEN_NAME = "Prof. Pipen";
  const STUD_JORDAN_NAME = "Michael Jordan";

  function testCreate()
  {
    $Sally = Student::create( "Sally Miller" );
    $John = Student::create( "Jon Smith" );
    $Michael = Student::create( self::STUD_JORDAN_NAME );

    $ProfPipen = Teacher::create( self::PROF_PIPEN_NAME );
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

  function testRead()
  {
    $Db = $this->getDb();
    /* @var $University University */
    $University = $Db->get( University::UUID );
    $this->assert( $University->Decane->Name == self::PROF_PIPEN_NAME );
    $Michael = $University->Students->findFirst( function($value)
    {
      return $value instanceof Student && $value->Name == self::STUD_JORDAN_NAME;
    } );
    return $Michael->getUuid();
  }

  function testModify( $MichaelUuid )
  {
    $Db = $this->getDb();
    /* @var $University University */
    $University = $Db->get( University::UUID );
    $Michael = $University->Students->findFirst( function($value) use($MichaelUuid)
    {
      return $value instanceof \qck\db\interfaces\UuidProvider && $MichaelUuid == $value->getUuid();
    }
    );
    /* @var $Michael Student */
    $Michael->Name = "Michael Air Jordan";
    $Db->sync();

    $NewDatabase = $this->getDb();
    $University2 = $NewDatabase->get( University::UUID );
    $Michael2 = $University2->Students->findFirst( function($value) use($MichaelUuid)
    {
      return $value instanceof \qck\db\interfaces\UuidProvider && $MichaelUuid == $value->getUuid();
    }
    );

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

    $Uni1->Students->removeWhere( function($val)
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
    $SallyMiller = $Uni3->Students->findFirst( function($val)
    {
      return $val instanceof Student && $val->Name == "Sally The Real Miller";
    } );
    $this->assert( $SallyMiller->Name == "Sally The Real Miller" );
    $Michael = $Uni3->Students->findFirst( function($val)
    {
      return $val instanceof Student && ($val->Name == "Michael Air Jordan");
    } );
    $this->assert( $Michael == null );
  }

  function createTests( \qck\core\interfaces\AppConfig $config )
  {
    /* @var $config \qck\ext\abstracts\AppConfig */
    $this->Dir = $this->getTempDir( "NodeTest_" . University::UUID, true, true );
    try
    {
      $this->testCreate();
      $MichaelUuid = $this->testRead();
      $this->testModify( $MichaelUuid );
      $this->testConcurrentModify();
    }
    finally
    {
      #$config->getFileService()->delete( $this->Dir );
      #$this->rrmdir($this->Dir);
    }
  }

  public function getRequiredTests()
  {
    return array ();
  }

  protected $Dir;

}
