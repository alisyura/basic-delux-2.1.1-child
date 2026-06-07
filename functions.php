<?php
/**
 * Functions file для дочерней темы
 * Все логические блоки вынесены в папку /inc/
 */

if (!defined('ABSPATH')) {
    exit;
}

$inc_path = get_stylesheet_directory() . '/inc/';

$modules = [
    'setup.php',                 // подключение стилей дочерней темы
    'cleanup-head.php',          // чистка <head>
    'disable-jquery.php',        // отключение jQuery для гостей
    'remove-query-strings.php',  // удаляем ver=, добавляем версию через filemtime
    'rest-api-security.php',     // закрываем REST API
    'xmlrpc-security.php',       // отключаем XML-RPC
    'highlight-menu-item.php',   // подсветка пункта меню
    'tinymce-clean-paste.php',   // очистка вставляемого текста
    'tinymce-table-button.php',  // кнопка таблиц
    'adaptive-table-script.php', // data-label для таблиц
    'relative-paths.php',        // относительные пути для картинок/файлов
    'footer-widgets.php',        // виджет-область в подвале
    'has-menu-items.php',        // функция проверки меню
    'hide-comments.php',         // скрываем комментарии для гостей
    'base64-links.php',          // кнопка и обработка [urlspan64]
    'video-embed.php',           // вставка видео через модалку
    'related-posts.php',         // связанные записи
    'recent-posts-widget.php',   // виджет свежие записи
    'author-box.php',            // блок об авторе в конце постов
    'user-profile.php',          // добавление соцсетей в профиль пользователей
//    'post-notes.php',            // заметки к посту/странице прям под редактором
    'lightbox-modal.php',        // модальный lightbox
//    'disable-json-ld-yoast.php', // отключить json-ld в yoast-seo
    'breadcrump.php',            // вставляем хлебные крошки в JSON-LD RankMath
    'keywords_rankmath_enable.php', // добавить в html вывод тэга keywords

];

foreach ($modules as $module) {
    $file = $inc_path . $module;
    if (file_exists($file)) {
        include_once $file;
    }
}




