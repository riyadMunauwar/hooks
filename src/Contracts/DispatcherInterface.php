<?php

declare(strict_types=1);

namespace Riyad\Hooks\Contracts;

use Riyad\Hooks\Event;

interface DispatcherInterface
{
    public function addListener(string $eventName, callable $callback, int $priority = 10, bool $once = false): string;
    public function removeListener(string $eventName, string $listenerId): void;
    public function dispatch(Event $event): void;
}
