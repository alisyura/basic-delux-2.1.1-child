<?php
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================
// НОВАЯ КНОПКА ДЛЯ BASE64 ССЫЛОК (добавляется к существующим)
// ============================================================

add_action('init', 'urlspan64_tinymce_button');
function urlspan64_tinymce_button() {  
    if(!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }
    if(get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "urlspan64_tinymce_addplugin");
        add_filter('mce_buttons', 'urlspan64_tinymce_registerbutton');
    }
}

function urlspan64_tinymce_registerbutton($buttons) {
    // Добавляем только новую кнопку (существующие не трогаем)
    $buttons[] = 'urlspan64';
    return $buttons;
}

function urlspan64_tinymce_addplugin($plugin_array) {
    $plugin_array['urlspan64'] = get_stylesheet_directory_uri() . '/inc/urlspan64/editor_plugin64.js';
    return $plugin_array;
}

// ============================================================
// ОБРАБОТКА BASE64 ССЫЛОК НА САЙТЕ
// ============================================================
// Обработка base64 ссылок с сохранением текста
function replace_link_base64($content) {
    // Новый паттерн: [urlspan64]URL|ТЕКСТ[/urlspan64]
    $pattern = '/\[urlspan64\](.*?)\|(.*?)\[\/urlspan64\]/is';
    
    $content = preg_replace_callback($pattern, function($matches) {
        $url = trim($matches[1]);
        $text = $matches[2];
        $encoded = base64_encode($url);
        return '<span class="span64" data-base64="' . $encoded . '">' . $text . '</span>';
    }, $content);
    
    return $content;
}
add_filter('the_content', 'replace_link_base64');
// ============================================================
// JAVASCRIPT ДЛЯ ДЕКОДИРОВАНИЯ
// ============================================================
function base64_decode_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.span64[data-base64]').forEach(function(el) {
            el.onclick = function(e) {
                e.preventDefault();
                try {
                    var url = atob(this.getAttribute('data-base64'));
                    window.open(url, '_blank');
                } catch(e) {
                    console.log('Ошибка декодирования');
                }
            };
            el.style.cursor = 'pointer';
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'base64_decode_script');
