<?php
if (!defined('ABSPATH')) {
    exit;
}

// убираем версию вордпрес из rss
function remove_wp_version_rss() {
    return '';
}
add_filter('the_generator', 'remove_wp_version_rss');

// убираем лишнее из хедера
remove_action('wp_head','feed_links_extra', 3); // убирает ссылки на rss категорий
remove_action('wp_head','feed_links', 2); // минус ссылки на основной rss и комментарии
remove_action('wp_head','rsd_link');  // сервис Really Simple Discovery
remove_action('wp_head','wlwmanifest_link'); // Windows Live Writer
remove_action('wp_head','wp_generator');  // скрыть версию wordpress

// Также можно скрыть разные линки при отображении постов блога (следующий, предыдущий, короткий url).
remove_action('wp_head','start_post_rel_link',10,0);
remove_action('wp_head','index_rel_link');
remove_action('wp_head','adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action('wp_head','wp_shortlink_wp_head', 10, 0 );

// Удаление JSON API ссылок:
remove_action( 'wp_head', 'rest_output_link_wp_head');
remove_action( 'wp_head', 'wp_oembed_add_discovery_links');
remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );

// убираем эмодзи
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
