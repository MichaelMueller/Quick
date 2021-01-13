<?php

$vendorDir = __DIR__ . '/../../vendor';
if ( !is_dir( $vendorDir ) )
    $vendorDir = __DIR__ . '/../../..';

require_once $vendorDir . "/autoload.php";

/**
 * 
 * @author muellerm
 */
class BundleQuick implements \Qck\AppFunction
{

    public function run( \Qck\App $app )
    {
        $log         = \Qck\Log::new( $app->request() )->addTopic( Qck\LogMessage::ALL );
        global $vendorDir;
        $log->info( "vendor directory is in '%s'" )->addArg( $vendorDir )->send();
        $projectDir  = realpath( dirname( $vendorDir ) );
        $log->info( "projectDir directory is in '%s'" )->addArg( $projectDir )->send();
        $outputFile  = $projectDir . "/srcBundle/" . pathinfo( $projectDir, PATHINFO_BASENAME ) . ".php";
        $log->info( "outputFile is at '%s'" )->addArg( $outputFile )->send();
        $codeBundler = Qck\ComposerCodeBundler::new( $log, $outputFile );
        $codeBundler();
    }

}

Qck\App::new( "Bundle Quick App", BundleQuick::class )->setShowErrors( true )->run();

