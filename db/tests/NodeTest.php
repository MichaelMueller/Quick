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
    $this->createTests();
  }

  function createTests()
  {
    $Dir = sys_get_temp_dir()."/nodes".uniqid();
    $Backend = new \qck\db\FileBackend( $Dir );
    $MyUniversity = new University( $Backend, "My University" );

    $Sally = new Student( $Backend, "Sally Miller" );
    $John = new Student( $Backend, "Jon Smith" );
    $Michael = new Student( $Backend, "Michael Jordan" );

    $ProfPipen = new Teacher( $Backend, "Prof. Pipen" );
    $ProfSteinberg = new Teacher( $Backend, "Prof. Steinberg" );

    $MyUniversity->Decane = $ProfPipen;
    $ProfPipen->addStudent( $Sally );
    $ProfPipen->addStudent( $John );
    $ProfPipen->addStudent( $Michael );
    $ProfSteinberg->addStudent( $Sally );
        
     
    print_r($MyUniversity);
  }

  public function getRequiredTests()
  {
    return array ();
  }
}
