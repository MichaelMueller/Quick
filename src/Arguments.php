<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Arguments implements \Qck\Interfaces\Arguments
{

    function __construct( array $userArgs = [] )
    {
        $this->createArgs( $userArgs );
    }

    function toArray()
    {
        return $this->args;
    }

    public function keys()
    {
        return array_keys( $this->args );
    }

    public function get( $Name, $Default = null )
    {
        return isset( $this->args[ $Name ] ) ? $this->args[ $Name ] : $Default;
    }

    public function has( $Name )
    {
        return isset( $this->args[ $Name ] );
    }

    public function isHttpRequest()
    {
        if ( is_null( $this->httpRequest ) )
            $this->httpRequest = !isset( $_SERVER[ "argv" ] ) || is_null( $_SERVER[ "argv" ] ) || is_string( $_SERVER[ "argv" ] );
        return $this->httpRequest;
    }

    protected function createArgs( array $userArgs = [] )
    {
        if ( $this->isHttpRequest() )
            $this->args = array_merge( $_COOKIE, $_GET, $_POST );
        else
            $this->args = $this->parseArgv( $_SERVER[ "argv" ] );

        $this->args = array_merge( $this->args, $userArgs );
    }

    protected function parseArgv( array $argv )
    {
        if ( $this->cliParser )
            return $this->cliParser->parse( $argv );
        else if ( count( $argv ) > 1 )
            return parse_str( $argv[ 1 ], $args );
        else
            return [];
    }

    public function checkEmail( $field, $error = null )
    {
        $error     = $error ?? $field . " must be a valid E-Mail address";
        $validator = function() use( $field, $error )
        {
            return (filter_var( $this->get( $field ), FILTER_VALIDATE_EMAIL ) === false) ? $error : null;
        };
        return $this->addValidator( $field, $validator );
    }

    public function checkMinLength( $field, $numChars, $error = null )
    {
        $error     = $error ?? $field . " must contain at least $numChars";
        $validator = function() use( $field, $numChars, $error )
        {
            return mb_strlen( $this->get( $field ) ) < $numChars ? $error : null;
        };
        return $this->addValidator( $field, $validator );
    }

    public function checkNotNull( $field, $error = null )
    {
        $error     = $error ?? $field . " must not be null";
        $validator = function() use( $field, $error )
        {
            return !$this->has( $field ) ? $error : null;
        };
        return $this->addValidator( $field, $validator );
    }

    public function validate()
    {
        $errors = [];
        foreach ( $this->validators as $field => $validators )
        {
            foreach ( $validators as $validator )
            {
                $error = $validator();
                if ( $error !== null )
                {
                    if ( !isset( $errors[ $field ] ) )
                        $errors[ $field ]   = [];
                    $errors[ $field ][] = $error;
                }
            }
        }
        return $errors;
    }

    protected function addValidator( $field, callable $validator )
    {
        if ( !isset( $this->validators[ $field ] ) )
            $this->validators[ $field ]   = [];
        $this->validators[ $field ][] = $validator;
        return $this;
    }

    /**
     *
     * @var array
     */
    protected $args = [];

    /**
     *
     * @var \Qck\Interfaces\CliParser
     */
    protected $cliParser;

    // STATE

    /**
     *
     * @var bool
     */
    protected $httpRequest;

    /**
     *
     * @var callable[]
     */
    protected $validators = [];

}
