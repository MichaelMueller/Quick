<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Deploy implements Qck\Interfaces\AppFunction
{

    public function run( \Qck\Interfaces\App $app )
    {        
        $exception      = $app->createException();
        $cmd           = $app->createCmd( "git" )->arg( "add" )->arg( "-A" );
        $exception->assert( $cmd->run()->successful(), "failed to run git add" );
        $dateTime      = (new DateTime() )->format( "Ymd_hhmmss" );
        $commitMessage = "Checkpoint commit " . $dateTime;
        $cmd           = $app->createCmd( "git" )->arg( "commit" )->arg( "-m" )->escapeArg( $commitMessage );
        $exception->assert( $cmd->run()->successful(), "failed to run git commit" );
        
        $interfaceFiles = glob( __DIR__ . "/Interfaces/*.php", GLOB_BRACE );
        print_r( "found the following interface files" . print_r( $interfaceFiles, true ) );
        $newContents = PHP_EOL.PHP_EOL."namespace \Qck\Interfaces";
        foreach ( $interfaceFiles as $interfaceFile )
            $newContents .= PHP_EOL.file_get_contents($interfaceFile).PHP_EOL;
        
        file_put_contents("App.php", $newContents, FILE_APPEND);
        
    }

}

\Qck\App::createConfig( "Deploy", Deploy::class )->setShowErrors( true )->runApp();

