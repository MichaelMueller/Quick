<?php

namespace Qck;

/**
 * 
 * @author muellerm
 */
class ClassTools
{

    function __construct( $namespace="\\" )
    {
        $this->namespace = $namespace;
    }

    function setFileExtensions( $fileExtensions )
    {
        $this->fileExtensions = $fileExtensions;
    }

    function camelize( $input, $separator = '_' )
    {
        return str_replace( $separator, '', ucwords( $input, $separator ) );
    }

    function classExists( $className )
    {
        $fqcn = $this->fqcn( $className );
        return class_exists( $fqcn, true );
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
        $dir     = str_replace( "\\", "/", $this->namespace );
        $paths   = glob( sprintf( "%s/*.{%s}", $dir, implode( ",", $this->fileExtensions ) ), GLOB_BRACE );
        $fqcns   = [];
        foreach ($paths as $path)
            $fqcns[] = pathinfo( $path, PATHINFO_FILENAME );
        return $fqcns;
    }

    function instanceFromFqcn( $fqcn, array $args = [] )
    {

        $reflector = new \ReflectionClass( $fqcn );
        $instance  = $reflector->newInstanceArgs( $args );
        return $instance;
    }

    protected function fqcn( $className )
    {
        if (!preg_match( '/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $className ))
            throw new \InvalidArgumentException( "Invalid classname $className", Interfaces\HttpHeader::EXIT_CODE_BAD_REQUEST );
        return $this->namespace . "\\" . $className;
    }

    protected $namespace;
    protected $fileExtensions = ["php"];

}
