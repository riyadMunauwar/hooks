<?php

declare(strict_types=1);

namespace Riyad\Hooks;

use Riyad\Hooks\Contracts\DispatcherInterface;
use Riyad\Hooks\Event;
use Riyad\Hooks\Exceptions\InvalidListenerException;
use Closure;

final class Dispatcher implements DispatcherInterface
{
    private ListenerCollection $listeners;

    public function __construct()
    {
        $this->listeners = new ListenerCollection();
    }

    public function addListener(string $eventName, callable $callback, int $priority = 10, bool $once = false): string
    {
        $id = bin2hex(random_bytes(8));
        $listener = new Listener($id, Closure::fromCallable($callback), $priority, $once);
        $this->listeners->add($eventName, $listener);
        return $id;
    }

    public function removeListener(string $eventName, string $listenerId): void
    {
        $this->listeners->remove($eventName, $listenerId);
    }

    public function dispatch(Event $event): void
    {
        foreach ($this->listeners->get($event->name()) as $listener) {
            if ($event->isPropagationStopped()) {
                break;
            }
            ($listener->callback())($event);

            if ($listener->once()) {
                $this->removeListener($event->name(), $listener->id());
            }
        }
    }

    /**
     * Convenience: one-time listener
     */
    public function once(string $eventName, callable $callback, int $priority = 10): string
    {
        return $this->addListener($eventName, $callback, $priority, true);
    }

    /**
     * Get all listeners for inspection
     * @return array<string,array<int,Listener>>
     */
    public function getListeners(string $eventName): array
    {
        return $this->listeners->get($eventName);
    }
}
