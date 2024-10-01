<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc9161c928ba081a1068e95a29b9d5e74
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Mollie\\Api\\' => 11,
        ),
        'C' => 
        array (
            'Composer\\CaBundle\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Mollie\\Api\\' => 
        array (
            0 => __DIR__ . '/..' . '/mollie/mollie-api-php/src',
        ),
        'Composer\\CaBundle\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/ca-bundle/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc9161c928ba081a1068e95a29b9d5e74::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc9161c928ba081a1068e95a29b9d5e74::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc9161c928ba081a1068e95a29b9d5e74::$classMap;

        }, null, ClassLoader::class);
    }
}
