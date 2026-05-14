<?php
if (!defined('ABSPATH')) {
    exit;
}

// Добавляем социальные сети в стандартный блок контактов профиля
function add_author_social_fields( $contactmethods ) {

    // Добавляем новые поля в раздел "Контактная информация"
    return [
        'max_profile' => 'Макс',
        'dzen_profile' => 'Дзен', 
        'vk_profile' => 'Вконтакте',
        'telegram_profile' => 'Телеграм',
        // Если в теме или плагине поле YouTube стандартное не отображается, можно добавить и его
        // 'youtube_profile' => 'YouTube'
    ];
}
add_filter( 'user_contactmethods', 'add_author_social_fields' );