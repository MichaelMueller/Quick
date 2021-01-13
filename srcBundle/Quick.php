<?php

namespace Qck;

/**
 * Basic App infrastructure for dispatching to different App Functions
 * 
 * @author muellerm
 */
class App
{

    static function new( $name, $defaultAppFunctionFqcn ): App
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

    function addAppFunctionNamespace( $appFunctionNamespace ): App
    {
        $this->appFunctionNamespaces[] = $appFunctionNamespace;
        return $this;
    }

    function addRoute( $fqcn, $routeName = null ): App
    {
        $fqcnParts                  = explode( "\\", $fqcn );
        $routeName                  = $routeName ? $routeName : array_pop( $fqcnParts );
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

    /**
     * 
     * @return string the name of the current route or null
     */
    function currentRoute()
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

    function name(): string
    {
        return $this->name;
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

        $exception = Exception::new()->setHttpReturnCode( HttpResponse::EXIT_CODE_NOT_FOUND );
        if ( !preg_match( "/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/", $route ) )
            $exception->argError( "Invalid route '%s'", $this->routeParamName, $route )->throw();

        $fqcn = $this->routes[ $route ] ?? null;
        if ( is_null( $fqcn ) && count( $this->appFunctionNamespaces ) > 0 )
        {
            foreach ( $this->appFunctionNamespaces as $appFunctionNamespace )
            {
                $fqcn = $appFunctionNamespace . "\\" . $route;
                if ( class_exists( $fqcn, true ) )
                    break;
            }
        }

        $appFunction = null;
        if ( !class_exists( $fqcn, true ) )
            $exception->argError( "Class '%s' does not exist", $this->routeParamName, $fqcn )->throw();

        $appFunction = new $fqcn();
        if ( !$appFunction instanceof \Qck\AppFunction )
            $exception->argError( "Class '%s' does not implement interface '%s'", $this->routeParamName, $fqcn, \Qck\AppFunction::class )->throw();
        $appFunction->run( $this );
    }

    protected function installErrorHandler(): void
    {
        $this->errorHandler = ErrorHandler::new()->setRequest( $this->request() );
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
     * @var string[] 
     */
    protected $appFunctionNamespaces = [];

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

namespace Qck;

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

namespace Qck;

/**
 * Class representing a system command
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class Cmd
{

    static function new( string $executable ): Cmd
    {
        return new Cmd( $executable );
    }

    function __construct( string $executable )
    {
        $this->executable = $executable;
    }

    public function arg( $arg ): Cmd
    {
        $this->args[] = $arg;
        return $this;
    }

    public function escapeArg( $arg ): Cmd
    {
        $this->args[] = escapeshellarg( $arg );
        return $this;
    }

    public function run(): CmdOutput
    {
        $args        = [ $this->executable ];
        $args        = array_merge( $args, $this->args );
        $outputArray = [];
        $returnCode  = -1;
        flush();
        exec( implode( " ", $args ), $outputArray, $returnCode );
        return new CmdOutput( implode( "\n", $outputArray ), $returnCode );
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

namespace Qck;

/**
 * Class representing the output of a system command
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class CmdOutput
{

    function __construct( string $output, int $returnCode )
    {
        $this->output     = $output;
        $this->returnCode = $returnCode;
    }

    function output(): string
    {
        return $this->output;
    }

    function returnCode(): int
    {
        return $this->returnCode;
    }

    public function successful(): bool
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

namespace Qck;

/**
 * Class for collecting all Psr4 compatible classes and bundle it into one file.
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class ComposerCodeBundler
{

    static function new( Log $log, string $outputFile ): ComposerCodeBundler
    {
        return new ComposerCodeBundler( $log, $outputFile );
    }

    function __construct( Log $log, string $outputFile )
    {
        $this->log        = $log;
        $this->outputFile = $outputFile;
    }

    function __invoke()
    {
        $this->log->info( "Searching composer autoloader" )->send();
        $code                = "";
        $res                 = get_declared_classes();
        $autoloaderClassName = '';
        foreach ( $res as $className )
        {
            if ( strpos( $className, 'ComposerAutoloaderInit' ) === 0 )
            {
                $autoloaderClassName = $className; // ComposerAutoloaderInit323a579f2019d15e328dd7fec58d8284 for me
                break;
            }
        }

        $this->log->info( "Searching composer autoloader" )->send();
        /* @var $classLoader \Composer\Autoload\ClassLoader */
        $classLoader = $autoloaderClassName::getLoader();
        $filenames   = [];
        foreach ( $classLoader->getPrefixesPsr4() as $prefix => $paths )
        {
            foreach ( $paths as $path )
            {
                $handle = opendir( $path );

                while ( false !== ($entry = readdir( $handle )) )
                {
                    $filename = realpath( $path . "/" . $entry );
                    //print_r( $filename );
                    if ( is_file( $filename ) == false || in_array( $filename, $this->excludedFiles ) )
                    {
                        $this->log->info( "Skipping %s. No file or excluded." )->addArg( $filename )->send();
                        continue;
                    }
                    $ext = pathinfo( $filename, PATHINFO_EXTENSION );
                    if ( !in_array( $ext, $this->extensions ) )
                    {
                        $this->log->info( "Skipping %s. Extension '%s' not found in extensions '%s'." )
                                ->addArg( $filename )->addArg( $ext )->addArg( implode( ", ", $this->extensions ) )->send();
                        continue;
                    }
                    $className = pathinfo( $filename, PATHINFO_FILENAME );
                    $fqcn      = "\\" . $prefix . $className;
                    if ( !class_exists( $fqcn, true ) && !interface_exists( $fqcn, true ) )
                    {
                        $this->log->warn( "Skipping %s. A corresponding PSR-4 compliant class '%s' was not found." )
                                ->addArg( $filename )->addArg( $fqcn )->send();
                        continue;
                    }

                    $filenames[] = $filename;
                }

                closedir( $handle );
            }
        }

        $this->log->info( "Generating code" )->send();
        foreach ( $filenames as $filename )
        {
            $this->log->info( "Processing file %s" )->addArg( $filename )->send();
            //print sprintf("processing %s\n", $filename);
            $ext = pathinfo( $filename, PATHINFO_EXTENSION );
            if ( in_array( $ext, $this->extensions ) )
            {
                //print sprintf("extracting from %s\n", $filename);
                $contents = file_get_contents( $filename );
                $startDef = "<?php";
                $start    = strpos( $contents, $startDef );
                if ( $start === false )
                    continue;

                $contents = trim( mb_substr( $contents, $start + mb_strlen( $startDef ) ) );
                if ( mb_strlen( $contents ) > 2 && mb_substr( $contents, -2 ) == "?>" )
                    $contents = mb_substr( $contents, 0, mb_strlen( $contents ) - 2 );

                $code .= PHP_EOL . PHP_EOL . $contents;
            }
        }

        $targetFile = $this->outputFile;
        //print sprintf("dumping code to %s\n", $targetFile);
        $this->log->info( "Dumping code to %s" )->addArg( $targetFile )->send();
        file_put_contents( $targetFile, "<?php" . $code );
    }

    /**
     * 
     * @param string $excludedFile
     * @return $this
     */
    function addExcludedFile( $excludedFile )
    {
        $this->excludedFiles[] = $excludedFile;
        return $this;
    }

    /**
     * 
     * @param string $ext
     * @return $this
     */
    function addPhpExtension( $ext )
    {
        $this->extensions[] = $ext;
        return $this;
    }

    /**
     *
     * @var Log
     */
    protected $log;

    /**
     *
     * @var string
     */
    protected $outputFile;

    /**
     *
     * @var string[]
     */
    protected $extensions = [ "php" ];

    /**
     *
     * @var string[]
     */
    protected $excludedFiles = [];

}

namespace Qck;

/**
 * Class for representing an Error (possibly related to an Argument specified by relatedKey)
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
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

namespace Qck;

/**
 * A basic Error Handler
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class ErrorHandler
{

    static function new( ): ErrorHandler
    {
        return new ErrorHandler( );
    }

    function __construct()
    {
        error_reporting( E_ALL );
        ini_set( 'log_errors', 1 );
        ini_set( 'display_errors', 0 );
        ini_set( 'html_errors', 0 );
        $this->install();
    }

    function errorHandler( $errno, $errstr, $errfile, $errline )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }

    function exceptionHandler( \Throwable $exception )
    {
        /* @var $exception \Exception */
        if ( $this->request !== null && $this->request->isHttpRequest() )
        {
            $code = $exception instanceof Exception ? $exception->httpReturnCode() : \Qck\HttpResponse::EXIT_CODE_INTERNAL_ERROR;
            http_response_code( $code );
        }

        throw $exception;
    }

    function setRequest( Request $request ): ErrorHandler
    {
        $this->request = $request;
        ini_set( 'html_errors', intval( $request->isHttpRequest() ) );
        return $this;
    }

    function setShowErrors( bool $showErrors ): ErrorHandler
    {
        $this->showErrors = $showErrors;

        ini_set( 'log_errors', intval( $showErrors ) );
        ini_set( 'display_errors', intval( $showErrors ) );
        return $this;
    }

    function install(): void
    {
        if ( $this->errorHandler && $this->exceptionHandler )
            return;
        $this->errorHandler     = set_error_handler( array ( $this, "errorHandler" ) );
        $this->exceptionHandler = set_exception_handler( array ( $this, "exceptionHandler" ) );
    }

    protected function uninstall(): void
    {
        if ( !$this->errorHandler || !$this->exceptionHandler )
            return;
        set_error_handler( $this->errorHandler );
        set_exception_handler( $this->exceptionHandler );
        $this->errorHandler     = null;
        $this->exceptionHandler = null;
    }

    public function __destruct()
    {
        $this->uninstall();
    }

    /**
     *
     * @var Request
     */
    protected $request;

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

namespace Qck;

/**
 * An exception class carying more detailed information than the PHP Exception base class
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class Exception extends \Exception
{

    static function new(): Exception
    {
        return new \Qck\Exception();
    }

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

    public function setHttpReturnCode( $returnCode = \Qck\HttpResponse::EXIT_CODE_INTERNAL_ERROR )
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
        $this->code       = $this->returnCode;
        return $this;
    }

    public function assert( $condition, $error )
    {
        if ( $condition == false )
        {
            $this->error( $error );
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

namespace Qck;

/**
 * Class representing HttpContent
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class HttpContent implements Snippet
{

    // CONSTANTS
    const CONTENT_TYPE_TEXT_PLAIN               = "text/plain";
    const CONTENT_TYPE_TEXT_HTML                = "text/html";
    const CONTENT_TYPE_TEXT_CSS                 = "text/css";
    const CONTENT_TYPE_TEXT_JAVASCRIPT          = "text/javascript";
    const CONTENT_TYPE_TEXT_CSV                 = "text/csv";
    const CONTENT_TYPE_APPLICATION_JSON         = "application/json";
    const CONTENT_TYPE_APPLICATION_OCTET_STREAM = "application/octet-stream";
    const CHARSET_ISO_8859_1                    = "ISO-8859-1";
    const CHARSET_UTF_8                         = "utf-8";
    const CHARSET_BINARY                        = "binary";

    function __construct( HttpResponse $response, $body = null )
    {
        $this->response = $response;
        $this->body     = $body;
    }

    function setBody( $body ): HttpContent
    {
        $this->body = $body;
        return $this;
    }

    public function response()
    {
        return $this->response;
    }

    public function setCharset( $charSet = \Qck\HttpContent::CHARSET_UTF_8 )
    {
        $this->charSet = $charSet;
        return $this;
    }

    public function setContentType( $contentType = \Qck\HttpContent::CONTENT_TYPE_TEXT_HTML )
    {
        $this->contentType = $contentType;
        return $this;
    }

    function toString(): string
    {
        return $this->body instanceof Snippet ? $this->body->toString() : strval( $this->body );
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
     * @var HttpResponse
     */
    protected $response;

    /**
     *
     * @var Snippet|string
     */
    protected $body;

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

namespace Qck;

/**
 * A basic representing a HttpRequest
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class HttpRequest extends Request
{

    public function __construct( array $userArgs = [] )
    {
        parent::__construct( $userArgs );
    }

    function httpRequest()
    {
        return $this;
    }

    public function isHttpRequest()
    {
        return true;
    }

    public function ipAddress()
    {
        if ( is_null( $this->ipAddress ) )
            $this->ipAddress = new IpAddress();
        return $this->ipAddress;
    }

    /**
     *
     * @var \Qck\IpAddress
     */
    protected $ipAddress;

}

namespace Qck;

class HttpResponse
{

    const EXIT_CODE_OK                   = 200;
    const EXIT_CODE_BAD_REQUEST          = 400;
    const EXIT_CODE_UNAUTHORIZED         = 401;
    const EXIT_CODE_FORBIDDEN            = 403;
    const EXIT_CODE_NOT_FOUND            = 404;
    const EXIT_CODE_UNPROCESSABLE_ENTITY = 422;
    const EXIT_CODE_INTERNAL_ERROR       = 500;
    const EXIT_CODE_NOT_IMPLEMENTED      = 501;
    const EXIT_CODE_MOVED_PERMANENTLY    = 301;
    const EXIT_CODE_REDIRECT_FOUND       = 302;

    /**
     * 
     * @return \Qck\HttpResponse
     */
    static function new()
    {
        return new HttpResponse();
    }

    public function createContent( $body )
    {
        $this->content = new HttpContent( $this, $body );
        return $this->content;
    }

    public function send()
    {
        http_response_code( $this->returnCode );

        header( sprintf( "Content-Type: %s; charset=%s", $this->content->contentType(), $this->content->charSet() ) );
        echo $this->content->toString();
    }

    public function setReturnCode( $returnCode = \Qck\HttpResponse::EXIT_CODE_OK )
    {
        $this->returnCode = $returnCode;
        return $this;
    }

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

namespace Qck;

/**
 * 
 */
class IpAddress
{

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
                Exception::new()->error( "Invalid ip %s", $this->ip )->exception()->throw();
        }

        return $this->ip;
    }

    public function __toString()
    {
        return $this->value();
    }

    public function setValidationFlags( $validationFlags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE )
    {
        $this->validationFlags = $validationFlags;
        $this->ip = null;
        return $this;
    }

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

namespace Qck;

/**
 * Basic logging class.
 * 
 * @todo Implement channels via request / file logging
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class Log
{

    static function new( Request $request ): Log
    {
        return new Log( $request );
    }

    function __construct( Request $request )
    {
        $this->request = $request;
    }

    function send( LogMessage $logMessage )
    {
        $matchingTopics = array_values( array_intersect( $this->acitveTopics, $logMessage->topics() ) );
        if ( count( $matchingTopics ) == 0 )
            return;
        $text           = strval( $logMessage );
        $this->handleMessage( $text, $matchingTopics );
    }

    protected function handleMessage( $text, $matchingTopics )
    {
        if ( in_array( LogMessage::ERROR, $matchingTopics ) && $this->request->isHttpRequest() == false )
            fwrite( STDERR, $text . PHP_EOL );
        else
            print($text ) . PHP_EOL;
        flush();
    }

    function msg( $text, $topic ): LogMessage
    {
        $logMessage = LogMessage::new( $this, $text, $topic );
        $logMessage->setShowDateTime( $this->showDateTime )->setShowFile( $this->showFile )->setShowTopics( $this->showTopics );
        return $logMessage;
    }

    function addTopic( $activeTopic ): Log
    {
        $this->acitveTopics[] = $activeTopic;
        return $this;
    }

    function info( $text ): LogMessage
    {
        return $this->msg( $text, LogMessage::INFO );
    }

    function warn( $text )
    {
        return $this->msg( $text, LogMessage::WARN );
    }

    function error( $text )
    {
        return $this->msg( $text, LogMessage::ERROR );
    }

    function setShowDateTime( bool $showDateTime ): Log
    {
        $this->showDateTime = $showDateTime;
        return $this;
    }

    function setShowFile( string $showFile ): Log
    {
        $this->showFile = $showFile;
        return $this;
    }

    function setShowTopics( bool $showTopics ): Log
    {
        $this->showTopics = $showTopics;
        return $this;
    }

    /**
     *
     * @var Request
     */
    protected $request;

    /**
     *
     * @var bool
     */
    protected $showDateTime = true;

    /**
     *
     * @var bool
     */
    protected $showFile = true;

    /**
     *
     * @var bool
     */
    protected $showTopics = false;

    /**
     *
     * @var string[]
     */
    protected $acitveTopics = [];

}

namespace Qck;

/**
 * Class representing a log message.
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class LogMessage
{

    const ALL   = "all";
    const INFO  = "info";
    const WARN  = "warn";
    const ERROR = "error";
    const DEBUG = "debug";

    static function new( Log $log, string $text, string $topic ): LogMessage
    {
        return new LogMessage( $log, $text, $topic );
    }

    function __construct( Log $log, string $text, string $topic )
    {
        $this->log  = $log;
        $this->text = $text;
        $this->addTopic( $topic );
        $this->addTopic( self::ALL );

        $date           = \DateTime::createFromFormat( 'U.u', microtime( TRUE ) );
        $this->dateTime = $date->format( 'Y-m-d H:i:s.u' );
        $trace          = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 5 );
        if ( count( $trace ) > 3 )
        {
            $this->traceThirdElement = $trace[ 3 ];

            if ( count( $trace ) > 4 && isset( $trace[ 4 ][ "class" ] ) )
                $this->addTopic( $trace[ 4 ][ "class" ] );
        }
    }

    function send()
    {
        $this->log->send( $this );
    }

    function addTopic( $topic ): LogMessage
    {
        $this->topics[] = $topic;
        return $this;
    }

    function topics(): array
    {
        return $this->topics;
    }

    function hasTopic( $topic ): bool
    {
        return in_array( $topic, $this->topics );
    }

    function addArg( $arg ): LogMessage
    {
        $this->args[] = $arg;
        return $this;
    }

    function setShowDateTime( bool $showDateTime ): LogMessage
    {
        $this->showDateTime = $showDateTime;
        return $this;
    }

    function setShowFile( string $showFile ): LogMessage
    {
        $this->showFile = $showFile;
        return $this;
    }

    function setShowTopics( bool $showTopics ): LogMessage
    {
        $this->showTopics = $showTopics;
        return $this;
    }

    function text()
    {

        $msg = "";
        if ( $this->showTopics )
            $msg .= "[" . implode( ",", $this->topics ) . "]";
        if ( $this->showDateTime )
            $msg .= "[" . $this->dateTime . "]";
        if ( $this->showFile )
            $msg .= "[" . pathinfo( $this->traceThirdElement[ "file" ], PATHINFO_BASENAME ) . ":" . $this->traceThirdElement[ "line" ] . "]";
        $msg .= ": " . vsprintf( $this->text, $this->args );
        return $msg;
    }

    function __toString()
    {
        return $this->text();
    }

    /**
     *
     * @var Log
     */
    protected $log;

    /**
     *
     * @var string
     */
    protected $text;

    /**
     *
     * @var string[]
     */
    protected $args;

    /**
     *
     * @var string
     */
    protected $topics;

    /**
     *
     * @var bool
     */
    protected $showDateTime = true;

    /**
     *
     * @var bool
     */
    protected $showFile = true;

    /**
     *
     * @var bool
     */
    protected $showTopics = false;


    // state

    /**
     *
     * @var string
     */
    private $dateTime;

    /**
     *
     * @var array
     */
    private $traceThirdElement;

}

namespace Qck;

/**
 * 
 */
class Request
{

    function __construct( array $userArgs = [] )
    {
        $this->userArgs = $userArgs;
    }

    /**
     * 
     * @return HttpRequest or null
     */
    function httpRequest()
    {
        return null;
    }

    function isHttpRequest()
    {
        return false;
    }

    function has( $key )
    {
        $this->assertArgs();
        return isset( $this->args[ $key ] );
    }

    function get( $key, $default = null )
    {
        $this->assertArgs();
        return $this->args[ $key ] ?? $default;
    }

    public function args()
    {
        $this->assertArgs();
        return $this->args;
    }

    protected function assertArgs()
    {

        if ( is_null( $this->args ) )
        {
            // create args
            if ( $this->isHttpRequest() )
                $this->args = array_merge( $_COOKIE, $_GET, $_POST, $this->userArgs );
            else
            {
                $cmdArgs    = count( $_SERVER[ "argv" ] ) > 1 ? parse_str( $_SERVER[ "argv" ][ 1 ] ) : [];
                $this->args = array_merge( $cmdArgs, $this->userArgs );
            }
        }
        return $this->args;
    }

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

}

namespace Qck;

/**
 * 
 */
class RequestFactory
{

    function request()
    {
        if ( is_null( $this->request ) )
        {
            if ( ! isset( $_SERVER[ "argv" ] ) || is_null( $_SERVER[ "argv" ] ) || is_string( $_SERVER[ "argv" ] ) )
                $this->request = new HttpRequest();
            else
                $this->request = new Request ( );
        }

        return $this->request;
    }

    /**
     *
     * @var Request
     */
    protected $request;

}

namespace Qck;

/**
 * 
 * @author muellerm
 */
interface Snippet
{

    /**
     * 
     * @return string
     */
    public function toString(): string;
}