<?php

namespace Qck;

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
