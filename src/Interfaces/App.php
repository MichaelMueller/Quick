<?php

namespace Qck\Interfaces;

/**
 * Interface for an App Config. In its basic form it only provides an Inputs interface
 * 
 * @author muellerm
 */
interface App
{

    /**
     * @return string
     */
    function buildUrl( $RouteName, array $QueryData = [] );
}
