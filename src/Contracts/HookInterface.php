<?php
declare(strict_types=1);

namespace Riyad\Hooks\Contracts;

use Riyad\Hooks\Dispatcher;

/**
 * Interface HookInterface
 *
 * Defines the contract for the Hook class — a WordPress-style
 * hook system supporting both actions and filters.
 */
interface HookInterface
{
    /* ============================================================
     *  FILTER METHODS
     * ========================================================== */

    /**
     * Add a filter hook.
     *
     * @param string   $tag
     * @param callable $callback
     * @param int      $priority
     *
     * @return string Listener ID
     */
    public function addFilter(string $tag, callable $callback, int $priority = 10): string;

    /**
     * Apply all filters attached to a hook.
     *
     * @param string $tag
     * @param mixed  $value
     * @param mixed  ...$args
     *
     * @return mixed
     */
    public function applyFilters(string $tag, mixed $value, mixed ...$args): mixed;

    /**
     * Remove a filter by its listener ID.
     */
    public function removeFilter(string $tag, string $listenerId): void;

    /**
     * Remove all filters for a given tag.
     */
    public function removeAllFilters(string $tag): void;

    /**
     * Check if a filter exists.
     */
    public function hasFilter(string $tag): bool;

    /* ============================================================
     *  ACTION METHODS
     * ========================================================== */

    /**
     * Add an action hook.
     *
     * @param string   $tag
     * @param callable $callback
     * @param int      $priority
     *
     * @return string Listener ID
     */
    public function addAction(string $tag, callable $callback, int $priority = 10): string;

    /**
     * Execute all actions attached to a hook.
     *
     * @param string $tag
     * @param mixed  ...$args
     */
    public function doAction(string $tag, mixed ...$args): void;

    /**
     * Remove an action by its listener ID.
     */
    public function removeAction(string $tag, string $listenerId): void;

    /**
     * Remove all actions for a given tag.
     */
    public function removeAllActions(string $tag): void;

    /**
     * Check if an action exists.
     */
    public function hasAction(string $tag): bool;

    /* ============================================================
     *  HELPERS MANAGEMENT
     * ========================================================== */

    /**
     * Enable global helper functions (functions.php).
     */
    public function enableHelpers(): void;

    /**
     * Disable global helper functions (prevent loading again).
     */
    public function disableHelpers(): void;

    /**
     * Check if helper functions are enabled.
     */
    public function helpersEnabled(): bool;

    /**
     * Get the underlying Dispatcher instance.
     */
    public function getDispatcher(): Dispatcher;
}