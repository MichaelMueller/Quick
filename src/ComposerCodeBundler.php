<?php

namespace Qck;

/**
 * Class for collecting all Psr4 compatible classes and bundle it into one file.
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class ComposerCodeBundler
{

    static function new( Log $log, string $outputFile ): ComposerCodeBundler
    {
        return new ComposerCodeBundler( $log, $outputFile );
    }

    function __construct( Log $log, string $outputFile )
    {
        $this->log        = $log;
        $this->outputFile = $outputFile;
    }

    function __invoke()
    {
        $this->log->info( "Searching composer autoloader" )->send();
        $code                = "";
        $res                 = get_declared_classes();
        $autoloaderClassName = '';
        foreach ( $res as $className )
        {
            if ( strpos( $className, 'ComposerAutoloaderInit' ) === 0 )
            {
                $autoloaderClassName = $className; // ComposerAutoloaderInit323a579f2019d15e328dd7fec58d8284 for me
                break;
            }
        }

        $this->log->info( "Searching composer autoloader" )->send();
        /* @var $classLoader \Composer\Autoload\ClassLoader */
        $classLoader = $autoloaderClassName::getLoader();
        $filenames   = [];
        foreach ( $classLoader->getPrefixesPsr4() as $prefix => $paths )
        {
            foreach ( $paths as $path )
            {
                $handle = opendir( $path );

                while ( false !== ($entry = readdir( $handle )) )
                {
                    $filename = realpath( $path . "/" . $entry );
                    //print_r( $filename );
                    if ( is_file( $filename ) == false || in_array( $filename, $this->excludedFiles ) )
                    {
                        $this->log->info( "Skipping %s. No file or excluded." )->addArg( $filename )->send();
                        continue;
                    }
                    $ext = pathinfo( $filename, PATHINFO_EXTENSION );
                    if ( !in_array( $ext, $this->extensions ) )
                    {
                        $this->log->info( "Skipping %s. Extension '%s' not found in extensions '%s'." )
                                ->addArg( $filename )->addArg( $ext )->addArg( implode( ", ", $this->extensions ) )->send();
                        continue;
                    }
                    $className = pathinfo( $filename, PATHINFO_FILENAME );
                    $fqcn      = "\\" . $prefix . $className;
                    if ( !class_exists( $fqcn, true ) && !interface_exists( $fqcn, true ) )
                    {
                        $this->log->warn( "Skipping %s. A corresponding PSR-4 compliant class '%s' was not found." )
                                ->addArg( $filename )->addArg( $fqcn )->send();
                        continue;
                    }

                    $filenames[] = $filename;
                }

                closedir( $handle );
            }
        }

        $this->log->info( "Generating code" )->send();
        foreach ( $filenames as $filename )
        {
            $this->log->info( "Processing file %s" )->addArg( $filename )->send();
            //print sprintf("processing %s\n", $filename);
            $ext = pathinfo( $filename, PATHINFO_EXTENSION );
            if ( in_array( $ext, $this->extensions ) )
            {
                //print sprintf("extracting from %s\n", $filename);
                $contents = file_get_contents( $filename );
                $startDef = "<?php";
                $start    = strpos( $contents, $startDef );
                if ( $start === false )
                    continue;

                $contents = trim( mb_substr( $contents, $start + mb_strlen( $startDef ) ) );
                if ( mb_strlen( $contents ) > 2 && mb_substr( $contents, -2 ) == "?>" )
                    $contents = mb_substr( $contents, 0, mb_strlen( $contents ) - 2 );

                $code .= PHP_EOL . PHP_EOL . $contents;
            }
        }

        $targetFile = $this->outputFile;
        //print sprintf("dumping code to %s\n", $targetFile);
        $this->log->info( "Dumping code to %s" )->addArg( $targetFile )->send();
        file_put_contents( $targetFile, "<?php" . $code );
    }

    /**
     * 
     * @param string $excludedFile
     * @return $this
     */
    function addExcludedFile( $excludedFile )
    {
        $this->excludedFiles[] = $excludedFile;
        return $this;
    }

    /**
     * 
     * @param string $ext
     * @return $this
     */
    function addPhpExtension( $ext )
    {
        $this->extensions[] = $ext;
        return $this;
    }

    /**
     *
     * @var Log
     */
    protected $log;

    /**
     *
     * @var string
     */
    protected $outputFile;

    /**
     *
     * @var string[]
     */
    protected $extensions = [ "php" ];

    /**
     *
     * @var string[]
     */
    protected $excludedFiles = [];

}
