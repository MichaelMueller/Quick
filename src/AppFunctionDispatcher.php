<?php

namespace Qck;

/**
 * A Dispatcher for AppFunctions.
 * It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class AppFunctionDispatcher implements Interfaces\AppFunction
{

    function __construct( $Routes = [], $DefaultAppFunction = null, $FunctionParamName = "q" )
    {
        $this->Routes             = $Routes;
        $this->DefaultAppFunction = $DefaultAppFunction;
        $this->FunctionParamName  = $FunctionParamName;
    }

    function addRoute( $AppFunction, $Fqcn )
    {
        $this->Routes[ $AppFunction ] = $Fqcn;
    }
    function setDefaultAppFunction( $DefaultAppFunction )
    {
        $this->DefaultAppFunction = $DefaultAppFunction;
    }

    function setFunctionParamName( $FunctionParamName )
    {
        $this->FunctionParamName = $FunctionParamName;
    }

        function setDefaultToFirstRoute( $DefaultToFirstRoute )
    {
        $this->DefaultToFirstRoute = $DefaultToFirstRoute;
    }

    public function run( Interfaces\Inputs $Inputs )
    {
        $RequestedAppFunction = $Inputs->get( $this->FunctionParamName );
        if ( !$RequestedAppFunction && $this->DefaultAppFunction )
            $RequestedAppFunction = $this->DefaultAppFunction;
        $AppFunctionFqcn      = isset( $this->Routes[ $RequestedAppFunction ] ) ? $this->Routes[ $RequestedAppFunction ] : null;
        if ( class_exists( $AppFunctionFqcn, true ) === false )
            throw new \InvalidArgumentException( sprintf( "AppFunction %s or AppFunction Class %s not found", $RequestedAppFunction, $AppFunctionFqcn ), Interfaces\HttpResponder::EXIT_CODE_BAD_REQUEST );
        $AppFunction          = new $AppFunctionFqcn();
        $AppFunction->run( $Inputs );
    }

    /**
     *
     * @var string[]
     */
    protected $Routes = [];

    /**
     *
     * @var bool
     */
    protected $DefaultAppFunction;

    /**
     *
     * @var string
     */
    protected $FunctionParamName;

}
