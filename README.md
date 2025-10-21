```markdown
# Riyad Hooks

> A lightweight, modern, WordPress-style Hook System for PHP.

---

## 🚀 Overview

**Riyad Hooks** brings the simplicity of WordPress **actions** and **filters** to modern PHP —  
with a fully object-oriented, type-safe architecture and support for **wildcard hooks**.

---

## 📦 Installation

```bash
composer require devriyad/hooks
````

---

## ⚙️ Basic Usage

```php
<?php
require __DIR__ . '/vendor/autoload.php';

add_action('init', function () {
    echo "App initialized!";
});

add_filter('title', function ($title) {
    return strtoupper($title);
});

do_action('init');
echo apply_filters('title', 'hello world');
```

Output:

```
App initialized!
HELLO WORLD
```

---

## 🧩 Object Method Example

```php
class App {
    public function boot() {
        add_action('init', [$this, 'onInit']);
        add_filter('title', [$this, 'formatTitle']);
    }

    public function onInit() {
        echo "System initialized!\n";
    }

    public function formatTitle($title) {
        return "🔥 " . ucfirst($title);
    }
}

$app = new App();
$app->boot();

do_action('init');
echo apply_filters('title', 'welcome');
```

Output:

```
System initialized!
🔥 Welcome
```

---

## ✨ Wildcard Hooks

```php
add_action('user.*', function ($user) {
    echo "User action: {$user->name}\n";
});

do_action('user.login', (object)['name' => 'Riyad']);
do_action('user.logout', (object)['name' => 'Khairul']);
```

Output:

```
User action: Riyad
User action: Khairul
```

---

## 🧠 Features

✅ WordPress-style syntax (`add_action`, `add_filter`)
✅ Supports closures, object methods, and static callables
✅ Wildcard hook matching
✅ Type-safe and PSR-4 compliant
✅ Lightweight, no dependencies
✅ Fully synchronous execution

---

## 🧱 Architecture

```
riyad-hooks/
├── src/
│   ├── Contracts/
│   ├── Exceptions/
│   ├── Utils/
│   ├── Event.php
│   ├── Listener.php
│   ├── ListenerCollection.php
│   └── Dispatcher.php
├── docs/
│   ├── USAGE.md
│   └── DESIGN.md
├── tests/
├── composer.json
├── README.md
└── LICENSE
```

---

## 📚 Documentation

* [Usage Guide](docs/USAGE.md)
* [Design Documentation](docs/DESIGN.md)

---

## 🧑‍💻 Author

**Riyad Munauwar**
📧 [riyadmunauwar@gmail.com](mailto:riyadmunauwar@gmail.com)

---

## 📄 License

MIT License © 2025 Riyad Munauwar
You are free to use, modify, and distribute this software with attribution.


