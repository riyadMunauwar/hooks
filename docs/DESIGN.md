```md
# Design Overview — riyad/hooks

This document explains the internal design, architecture, and philosophy of the `riyad/hooks` system.

---

## 🎯 Design Goals

- **Simplicity:** Easy to use, familiar WordPress-style API.
- **Clean Architecture:** Built with modern PHP OOP patterns.
- **Production-grade:** Type-safe, PSR-12 compliant, and lightweight.
- **Extensible:** Interfaces allow easy replacement or extension.
- **Safe Global Helpers:** Enable or disable function helpers dynamically.
- **No Async or Caching:** 100% synchronous, predictable, lightweight.

---

## 🧱 Core Components

### 1. Hook (Singleton)

`Riyad\Hooks\Hook`  
The main class that manages **actions** and **filters**, exposing a public API similar to WordPress.

Implements:  
`Riyad\Hooks\Contracts\HookInterface`

Responsibilities:
- Manage the global `Dispatcher` instance.
- Register, dispatch, and remove listeners.
- Enable or disable functional helpers.
- Maintain single instance across runtime.

---

### 2. Dispatcher

`Riyad\Hooks\Dispatcher`  
Handles event registration and execution.  
Each hook (`filter.*`, `action.*`) maps to an internal listener group.

Responsibilities:
- Add and remove listeners.
- Dispatch events synchronously.
- Manage priorities for callbacks.

---

### 3. Event

`Riyad\Hooks\Event`  
Encapsulates event data.  
For filters, it carries `value` and `args`.  
For actions, only `args`.

---

### 4. HookInterface

`Riyad\Hooks\Contracts\HookInterface`  
Defines the contract for all hook managers.  
Allows future alternative implementations (e.g., async or cached versions).

---

### 5. Functional Helpers (Optional)

`src/Helpers/functions.php`  
Provides global WordPress-style functions like `add_action()` and `apply_filters()`.  
They delegate all calls to `Hook::instance()` internally.

Can be **enabled or disabled** dynamically using:

```php
$hook->enableHelpers();
$hook->disableHelpers();
````

---

## 🧩 Internal Flow

**Filters Flow:**

```php
addFilter('title', fn($t) => strtoupper($t));
applyFilters('title', 'hello');

// => 'HELLO'
```

Under the hood:

1. Listener registered to event `filter.title`
2. `applyFilters()` creates a new `Event`
3. `Dispatcher` triggers callbacks by priority
4. `Event`’s value is updated and returned

**Actions Flow:**

```php
addAction('init', fn() => print "Init!");
doAction('init');
```

* No return value — actions only execute callbacks.

---

## 🧰 Design Patterns

| Pattern                        | Used For                                |
| ------------------------------ | --------------------------------------- |
| **Singleton**                  | Ensures one Hook instance globally      |
| **Observer/Event**             | Actions and filters as event listeners  |
| **Strategy (via Interface)**   | Pluggable implementations (e.g., async) |
| **Dependency Injection Ready** | Supports framework integration          |

---

## 🧠 Future Extensions

| Idea                  | Description                         |
| --------------------- | ----------------------------------- |
| **AsyncHook**         | Non-blocking dispatch version       |
| **CachedHook**        | Store results for repeated filters  |
| **HookCollection**    | Manage multiple hook containers     |
| **Integration Layer** | Laravel or Symfony service provider |

---

## ⚙️ Performance Notes

* **Synchronous execution** (no threading or async).
* **No caching layer** (for predictable flow).
* **Minimal memory footprint.**
* **No global state pollution** when helpers are disabled.

---

## 🧩 Summary

| Component     | Purpose                              |
| ------------- | ------------------------------------ |
| Hook          | Main Singleton class managing hooks  |
| Dispatcher    | Core event system                    |
| Event         | Data wrapper for hooks               |
| HookInterface | Contract for future extensibility    |
| Helpers       | Optional global procedural functions |

---

### Diagram

```
Hook (Singleton)
│
├── Dispatcher
│     ├── addListener()
│     ├── removeListener()
│     └── dispatch()
│
├── Event
│     └── value, args
│
└── Helpers/functions.php (optional)
```

````