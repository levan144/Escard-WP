<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7f8baa1d68670426e71692b27ea83e17
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Codemanas\\InactiveLogout\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Codemanas\\InactiveLogout\\' => 
        array (
            0 => __DIR__ . '/../..' . '/core',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7f8baa1d68670426e71692b27ea83e17::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7f8baa1d68670426e71692b27ea83e17::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7f8baa1d68670426e71692b27ea83e17::$classMap;

        }, null, ClassLoader::class);
    }
}