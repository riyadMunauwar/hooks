<?php
declare(strict_types=1);

use Riyad\Hooks\Dispatcher;
use Riyad\Hooks\Event;

/**
 * Get the global dispatcher instance.
 *
 * @return Dispatcher
 */
if (!function_exists('hooks')) {
    function hooks(): Dispatcher
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new Dispatcher();
        }
        return $instance;
    }
}

/* ---------------- Filters ---------------- */

/**
 * Add a callback to a filter hook.
 *
 * @param string   $tag
 * @param callable $callback
 * @param int      $priority
 *
 * @return string Listener ID
 */
if (!function_exists('add_filter')) {
    function add_filter(string $tag, callable $callback, int $priority = 10): string
    {
        return hooks()->addListener(
            "filter.$tag",
            function (Event $event) use ($callback) {
                $value = $event->get('value');
                $event->set('value', $callback($value, ...$event->get('args', [])));
            },
            $priority
        );
    }
}

/**
 * Apply all callbacks attached to a filter hook.
 *
 * @param string $tag
 * @param mixed  $value
 * @param mixed  ...$args
 *
 * @return mixed
 */
if (!function_exists('apply_filters')) {
    function apply_filters(string $tag, mixed $value, mixed ...$args): mixed
    {
        $event = new Event("filter.$tag", [
            'value' => $value,
            'args'  => $args
        ]);
        hooks()->dispatch($event);
        return $event->get('value');
    }
}

/**
 * Remove a specific callback from a filter hook.
 *
 * @param string $tag
 * @param string $listenerId
 */
if (!function_exists('remove_filter')) {
    function remove_filter(string $tag, string $listenerId): void
    {
        hooks()->removeListener("filter.$tag", $listenerId);
    }
}

/**
 * Remove all callbacks attached to a filter hook.
 *
 * @param string $tag
 */
if (!function_exists('remove_all_filters')) {
    function remove_all_filters(string $tag): void
    {
        foreach (hooks()->getListeners("filter.$tag") as $listener) {
            hooks()->removeListener("filter.$tag", $listener->id());
        }
    }
}

/**
 * Check if a filter hook has any callbacks.
 *
 * @param string $tag
 *
 * @return bool
 */
if (!function_exists('has_filter')) {
    function has_filter(string $tag): bool
    {
        return count(hooks()->getListeners("filter.$tag")) > 0;
    }
}

/* ---------------- Actions ---------------- */

/**
 * Add a callback to an action hook.
 *
 * @param string   $tag
 * @param callable $callback
 * @param int      $priority
 *
 * @return string Listener ID
 */
if (!function_exists('add_action')) {
    function add_action(string $tag, callable $callback, int $priority = 10): string
    {
        return hooks()->addListener(
            "action.$tag",
            function (Event $event) use ($callback) {
                $callback(...$event->get('args', []));
            },
            $priority
        );
    }
}

/**
 * Execute all callbacks attached to an action hook.
 *
 * @param string $tag
 * @param mixed  ...$args
 */
if (!function_exists('do_action')) {
    function do_action(string $tag, mixed ...$args): void
    {
        $event = new Event("action.$tag", ['args' => $args]);
        hooks()->dispatch($event);
    }
}

/**
 * Remove a specific callback from an action hook.
 *
 * @param string $tag
 * @param string $listenerId
 */
if (!function_exists('remove_action')) {
    function remove_action(string $tag, string $listenerId): void
    {
        hooks()->removeListener("action.$tag", $listenerId);
    }
}

/**
 * Remove all callbacks attached to an action hook.
 *
 * @param string $tag
 */
if (!function_exists('remove_all_actions')) {
    function remove_all_actions(string $tag): void
    {
        foreach (hooks()->getListeners("action.$tag") as $listener) {
            hooks()->removeListener("action.$tag", $listener->id());
        }
    }
}

/**
 * Check if an action hook has any callbacks.
 *
 * @param string $tag
 *
 * @return bool
 */
if (!function_exists('has_action')) {
    function has_action(string $tag): bool
    {
        return count(hooks()->getListeners("action.$tag")) > 0;
    }
}
