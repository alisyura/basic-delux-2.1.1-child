<?php
if (!defined('ABSPATH')) {
    exit;
}

// Подключаем плагин table из папки ДОЧЕРНЕЙ темы
function add_table_plugin_from_theme( $plugins ) {
    // Используем get_stylesheet_directory_uri() для дочерней темы
    $plugins['table'] = get_stylesheet_directory_uri() . '/assets/js/tinymce/plugins/table/plugin.min.js';
    return $plugins;
}
add_filter( 'mce_external_plugins', 'add_table_plugin_from_theme' );

// Добавляем кнопку таблиц на панель инструментов
function add_table_button_from_theme( $buttons ) {
    $buttons[] = 'table';
    return $buttons;
}
add_filter( 'mce_buttons_2', 'add_table_button_from_theme' );


