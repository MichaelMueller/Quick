<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Mailer
{

    /**
     * 
     * @param array $recipients
     * @param string $subject
     * @param string $text
     * @param array $attachments
     * @param bool $isHtml
     * @return string|null
     */
    function send( array $recipients, $subject, $text, array $attachments=[], $isHtml=false );
}
