<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * 
 * @author muellerm
 */
class BundleQuick implements \Qck\AppFunction
{

    public function run(\Qck\App $app)
    {
        $code = "";
        $path = __DIR__;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $filename)
        {
            if (realpath($filename) == realpath(__FILE__))
                continue;
            print sprintf("processing %s\n", $filename);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if ($ext == "php")
            {
                print sprintf("extracting from %s\n", $filename);
                $contents = file_get_contents($filename);
                $namespaceQckDef = "namespace Qck;";
                $namespaceQck = strpos($contents, $namespaceQckDef);
                if ($namespaceQck === false)
                    continue;
                $classClose = strrpos($contents, "}");
                $contents = mb_substr($contents, $namespaceQck + mb_strlen($namespaceQckDef), $classClose);
                $code .= $contents;
            }
        }

        $targetFile = __DIR__ . "/../Quick.php";
        print sprintf("dumping code to %s\n", $targetFile);
        file_put_contents($targetFile, "<?php" . $code);
    }

}

Qck\App::create("Bundle Quick App", BundleQuick::class, true)->run();

