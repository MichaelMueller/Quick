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

    static function createConfig( $name, $defaultAppFunctionFqcn, $defaultRouteName = null )
    {
        return new \App\Config( $name, $defaultAppFunctionFqcn, $defaultRouteName );
    }

    function __construct( \App\Config $config )
    {
        $this->config = $config;
        // setup error handling
        error_reporting( E_ALL );
        ini_set( 'log_errors', intval( $config->showErrors() ) );
        ini_set( 'display_errors', intval( $config->showErrors() ) );
        ini_set( 'html_errors', intval( $this->isHttpRequest() ) );
        new \App\ErrorHandler( $this->isHttpRequest(), $this->config->showErrors() );

        // run the router
        $this->router()->run();
    }

    public function name()
    {
        return $this->config->name();
    }

    public function args()
    {
        if ( is_null( $this->args ) )
        {
            // create args
            if ( $this->httpRequest() )
                $this->args = array_merge( $_COOKIE, $_GET, $_POST, $this->config->userArgs() );
            else
            {
                $cmdArgs = count( $_SERVER[ "argv" ] ) > 1 ? parse_str( $_SERVER[ "argv" ][ 1 ] ) : [];
                $this->args = array_merge( $cmdArgs, $this->config->userArgs() );
            }
        }
        return $this->args;
    }

    public function httpRequest()
    {
        if ( $this->isHttpRequest() && is_null( $this->httpRequest ) )
            $this->httpRequest = new \App\HttpRequest( $this );
        return $this->httpRequest;
    }

    /**
     * 
     * @return \App\Router
     */
    public function router()
    {
        if ( is_null( $this->router ) )
            $this->router = new \App\Router( $this, $this->config->routes(), $this->config->appFunctionNamespace() );

        return $this->router;
    }

    public function httpResponse()
    {

        if ( is_null( $this->httpResponse ) )
            $this->httpResponse = new \App\HttpResponse( $this );
        return $this->httpResponse;
    }

    public function createException()
    {
        return new \App\Exception();
    }

    protected function isHttpRequest()
    {
        if ( is_null( $this->isHttpRequest ) )
            $this->isHttpRequest = ! isset( $_SERVER[ "argv" ] ) || is_null( $_SERVER[ "argv" ] ) || is_string( $_SERVER[ "argv" ] );

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

    function __construct( $name, $defaultAppFunctionFqcn, $defaultRouteName = null )
    {
        $this->name = $name;
        $this->addRoute( $defaultAppFunctionFqcn, $defaultRouteName );
    }

    function name()
    {
        return $this->name;
    }

    function appFunctionNamespace()
    {
        return $this->appFunctionNamespace;
    }

    function setAppFunctionNamespace( $appFunctionNamespace )
    {
        $this->appFunctionNamespace = $appFunctionNamespace;
        return $this;
    }

    function addRoute( $fqcn, $routeName = null )
    {
        $fqcnParts = explode( "\\", $fqcn );
        $routeName = $routeName ? $routeName : array_pop( $fqcnParts );
        $this->routes[ $routeName ] = $fqcn;
        return $this;
    }

    function routes()
    {
        return $this->routes;
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
        new \Qck\App( $this );
    }

    public function setShowErrors( $showErrors = false )
    {
        $this->showErrors = $showErrors;
        return $this;
    }

    public function setUserArgs( array $args = array() )
    {
        $this->userArgs = $args;
        return $this;
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
     * @var string[]
     */
    protected $routes = [];

    /**
     *
     * @var bool
     */
    protected $showErrors = false;

    /**
     *
     * @var array
     */
    protected $userArgs = [];

    /**
     *
     * @var \Qck\Interfaces\App
     */
    protected $app;

}

/**
 * 
 */
class HttpRequest implements \Qck\Interfaces\HttpRequest
{

    function __construct( \Qck\Interfaces\App $app )
    {
        $this->app = $app;
    }

    public function app()
    {
        return $this->app;
    }

    public function ipAddress()
    {
        if ( is_null( $this->ipAddress ) )
            $this->ipAddress = new IpAddress( $this );
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

    function __construct( \Qck\Interfaces\HttpRequest $httpRequest )
    {
        $this->httpRequest = $httpRequest;
    }

    public function value()
    {
        if ( ! $this->ip )
        {
            if ( ! empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) )
            {
                //ip from share internet
                $this->ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
            }
            elseif ( ! empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) )
            {
                //ip pass from proxy
                $this->ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
            }
            elseif ( ! empty( $_SERVER[ 'REMOTE_ADDR' ] ) )
                $this->ip = $_SERVER[ 'REMOTE_ADDR' ];
            else
            {
                $this->ip = null;
            }
        }
        if ( $this->validationFlags )
        {
            $this->ip = filter_var( $this->ip, FILTER_VALIDATE_IP, $this->validationFlags );
            if ( $this->ip === false )
                $this->httpRequest->app()->createException()->error( "Invalid ip %s", $this->ip )->exception()->throw();
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

    public function setValidationFlags( $validationFlags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE )
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

    function __construct( \Qck\Interfaces\App $app, array $routes, $appFunctionNamespace = null, $routeParamName = "q" )
    {
        $this->app = $app;
        $this->routes = $routes;
        $this->appFunctionNamespace = $appFunctionNamespace;
        $this->routeParamName = $routeParamName;
    }

    function currentRoute()
    {
        if ( is_null( $this->currentRoute ) )
        {
            $args = $this->app->args();
            $this->currentRoute = $args[ $this->routeParamName ] ?? null;
        }
        return $this->currentRoute;
    }

    function routeParamName()
    {
        return $this->routeParamName;
    }

    function buildUrl( $routeName, array $queryData = [] )
    {
        $completeQueryData = array_merge( $queryData, [ $this->routeParamName => $routeName ] );
        return "?" . http_build_query( $completeQueryData );
    }

    function run()
    {
        $route = $this->currentRoute();
        if ( is_null( $route ) )
            $route = array_keys( $this->routes )[ 0 ];

        $exception = $this->app->createException()->setHttpReturnCode( HttpResponse::EXIT_CODE_NOT_FOUND );
        if ( ! preg_match( "/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/", $route ) )
            $exception->argError( "Invalid route '%s'", $this->routeParamName, $route )->throw();

        $fqcn = $this->routes[ $route ] ?? null;
        if ( is_null( $fqcn ) && $this->appFunctionNamespace !== null )
            $fqcn = $this->appFunctionNamespace . "\\" . $route;

        $appFunction = null;
        if ( ! class_exists( $fqcn, true ) )
            $exception->argError( "Class '%s' does not exist", $this->routeParamName, $fqcn )->throw();

        $appFunction = new $fqcn();
        if ( ! $appFunction instanceof \Qck\Interfaces\AppFunction )
            $exception->argError( "Class '%s' does not implement interface '%s'", $this->routeParamName, $fqcn, \Qck\Interfaces\AppFunction::class )->throw();
        $appFunction->run( $this->app );
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

/**
 * Default Error Handler
 * 
 * @author muellerm
 */
class ErrorHandler
{

    function __construct( $isHttpRequest, $showErrors = false )
    {
        $this->isHttpRequest = $isHttpRequest;
        $this->showErrors = $showErrors;
        $this->install();
    }

    function errorHandler( $errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }

    function exceptionHandler( \Throwable $exception )
    {
        /* @var $exception \Exception */
        if ( $this->isHttpRequest )
        {
            $code = $exception instanceof Exception ? $exception->httpReturnCode() : \Qck\Interfaces\HttpResponse::EXIT_CODE_INTERNAL_ERROR;
            http_response_code( $code );
        }

        throw $exception;
    }

    function install()
    {
        if ( $this->errorHandler && $this->exceptionHandler )
            return;
        $this->errorHandler = set_error_handler( array( $this, "errorHandler" ) );
        $this->exceptionHandler = set_exception_handler( array( $this, "exceptionHandler" ) );
    }

    protected function uninstall()
    {
        if ( ! $this->errorHandler || ! $this->exceptionHandler )
            return;
        set_error_handler( $this->errorHandler );
        set_exception_handler( $this->exceptionHandler );
        $this->errorHandler = null;
        $this->exceptionHandler = null;
    }

    public function __destruct()
    {
        $this->uninstall();
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

class Exception extends \Exception implements \Qck\Interfaces\Exception
{

    public function __construct( string $message = "", int $code = 0, \Throwable $previous = NULL )
    {
        parent::__construct( $message, $code, $previous );
        $this->code = $this->returnCode;
    }

    function httpReturnCode()
    {
        return $this->httpReturnCode;
    }

    function returnCode()
    {
        return $this->returnCode;
    }

    protected function generateMessage()
    {
        $this->message = implode( ", ", array_map( 'strval', $this->errors ) );
    }

    public function argError( $text, $relatedKey, ...$args )
    {
        $this->errors[] = new Error( vsprintf( $text, $args ), $relatedKey );
        $this->generateMessage();
        return $this;
    }

    public function error( $text, ...$args )
    {
        $this->errors[] = new Error( vsprintf( $text, $args ) );
        $this->generateMessage();
        return $this;
    }

    function errors()
    {
        return $this->errors;
    }

    public function setHttpReturnCode( $returnCode = \Qck\Interfaces\HttpResponse::EXIT_CODE_INTERNAL_ERROR )
    {
        $this->httpReturnCode = $returnCode;
        return $this;
    }

    public function throw()
    {
        throw $this;
    }

    public function setReturnCode( $returnCode = -1 )
    {
        $this->returnCode = $returnCode;
        $this->code = $this->returnCode;
        return $this;
    }

    /**
     *
     * @var int
     */
    protected $httpReturnCode = \Qck\Interfaces\HttpResponse::EXIT_CODE_INTERNAL_ERROR;

    /**
     *
     * @var int
     */
    protected $returnCode = -1;

    /**
     *
     * @var Error[]
     */
    protected $errors = [];

}

class Error implements \Qck\Interfaces\Error
{

    function __construct( string $text, string $relatedKey = null )
    {
        $this->text = $text;
        $this->relatedKey = $relatedKey;
    }

    public function __toString()
    {
        return ($this->relatedKey ? $this->relatedKey . ": " : null) . $this->text;
    }

    public function relatedKey()
    {
        return $this->relatedKey;
    }

    public function text()
    {
        return $this->text;
    }

    /**
     *
     * @var string
     */
    protected $text;

    /**
     *
     * @var string
     */
    protected $relatedKey;

}

class HttpResponse implements \Qck\Interfaces\HttpResponse
{

    function __construct( \Qck\Interfaces\App $app )
    {
        $this->app = $app;
    }

    public function createContent( $text )
    {
        $this->content = new HttpContent( $this, $text );
        return $this->content;
    }

    public function send()
    {
        http_response_code( $this->returnCode );

        header( sprintf( "Content-Type: %s; charset=%s", $this->content->contentType(), $this->content->charSet() ) );
        echo $this->content->text();
    }

    public function setReturnCode( $returnCode = \Qck\Interfaces\HttpResponse::EXIT_CODE_OK )
    {
        $this->returnCode = $returnCode;
        return $this;
    }

    public function app()
    {
        return $this->app();
    }

    /**
     *
     * @var \Qck\Interfaces\App
     */
    protected $app;

    /**
     *
     * @var HttpContent
     */
    protected $content;

    /**
     *
     * @var string
     */
    protected $returnCode = \Qck\Interfaces\HttpResponse::EXIT_CODE_INTERNAL_ERROR;

}

class HttpContent implements \Qck\Interfaces\HttpContent
{

    function __construct( \Qck\Interfaces\HttpResponse $response, $text )
    {
        $this->response = $response;
        $this->text = $text;
    }

    public function response()
    {
        return $this->response;
    }

    public function setCharset( $charSet = \Qck\Interfaces\HttpContent::CHARSET_UTF_8 )
    {
        $this->charSet = $charSet;
        return $this;
    }

    public function setContentType( $contentType = \Qck\Interfaces\HttpContent::CONTENT_TYPE_TEXT_HTML )
    {
        $this->contentType = $contentType;
        return $this;
    }

    function text()
    {
        return strval( $this->text );
    }

    function contentType()
    {
        return $this->contentType;
    }

    function charSet()
    {
        return $this->charSet;
    }

    /**
     *
     * @var \Qck\Interfaces\HttpResponse
     */
    protected $response;

    /**
     *
     * @var object|string
     */
    protected $text;

    /**
     *
     * @var string
     */
    protected $contentType = \Qck\Interfaces\HttpContent::CONTENT_TYPE_TEXT_HTML;

    /**
     *
     * @var string
     */
    protected $charSet = \Qck\Interfaces\HttpContent::CHARSET_UTF_8;

}
