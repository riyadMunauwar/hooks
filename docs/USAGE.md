````markdown
# Riyad Hooks - Usage Guide

This document shows how to use **Riyad Hooks**, a modern, WordPress-style Hook System built in pure PHP.

---

## 1. Installation

Install the package using Composer:

```bash
composer require riyad/hooks
````

Then include the Composer autoloader:

```php
require __DIR__ . '/vendor/autoload.php';
```

---

## 2. Introduction

**Riyad Hooks** provides two main types of hooks:

* **Actions** → perform side effects (logging, sending mail, etc.)
* **Filters** → modify and return data

Both follow a **WordPress-like API**, but implemented with modern PHP features, interfaces, and strict typing.

---

## 3. Actions

### Add an Action

```php
add_action('app.init', function () {
    echo "Application started!";
});
```

### Trigger the Action

```php
do_action('app.init');
```

Output:

```
Application started!
```

### Remove Actions

```php
remove_action('app.init', $listenerId);
remove_all_actions('app.init');
```

### Check for Action

```php
if (has_action('app.init')) {
    echo "Action exists.";
}
```

---

## 4. Filters

### Add a Filter

```php
add_filter('content.title', function ($title) {
    return ucfirst($title);
});
```

### Apply Filter

```php
$title = apply_filters('content.title', 'hello world');
echo $title; // Output: Hello world
```

### Remove Filters

```php
remove_filter('content.title', $listenerId);
remove_all_filters('content.title');
```

---

## 5. Using Object Methods

You can register class methods just like in WordPress:

```php
class Blog {
    public function boot() {
        add_action('init', [$this, 'onInit']);
        add_filter('title', [$this, 'formatTitle']);
    }

    public function onInit() {
        echo "Blog initialized!";
    }

    public function formatTitle(string $title): string {
        return "📖 " . strtoupper($title);
    }
}

$blog = new Blog();
$blog->boot();

do_action('init');
echo apply_filters('title', 'welcome home');
```

Output:

```
Blog initialized!
📖 WELCOME HOME
```

---

## 6. Wildcard Hooks

**Riyad Hooks** supports wildcard matching.
You can listen to multiple events using patterns like `user.*`.

```php
add_action('user.*', function ($user) {
    echo "User action triggered: {$user->name}";
});

do_action('user.register', (object)['name' => 'Riyad']);
do_action('user.login', (object)['name' => 'Khairul']);
```

Output:

```
User action triggered: Riyad
User action triggered: Khairul
```

---

## 7. Summary of Helper Functions

| Type    | Function Names                                                                               |
| ------- | -------------------------------------------------------------------------------------------- |
| Filters | `add_filter()`, `remove_filter()`, `apply_filters()`, `has_filter()`, `remove_all_filters()` |
| Actions | `add_action()`, `remove_action()`, `do_action()`, `has_action()`, `remove_all_actions()`     |

---

## 8. Notes

* Hooks are **synchronous**.
* Execution order follows **priority** (default: 10).
* Supports any **valid PHP callable**.
* Designed for modern PHP (8.1+).

---

````

---