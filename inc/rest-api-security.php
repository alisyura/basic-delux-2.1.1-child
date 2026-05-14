<?php
if (!defined('ABSPATH')) {
    exit;
}

// Закрываем публичный доступ к REST API эндпоинту пользователей
add_filter( 'rest_endpoints', function( $endpoints ) {
    // Проверяем, существует ли эндпоинт списка пользователей
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        // Меняем правило доступа: разрешаем только авторизованным пользователям с правом редактировать посты
        $endpoints['/wp/v2/users'][0]['permission_callback'] = function () {
            return current_user_can( 'edit_posts' );
        };
    }

    // Закрываем отдельного пользователя по ID (/wp/v2/users/{id})
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        $endpoints['/wp/v2/users/(?P<id>[\d]+)'][0]['permission_callback'] = function () {
            return current_user_can( 'edit_posts' );
        };
    }

    return $endpoints;
});

// Отключаем REST API для неавторизованных пользователей (но оставляем для залогиненных)
/*add_filter( 'rest_authentication_errors', function( $result ) {
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_not_logged_in', 'Вы не авторизованы.', array( 'status' => 401 ) );
    }
    return $result;
});    */


add_filter( 'rest_authentication_errors', function( $result ) {
    // Если уже есть ошибка — не трогаем
    if ( is_wp_error( $result ) ) {
        return $result;
    }
    
    // Для авторизованных — всё ок
    if ( is_user_logged_in() ) {
        return $result;
    }
    
    // Получаем текущий эндпоинт
    $request_uri = $_SERVER['REQUEST_URI'];
    
    // Список разрешённых эндпоинтов (настройте под свои нужды)
    $allowed_endpoints = [
        //'contact-form-7',    // Contact Form 7
        'wp/v2/posts',       // Чтение постов (нужно для кэширования)
        'wp/v2/pages',       // Чтение страниц
    ];
    
    // Проверяем, не входит ли запрос в список разрешённых
    foreach ( $allowed_endpoints as $endpoint ) {
        if ( strpos( $request_uri, $endpoint ) !== false ) {
            return $result; // Разрешаем
        }
    }
    
    // Всё остальное — запрещаем
    return new WP_Error( 'rest_not_logged_in', 'Вы не авторизованы.', array( 'status' => 401 ) );
});