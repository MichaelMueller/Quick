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
     * @param string $subject
     * @param string $message
     */
    public function sendToAdmin( $subject, $message );
}
