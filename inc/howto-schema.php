<?php
if (!defined('ABSPATH')) {
    exit;
}

// Автоматическая микроразметка HowTo для статей с упражнениями
// Поддерживаются маркированные (<ul>) и нумерованные (<ol>) списки

$howto_markers = [
    'встаньте', 'сядьте', 'лягте', 'поднимите', 'опустите', 'согните',
    'разогните', 'вытяните', 'поверните', 'наклонитесь', 'сделайте',
    'положите', 'прижмите', 'напрягите', 'расслабьте', 'потянитесь',
    'задержитесь', 'выдохните', 'вдохните', 'повторите', 'медленно', 'плавно'
];

function article_has_howto_steps($content, $markers) {
    if (preg_match_all('/<(ul|ol)[^>]*>(.*?)<\/(ul|ol)>/is', $content, $matches)) {
        foreach ($matches[2] as $list_content) {
            if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $list_content, $li_matches)) {
                foreach ($li_matches[1] as $li_text) {
                    $clean_text = trim(strip_tags($li_text));
                    $clean_text = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $clean_text);
                    $lower_text = mb_strtolower($clean_text, 'UTF-8');
                    foreach ($markers as $marker) {
                        if (strpos($lower_text, mb_strtolower($marker, 'UTF-8')) === 0) {
                            return true;
                        }
                    }
                }
            }
        }
    }
    return false;
}

function add_howto_schema() {
    if (!is_single()) return;
    global $post, $howto_markers;
    if (!$post) return;
    
    $content = apply_filters('the_content', $post->post_content);
    if (!article_has_howto_steps($content, $howto_markers)) return;
    
    $steps = array();
    if (preg_match_all('/<(ul|ol)[^>]*>(.*?)<\/(ul|ol)>/is', $content, $matches)) {
        foreach ($matches[2] as $list_content) {
            if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $list_content, $li_matches)) {
                foreach ($li_matches[1] as $li_text) {
                    $clean_text = trim(strip_tags($li_text));
                    $clean_text = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $clean_text);
                    $lower_text = mb_strtolower($clean_text, 'UTF-8');
                    $is_step = false;
                    foreach ($howto_markers as $marker) {
                        if (strpos($lower_text, mb_strtolower($marker, 'UTF-8')) === 0) {
                            $is_step = true;
                            break;
                        }
                    }
                    if ($is_step && mb_strlen($clean_text) > 10) {
                        $steps[] = array('@type' => 'HowToStep', 'text' => $clean_text);
                        if (count($steps) >= 15) break;
                    }
                }
            }
            if (count($steps) >= 15) break;
        }
    }
    
    if (empty($steps)) return;
    
    $howto_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'HowTo',
        'name' => get_the_title($post),
        'description' => wp_trim_words(get_the_excerpt($post), 30),
        'step' => $steps,
    );
    
    if (has_post_thumbnail($post)) {
        $image_url = wp_get_attachment_image_url(get_post_thumbnail_id($post), 'full');
        if ($image_url) $howto_schema['image'] = $image_url;
    }
    
    echo '<script type="application/ld+json">' . json_encode($howto_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
}
add_action('wp_head', 'add_howto_schema', 99);