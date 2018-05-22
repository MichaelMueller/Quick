<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class GraphSchema
{

  function __construct( $SchemaCacheFile )
  {
    $this->SchemaCacheFile = $SchemaCacheFile;
  }

  function addMetaObject( MetaObject $MetaObject )
  {
    $this->MetaObjects[ $MetaObject->getId() ] = $MetaObject;
  }

  function getMetaObjects()
  {
    return $this->MetaObjects;
  }

  function getSchemaElements()
  {
    $SchemaElements = [];

    /* @var $ObjSchema MetaObject */
    foreach ( $this->MetaObjects as $ObjSchema )
    {
      $SchemaElements[] = $ObjSchema;
      foreach ( $ObjSchema->getProperties() as $Property )
        $SchemaElements[] = $Property;
    }

    return $SchemaElements;
  }

  /**
   * 
   * @return \qck\db\GraphSchemaDiff
   */
  function getDiff()
  {
    $Schema = $this->Schema;
    $GraphSchemaDiff = new GraphSchemaDiff();

    // GET ALL SCHEMA OBJECTS FROM CURRENT AND PREVIOUS
    /* @var $PrevSchema GraphSchema */
    $PrevSchema = file_exists( $this->SchemaCacheFile ) ? unserialize( file_get_contents( $this->SchemaCacheFile ) ) : null;
    $CurrSchemaElements = $Schema->getSchemaElements();
    $PrevSchemaElements = $PrevSchema ? $PrevSchema->getSchemaElements() : [];

    // GET ADDED AND DROPPED SCHEMAOBJECTS
    // 
    /* @var $SchemaElement SchemaElement */
    foreach ( $CurrSchemaElements as $Id => $SchemaElement )
    {
      /* @var $PrevSchemaElement SchemaElement */
      $PrevSchemaElement = isset( $PrevSchemaElements[ $Id ] ) ? $PrevSchemaElements[ $Id ] : null;
      if ( $PrevSchemaElement )
      {
        unset( $PrevSchemaElements[ $Id ] );
        if ( $SchemaElement->getName() != $PrevSchemaElement->getName() )
          $GraphSchemaDiff->addDiff( new SchemaElementRenamed( $SchemaElement, $PrevSchemaElement ) );
        if ( $SchemaElement->hasChanged( $PrevSchemaElement ) )
          $GraphSchemaDiff->addDiff( new SchemaElementChanged( $SchemaElement, $PrevSchemaElement ) );
      }
      else
        $GraphSchemaDiff->addDiff( new SchemaElementAdded( $SchemaElement ) );
    }
    foreach ( $PrevSchemaElements as $SchemaElement )
      $GraphSchemaDiff->addDiff( new SchemaElementDropped( $SchemaElement ) );

    file_put_contents( $this->SchemaCacheFile, serialize( $Schema ) );
    return $GraphSchemaDiff;
  }

  protected $SchemaCacheFile;

  /**
   *
   * @var array
   */
  protected $MetaObjects = [];

}
