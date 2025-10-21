<?php

declare(strict_types=1);

namespace Riyad\Hooks;

use Riyad\Hooks\Contracts\HookInterface;
use Riyad\Hooks\Event;
use Riyad\Hooks\Dispatcher;

/**
 * Class HookManager
 *
 * A singleton, object-oriented wrapper for WordPress-style
 * actions and filters built on top of the Dispatcher.
 *
 * Example:
 *  $hooks = HookManager::instance();
 *  $hooks->addAction('init', fn() => echo "Initialized");
 *  $hooks->doAction('init');
 */
class Hook implements HookInterface
{
    /**
     * The single instance of the HookManager.
     */
    private static ?Hook $instance = null;

    /**
     * Dispatcher instance.
     */
    private Dispatcher $dispatcher;

    /**
     * Whether helper functions are enabled.
     */
    private bool $helpersEnabled = false;

    /**
     * Private constructor for singleton.
     */
    private function __construct()
    {
        $this->dispatcher = new Dispatcher();
    }

    /**
     * Get the singleton instance.
     *
     * @return Hook
     */
    public static function instance(): Hook
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /* ========================================================
     *  FILTER METHODS
     * ====================================================== */

    /**
     * Add a filter hook.
     */
    public function addFilter(string $tag, callable $callback, int $priority = 10): string
    {
        return $this->dispatcher->addListener(
            "filter.$tag",
            function (Event $event) use ($callback) {
                $value = $event->get('value');
                $event->set('value', $callback($value, ...$event->get('args', [])));
            },
            $priority
        );
    }

    /**
     * Apply all filters attached to a hook.
     */
    public function applyFilters(string $tag, mixed $value, mixed ...$args): mixed
    {
        $event = new Event("filter.$tag", [
            'value' => $value,
            'args'  => $args
        ]);

        $this->dispatcher->dispatch($event);

        return $event->get('value');
    }

    /**
     * Remove a filter listener by ID.
     */
    public function removeFilter(string $tag, string $listenerId): void
    {
        $this->dispatcher->removeListener("filter.$tag", $listenerId);
    }

    /**
     * Remove all filters for a given tag.
     */
    public function removeAllFilters(string $tag): void
    {
        foreach ($this->dispatcher->getListeners("filter.$tag") as $listener) {
            $this->dispatcher->removeListener("filter.$tag", $listener->id());
        }
    }

    /**
     * Check if a filter hook has listeners.
     */
    public function hasFilter(string $tag): bool
    {
        return count($this->dispatcher->getListeners("filter.$tag")) > 0;
    }

    /* ========================================================
     *  ACTION METHODS
     * ====================================================== */

    /**
     * Add an action hook.
     */
    public function addAction(string $tag, callable $callback, int $priority = 10): string
    {
        return $this->dispatcher->addListener(
            "action.$tag",
            function (Event $event) use ($callback) {
                $callback(...$event->get('args', []));
            },
            $priority
        );
    }

    /**
     * Execute all actions attached to a hook.
     */
    public function doAction(string $tag, mixed ...$args): void
    {
        $event = new Event("action.$tag", ['args' => $args]);
        $this->dispatcher->dispatch($event);
    }

    /**
     * Remove an action listener by ID.
     */
    public function removeAction(string $tag, string $listenerId): void
    {
        $this->dispatcher->removeListener("action.$tag", $listenerId);
    }

    /**
     * Remove all action listeners for a tag.
     */
    public function removeAllActions(string $tag): void
    {
        foreach ($this->dispatcher->getListeners("action.$tag") as $listener) {
            $this->dispatcher->removeListener("action.$tag", $listener->id());
        }
    }

    /**
     * Check if an action hook has listeners.
     */
    public function hasAction(string $tag): bool
    {
        return count($this->dispatcher->getListeners("action.$tag")) > 0;
    }

    /**
     * Enable functional helper functions (functions.php)
     * for global usage.
     *
     * @return void
     */
    public function enableHelpers(): void
    {
        if (!$this->helpersEnabled) {
            $helpersPath = __DIR__ . '/Helpers/functions.php';
            if (file_exists($helpersPath)) {
                require_once $helpersPath;
                $this->helpersEnabled = true;
            }
        }
    }

    /**
     * Disable helpers — prevents loading functional style.
     *
     * Note: Once loaded, PHP cannot "unload" functions,
     * but this flag ensures they won't load twice or in tests.
     *
     * @return void
     */
    public function disableHelpers(): void
    {
        $this->helpersEnabled = false;
    }

    /**
     * Check if helper functions are enabled.
     */
    public function helpersEnabled(): bool
    {
        return $this->helpersEnabled;
    }


    /**
     * Get the underlying Dispatcher instance.
     */
    public function getDispatcher(): Dispatcher
    {
        return $this->dispatcher;
    }
}
