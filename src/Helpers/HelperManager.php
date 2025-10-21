<?php

namespace Riyad\Hooks\Helpers;

/**
 * Helper Manager
 *
 * Provides a clean, configurable way to enable or disable
 * the global helper functions like `add_action()`, `do_action()`, etc.
 *
 * Example usage:
 *
 *   use Riyad\Hooks\Helpers\HelpersManager;
 *   HelpersManager::enable();   // Load helpers globally
 *   HelpersManager::disable();  // Disable helpers (if reloaded)
 */
final class HelperManager
{
    /**
     * @var bool Whether helpers are currently enabled.
     */
    private static bool $enabled = false;

    /**
     * Enable helper functions globally.
     */
    public static function enable(): void
    {
        if (!self::$enabled) {
            require_once __DIR__ . '/functions.php';
            self::$enabled = true;
        }
    }

    /**
     * Disable helpers by unsetting them from global scope (if necessary).
     * Note: PHP cannot "undefine" functions, but this flag prevents redefinition.
     */
    public static function disable(): void
    {
        self::$enabled = false;
    }

    /**
     * Check if helpers are currently enabled.
     */
    public static function isEnabled(): bool
    {
        return self::$enabled;
    }
}