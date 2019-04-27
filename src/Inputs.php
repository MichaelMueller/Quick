<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Inputs implements \Qck\Interfaces\Inputs
{

    function __construct(CliDetector $CliDetector, $Data = [])
    {
        $this->CliDetector = $CliDetector;
        $this->Data = $Data;
    }

    function setData($Data)
    {
        $this->Data = $Data;
    }

    public function get($Name, $Default = null)
    {
        $Data = $this->getData();
        return isset($Data[$Name]) ? $Data[$Name] : $Default;
    }

    protected function parseArgv()
    {
        $argv = $_SERVER['argv'];
        array_shift($argv);
        $out = array();
        foreach ($argv as $arg)
        {
            // --foo --bar=baz
            if (substr($arg, 0, 2) == '--')
            {
                $eqPos = strpos($arg, '=');
                // --foo
                if ($eqPos === false)
                {
                    $key = substr($arg, 2);
                    $value = isset($out[$key]) ? $out[$key] : true;
                    $out[$key] = $value;
                }
                // --bar=baz
                else
                {
                    $key = substr($arg, 2, $eqPos - 2);
                    $value = substr($arg, $eqPos + 1);
                    $out[$key] = $value;
                }
            }
            // -k=value -abc
            else if (substr($arg, 0, 1) == '-')
            {
                // -k=value
                if (substr($arg, 2, 1) == '=')
                {
                    $key = substr($arg, 1, 1);
                    $value = substr($arg, 3);
                    $out[$key] = $value;
                }
                // -abc
                else
                {
                    $chars = str_split(substr($arg, 1));
                    foreach ($chars as $char)
                    {
                        $key = $char;
                        $value = isset($out[$key]) ? $out[$key] : true;
                        $out[$key] = $value;
                    }
                }
            }
            // plain-arg
            else
            {
                $value = $arg;
                $out[] = $value;
            }
        }

        return $out;
    }

    public function has($Name)
    {
        $Data = $this->getData();
        return isset($Data[$Name]);
    }

    public function getData()
    {
        if (!$this->Merged)
        {
            $InputData = $this->CliDetector->isCli() ? $this->parseArgv() : $_REQUEST;
            $this->Data = array_merge($InputData, $this->Data);
            $this->Merged = true;
        }
        return $this->Data;
    }

    /**
     *
     * @var CliDetector
     */
    protected $CliDetector;

    /**
     *
     * @var array
     */
    protected $Data;

    /**
     *
     * @var bool
     */
    protected $Merged = False;

}
