<?php
/**
 * PSR-4: Autoloader implementation
 * 
 * @param string $class
 * @return void
 */
spl_autoload_register(function ($class) {
    $prefix = 'GBAF\\';

    $baseDir = __DIR__ . '/src/';

    $length = strlen($prefix);
    if (strncmp($prefix, $class, $length) !== 0) {
        return;
    }

    $relativeClass = substr($class, $length);

    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
