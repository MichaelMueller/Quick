<?php

namespace Qck;

/**
 * App class maps arguments to specific functions
 * 
 * @author muellerm
 */
class App
{

    static function new( $name, $defaultAppFunctionFqcn )
    {
        return new App( $name, $defaultAppFunctionFqcn );
    }

    function __construct( $name, $defaultAppFunctionFqcn )
    {
        $this->installErrorHandler();
        $this->name = $name;
        $this->addRoute( $defaultAppFunctionFqcn, null );
    }

    function appFunctionNamespace(): string
    {
        return $this->appFunctionNamespace;
    }

    function setUserArgs( array $userArgs ): App
    {
        $this->userArgs = $userArgs;
        return $this;
    }

    function setShowErrors( $showErrors ): App
    {
        $this->errorHandler->setShowErrors( $showErrors );
        return $this;
    }

    function setAppFunctionNamespace( $appFunctionNamespace ): App
    {
        $this->appFunctionNamespace = $appFunctionNamespace;
        return $this;
    }

    function addRoute( $fqcn, $routeName = null ): App
    {
        $fqcnParts = explode( "\\", $fqcn );
        $routeName = $routeName ? $routeName : array_pop( $fqcnParts );
        $this->routes[ $routeName ] = $fqcn;
        return $this;
    }

    function request(): Request
    {
        if ( is_null( $this->requestFactory ) )
            $this->requestFactory = new RequestFactory ( );
        return $this->requestFactory->request();
    }

    function routes(): array
    {
        return $this->routes;
    }

    function currentRoute(): string
    {
        if ( is_null( $this->currentRoute ) )
            $this->currentRoute = $this->request()->get( $this->routeParamName );
        return $this->currentRoute;
    }

    function setRouteParamName( string $routeParamName ): App
    {
        $this->routeParamName = $routeParamName;
        return $this;
    }

    function routeParamName(): string
    {
        return $this->routeParamName;
    }

    function buildUrl( $routeName, array $queryData = [] ): string
    {
        $completeQueryData = array_merge( $queryData, [ $this->routeParamName => $routeName ] );
        return "?" . http_build_query( $completeQueryData );
    }

    function run(): void
    {
        $route = $this->currentRoute();
        if ( is_null( $route ) )
            $route = array_keys( $this->routes )[ 0 ];

        $exception = $this->app->newException()->setHttpReturnCode( HttpResponse::EXIT_CODE_NOT_FOUND );
        if ( ! preg_match( "/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/", $route ) )
            $exception->argError( "Invalid route '%s'", $this->routeParamName, $route )->throw();

        $fqcn = $this->routes[ $route ] ?? null;
        if ( is_null( $fqcn ) && $this->appFunctionNamespace !== null )
            $fqcn = $this->appFunctionNamespace . "\\" . $route;

        $appFunction = null;
        if ( ! class_exists( $fqcn, true ) )
            $exception->argError( "Class '%s' does not exist", $this->routeParamName, $fqcn )->throw();

        $appFunction = new $fqcn();
        if ( ! $appFunction instanceof \Qck\AppFunction )
            $exception->argError( "Class '%s' does not implement interface '%s'", $this->routeParamName, $fqcn, \Qck\AppFunction::class )->throw();
        $appFunction->run( $this->app );
    }

    protected function installErrorHandler(): void
    {
        $this->errorHandler = new ErrorHandler();
    }

    /**
     *
     * @var ErrorHandler
     */
    protected $errorHandler;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var array
     */
    protected $userArgs = [];

    /**
     *
     * @var string 
     */
    protected $appFunctionNamespace;

    /**
     *
     * @var string 
     */
    protected $routes;

    /**
     *
     * @var string 
     */
    protected $routeParamName = "q";

    // state

    /**
     *
     * @var RequestFactory 
     */
    private $requestFactory;

    /**
     *
     * @var string 
     */
    private $currentRoute;

}
