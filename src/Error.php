<?php

namespace Qck;

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
