<?php

namespace Qck;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class CssSizeGenerator implements \Qck\Interfaces\Snippet
{

    const BASE_SIZE = "8px + 0.9vw";

    function __construct( $BaseIndent = 0, $BaseSize = self::BASE_SIZE, $Indent = "    " )
    {
        $this->BaseSize   = $BaseSize;
        $this->BaseIndent = $BaseIndent;
        $this->Indent     = $Indent;
    }

    function addRule( $Selector, $Property, $Multiplier )
    {
        if ( !isset( $this->Rules[ $Selector ] ) )
            $this->Rules[ $Selector ] = [];

        $this->Rules[ $Selector ][ $Property ] = $Multiplier;
    }

    public function __toString()
    {
        $Css    = "";
        $Indent = $this->Indent;
        foreach ( $this->Rules as $Selector => $Props )
        {
            $Css .= str_repeat( $Indent, $this->BaseIndent ) . $Selector . PHP_EOL;
            $Css .= str_repeat( $Indent, $this->BaseIndent ) . "{" . PHP_EOL;
            foreach ( $Props as $Property => $Multiplier )
            {
                if ( is_string( $Multiplier ) )
                    $Size = $Multiplier;
                else
                    $Size = $Multiplier ? $this->getSize( $Multiplier ) : 0;
                $Css  .= str_repeat( $Indent, $this->BaseIndent + 1 ) . $Property . ": " . $Size . ";" . PHP_EOL;
            }
            $Css .= str_repeat( $Indent, $this->BaseIndent ) . "}" . PHP_EOL;
        }

        return $Css;
    }

    function getSizes( array $Multipliers )
    {
        $Sizes   = [];
        foreach ( $Multipliers as $Multiplier )
            $Sizes[] = $this->getSize( $Multiplier );
        return $Sizes;
    }

    function getSize( $Multiplier = 1.0 )
    {
        return sprintf( "calc(%.2f*(%s))", $Multiplier, $this->BaseSize );
    }

    /**
     *
     * @var int
     */
    protected $BaseIndent;

    /**
     *
     * @var string
     */
    protected $BaseSize;

    /**
     *
     * @var string
     */
    protected $Indent;

    /**
     *
     * @var array[]
     */
    protected $Rules = [];

}
