<?php

namespace Qck;

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
