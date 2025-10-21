```markdown
# Riyad Hooks - Design Documentation

This document explains the **architecture and design principles** of the Riyad Hooks system.

---

## 1. Overview

Riyad Hooks is a **modular event-driven system** inspired by WordPress hooks, rebuilt with modern PHP design patterns.  
It provides a clean, PSR-4-compatible, object-oriented architecture for **actions** and **filters**.

---

## 2. Architecture Overview

````

riyad-hooks/
├── src/
│   ├── Contracts/
│   │   ├── EventInterface.php
│   │   └── DispatcherInterface.php
│   ├── Event.php
│   ├── Listener.php
│   ├── ListenerCollection.php
│   ├── Dispatcher.php
│   ├── Exceptions/
│   │   ├── InvalidListenerException.php
│   │   └── EventDispatchException.php
│   └── Utils/
│       └── WildcardMatcher.php

````

---

## 3. Core Components

### 🧩 3.1 `Dispatcher`

**Namespace:** `Riyad\Hooks`

The central component of the system.  
Responsible for:
- Registering listeners (actions/filters)
- Managing priorities
- Executing them synchronously
- Supporting wildcard event names

Implements `DispatcherInterface`.

**Key methods:**
| Method | Description |
|--------|-------------|
| `addListener(string $hook, callable $callback, int $priority = 10)` | Register a new listener |
| `removeListener(string $hook, string $id)` | Remove a specific listener |
| `removeAllListeners(string $hook)` | Remove all listeners for a hook |
| `doAction(string $hook, ...$args)` | Execute an action hook |
| `applyFilters(string $hook, $value, ...$args)` | Execute a filter hook and return the modified value |

---

### 🧩 3.2 `Event`

Encapsulates event data (name, arguments, and propagation).  
Used internally to carry information across the dispatcher.

Implements `EventInterface`.

---

### 🧩 3.3 `Listener`

Represents a single listener.  
Holds:
- The callback function
- Its priority
- A unique identifier

---

### 🧩 3.4 `ListenerCollection`

Stores multiple `Listener` instances for a single hook.  
Supports sorting listeners by priority and iterating through them during execution.

---

### 🧩 3.5 `WildcardMatcher`

Utility for matching hook names like `user.*` or `system.db.*`.

This allows:
```php
add_action('user.*', $callback);
do_action('user.register');
````

---

### 🧩 3.6 `Contracts`

Defines interfaces for `Event` and `Dispatcher`, enabling easy extension or replacement of the core behavior.

* `DispatcherInterface` — defines all dispatcher methods
* `EventInterface` — defines event structure and propagation controls

---

### 🧩 3.7 `Exceptions`

* **InvalidListenerException** — thrown when a listener is invalid or non-callable
* **EventDispatchException** — thrown when execution fails during dispatch

---

## 4. Design Philosophy

| Principle             | Description                                           |
| --------------------- | ----------------------------------------------------- |
| **Familiar API**      | Matches WordPress-style `add_action` and `add_filter` |
| **Modern OOP Design** | Clean separation of responsibilities                  |
| **Extensible**        | Interfaces and contracts allow custom implementations |
| **Predictable**       | Synchronous, deterministic execution order            |
| **Lightweight**       | No external dependencies                              |
| **Type-Safe**         | Strict types and contracts ensure reliability         |

---

## 5. Hook Lifecycle

### Filters

1. Register callback using `add_filter()`
2. Execute with `apply_filters()`
3. Value passes through all callbacks sequentially
4. Return the final modified value

### Actions

1. Register callback using `add_action()`
2. Execute with `do_action()`
3. Each listener runs synchronously
4. Return values are ignored

---

## 6. Example Lifecycle Flow

```
add_filter('title', callback1)
add_filter('title', callback2)
apply_filters('title', $value)

Dispatcher
 └── ListenerCollection
      ├── callback1 modifies value
      └── callback2 modifies value again
Return final value
```

---

## 7. Extending the System

You can implement your own Dispatcher:

```php
class CustomDispatcher implements \Riyad\Hooks\Contracts\DispatcherInterface {
    // Custom logic
}
```

Then override the global dispatcher instance.

---

## 8. Limitations

* Execution is **synchronous only**
* Hooks exist **in memory** (not persistent)
* No asynchronous queue support yet

---

## 9. Future Enhancements

* Async hooks using queues
* Hook profiling and debug tools
* Persistent hook registration (via cache or database)

---

## 10. Summary

Riyad Hooks provides a **modular, extensible, WordPress-inspired** hook mechanism for modern PHP.
It balances **familiarity** with **robust OOP architecture**.

````

---