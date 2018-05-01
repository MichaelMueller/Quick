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
   * @return \qck\db\FileNodeDb
   */
  function getFileDatabase()
  {
    $JsonSerializer = new \qck\db\JsonSerializer();
    $FileDatabase = new \qck\db\FileNodeDb( $this->Dir, $JsonSerializer );

    $SqliteFile = $this->Dir . DIRECTORY_SEPARATOR . "sqlite.db";
    $SqliteDatabase = new \qck\db\SqliteDb( $SqliteFile );

    $MultiDb = new \qck\db\MultiDatabase( $SqliteDatabase );
    $MultiDb->addNodeDb( $FileDatabase );
    return $MultiDb;
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

    $FileDatabase = $this->getFileDatabase();
    $FileDatabase->add( $MyUniversity );
    $FileDatabase->commit();
  }

  function testRead()
  {
    $FileDatabase = $this->getFileDatabase();
    /* @var $University University */
    $University = $FileDatabase->getNode( University::UUID );
    $this->assert( $University->Decane->Name == self::PROF_PIPEN_NAME );
    $Michael = $University->Students->findFirst( function($value)
    {
      return $value instanceof Student && $value->Name == self::STUD_JORDAN_NAME;
    } );
    return $Michael->getUuid();
  }

  function testModify( $MichaelUuid )
  {
    $FileDatabase = $this->getFileDatabase();
    /* @var $University University */
    $University = $FileDatabase->getNode( University::UUID );
    $Michael = $University->Students->findFirst( function($value) use($MichaelUuid)
    {
      return $value instanceof \qck\db\interfaces\UuidProvider && $MichaelUuid == $value->getUuid();
    }
    );
    /* @var $Michael Student */
    $Michael->Name = "Michael Air Jordan";
    $FileDatabase->commit();

    $NewDatabase = $this->getFileDatabase();
    $University2 = $NewDatabase->getNode( University::UUID );
    $Michael2 = $University2->Students->findFirst( function($value) use($MichaelUuid)
    {
      return $value instanceof \qck\db\interfaces\UuidProvider && $MichaelUuid == $value->getUuid();
    }
    );

    $this->assert( $Michael->Name == $Michael2->Name, $Michael->Name . "is not equals " . $Michael2->Name );
  }

  function testConcurrentModify()
  {
    
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
