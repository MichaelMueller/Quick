<?php

require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * 
 * @author muellerm
 */
class BundleQuick implements \Qck\AppFunction
{

    public function run( \Qck\App $app )
    {
        $log         = \Qck\Log::new( $app->request() )->addTopic( Qck\LogMessage::ALL );
        $codeBundler = Qck\ComposerCodeBundler::new( $log, __DIR__ . "/../../srcBundle/Quick.php" );
        $codeBundler();
    }

}

Qck\App::new( "Bundle Quick App", BundleQuick::class )->setShowErrors( true )->run();

