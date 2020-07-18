<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface AdminMailer
{

    /**
     * 
     * @param string $message
     */
    public function sendToAdmin( $message );
}
