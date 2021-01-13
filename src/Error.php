<?php

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
