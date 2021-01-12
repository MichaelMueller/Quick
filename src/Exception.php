<?php

namespace Qck;

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
