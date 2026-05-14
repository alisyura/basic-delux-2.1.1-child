<?php
if (!defined('ABSPATH')) {
    exit;
}

// Делаем пути при вставке в редактор относительными от корня сайта /...

// Для картинок
function relative_image_path($html, $id, $caption, $title, $align, $url, $size, $alt) {
    $new_html = preg_replace('#src="https?://[^/]+/#', 'src="/', $html);
    return $new_html;
}
add_filter('image_send_to_editor', 'relative_image_path', 10, 8);

// Для файлов (PDF, DOC, ZIP и т.д.)
function relative_file_links($html, $id, $attachment) {
    $new_html = preg_replace('#href="https?://[^/]+(/wp-content/uploads/[^"]+)"#', 'href="$1"', $html);
    return $new_html;
}
add_filter('media_send_to_editor', 'relative_file_links', 10, 3);
