<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Deploy implements Qck\Interfaces\AppFunction
{

    public function run( \Qck\Interfaces\App $app )
    {
        $exception     = $app->createException();
        $cmd           = $app->createCmd( "git" )->arg( "add" )->arg( "-A" );
        $exception->assert( $cmd->run()->successful(), "failed to run git add" );
        $dateTime      = (new DateTime() )->format( "Ymd_hhmmss" );
        $commitMessage = "Checkpoint commit " . $dateTime;
        $cmd           = $app->createCmd( "git" )->arg( "commit" )->arg( "-m" )->escapeArg( $commitMessage );
        $exception->assert( $cmd->run()->successful(), "failed to run git commit" );

        $interfaceFiles       = glob( __DIR__ . "/Interfaces/*.php", GLOB_BRACE );
        print_r( "found the following interface files" . print_r( $interfaceFiles, true ) );
        $namespaceDeclaration = "namespace \\Qck\\Interfaces;";
        $newContents          = PHP_EOL . "if(interface_exists()==false)" . PHP_EOL;
        $newContents          .= "{" . PHP_EOL;
        $newContents          .= $namespaceDeclaration . PHP_EOL;
        foreach ( $interfaceFiles as $interfaceFile )
        {
            $fileContents = file_get_contents( $interfaceFile );
            $fileContents = str_replace( "<?php", "", $fileContents );
            $fileContents = str_replace( $namespaceDeclaration, "", $fileContents );
            $fileContents = trim( $fileContents );
            $newContents  .= $fileContents . PHP_EOL;
        }
        $newContents .= "}" . PHP_EOL;

        $appFile         = __DIR__ . "/App.php";
        $appFileContents = file_get_contents( $appFile );
        $find            = strpos( $appFileContents, $namespaceDeclaration );
        if ( $find !== false )
            $classesPart     = substr( $appFileContents, 0, $find );
        else 
            $classesPart = $appFileContents; 
        file_put_contents( $appFile, $classesPart . $newContents );
    }

}

\Qck\App::createConfig( "Deploy", Deploy::class )->setShowErrors( true )->runApp();

