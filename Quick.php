<?php

/**
 * 
 * @author muellerm
 */
class App
{
    /*
     * @return Interfaces\AppConfig
     */

    static function create($name, $defaultAppFunctionFqcn, $showErrors = false, $defaultRouteName = null)
    {
        return new App($name, $defaultAppFunctionFqcn, $showErrors, $defaultRouteName);
    }

    function __construct($name, $defaultAppFunctionFqcn, $showErrors = false, $defaultRouteName = null)
    {
        // setup error handling
        $this->errorHandler = new ErrorHandler($this, $showErrors);

        // setup member
        $this->name = $name;
        $this->router = new Router($this, $defaultAppFunctionFqcn, $defaultRouteName);
    }

    function run()
    {
        $this->router->run();
    }

    public function name()
    {
        return $this->name;
    }

    function newCmd($executable)
    {
        return new Cmd($executable);
    }

    public function args()
    {
        if (is_null($this->args))
        {
            // create args
            if ($this->httpRequest())
                $this->args = array_merge($_COOKIE, $_GET, $_POST, $this->userArgs);
            else
            {
                $cmdArgs = count($_SERVER["argv"]) > 1 ? parse_str($_SERVER["argv"][1]) : [];
                $this->args = array_merge($cmdArgs, $this->userArgs);
            }
        }
        return $this->args;
    }

    function hasHttpRequest()
    {
        return $this->httpRequest() != null;
    }

    public function httpRequest()
    {
        if ($this->isHttpRequest() && is_null($this->httpRequest))
            $this->httpRequest = new HttpRequest($this);
        return $this->httpRequest;
    }

    function showErrors()
    {
        return $this->showErrors;
    }

    public function setShowErrors($showErrors = false)
    {
        $this->errorHandler->setShowErrors($showErrors);
        return $this;
    }

    public function setUserArgs(array $args = array())
    {
        $this->userArgs = $args;
        return $this;
    }

    /**
     * 
     * @return Router
     */
    public function router()
    {
        return $this->router;
    }

    public function httpResponse()
    {

        if (is_null($this->httpResponse))
            $this->httpResponse = new HttpResponse($this);
        return $this->httpResponse;
    }

    public function newException()
    {
        return new Exception();
    }

    protected function isHttpRequest()
    {
        if (is_null($this->isHttpRequest))
            $this->isHttpRequest = !isset($_SERVER["argv"]) || is_null($_SERVER["argv"]) || is_string($_SERVER["argv"]);

        return $this->isHttpRequest;
    }

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var ErrorHandler
     */
    protected $errorHandler;

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
     * @var Router
     */
    protected $router;

    /**
     *
     * @var Interfaces\HttpResponse
     */
    protected $httpResponse;

}


/**
 * 
 * @author muellerm
 */
interface AppFunction
{

    /**
     * 
     * @param App $app
     */
    public function run(App $app);
}


class Cmd
{

    function __construct(string $executable)
    {
        $this->executable = $executable;
    }

    public function arg($arg)
    {
        $this->args[] = $arg;
        return $this;
    }

    public function escapeArg($arg)
    {
        $this->args[] = escapeshellarg($arg);
        return $this;
    }

    public function run()
    {
        $args = [$this->executable];
        $args = array_merge($args, $this->args);
        $outputArray = [];
        $returnCode = -1;
        flush();
        exec(implode(" ", $args), $outputArray, $returnCode);
        return new CmdOutput(implode("\n", $outputArray), $returnCode);
    }

    /**
     *
     * @var string
     */
    protected $executable;

    /**
     *
     * @var string[]
     */
    protected $args = [];

}


class CmdOutput
{

    function __construct(string $output, int $returnCode)
    {
        $this->output = $output;
        $this->returnCode = $returnCode;
    }

    function output()
    {
        return $this->output;
    }

    function returnCode()
    {
        return $this->returnCode;
    }

    public function successful()
    {
        return $this->returnCode == 0;
    }

    /**
     *
     * @var string
     */
    protected $output;

    /**
     *
     * @var int
     */
    protected $returnCode;

}


class Error
{

    function __construct(string $text, string $relatedKey = null)
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


/**
 * Default Error Handler
 * 
 * @author muellerm
 */
class ErrorHandler
{

    function __construct(App $app, $showErrors = true)
    {
        error_reporting(E_ALL);
        ini_set('log_errors', intval($showErrors));
        ini_set('display_errors', intval($showErrors));
        ini_set('html_errors', intval($app->hasHttpRequest()));
        $this->app = $app;
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
        if ($this->app->hasHttpRequest())
        {
            $code = $exception instanceof Exception ? $exception->httpReturnCode() : \Qck\HttpResponse::EXIT_CODE_INTERNAL_ERROR;
            http_response_code($code);
        }

        throw $exception;
    }

    function setShowErrors(bool $showErrors)
    {
        $this->showErrors = $showErrors;

        ini_set('log_errors', intval($showErrors));
        ini_set('display_errors', intval($showErrors));
        return $this;
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
        $this->uninstall();
    }

    /**
     *
     * @var App
     */
    protected $app;

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


class Exception extends \Exception
{

    public function __construct(string $message = "", int $code = 0, \Throwable $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
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
        $this->message = implode(", ", array_map('strval', $this->errors));
    }

    public function argError($text, $relatedKey, ...$args)
    {
        $this->errors[] = new Error(vsprintf($text, $args), $relatedKey);
        $this->generateMessage();
        return $this;
    }

    public function error($text, ...$args)
    {
        $this->errors[] = new Error(vsprintf($text, $args));
        $this->generateMessage();
        return $this;
    }

    function errors()
    {
        return $this->errors;
    }

    public function setHttpReturnCode($returnCode = \Qck\HttpResponse::EXIT_CODE_INTERNAL_ERROR)
    {
        $this->httpReturnCode = $returnCode;
        return $this;
    }

    public function throw()
    {
        throw $this;
    }

    public function setReturnCode($returnCode = -1)
    {
        $this->returnCode = $returnCode;
        $this->code = $this->returnCode;
        return $this;
    }

    public function assert($condition, $error)
    {
        if ($condition == false)
        {
            $this->error($error);
            $this->throw();
        }
    }

    /**
     *
     * @var int
     */
    protected $httpReturnCode = \Qck\HttpResponse::EXIT_CODE_INTERNAL_ERROR;

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


class HttpContent
{

    // CONSTANTS
    const CONTENT_TYPE_TEXT_PLAIN = "text/plain";
    const CONTENT_TYPE_TEXT_HTML = "text/html";
    const CONTENT_TYPE_TEXT_CSS = "text/css";
    const CONTENT_TYPE_TEXT_JAVASCRIPT = "text/javascript";
    const CONTENT_TYPE_TEXT_CSV = "text/csv";
    const CONTENT_TYPE_APPLICATION_JSON = "application/json";
    const CONTENT_TYPE_APPLICATION_OCTET_STREAM = "application/octet-stream";
    const CHARSET_ISO_8859_1 = "ISO-8859-1";
    const CHARSET_UTF_8 = "utf-8";
    const CHARSET_BINARY = "binary";

    function __construct(\Qck\HttpResponse $response, $text)
    {
        $this->response = $response;
        $this->text = $text;
    }

    public function response()
    {
        return $this->response;
    }

    public function setCharset($charSet = \Qck\HttpContent::CHARSET_UTF_8)
    {
        $this->charSet = $charSet;
        return $this;
    }

    public function setContentType($contentType = \Qck\HttpContent::CONTENT_TYPE_TEXT_HTML)
    {
        $this->contentType = $contentType;
        return $this;
    }

    function text()
    {
        return strval($this->text);
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
     * @var \Qck\HttpResponse
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
    protected $contentType = \Qck\HttpContent::CONTENT_TYPE_TEXT_HTML;

    /**
     *
     * @var string
     */
    protected $charSet = \Qck\HttpContent::CHARSET_UTF_8;

}


/**
 * 
 */
class HttpRequest
{

    function __construct(App $app)
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
     * @var App
     */
    protected $app;

    /**
     *
     * @var \Qck\IpAddress
     */
    protected $ipAddress;

}


class HttpResponse
{
    const EXIT_CODE_OK = 200;
    const EXIT_CODE_BAD_REQUEST = 400;
    const EXIT_CODE_UNAUTHORIZED = 401;
    const EXIT_CODE_FORBIDDEN = 403;
    const EXIT_CODE_NOT_FOUND = 404;
    const EXIT_CODE_UNPROCESSABLE_ENTITY = 422;
    const EXIT_CODE_INTERNAL_ERROR = 500;
    const EXIT_CODE_NOT_IMPLEMENTED = 501;
    const EXIT_CODE_MOVED_PERMANENTLY = 301;
    const EXIT_CODE_REDIRECT_FOUND = 302;

    function __construct(App $app)
    {
        $this->app = $app;
    }

    public function createContent($text)
    {
        $this->content = new HttpContent($this, $text);
        return $this->content;
    }

    public function send()
    {
        http_response_code($this->returnCode);

        header(sprintf("Content-Type: %s; charset=%s", $this->content->contentType(), $this->content->charSet()));
        echo $this->content->text();
    }

    public function setReturnCode($returnCode = \Qck\HttpResponse::EXIT_CODE_OK)
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
     * @var App
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
    protected $returnCode = \Qck\HttpResponse::EXIT_CODE_OK;

}


/**
 * 
 */
class IpAddress
{

    function __construct( \Qck\HttpRequest $httpRequest )
    {
        $this->httpRequest = $httpRequest;
    }

    public function value()
    {
        if ( !$this->ip )
        {
            if ( !empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) )
            {
                //ip from share internet
                $this->ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
            }
            elseif ( !empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) )
            {
                //ip pass from proxy
                $this->ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
            }
            elseif ( !empty( $_SERVER[ 'REMOTE_ADDR' ] ) )
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
                $this->httpRequest->app()->newException()->error( "Invalid ip %s", $this->ip )->exception()->throw();
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
        $this->ip              = null;
        return $this;
    }

    /**
     *
     * @var \Qck\HttpRequest
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
