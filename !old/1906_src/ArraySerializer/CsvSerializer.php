<?php

namespace Qck\ArraySerializer;

/**
 * Description of PhpSerializer
 *
 * @author muellerm
 */
class CsvSerializer implements \Qck\Interfaces\ArraySerializer
{

  function __construct( $Delimiter = "\t" )
  {
    $this->Delimiter = $Delimiter;
  }

  public function getFileExtension()
  {
    return "csv";
  }

  public function serialize( array $ArrayOfScalars )
  {
    return $this->toCsv( $ArrayOfScalars );
  }

  public function unserialize( $DataString )
  {
    return str_getcsv( $DataString, $this->Delimiter );
  }

  function toCsv( $array )
  {
    ob_start();
    $fp  = fopen( 'php://output', 'w' );
    fputcsv( $fp, $array, $this->Delimiter );
    fclose( $fp );
    $csv = ob_get_clean();
    return str_replace( "\n", PHP_EOL, $csv );
  }

  protected $Delimiter;

}
