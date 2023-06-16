<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit728f96e67824ff0c88d9b31fcd5c7681
{
    public static $files = array (
        'decc78cc4436b1292c6c0d151b19445c' => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib/bootstrap.php',
        '2cffec82183ee1cea088009cef9a6fc3' => __DIR__ . '/..' . '/ezyang/htmlpurifier/library/HTMLPurifier.composer.php',
    );

    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpseclib\\' => 10,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
        'L' => 
        array (
            'Laizerox\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpseclib\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'Laizerox\\' => 
        array (
            0 => __DIR__ . '/..' . '/laizerox/php-wowemu-auth/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PayPal' => 
            array (
                0 => __DIR__ . '/..' . '/paypal/rest-api-sdk-php/lib',
            ),
        ),
        'H' => 
        array (
            'HTMLPurifier' => 
            array (
                0 => __DIR__ . '/..' . '/ezyang/htmlpurifier/library',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit728f96e67824ff0c88d9b31fcd5c7681::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit728f96e67824ff0c88d9b31fcd5c7681::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit728f96e67824ff0c88d9b31fcd5c7681::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit728f96e67824ff0c88d9b31fcd5c7681::$classMap;

        }, null, ClassLoader::class);
    }
}
