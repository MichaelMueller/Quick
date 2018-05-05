<?php

namespace qck\node\abstracts;

/**
 * abstract implementation of a NodeDb. Extending classes will mostly deal
 * with the end-storage specific details (e.g. file based or sql or whatever)
 * 
 * @author muellerm
 */
abstract class Serializer implements \qck\node\interfaces\NodeSerializer
{

  const KEY_UUID = 0;
  const KEY_FQCN = 1;
  const KEY_MODTIME = 2;
  const KEY_DATA = 3;
  const TYPE_UUID = 0;
  const TYPE_SERIALIZED_OBJECT = 1;

  abstract protected function unserializeToArray( $String );

  abstract protected function serializeArray( $Array );

  public function fromString( $String, \qck\node\interfaces\NodeDb $NodeDb )
  {
    $SerializedArray = $this->unserializeToArray( $String );

    // create node
    $Uuid = $SerializedArray[ self::KEY_UUID ];
    $Fqcn = $SerializedArray[ self::KEY_FQCN ];
    $Node = new $Fqcn( $Uuid );

    // set data
    $Data = $SerializedArray[ self::KEY_DATA ];
    foreach ( $Data as $key => $value )
    {
      if ( is_array( $value ) )
      {
        if ( $value[ 0 ] == self::TYPE_UUID )
          $value = new \qck\node\NodeRef( $value[ 1 ], $NodeDb );
        else if ( $value[ 0 ] == self::TYPE_SERIALIZED_OBJECT )
          $value = unserialize( $value[ 1 ] );
      }
      $Node->set( $key, $value );
    }
    return $Node;
  }

  public function toString( \qck\node\interfaces\Node $Node )
  {
    $Data = [];
    foreach ( $Node->keys() as $key )
    {
      $value = $Node->get( $key, FALSE );
      if ( $value instanceof \qck\node\interfaces\UuidProvider )
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
    return $this->serializeArray( $SerializableArray );
  }
}
