<?php

declare(strict_types=1);

namespace Riyad\Hooks;

final class ListenerCollection
{
    /** @var array<string,array<int,Listener>> */
    private array $listeners = [];

    public function add(string $eventName, Listener $listener): void
    {
        $this->listeners[$eventName] ??= [];
        $this->listeners[$eventName][] = $listener;
        // sort by priority descending
        usort($this->listeners[$eventName], fn(Listener $a, Listener $b) => $b->priority() <=> $a->priority());
    }

    public function remove(string $eventName, string $listenerId): void
    {
        if (!isset($this->listeners[$eventName])) {
            return;
        }
        $this->listeners[$eventName] = array_filter(
            $this->listeners[$eventName],
            fn(Listener $l) => $l->id() !== $listenerId
        );
    }

    /**
     * @return Listener[]
     */
    public function get(string $eventName): array
    {
        return $this->listeners[$eventName] ?? [];
    }

    /**
     * @return Listener[]
     */
    public function all(): array
    {
        return $this->listeners;
    }
}
