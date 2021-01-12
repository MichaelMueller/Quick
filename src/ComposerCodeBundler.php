<?php

namespace Qck;

/**
 * Router class maps arguments to specific functions
 * 
 * @author muellerm
 */
class ComposerCodeBundler
{

    function __construct( Log $log, string $outputFile )
    {
        $this->log = $log;
        $this->outputFile = $outputFile;
    }

        function __invoke()
    {
        $this->log->info($text);
        $code = "";
        $res = get_declared_classes();
        $autoloaderClassName = '';
        foreach ( $res as $className )
        {
            if ( strpos( $className, 'ComposerAutoloaderInit' ) === 0 )
            {
                $autoloaderClassName = $className; // ComposerAutoloaderInit323a579f2019d15e328dd7fec58d8284 for me
                break;
            }
        }
        /* @var $classLoader \Composer\Autoload\ClassLoader */
        $classLoader = $autoloaderClassName::getLoader();
        $filenames = [];
        foreach ( $classLoader->getPrefixesPsr4() as $prefix => $paths )
        {
            foreach ( $paths as $path )
            {
                $handle = opendir( $path );

                while ( false !== ($entry = readdir( $handle )) )
                {
                    $filename = realpath( $path."/".$entry );
                    //print_r( $filename );
                    if ( is_file( $filename ) == false || in_array( $filename, $this->excludedFiles ) )
                        continue;
                    $filenames[] = $filename;
                }

                closedir( $handle );
            }
        }

        foreach($filenames as $filename)
        {            
            //print sprintf("processing %s\n", $filename);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (in_array($ext, $this->extensions))
            {
                //print sprintf("extracting from %s\n", $filename);
                $contents = file_get_contents($filename);
                $startDef = "<?php";
                $start = strpos($contents, $startDef);
                if ($start === false)
                    continue;

                $contents = trim(mb_substr($contents, $start + mb_strlen($startDef)));
                if (mb_strlen($contents) > 2 && mb_substr($contents, -2) == "?>")
                    $contents = mb_substr($contents, 0, mb_strlen($contents) - 2);

                $code .= PHP_EOL . PHP_EOL. $contents;
            }
        }

        $targetFile = $this->outputFile;
        //print sprintf("dumping code to %s\n", $targetFile);
        file_put_contents( $targetFile, "<?php" . $code );
    }

    /**
     * 
     * @param string $path
     * @return $this
     */
    function addPath( $path )
    {
        $this->paths[] = $path;
        return $this;
    }

    /**
     * 
     * @param string $path
     * @return $this
     */
    function addExcludedFile( $excludedFile )
    {
        $this->excludedFiles[] = $excludedFile;
        return $this;
    }

    /**
     * 
     * @param string $path
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
