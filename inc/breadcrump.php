<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Полностью заменяем BreadcrumbList в JSON-LD, добавляя категорию
 */
add_filter( 'rank_math/json_ld', function( $data, $jsonld ) {
    // Только для страниц записей
    if ( ! is_single() || is_admin() ) {
        return $data;
    }
    
    // Получаем категорию
    $categories = get_the_category();
    if ( empty( $categories ) || ! isset( $categories[0] ) ) {
        return $data;
    }
    
    $category = $categories[0];
    $post_title = get_the_title();
    $post_url = get_permalink();
    $category_url = get_category_link( $category->term_id );
    $home_url = home_url( '/' );
    
    // Создаем НОВЫЙ BreadcrumbList с трех уровневой структурой
    $new_breadcrumb = array(
        '@type' => 'BreadcrumbList',
        '@id' => $data['BreadcrumbList']['@id'] ?? get_permalink() . '#breadcrumb',
        'itemListElement' => array(
            array(
                '@type' => 'ListItem',
                'position' => 1,
                'item' => array(
                    '@id' => $home_url,
                    'name' => 'Главная',
                ),
            ),
            array(
                '@type' => 'ListItem',
                'position' => 2,
                'item' => array(
                    '@id' => $category_url,
                    'name' => $category->name,
                ),
            ),
            array(
                '@type' => 'ListItem',
                'position' => 3,
                'item' => array(
                    '@id' => $post_url,
                    'name' => $post_title,
                ),
            ),
        ),
    );
    
    // Заменяем старый BreadcrumbList новым
    $data['BreadcrumbList'] = $new_breadcrumb;
    
    return $data;
}, 999, 2 ); // Приоритет 999 - чтобы сработало после всех других фильтров