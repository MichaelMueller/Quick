<?php

namespace Qck\ThirdParty;

/**
 *
 * @author muellerm
 */
class ContentNegotiator implements \Qck\Interfaces\ContentNegotiator
{

    public function getPreferredContentType( array $ProvidedContentTypes, $default = \Qck\Interfaces\HttpContent::CONTENT_TYPE_TEXT_HTML )
    {
        assert( isset( $_SERVER[ "HTTP_ACCEPT" ] ) );
        $negotiator = new \Negotiation\FormatNegotiator();
        $format     = $negotiator->getBest( $_SERVER[ "HTTP_ACCEPT" ], $ProvidedContentTypes );
        return $format ? $format : $default;
    }

    public function getPreferredLanguage( array $ProvidedLanguages, $default = "en" )
    {
        assert( isset( $_SERVER[ "HTTP_ACCEPT_LANGUAGE" ] ) );
        $negotiator = new \Negotiation\LanguageNegotiator();
        $language   = $negotiator->getBest( $ProvidedLanguages );
        return $language ? $language : $default;
    }

}
