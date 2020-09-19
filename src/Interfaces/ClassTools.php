<?php

namespace Qck\Interfaces;

/**
 * Tools for classes in a certain namespace
 * 
 * @author muellerm
 */
interface ClassTools
{

    /**
     * 
     * @param string $fqcn
     * @return string the short className
     */
    function className( $fqcn );

    /**
     * 
     * @param string $className
     * @param bool $checkClassName
     * @return bool
     */
    function classExists( $className, $checkClassName = true );

    /**
     * 
     * @param string $className
     * @param array $args
     * @return object
     */
    function instance( $className, array $args = [], $checkClassName = true );

    /**
     * 
     * @param array $args
     * @return object[]
     */
    function instances( array $args = [] );

    /**
     * @return string the short className
     */
    function fqcns();

    /**
     * 
     * @param string $fqcn
     * @param array $args
     * @return string the short className
     */
    function instanceFromFqcn( $fqcn, array $args = [] );

    /**
     * 
     * @param string $className
     * @param bool $checkClassName
     * @return string the short className
     */
    function fqcn( $className, $checkClassName = true );
}
