<?php
if (!defined('ABSPATH')) {
    exit;
}

// Блок автора с иконками-картинками
function add_custom_author_box($content) {
    if (is_single() && get_the_author_meta('description')) {
        $author_id = get_the_author_meta('ID');
        $icons_url = get_stylesheet_directory_uri() . '/assets/icons/';
        
        // Сопоставление полей с иконками
        $social_icons = [
            'vk_profile'       => ['name' => 'ВК',        'icon' => 'vk.png'],
            'dzen_profile'     => ['name' => 'Дзен',      'icon' => 'dzen.png'],
            'telegram_profile' => ['name' => 'Telegram',  'icon' => 'tg.png'],
            'max_profile'      => ['name' => 'Макс',      'icon' => 'max.png'],
            'youtube_profile'  => ['name' => 'Youtube',   'icon' => 'youtube.png'],
        ];
        
        $social_links = '';
        foreach ($social_icons as $field => $data) {
            $url = get_user_meta($author_id, $field, true);
            if (!empty($url)) {
                $icon_path = $icons_url . $data['icon'];
                $social_links .= sprintf(
                    '<a href="%s" rel="nofollow noopener noreferrer" target="_blank" class="author-social-link" title="%s">',
                    esc_url($url),
                    esc_attr($data['name'])
                );
                $social_links .= '<img src="' . esc_url($icon_path) . '" alt="' . esc_attr($data['name']) . '" width="20" height="20">';
                $social_links .= '</a>';
            }
        }
        
        $author_box_html = '
        <div class="custom-author-box">
            <div class="custom-author-avatar">' . get_avatar($author_id, 100) . '</div>
            <div class="custom-author-info">
                <h3 class="custom-author-title">Об авторе: ' . get_the_author() . '</h3>
                <div class="custom-author-description">' . wp_kses_post(wpautop(get_the_author_meta('description'))) . '</div>
                <div class="custom-author-links">
                    <a href="' . esc_url(get_author_posts_url($author_id)) . '" class="author-all-posts">Все статьи автора</a>
                    ' . (!empty($social_links) ? '<span class="author-socials">' . $social_links . '</span>' : '') . '
                </div>
            </div>
        </div>';
        
        $content .= $author_box_html;
    }
    return $content;
}
add_action('the_content', 'add_custom_author_box');