<?php

namespace Qck;

/**
 * Router class maps arguments to specific functions
 * 
 * @author muellerm
 */
class Router
{

    function __construct(App $app, $defaultAppFunctionFqcn, $defaultRouteName = null)
    {
        $this->app = $app;
        $this->addRoute($defaultAppFunctionFqcn, $defaultRouteName);
    }

    function appFunctionNamespace()
    {
        return $this->appFunctionNamespace;
    }

    function setAppFunctionNamespace($appFunctionNamespace)
    {
        $this->appFunctionNamespace = $appFunctionNamespace;
        return $this;
    }

    function addRoute($fqcn, $routeName = null)
    {
        $fqcnParts = explode("\\", $fqcn);
        $routeName = $routeName ? $routeName : array_pop($fqcnParts);
        $this->routes[$routeName] = $fqcn;
        return $this;
    }

    function routes()
    {
        return $this->routes;
    }

    function currentRoute()
    {
        if (is_null($this->currentRoute))
        {
            $args = $this->app->args();
            $this->currentRoute = $args[$this->routeParamName] ?? null;
        }
        return $this->currentRoute;
    }

    function routeParamName()
    {
        return $this->routeParamName;
    }

    function buildUrl($routeName, array $queryData = [])
    {
        $completeQueryData = array_merge($queryData, [$this->routeParamName => $routeName]);
        return "?" . http_build_query($completeQueryData);
    }

    function run()
    {
        $route = $this->currentRoute();
        if (is_null($route))
            $route = array_keys($this->routes)[0];

        $exception = $this->app->newException()->setHttpReturnCode(HttpResponse::EXIT_CODE_NOT_FOUND);
        if (!preg_match("/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/", $route))
            $exception->argError("Invalid route '%s'", $this->routeParamName, $route)->throw();

        $fqcn = $this->routes[$route] ?? null;
        if (is_null($fqcn) && $this->appFunctionNamespace !== null)
            $fqcn = $this->appFunctionNamespace . "\\" . $route;

        $appFunction = null;
        if (!class_exists($fqcn, true))
            $exception->argError("Class '%s' does not exist", $this->routeParamName, $fqcn)->throw();

        $appFunction = new $fqcn();
        if (!$appFunction instanceof \Qck\AppFunction)
            $exception->argError("Class '%s' does not implement interface '%s'", $this->routeParamName, $fqcn, \Qck\AppFunction::class)->throw();
        $appFunction->run($this->app);
    }

    public function app()
    {
        return $this->app;
    }

    /**
     *
     * @var App
     */
    protected $app;

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
    protected $routeParamName;

    // state

    /**
     *
     * @var string 
     */
    private $currentRoute;

}
