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
    $MyUniversity = new University( "My University" );

    $Sally = new Student( "Sally Miller" );
    $John = new Student( "Jon Smith" );
    $Michael = new Student( "Michael Jordan" );

    $ProfPipen = new Teacher( "Prof. Pipen" );
    $ProfSteinberg = new Teacher( "Prof. Steinberg" );

    $MyUniversity->Decane = $ProfPipen;
    $ProfPipen->addStudent( $Sally );
    $ProfPipen->addStudent( $John );
    $ProfPipen->addStudent( $Michael );
    $ProfSteinberg->addStudent( $Sally );

    /* @var $config \qck\ext\abstracts\AppConfig */
    $Dir = $config->getFileService()->createUniqueFileName( $config->getDataDir() );
    $config->getFileService()->createDir( $Dir );
    try
    {
      $Backend = new \qck\db\FileBackend( $Dir );
      $Backend->save( $MyUniversity );
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
