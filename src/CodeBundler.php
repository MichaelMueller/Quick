<?php

namespace Qck;

/**
 * Router class maps arguments to specific functions
 * 
 * @author muellerm
 */
class CodeBundler
{

    function __construct($directory, $outputFile)
    {
        $this->addDirectory($directory);
        $this->outputFile = $outputFile;
    }

    function __invoke()
    {
        $code = "";
        foreach ($this->directories as $directory)
        {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $filename)
            {
                $filename = realpath($filename);
                if (in_array($filename, $this->excludedFiles))
                    continue;

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
        }

        $targetFile = $this->outputFile;
        //print sprintf("dumping code to %s\n", $targetFile);
        file_put_contents($targetFile, "<?php" . $code);
    }

    /**
     * 
     * @param string $directory
     * @return $this
     */
    function addDirectory($directory)
    {
        $this->directories[] = $directory;
        return $this;
    }

    /**
     * 
     * @param string $directory
     * @return $this
     */
    function addExcludedFile($excludedFile)
    {
        $this->excludedFiles[] = $excludedFile;
        return $this;
    }

    /**
     * 
     * @param string $directory
     * @return $this
     */
    function addPhpExtension($ext)
    {
        $this->extensions[] = $ext;
        return $this;
    }

    /**
     *
     * @var string[]
     */
    protected $directories = [];

    /**
     *
     * @var string
     */
    protected $outputFile;

    /**
     *
     * @var string[]
     */
    protected $extensions = ["php"];

    /**
     *
     * @var string[]
     */
    protected $excludedFiles = [];

}
