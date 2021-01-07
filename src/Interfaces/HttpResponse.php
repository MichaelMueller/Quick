<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface HttpResponse
{

    const EXIT_CODE_OK = 200;
    const EXIT_CODE_BAD_REQUEST = 400;
    const EXIT_CODE_UNAUTHORIZED = 401;
    const EXIT_CODE_FORBIDDEN = 403;
    const EXIT_CODE_NOT_FOUND = 404;
    const EXIT_CODE_UNPROCESSABLE_ENTITY = 422;
    const EXIT_CODE_INTERNAL_ERROR = 500;
    const EXIT_CODE_NOT_IMPLEMENTED = 501;
    const EXIT_CODE_MOVED_PERMANENTLY = 301;
    const EXIT_CODE_REDIRECT_FOUND = 302;

    /**
     * @return HttpResponse
     */
    function setReturnCode($returnCode = HttpResponse::EXIT_CODE_OK);

    /**
     * @return HttpContent
     */
    function createContent($text);

    /**
     * @return void
     */
    public function send();

    /**
     * @return App
     */
    function app();
}
