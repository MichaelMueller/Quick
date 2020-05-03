<?php

namespace Qck;

class CssGridGenerator implements Interfaces\Snippet
{

    function __construct( $GridWidth, $CellName = "Cell", $BaseSize = 360, $NumBreakPoints = null )
    {
        $this->GridWidth      = $GridWidth;
        $this->BaseSize       = $BaseSize;
        $this->CellName       = $CellName;
        $this->NumBreakPoints = $NumBreakPoints;
    }

    function setIndent( $Indent )
    {
        $this->Indent = $Indent;
    }

    function setIndentString( $IndentString )
    {
        $this->IndentString = $IndentString;
    }

    protected function addLine( $Indent, $String )
    {
        return str_repeat( $this->IndentString, $Indent ) . $String . PHP_EOL;
    }

    function __toString()
    {
        $Indent         = $this->Indent;
        $GridWidth      = $this->GridWidth;
        $BaseSize       = $this->BaseSize;
        $NumBreakPoints = $this->NumBreakPoints;
        $CellClassName  = "." . $this->CellName . "1Of" . strval( $GridWidth );
        // start generate
        $Css            = "";
        $Css            .= $this->addLine( $Indent, "/* Generated Grid, Width: $GridWidth, BaseSize: " . $BaseSize . ($NumBreakPoints ? ", NumBreakPoints: " . $NumBreakPoints : "") . " */" );
        $Css            .= $this->addLine( $Indent, $CellClassName );
        $Css            .= $this->addLine( $Indent, "{" );
        $Css            .= $this->addLine( $Indent + 1, "width: 100%;" );
        $Css            .= $this->addLine( $Indent + 1, "min-width: 100%;" );
        $Css            .= $this->addLine( $Indent, "}" );

        if ( $NumBreakPoints )
        {
            $Range    = $NumBreakPoints;
            $Values   = [];
            for ( $i = $Range; $i >= 1; $i-- )
                $Values[] = round( $GridWidth / $i );
        }
        else
            $Values = range( 1, $GridWidth );

        foreach ( $Values as $i )
        {
            $Width    = $i * $BaseSize;
            $MinWidth = 100.0 / (floatval( $Width ) / floatval( $BaseSize ));
            $Css      .= $this->addLine( $Indent, "@media screen and (min-width: " . $Width . "px)" );
            $Css      .= $this->addLine( $Indent, "{" );
            $Css      .= $this->addLine( $Indent + 1, $CellClassName );
            $Css      .= $this->addLine( $Indent + 1, "{" );
            $Css      .= $this->addLine( $Indent + 2, "width: " . $MinWidth . "%;" );
            $Css      .= $this->addLine( $Indent + 2, "min-width: " . $MinWidth . "%;" );
            $Css      .= $this->addLine( $Indent + 1, "}" );
            $Css      .= $this->addLine( $Indent, "}" );
        }

        $Css .= $this->addLine( $Indent, "@media screen and (min-width: " . (($GridWidth + 1) * $BaseSize) . "px)" );
        $Css .= $this->addLine( $Indent, "{" );
        $Css .= $this->addLine( $Indent + 1, $CellClassName );
        $Css .= $this->addLine( $Indent + 1, "{" );
        $Css .= $this->addLine( $Indent + 2, "flex: 1;" );
        $Css .= $this->addLine( $Indent + 1, "}" );
        $Css .= $this->addLine( $Indent, "}" );

        return $Css;
    }

    /**
     *
     * @var int
     */
    protected $GridWidth;

    /**
     *
     * @var string
     */
    protected $CellName = "Cell";

    /**
     *
     * @var int
     */
    protected $BaseSize;

    /**
     *
     * @var int
     */
    protected $NumBreakPoints = null;

    /**
     *
     * @var int
     */
    protected $Indent = 0;

    /**
     *
     * @var string
     */
    protected $IndentString = "    ";

}
