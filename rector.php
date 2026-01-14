<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

/**
 * Rector configuration for Akeneo PIM project migration
 * 
 * This configuration is used for migrating PHP 8.1 → 8.4 → 8.5 and Symfony 5.4 → 8.0
 * Apply rules one by one using individual sets via command line:
 * 
 * PHP 8.2: vendor/bin/rector process --set=PHP_82 --dry-run
 * PHP 8.3: vendor/bin/rector process --set=PHP_83 --dry-run
 * PHP 8.4: vendor/bin/rector process --set=PHP_84 --dry-run
 * PHP 8.5: vendor/bin/rector process --set=PHP_85 --dry-run
 * 
 * Symfony 6.0: vendor/bin/rector process --set=SYMFONY_60 --dry-run
 * Symfony 6.4: vendor/bin/rector process --set=SYMFONY_64 --dry-run
 * Symfony 7.0: vendor/bin/rector process --set=SYMFONY_70 --dry-run
 * Symfony 8.0: vendor/bin/rector process --set=SYMFONY_80 --dry-run
 */

return static function (RectorConfig $rectorConfig): void {
    // Paths to analyze
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/upgrades',
        __DIR__ . '/components',
    ]);

    // Paths to skip
    $rectorConfig->skip([
        // Ignore existing migration files
        __DIR__ . '/std-build/migration',
        // Ignore vendors
        __DIR__ . '/vendor',
        // Ignore generated files
        __DIR__ . '/var',
        // Ignore frontend files
        __DIR__ . '/front-packages',
        __DIR__ . '/frontend',
    ]);

    // Phase 2.2: PHP 8.2 → 8.3 migration
    // NOTE: Rector 0.15.0 does not support PHP_83 or PHP_84 sets
    // The codebase is already compatible with PHP 8.2 (no changes needed in Phase 2.1)
    // For PHP 8.3 and 8.4, we verify compatibility manually by running tests
    // No Rector rules applied - codebase verified compatible via testing
    
    // Note: Rector 0.15.0 only supports PHP_80, PHP_81, PHP_82
    // To use PHP_83/PHP_84 sets, Rector would need to be upgraded to 1.x or 2.x
};
