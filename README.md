```md
# riyad/hooks

> 🪝 A lightweight, synchronous, WordPress-style hook system for modern PHP — production-grade, extensible, and type-safe.

---

## 🚀 Features

✅ WordPress-style **actions** & **filters**  
✅ **Singleton** pattern for global consistency  
✅ Optional **functional helpers** (`add_action`, `apply_filters`, etc.)  
✅ **Enable/disable** helpers dynamically  
✅ **Clean OOP** design with interfaces  
✅ **Composer + PSR-4** compliant  
✅ **Production-ready**, no async or caching  

---

## 📦 Installation

```bash
composer require devriyad/hooks
````

Requires **PHP ≥ 8.1**

---

## ⚙️ Basic Usage

```php
use Riyad\Hooks\Hook;

$hook = Hook::instance();

// Add an action
$hook->addAction('init', fn() => print "Initialized!\n");
$hook->doAction('init');

// Add a filter
$hook->addFilter('title', fn($title) => strtoupper($title));
echo $hook->applyFilters('title', 'hello world'); // HELLO WORLD
```

---

## 🧩 Enable Functional Helpers

```php
$hook = Hook::instance();
$hook->enableHelpers();

add_action('boot', fn() => print "Booting...\n");
do_action('boot');

add_filter('message', fn($msg) => strtoupper($msg));
echo apply_filters('message', 'hello world');
```

---

## 🔧 Configuration

```php
$hook->disableHelpers(); // Disable global functions
$hook->helpersEnabled(); // true or false
$hook->getDispatcher();  // Access underlying dispatcher
```

---

## 🧱 Architecture

```
devriyad-hooks/
├── src/
│   ├── Contracts/
│   │   └── EventInterface.php
│   │   └── DispatcherInterface.php
│   │   └── HookInterface.php
│   ├── Hook.php
│   ├── Dispatcher.php
│   ├── Event.php
│   ├── Listener.php
│   ├── ListenerCollection.php
│   ├── Helpers/functions.php
│   └── Exceptions/
│       ├── InvalidListenerException.php
│       └── EventDispatchException.php
├── docs/
│   ├── USAGE.md
│   └── DESIGN.md
├── composer.json
└── README.md
```

---

## 📘 Documentation

* [Usage Guide](./docs/USAGE.md)
* [Design Overview](./docs/DESIGN.md)

---

## 🧑‍💻 Author

**Riyad Munauwar**
📧 [riyadmunauwar@gmail.com](mailto:riyadmunauwar@gmail.com)
📦 Package: `devriyad/hooks`

---

## ⚖️ License

Licensed under the **MIT License**.
© 2025 Riyad Munauwar. All rights reserved.

```