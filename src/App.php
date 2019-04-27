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
     * @return string[]
     */
    abstract function getShellMethods();

    protected function setupErrorHandling()
    {
        error_reporting(E_ALL);
        ini_set('log_errors', intval(!$this->DevMode));
        ini_set('display_errors', intval($this->DevMode));
        ini_set('html_errors', intval(!$this->getCliDetector()->isCli()));
    }

    function buildUrl($MethodName, array $QueryData = [])
    {
        $CompleteQueryData = array_merge([$this->MethodParamName => $MethodName], $QueryData);
        return "?" . http_build_query($CompleteQueryData);
    }

    function handleError($Error, $ReturnCode)
    {
        if ($this->getCliDetector()->isCli() == false)
            http_response_code($ReturnCode);
        if ($this->DevMode)
            print $Error;
        else
            error_log($Error);
        exit(-1);
    }

    function run()
    {

        // find method and run
        $ShellMethods = $this->getShellMethods();
        $RequestedMethodName = $this->getInputs()->get($this->MethodParamName, $ShellMethods[0]);
        if (in_array($RequestedMethodName, $ShellMethods) === false)
            $this->handleError(sprintf("Method %s is not declared as Shell Method.", $RequestedMethodName), 404);

        $Method = new \ReflectionMethod($this, $RequestedMethodName);
        if ($Method->isPublic() == false)
        {
            $MethodAllowed = false;
            $User = $this->getSession()->getCurrentUser();
            if ($User)
                $MethodAllowed = $Method->isProtected() || ($Method->isPrivate() && $User->isAdmin());

            if ($MethodAllowed === false)
                $this->handleError(sprintf("Method %s is not allowed to be called.", $RequestedMethodName), Interfaces\HttpResponder::EXIT_CODE_UNAUTHORIZED);
        }
        $RequestedParams = $Method->getParameters();
        $FoundParams = [];
        foreach ($RequestedParams as $RequestedParam)
            $FoundParams[] = $this->getInputs()->get($RequestedParam->getName(), null);

        $Method->setAccessible(true);
        $Method->invokeArgs($this, $FoundParams);
    }

    /**
     *
     * @var bool 
     */
    protected $DevMode = false;

    /**
     *
     * @var string
     */
    protected $MethodParamName = "function";

}
