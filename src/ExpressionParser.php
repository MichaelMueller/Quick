<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class ExpressionParser
{

    const OPERATOR   = 0;
    const OPEN_PARA  = 1;
    const CLOSE_PARA = 2;
    const BOOL_OP    = 3;
    const VAR_NAME   = 5;
    const INTEGER    = 6;
    const FLOAT      = 7;
    const STRING     = 8;
    // Type Consts
    const TYPE_CHAIN = 0;
    const TYPE_CMP   = 1;
    const TYPE_FUNC  = 2;

    static function getTokenTypes()
    {
        $TokenTypes   = [];
        $TokenTypes[] = [ self::OPERATOR, "OPERATOR", "/^(\!\=|\=\=|\>\=|\<\=|\>|\<)$/" ];
        $TokenTypes[] = [ self::OPEN_PARA, "OPEN_PARA", "/^\($/" ];
        $TokenTypes[] = [ self::CLOSE_PARA, "CLOSE_PARA", "/^\)$/" ];
        $TokenTypes[] = [ self::BOOL_OP, "BOOL_OP", "/^([&][&]|[|][|]|and|or)$/" ];
        $TokenTypes[] = [ self::VAR_NAME, "VAR_NAME", "/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/" ];
        $TokenTypes[] = [ self::INTEGER, "INTEGER", "/^[1-9][0-9]*$/" ];
        $TokenTypes[] = [ self::FLOAT, "FLOAT", "/^[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)$/" ];
        $TokenTypes[] = [ self::STRING, "STRING", "#^\"[^\"\\\\]*(\\\\.[^\"\\\\]*)*\"$#" ];
        return $TokenTypes;
    }

    static function getIntegralTokenTypes()
    {
        return [ self::INTEGER, self::FLOAT, self::STRING ];
    }

    static function getOperandTokenTypes()
    {
        return array_merge( self::getIntegralTokenTypes(), [ self::VAR_NAME ] );
    }

    function __construct( $Expression = null )
    {
        $this->Expression = $Expression;
    }

    function getExpressionTree()
    {
        if ( is_null( $this->ExpressionTree ) )
            $this->ExpressionTree = $this->buildTree();

        return $this->ExpressionTree;
    }

    protected function buildTree()
    {
        $this->parseTokens();
        print_r( $this->Tokens );
        return $this->buildChain( null, false );
    }

    protected function createChain()
    {
        $Chain           = new \stdClass();
        $Chain->Children = [];
        $Chain->Op       = null;
        return $Chain;
    }

    protected function harmonizeBoolOperators( $Value )
    {
        if ( $Value == "||" )
            $Value = "or";
        else if ( $Value == "&&" )
            $Value = "and";

        return $Value;
    }

    protected function buildChain( $ExpectParantheses = true )
    {
        $Chain = $this->createChain();

        if ( $ExpectParantheses )
            $this->eat( self::OPEN_PARA );

        while ( true )
        {
            if ( $this->lookahead() == self::OPEN_PARA )
                $Chain->Children[] = $this->buildChain( true );
            else
                $Chain->Children[] = $this->buildComparison();

            if ( $this->lookahead() == self::BOOL_OP )
            {
                $Token     = $this->eat( self::BOOL_OP );
                $Value     = $this->harmonizeBoolOperators( $Token->Value );
                if ( $Chain->Op != null && $Chain->Op != $Value ) // operator precedence
                    $this->throwParserException( "Operator Precedence not implemtend.", $Token->Idx );
                else
                    $Chain->Op = $Value;
            }
            else if ( $ExpectParantheses )
            {
                $this->eat( self::CLOSE_PARA );
                break;
            }
            else
                break;
        }
        return $Chain;
    }

    protected function buildComparison()
    {
        $Cmp       = new \stdClass();
        $Cmp->Type = self::TYPE_CMP;

        // get op1
        $Cmp->Op1 = new \stdClass();
        if ( $this->lookahead( 2 ) == [ self::VAR_NAME, self::OPEN_PARA ] )
            $Cmp->Op1 = $this->buildFunction();
        else
            $Cmp->Op1 = $this->eat( self::getOperandTokenTypes() );

        // get op
        $Cmp->Op = new \stdClass();
        $Cmp->Op = $this->eat( [ self::OPERATOR ] );

        // get op2
        $Cmp->Op2 = new \stdClass();
        if ( $this->lookahead( 2 ) == [ self::VAR_NAME, self::OPEN_PARA ] )
            $Cmp->Op2 = $this->buildFunction();
        else
            $Cmp->Op2 = $this->eat( self::getOperandTokenTypes() );

        return $Cmp;
    }

    protected function buildFunction()
    {
        $Func       = new \stdClass();
        $Func->Type = self::TYPE_FUNC;
        $Func->Name = $this->eat( [ self::VAR_NAME ] );
        $this->eat( [ self::OPEN_PARA ] );
        $Func->Op   = $this->eat( self::getOperandTokenTypes() );
        $this->eat( [ self::CLOSE_PARA ] );

        return $Func;
    }

    protected function eat( $TypeOrTypes )
    {
        $Types = is_array( $TypeOrTypes ) ? $TypeOrTypes : [ $TypeOrTypes ];
        $Token = array_shift( $this->Tokens );
        if ( is_null( $Token ) )
            $this->throwParserException( sprintf( "No token found. Expected type %s.", implode( ", ", $Types ) ), mb_strlen( $this->Expression ) );
        else if ( !in_array( $Token->Type, $Types ) )
            throw new \InvalidArgumentException( sprintf( "Invalid Token Type. Expected type %s, got %s at position %s", implode( ", ", $Types ), $Token->Type, $Token->Idx ) );
        return $Token;
    }

    protected function lookahead( $Ahead = 1 )
    {
        $Types   = [];
        for ( $i = 0; $i < $Ahead && $i < count( $this->Tokens ); $i++ )
            $Types[] = $this->Tokens[ $i ]->Type;
        return count( $Types ) > 1 ? $Types : (count( $Types ) == 1 ? $Types[ 0 ] : null);
    }

    protected function parseTokens()
    {
        $Idx          = 0;
        $StrLength    = mb_strlen( $this->Expression );
        $this->Tokens = [];
        while ( true )
        {
            $Token          = new \stdClass();
            $Token->Type    = null;
            $Token->Name    = null;
            $Token->Value   = null;
            $Token->Idx     = null;
            $PossibleTokens = $this->getTokenTypes();
            for ( $Idx; $Idx < $StrLength; ++$Idx )
            {
                $Char = $this->Expression[ $Idx ];
                if ( ctype_space( $Char ) && is_null( $Token->Type ) )
                    continue;

                $Token->Value        .= $Char;
                $PossibleTokensCount = count( $PossibleTokens );
                for ( $i = 0; $i < $PossibleTokensCount; $i++ )
                {
                    $TokenTypeRegex = $PossibleTokens[ $i ][ 2 ];
                    if ( preg_match( $TokenTypeRegex, $Token->Value ) )
                    {
                        $Token->Type = $PossibleTokens[ $i ][ 0 ];
                        $Token->Name = $PossibleTokens[ $i ][ 1 ];
                        $Token->Idx  = $Idx;
                        break;
                    }
                    else if ( $Token->Type !== null )
                        unset( $PossibleTokens[ $i ] );
                }
                $PossibleTokens = array_values( $PossibleTokens );
                if ( count( $PossibleTokens ) == 0 )
                {
                    if ( $Token->Type === null )
                        $this->throwParserException( "Unexpected Character", $Idx );

                    break;
                }
            }

            $Token->Value = $Token->Value ? mb_substr( $Token->Value, 0, mb_strlen( $Token->Value ) - 1 ) : null;

            if ( $Token->Type !== null && $Token->Value !== null )
                $this->Tokens[] = $Token;
            else
                break;
        }
    }

    protected function throwParserException( $Msg, $Idx )
    {
        $Length    = 5;
        $NearStr   = $Idx + 1 > $Length ? mb_substr( $this->Expression, $Idx - $Length, $Length + 1 ) : null;
        
        $Msg       = $Msg . " Occured at position " . strval( $Idx ) . " near \"" . $NearStr . "\"";
        throw new \InvalidArgumentException( $Msg );
    }

    /**
     *
     * @var string
     */
    protected $Expression;

// state

    /**
     *
     * @var \stdClass
     */
    protected $Tokens;

    /**
     *
     * @var \stdClass
     */
    protected $ExpressionTree;

}
