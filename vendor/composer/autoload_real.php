<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit6127f358fd9f0f50c2d336dadc9abaea
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit6127f358fd9f0f50c2d336dadc9abaea', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit6127f358fd9f0f50c2d336dadc9abaea', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit6127f358fd9f0f50c2d336dadc9abaea::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
