<?php
if (!defined('ABSPATH')) {
    exit;
}

// Функция для проверки, содержит ли меню пункты. Используется в footer.php
function has_menu_items($theme_location) {
    $locations = get_nav_menu_locations();
    if (!isset($locations[$theme_location])) {
        return false;
    }
    
    $menu = wp_get_nav_menu_object($locations[$theme_location]);
    if (!$menu || is_wp_error($menu)) {
        return false;
    }
    
    $menu_items = wp_get_nav_menu_items($menu->term_id);
    return !empty($menu_items);
}
