<?php

declare(strict_types=1);

// Autoload Composer packages
require __DIR__ . '/vendor/autoload.php';

use Riyad\Hooks\Hook;

$hook = Hook::instance();
$hook->enableHelpers();

// Add an action
// $hook->addAction('init', fn() => print "App initialized!\n");
// $hook->doAction('init');

// // Add a filter
// $hook->addFilter('title', fn($title) => strtoupper($title));
// echo $hook->applyFilters('title', 'hello world'); // HELLO WORLD

// -----------------------------
// Example Data
// -----------------------------
$user = (object)[
    'name' => 'Riyad Munauwar',
    'email' => 'riyad@example.com'
];

$postTitle = 'hello world';

// -----------------------------
// FILTERS
// -----------------------------

// Add a filter to modify post titles
add_filter('post.title', function ($title) {
    return strtoupper($title); // convert title to uppercase
});

// Add another filter to append a suffix
add_filter('post.title', function ($title) {
    return $title . ' | My Blog';
}, 15); // higher priority runs first

// Apply filters
$filteredTitle = apply_filters('post.title', $postTitle);

echo "Filtered Title: {$filteredTitle}" . PHP_EOL;
// Example output: HELLO WORLD | My Blog

// -----------------------------
// ACTIONS
// -----------------------------

// Add an action when a user registers
add_action('user.registered', function ($user) {
    echo "Welcome {$user->name}!" . PHP_EOL;
});

// Add another action for logging
add_action('user.registered', function ($user) {
    echo "Logging user registration for {$user->email}" . PHP_EOL;
}, 5); // lower priority runs after default 10

// Trigger actions
do_action('user.registered', $user);

// -----------------------------
// REMOVE LISTENERS EXAMPLES
// -----------------------------

// Remove a specific filter (you need the listener ID from add_filter)
$listenerId = add_filter('post.title', fn($t) => $t . ' [TEST]');
remove_filter('post.title', $listenerId);

// Remove all actions for a hook
remove_all_actions('user.registered');

// -----------------------------
// CHECK EXISTENCE
// -----------------------------
if (has_filter('post.title')) {
    echo "Filters exist for 'post.title'" . PHP_EOL;
}

if (!has_action('user.registered')) {
    echo "No actions exist for 'user.registered'" . PHP_EOL;
}

