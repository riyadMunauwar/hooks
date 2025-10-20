<?php

declare(strict_types=1);

namespace Riyad\Hooks;

use Closure;
use InvalidArgumentException;

final class Listener
{
    private string $id;
    private Closure $callback;
    private int $priority;
    private bool $once;

    public function __construct(string $id, Closure $callback, int $priority = 10, bool $once = false)
    {
        $this->id = $id;
        $this->callback = $callback;
        $this->priority = $priority;
        $this->once = $once;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function callback(): Closure
    {
        return $this->callback;
    }

    public function priority(): int
    {
        return $this->priority;
    }

    public function once(): bool
    {
        return $this->once;
    }
}
