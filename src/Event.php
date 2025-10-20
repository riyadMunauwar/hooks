<?php

declare(strict_types=1);

namespace Riyad\Hooks;

use Riyad\Hooks\Contracts\EventInterface;

final class Event implements EventInterface
{
    private string $name;
    private array $payload = [];
    private bool $propagationStopped = false;

    public function __construct(string $name, array $payload = [])
    {
        $this->name = $name;
        $this->payload = $payload;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->payload[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->payload[$key] = $value;
    }

    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }

    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }
}