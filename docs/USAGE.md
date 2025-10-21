````md
# Usage Guide — riyad/hooks

This document explains how to use the `riyad/hooks` package — a **lightweight, synchronous, WordPress-style hook system** for PHP applications.

---

## 🧩 Installation

Install via Composer:

```bash
composer require riyad/hooks
````

Requires **PHP 8.1+**.

---

## 🚀 Getting Started

### 1. Autoload

Ensure Composer autoloading is enabled in your project entry file:

```php
require_once __DIR__ . '/vendor/autoload.php';
```

---

## 🔧 Basic Usage

The `Hook` class provides a clean, singleton-based API for managing **actions** and **filters**.

### Example

```php
use Riyad\Hooks\Hook;

$hook = Hook::instance();

// Add an action
$hook->addAction('init', fn() => print "App initialized!\n");
$hook->doAction('init');

// Add a filter
$hook->addFilter('title', fn($title) => strtoupper($title));
echo $hook->applyFilters('title', 'hello world'); // HELLO WORLD
```

---

## ⚙️ Using Functional Helpers

If you prefer WordPress-style functions like `add_action()` or `apply_filters()`,
you can **enable** the procedural helper functions:

```php
$hook = Hook::instance();
$hook->enableHelpers();
```

Once enabled, the following global functions become available:

| Type   | Function                                      | Description                     |
| ------ | --------------------------------------------- | ------------------------------- |
| Filter | `add_filter($tag, $callback, $priority = 10)` | Register a filter callback      |
| Filter | `apply_filters($tag, $value, ...$args)`       | Apply all filters for a tag     |
| Filter | `remove_filter($tag, $listenerId)`            | Remove a specific filter        |
| Filter | `remove_all_filters($tag)`                    | Remove all filters for a tag    |
| Filter | `has_filter($tag)`                            | Check if filters exist          |
| Action | `add_action($tag, $callback, $priority = 10)` | Register an action callback     |
| Action | `do_action($tag, ...$args)`                   | Run all callbacks for an action |
| Action | `remove_action($tag, $listenerId)`            | Remove a specific action        |
| Action | `remove_all_actions($tag)`                    | Remove all actions for a tag    |
| Action | `has_action($tag)`                            | Check if actions exist          |

Example:

```php
$hook = Hook::instance();
$hook->enableHelpers();

add_action('boot', fn() => print "Booting app...\n");
do_action('boot');

add_filter('message', fn($msg) => strtoupper($msg));
echo apply_filters('message', 'hello world');
```

---

## 🧠 Disabling Helpers

```php
$hook->disableHelpers();
```

This prevents `functions.php` from loading automatically in contexts like testing, multi-package environments, or frameworks where global functions may conflict.

---

## 🧪 Checking Helpers Status

```php
if ($hook->helpersEnabled()) {
    echo "Helpers are active!";
}
```

---

## 🧱 Accessing the Dispatcher

You can access the underlying `Dispatcher` instance for advanced event handling:

```php
$dispatcher = $hook->getDispatcher();
```

---

## ✅ Summary

| Feature          | Description                                              |
| ---------------- | -------------------------------------------------------- |
| Actions          | Execute callbacks (like `do_action` in WordPress)        |
| Filters          | Transform values before returning (like `apply_filters`) |
| Singleton        | Global access to the same dispatcher                     |
| Helpers          | Optional global WordPress-like functions                 |
| PSR-4            | Clean autoloading, fully namespaced                      |
| Production-ready | Tested, typed, and extensible                            |

---

## 🧩 Example Project

```php
use Riyad\Hooks\Hook;

$hook = Hook::instance();
$hook->enableHelpers();

// OOP
$hook->addAction('init', fn() => print "Initialized\n");
$hook->doAction('init');

// Functional
add_filter('greet', fn($name) => "Hello, $name!");
echo apply_filters('greet', 'Riyad');
```

Output:

```
Initialized
Hello, Riyad!
```

````