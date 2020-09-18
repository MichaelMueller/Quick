<?php

namespace Qck;

/**
 * 
 * @author muellerm
 */
class ClassTools implements Interfaces\ClassTools
{

    function __construct( $namespace = "\\", $namespaceDir = "." )
    {
        $this->namespace    = $namespace;
        $this->namespaceDir = $namespaceDir;
    }

    function setFileExtensions( $fileExtensions )
    {
        $this->fileExtensions = $fileExtensions;
    }

    function camelize( $input, $separator = '_' )
    {
        return str_replace( $separator, '', ucwords( $input, $separator ) );
    }

    function className( $fqcn )
    {
        $path = explode( '\\', $fqcn );
        return array_pop( $path );
    }

    function classExists( $className, $checkClassName = true )
    {
        $fqcn = $this->fqcn( $className, $checkClassName );
        return $fqcn ? class_exists( $fqcn, true ) : false;
    }

    function instance( $className, array $args = [] )
    {
        $fqcn = $this->fqcn( $className );
        return class_exists( $fqcn, true ) ? $this->instanceFromFqcn( $fqcn, $args ) : null;
    }

    function instances( array $args = [] )
    {
        $fqcns       = $this->fqcns();
        $instances   = [];
        foreach ($fqcns as $fqcn)
            $instances[] = $this->instanceFromFqcn( $fqcn, $args );
        return $instances;
    }

    function fqcns()
    {
        $paths   = glob( sprintf( "%s/*.{%s}", $this->namespaceDir, implode( ",", $this->fileExtensions ) ), GLOB_BRACE );
        $fqcns   = [];
        foreach ($paths as $path)
            $fqcns[] = $this->namespace . "\\" . pathinfo( $path, PATHINFO_FILENAME );
        return $fqcns;
    }

    function instanceFromFqcn( $fqcn, array $args = [] )
    {

        $reflector = new \ReflectionClass( $fqcn );
        $instance  = $reflector->newInstanceArgs( $args );
        return $instance;
    }

    function fqcn( $className, $checkClassName = true )
    {
        if ($checkClassName && !preg_match( '/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $className ))
            return null;
        return $this->namespace . "\\" . $className;
    }

    protected $namespace;
    protected $namespaceDir;
    protected $fileExtensions = ["php"];

}
