<?php

namespace Qck;

/**
 * App class is essentially the class to start.
 * It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class ContentNegotiator implements Interfaces\ContentNegotiator
{

    public function getRequestContentType( $DeliverableContentTypes = [] )
    {
        if ( !$this->AccpetedContentTypes )
            $this->AccpetedContentTypes = $this->parseAcceptHeader( "HTTP_ACCEPT" );
        return $this->checkInAcceptHeader( $DeliverableContentTypes, $this->AccpetedContentTypes );
    }

    public function getRequestedCharset( $DeliverableCharsets = array () )
    {
        if ( !$this->AcceptedCharsets )
            $this->AcceptedCharsets = $this->parseAcceptHeader( "HTTP_ACCEPT_CHARSET" );
        return $this->checkInAcceptHeader( $DeliverableCharsets, $this->AcceptedCharsets );
        
    }

    public function getRequestedLanguage( $DeliverableLanguages = array () )
    {
        if ( !$this->AcceptedLanugages )
            $this->AcceptedLanugages = $this->parseAcceptHeader( "HTTP_ACCEPT_LANGUAGE" );
        return $this->checkInAcceptHeader( $DeliverableLanguages, $this->AcceptedLanugages );
        
    }

    protected function checkInAcceptHeader( array $DeliverableTypes, array $AcceptTypes )
    {

        $LowerDeliverableTypes = array_map( 'strtolower', $DeliverableTypes );

        // let’s check our supported types:
        foreach ( $AcceptTypes as $Type => $q )
        {
            if ( $q && in_array( $Type, $LowerDeliverableTypes ) )
                return $Type;
        }
        // no mime-type found
        return null;
    }

    protected function parseAcceptHeader( $Name )
    {
        // Values will be stored in this array
        $AcceptTypes = Array ();

        // Accept header is case insensitive, and whitespace isn’t important
        $accept_parts = strtolower( str_replace( ' ', '', isset( $_SERVER[ $Name ] ) ? $_SERVER[ $Name ] : [] ) );
        // divide it into parts in the place of a ","
        $accept = explode( ',', $accept_parts );
        foreach ( $accept as $a )
        {
            // the default quality is 1.
            $q = 1;
            // check if there is a different quality
            if ( strpos( $a, ';q=' ) )
            {
                // divide "mime/type;q=X" into two parts: "mime/type" i "X"
                list($a, $q) = explode( ';q=', $a );
            }
            // mime-type $a is accepted with the quality $q
            // WARNING: $q == 0 means, that mime-type isn’t supported!
            $AcceptTypes[ $a ] = $q;
        }
        arsort( $AcceptTypes );

        return $AcceptTypes;
    }

    /**
     *
     * @var string[] 
     */
    protected $AccpetedContentTypes;

    /**
     *
     * @var string[] 
     */
    protected $AcceptedCharsets;

    /**
     *
     * @var string[] 
     */
    protected $AcceptedLanugages;

}
