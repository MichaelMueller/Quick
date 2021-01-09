<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Deploy implements Qck\Interfaces\AppFunction
{

    public function run( \Qck\Interfaces\App $app ): void
    {
        $interfaceFiles = glob( __DIR__ . "/Interfaces/*.php", GLOB_BRACE );
        print_r( "found the following interface files" . print_r( $interfaceFiles, true ) );
        $exception      = $app->createException();

        $cmd           = $app->createCmd( "git" )->arg( "add" )->arg( "-A" );
        $exception->assert( $cmd->run()->successful(), "failed to run git add" );
        $dateTime      = (new DateTime() )->format( "Ymd_hhmmss" );
        $commitMessage = "Checkpoint commit " . $dateTime;
        $cmd           = $app->createCmd( "git" )->arg( "commit" )->arg( "-m" )->escapeArg( $commitMessage );
        $exception->assert( $cmd->run()->successful(), "failed to run git commit" );
    }

}

\Qck\App::createConfig( "Deploy", Deploy::class )->setShowErrors( true )->runApp();

