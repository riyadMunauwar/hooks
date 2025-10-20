<?php

declare(strict_types=1);

namespace Riyad\Hooks\Contracts;

interface EventInterface
{
    public function name(): string;
    public function get(string $key, mixed $default = null): mixed;
    public function set(string $key, mixed $value): void;
    public function stopPropagation(): void;
    public function isPropagationStopped(): bool;
}