<?php
function loadClass($class)
{
    $packages = [
        "worker",
        "utils"
    ];

    foreach ($packages as $package)
    {
        if (file_exists(dirname(__FILE__).DS.$package.DS.$class.".php"))
        {
            require_once $package.DS.$class.".php";
            return;
        }
    }
    echo "no class or file named ".$class."\n";
}

spl_autoload_register("loadClass");