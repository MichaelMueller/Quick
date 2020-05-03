<?php

namespace Qck\Interfaces;

/**
 * Client specific information goes here.
 * @author muellerm
 */
interface Language
{

    // ISO 639-1 codes, see https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
    const EN = "en";
    const DE = "de";

    /**
     * @return string
     */
    function get();
}
