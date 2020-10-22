<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface App
{

    /**
     * @return string
     */
    function dataDir();

    /**
     * @return Dict
     */
    function args();

    /**
     * @return Request
     */
    function request();

    /**
     * @return AdminMailer
     */
    function adminMailer();

    /**
     * @return LanguageConfig
     */
    function languageConfig();

    /**
     * @return Language
     */
    function language();

    /**
     * @return HttpHeader|null if request is http, otherwise null
     */
    function httpHeader();

    /**
     * @return CliParser
     */
    function cliParser();

    /**
     * @return FunctorFactory
     */
    function functorFactory();
}
