<?php

namespace Qck;

/**
 * 
 * @author muellerm
 */
class App implements Interfaces\App
{
    /*
     * @return Interfaces\AppConfig
     */

    static function createConfig($name, $appFunctionNamespace)
    {
        return new \App\Config($name, $appFunctionNamespace);
    }

    function __construct(\App\Config $config)
    {
        $this->config = $config;
        // setup error handling
        error_reporting(E_ALL);
        ini_set('log_errors', intval($config->showErrors()));
        ini_set('display_errors', intval($config->showErrors()));
        ini_set('html_errors', intval($this->isHttpRequest()));
        new \App\ErrorHandler($this->isHttpRequest(), $this->config->showErrors());

        // run the router
        $this->router()->run();
    }

    public function name()
    {
        return $this->config->name();
    }

    public function args()
    {
        if (is_null($this->args))
        {
            // create args
            if ($this->httpRequest())
                $this->args = array_merge($_COOKIE, $_GET, $_POST, $this->args);
            else
            {
                $cmdArgs = count($_SERVER["argv"]) > 1 ? parse_str($_SERVER["argv"][1]) : [];
                $this->args = array_merge($cmdArgs, $argv);
            }
        }
        return $this->args;
    }

    public function httpRequest()
    {
        if ($this->isHttpRequest() && is_null($this->httpRequest))
            $this->httpRequest = new \App\HttpRequest($this);
        return $this->httpRequest;
    }

    /**
     * 
     * @return \App\Router
     */
    public function router()
    {
        if (is_null($this->router))
            $this->router = new \App\Router($this, $this->config->appFunctionNamespace(), $this->config->defaultRoute());

        return $this->router;
    }

    public function httpResponse()
    {
        
    }

    public function createException()
    {
        // TODO
    }

    protected function isHttpRequest()
    {
        if (is_null($this->isHttpRequest))
            $this->isHttpRequest = !isset($_SERVER["argv"]) || is_null($_SERVER["argv"]) || is_string($_SERVER["argv"]);

        return $this->isHttpRequest;
    }

    /**
     *
     * @var \App\Config
     */
    protected $config;

    /**
     *
     * @var array
     */
    protected $args;

    /**
     *
     * @var bool
     */
    protected $isHttpRequest;

    /**
     *
     * @var Interfaces\HttpRequest
     */
    protected $httpRequest;

    /**
     *
     * @var \App\Router
     */
    protected $router;

    /**
     *
     * @var Interfaces\HttpResponse
     */
    protected $httpResponse;

}

namespace App;

class Config implements \Qck\Interfaces\AppConfig
{

    function __construct($name, $appFunctionNamespace)
    {
        $this->name = $name;
        $this->appFunctionNamespace = $appFunctionNamespace;
    }

    function name()
    {
        return $this->name;
    }

    function appFunctionNamespace()
    {
        return $this->appFunctionNamespace;
    }

    function defaultRoute()
    {
        return $this->defaultRoute;
    }

    function showErrors()
    {
        return $this->showErrors;
    }

    function userArgs()
    {
        return $this->userArgs;
    }

    public function runApp()
    {
        new \Qck\App($this);
    }

    public function setDefaultRoute($defaultRoute = "Start")
    {
        $this->defaultRoute = $defaultRoute;
    }

    public function setShowErrors($showErrors = false)
    {
        $this->showErrors = $showErrors;
    }

    public function setUserArgs(array $args = array())
    {
        $this->userArgs = $args;
    }

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $appFunctionNamespace;

    /**
     *
     * @var string
     */
    protected $defaultRoute = "Start";

    /**
     *
     * @var bool
     */
    protected $showErrors;

    /**
     *
     * @var array
     */
    protected $userArgs;

    /**
     *
     * @var \Qck\Interfaces\App
     */
    protected $app;

}

/**
 * 
 */
class HttpRequest implements Qck\Interfaces\HttpRequest
{

    function __construct(\Qck\Interfaces\App $app)
    {
        $this->app = $app;
    }

    public function app()
    {
        return $this->app;
    }

    public function ipAddress()
    {
        if (is_null($this->ipAddress))
            $this->ipAddress = new IpAddress($this);
        return $this->ipAddress;
    }

    /**
     *
     * @var \Qck\Interfaces\App
     */
    protected $app;

    /**
     *
     * @var \Qck\Interfaces\IpAddress
     */
    protected $ipAddress;

}

/**
 * 
 */
class IpAddress implements \Qck\Interfaces\IpAddress
{

    function __construct(\Qck\Interfaces\HttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;
    }

    public function value()
    {
        if (!$this->ip)
        {
            if (!empty($_SERVER['HTTP_CLIENT_IP']))
            {
                //ip from share internet
                $this->ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                //ip pass from proxy
                $this->ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($_SERVER['REMOTE_ADDR']))
                $this->ip = $_SERVER['REMOTE_ADDR'];
            else
            {
                $this->ip = null;
            }
        }
        if ($this->validationFlags)
        {
            $this->ip = filter_var($this->ip, FILTER_VALIDATE_IP, $this->validationFlags);
            if ($this->ip === false)
                $this->httpRequest->app()->createException()->errorSet()->error("Invalid ip %s", $this->ip)->exception()->throw();
        }

        return $this->ip;
    }

    public function __toString()
    {
        return $this->value();
    }

    public function httpRequest()
    {
        return $this->httpRequest;
    }

    public function setValidationFlags($validationFlags)
    {
        $this->validationFlags = $validationFlags;
        $this->ip = null;
        return $this;
    }

    /**
     *
     * @var \Qck\Interfaces\HttpRequest
     */
    protected $httpRequest;

    /**
     *
     * @var int
     */
    protected $validationFlags;

    /**
     *
     * @var string
     */
    private $ip;

}

/**
 * Router class maps arguments to specific functions
 * 
 * @author muellerm
 */
class Router implements \Qck\Interfaces\Router
{

    function __construct(\Qck\Interfaces\App $app, $appFunctionNamespace, $defaultRoute = "Start", $routeParamName = "q")
    {
        $this->app = $app;
        $this->appFunctionNamespace = $appFunctionNamespace;
        $this->defaultRoute = $defaultRoute;
        $this->routeParamName = $routeParamName;
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
            $route = $this->defaultRoute;

        $exception = $this->app->createException();
        if (!preg_match("/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/", $route))
            $exception->argError("Invalid route '%s'", $this->routeParamName, $route)->exception()->throw();

        $fqcn = $this->appFunctionNamespace . "\\" . $route;
        $appFunction = null;
        if (!class_exists($fqcn, true))
            $exception->argError("Class '%s' does not exist", $this->routeParamName, $fqcn)->exception()->throw();

        $appFunction = new $fqcn();
        if (!$appFunction instanceof \Qck\Interfaces\AppFunction)
            $exception->argError("Class '%s' does not implement interface '%s'", $this->routeParamName, $fqcn, \Qck\Interfaces\AppFunction::class)->exception()->throw();
        $appFunction->run($this->app);
    }

    public function app()
    {
        return $this->app;
    }

    /**
     *
     * @var \Qck\Interfaces\App
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
    protected $defaultRoute;

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

/**
 * Default Error Handler
 * 
 * @author muellerm
 */
class ErrorHandler
{

    function __construct($isHttpRequest, $showErrors = false)
    {
        $this->isHttpRequest = $isHttpRequest;
        $this->showErrors = $showErrors;
        $this->install();
    }

    function errorHandler($errno, $errstr, $errfile, $errline)
    {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    function exceptionHandler(\Throwable $exception)
    {
        /* @var $exception \Exception */
        if ($this->isHttpRequest)
        {
            $code = $exception instanceof Exception ? $exception->httpReturnCode() : Interfaces\HttpHeader::EXIT_CODE_INTERNAL_ERROR;
            http_response_code($code);
        }

        throw $exception;
    }

    function install()
    {
        if ($this->errorHandler && $this->exceptionHandler)
            return;
        $this->errorHandler = set_error_handler(array($this, "errorHandler"));
        $this->exceptionHandler = set_exception_handler(array($this, "exceptionHandler"));
    }

    protected function uninstall()
    {
        if (!$this->errorHandler || !$this->exceptionHandler)
            return;
        set_error_handler($this->errorHandler);
        set_exception_handler($this->exceptionHandler);
        $this->errorHandler = null;
        $this->exceptionHandler = null;
    }

    public function __destruct()
    {
        $this->revokeHandlers();
    }

    /**
     *
     * @var bool
     */
    protected $isHttpRequest;

    /**
     *
     * @var bool
     */
    protected $showErrors;

    // state

    /**
     *
     * @var callable
     */
    protected $errorHandler;

    /**
     *
     * @var callable
     */
    protected $exceptionHandler;

}

class Exception // TODO
{

    function httpReturnCode()
    {
        
    }

}
