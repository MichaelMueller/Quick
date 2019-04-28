<?php

namespace Qck;

/**
 * App class is essentially the class to start.
 * It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
abstract class App
{

    /**
     * @return Interfaces\Inputs
     */
    abstract function getInputs();

    /**
     * @return Interfaces\CliDetector
     */
    abstract function getCliDetector();

    /**
     * @return Interfaces\Session
     */
    abstract function getSession();

    /**
     * @return Interfaces\UserDb
     */
    abstract function getUserDb();

    /**
     * @return string[]
     */
    abstract function getShellMethods();

    protected function setupErrorHandling()
    {
        error_reporting( E_ALL );
        ini_set( 'log_errors', intval( !$this->DevMode ) );
        ini_set( 'display_errors', intval( $this->DevMode ) );
        ini_set( 'html_errors', intval( !$this->getCliDetector()->isCli() ) );

        set_error_handler( array ( $this, "errorHandler" ) );
        set_exception_handler( array ( $this, "exceptionHandler" ) );
    }

    function buildUrl( $MethodName, array $QueryData = [] )
    {
        $CompleteQueryData = array_merge( [ $this->MethodParamName => $MethodName ], $QueryData );
        return "?" . http_build_query( $CompleteQueryData );
    }

    function errorHandler( $errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }

    function exceptionHandler( $Exception )
    {
        /* @var $Exception \Exception */
        if ( $this->getCliDetector()->isCli() == false )
            http_response_code( $Exception->getCode() );

        throw $Exception;
    }

    function run()
    {
        $this->setupErrorHandling();
        // find method and run
        $ShellMethods        = $this->getShellMethods();
        $RequestedMethodName = $this->getInputs()->get( $this->MethodParamName, $ShellMethods[ 0 ] );

        if ( in_array( $RequestedMethodName, $ShellMethods ) === false )
            throw new \Exception( sprintf( "Method %s is not declared as Shell Method.", $RequestedMethodName ), 404 );

        $Method                  = new \ReflectionMethod( $this, $RequestedMethodName );
        $IsCli                   = $this->getCliDetector()->isCli();
        $IsOnCliAndAuthNecessary = $this->AuthOnCli && $IsCli;
        if ( $Method->isPublic() == false && ($IsCli == false || $IsOnCliAndAuthNecessary) )
        {
            $MethodAllowed = false;
            $User          = $this->getUserDb()->getUser( $this->getSession()->getUsername() );
            if ( $User )
                $MethodAllowed = $Method->isProtected() || ($Method->isPrivate() && $User->isAdmin());

            if ( $MethodAllowed === false )
                throw new \Exception( sprintf( "Method %s is not allowed to be called.", $RequestedMethodName ), Interfaces\HttpResponder::EXIT_CODE_UNAUTHORIZED );
        }
        $RequestedParams = $Method->getParameters();
        $FoundParams     = [];
        foreach ( $RequestedParams as $RequestedParam )
            $FoundParams[]   = $this->getInputs()->get( $RequestedParam->getName(), null );

        $Method->setAccessible( true );
        $Method->invokeArgs( $this, $FoundParams );
    }

    function setAuthOnCli( $AuthOnCli )
    {
        $this->AuthOnCli = $AuthOnCli;
    }

    function isDevMode()
    {
        return $this->DevMode;
    }

    function getMethodParamName()
    {
        return $this->MethodParamName;
    }

    function setDevMode( $DevMode )
    {
        $this->DevMode = $DevMode;
    }

    function setMethodParamName( $MethodParamName )
    {
        $this->MethodParamName = $MethodParamName;
    }

    protected function getSingleton( $Name, callable $Factory )
    {
        if ( !isset( $this->Singletons[ $Name ] ) )
            $this->Singletons[ $Name ] = call_user_func( $Factory );
        return $this->Singletons[ $Name ];
    }

    /**
     *
     * @var bool 
     */
    protected $DevMode = false;

    /**
     *
     * @var bool 
     */
    protected $AuthOnCli = true;

    /**
     *
     * @var string
     */
    protected $MethodParamName = "q";

    /**
     *
     * @var array[object]
     */
    protected $Singletons;

}
