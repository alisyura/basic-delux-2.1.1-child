<?php
if (!defined('ABSPATH')) {
    exit;
}

// подсвечиваем пункт меню
add_filter('nav_menu_css_class', function($classes, $item) {
    $slug_to_check = 'pervye-shagi';
    if (preg_match("/\/{$slug_to_check}$/", $item->url)) {
        $classes[] = 'highlight-menu';
    }
    return $classes;
}, 10, 2);
