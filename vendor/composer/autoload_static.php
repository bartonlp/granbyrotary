<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5131fbdaac644cbb83f0319fe96e8fee
{
    public static $classMap = array (
        'GranbyRotary' => __DIR__ . '/../..' . '/includes/GranbyRotary.class.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit5131fbdaac644cbb83f0319fe96e8fee::$classMap;

        }, null, ClassLoader::class);
    }
}
