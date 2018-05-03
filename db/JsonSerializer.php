<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class JsonSerializer implements interfaces\NodeSerializer
{

  const KEY_UUID = 0;
  const KEY_FQCN = 1;
  const KEY_MODTIME = 2;
  const KEY_DATA = 3;
  const TYPE_UUID = 0;
  const TYPE_SERIALIZED_OBJECT = 1;

  public function fromString( $String, interfaces\NodeDb $NodeDb )
  {
    $SerializedArray = json_decode( $String, true );
    $Uuid = $SerializedArray[ self::KEY_UUID ];
    $Fqcn = $SerializedArray[ self::KEY_FQCN ];
    $Data = $SerializedArray[ self::KEY_DATA ];
    foreach ( $Data as $key => $value )
    {
      if ( is_array( $value ) )
      {
        if ( $value[ 0 ] == self::TYPE_UUID )
          $value = new NodeRef( $value[ 1 ], $NodeDb );
        else if ( $value[ 0 ] == self::TYPE_SERIALIZED_OBJECT )
          $value = unserialize( $value[ 1 ] );
      }
      $Data[ $key ] = $value;
    }

    return new $Fqcn( $Data, $Uuid );
  }

  public function toString( interfaces\Node $Node )
  {
    $Data = [];
    foreach ( $Node->getData() as $key => $value )
    {
      if ( $value instanceof interfaces\UuidProvider )
        $value = [ self::TYPE_UUID, $value->getUuid() ];
      else if ( is_object( $value ) || is_array( $value ) )
        $value = [ self::TYPE_SERIALIZED_OBJECT, serialize( $value ) ];
      $Data[ $key ] = $value;
    }
    $SerializableArray = [];
    $SerializableArray[ self::KEY_UUID ] = $Node->getUuid();
    $SerializableArray[ self::KEY_FQCN ] = get_class( $Node );
    $SerializableArray[ self::KEY_MODTIME ] = $Node->getModifiedTime();
    $SerializableArray[ self::KEY_DATA ] = $Data;
    return json_encode( $SerializableArray, JSON_PRETTY_PRINT );
  }

  public function getFileExtensionWithoutDot()
  {
    return "json";
  }
}
