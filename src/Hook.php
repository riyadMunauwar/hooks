<?php

declare(strict_types=1);

namespace Riyad\Hooks;

use Riyad\Hooks\Contracts\HookInterface;
use Riyad\Hooks\Event;
use Riyad\Hooks\Dispatcher;

/**
 * Class Hook
 *
 * A flexible hook manager that can act as:
 *  - Singleton (shared instance across app)
 *  - Normal object (many instances)
 *
 * Example:
 *  $hook = Hook::instance(); // Singleton mode
 *  $hook2 = Hook::make();    // Many-object mode
 */
class Hook implements HookInterface
{
    /**
     * The single instance of Hook.
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
     * Whether singleton mode is globally enabled.
     */
    private static bool $singletonMode = true;

    /**
     * Private constructor for controlled instantiation.
     */
    private function __construct()
    {
        $this->dispatcher = new Dispatcher();
    }

    /**
     * Enable singleton mode globally.
     */
    public static function enableSingleton(): void
    {
        self::$singletonMode = true;
    }

    /**
     * Disable singleton mode globally.
     */
    public static function disableSingleton(): void
    {
        self::$singletonMode = false;
        self::$instance = null;
    }

    /**
     * Get a Hook instance based on current mode.
     * - If singleton mode: returns the same shared instance.
     * - If not: returns a new instance every time.
     */
    public static function instance(): Hook
    {
        if (self::$singletonMode) {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        // Many-object mode
        return new self();
    }

    /**
     * Always returns a new instance (ignores singleton mode).
     */
    public static function make(): Hook
    {
        return new self();
    }

    /* ========================================================
     *  FILTER METHODS
     * ====================================================== */

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

    public function applyFilters(string $tag, mixed $value, mixed ...$args): mixed
    {
        $event = new Event("filter.$tag", [
            'value' => $value,
            'args'  => $args
        ]);

        $this->dispatcher->dispatch($event);

        return $event->get('value');
    }

    public function removeFilter(string $tag, string $listenerId): void
    {
        $this->dispatcher->removeListener("filter.$tag", $listenerId);
    }

    public function removeAllFilters(string $tag): void
    {
        foreach ($this->dispatcher->getListeners("filter.$tag") as $listener) {
            $this->dispatcher->removeListener("filter.$tag", $listener->id());
        }
    }

    public function hasFilter(string $tag): bool
    {
        return count($this->dispatcher->getListeners("filter.$tag")) > 0;
    }

    /* ========================================================
     *  ACTION METHODS
     * ====================================================== */

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

    public function doAction(string $tag, mixed ...$args): void
    {
        $event = new Event("action.$tag", ['args' => $args]);
        $this->dispatcher->dispatch($event);
    }

    public function removeAction(string $tag, string $listenerId): void
    {
        $this->dispatcher->removeListener("action.$tag", $listenerId);
    }

    public function removeAllActions(string $tag): void
    {
        foreach ($this->dispatcher->getListeners("action.$tag") as $listener) {
            $this->dispatcher->removeListener("action.$tag", $listener->id());
        }
    }

    public function hasAction(string $tag): bool
    {
        return count($this->dispatcher->getListeners("action.$tag")) > 0;
    }

    /* ========================================================
     *  HELPERS
     * ====================================================== */

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

    public function disableHelpers(): void
    {
        $this->helpersEnabled = false;
    }

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
