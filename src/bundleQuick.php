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
        $codeBundler = new Qck\CodeBundler(__DIR__, __DIR__ . "/../Quick.php");
        $codeBundler->addExcludedFile(__FILE__, __DIR__ . "/public_html/index.php");
        $codeBundler();
        print "finished";
    }

}

Qck\App::create("Bundle Quick App", BundleQuick::class, true)->run();

