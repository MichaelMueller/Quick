<?php

namespace Qck\Util\ArraySerializer;

/**
 * Description of PhpSerializer
 *
 * @author muellerm
 */
class CsvSerializer implements \Qck\Interfaces\ArraySerializer
{

  function __construct( $delimiter = "\t", $lineEnd=PHP_EOL )
  {
    $this->delimiter = $delimiter;
    $this->lineEnd = $lineEnd;
  }

  public function fileExtension()
  {
    return "csv";
  }

  public function serialize( array $ArrayOfScalars )
  {
    return $this->toCsv( $ArrayOfScalars );
  }

  public function unserialize( $DataString )
  {
    return str_getcsv( $DataString, $this->delimiter );
  }

  function toCsv( $array )
  {
    ob_start();
    $fp  = fopen( 'php://output', 'w' );
    fputcsv( $fp, $array, $this->delimiter );
    fclose( $fp );
    $csv = ob_get_clean();
    return str_replace( "\n", $this->lineEnd, $csv );
  }

  protected $delimiter;
  protected $lineEnd;

}
