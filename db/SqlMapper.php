<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
interface SqlMapper
{

  function handleAdd( Sql\DatabaseSchemaInterface $SchemaInterface );

  function handleDrop( Sql\DatabaseSchemaInterface $SchemaInterface );

  function handleRename( Sql\DatabaseSchemaInterface $SchemaInterface,
                         SchemaElement $PrevSchemaElement );

  function handleChange( Sql\DatabaseSchemaInterface $SchemaInterface,
                         SchemaElement $PrevSchemaElement );
}
