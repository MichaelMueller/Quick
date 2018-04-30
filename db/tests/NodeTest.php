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

  function createTests( \qck\core\interfaces\AppConfig $config )
  {
    /* @var $config \qck\ext\abstracts\AppConfig */
    $Dir = $config->getFileService()->createUniqueFileName( $config->getDataDir() );
    $config->getFileService()->createDir( $Dir );
    $JsonSerializer = new \qck\db\JsonSerializer();
    $FileDatabase = new \qck\db\FileDatabase( $Dir, $JsonSerializer );

    $MyUniversity = new University( $FileDatabase, "My University" );

    $Sally = new Student( $FileDatabase, "Sally Miller" );
    $John = new Student( $FileDatabase, "Jon Smith" );
    $Michael = new Student( $FileDatabase, "Michael Jordan" );

    $ProfPipen = new Teacher( $FileDatabase, "Prof. Pipen" );
    $ProfSteinberg = new Teacher( $FileDatabase, "Prof. Steinberg" );

    $MyUniversity->Decane = $ProfPipen;
    $ProfPipen->addStudent( $Sally );
    $ProfPipen->addStudent( $John );
    $ProfPipen->addStudent( $Michael );
    $ProfSteinberg->addStudent( $Sally );

    try
    {
      $FileDatabase->commit();
    }
    finally
    {

      #$config->getFileService()->delete( $Dir );
    }
  }

  public function getRequiredTests()
  {
    return array ();
  }
}
