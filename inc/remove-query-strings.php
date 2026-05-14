<?php
if (!defined('ABSPATH')) {
    exit;
}

// убираем версию у css и js файлов
function wpschool_remove_ver_css_js( $src, $handle ) {
    // Если в src есть ver= — удаляем его
    if ( strpos( $src, 'ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}
add_filter( 'style_loader_src', 'wpschool_remove_ver_css_js', 9999, 2 );
add_filter( 'script_loader_src', 'wpschool_remove_ver_css_js', 9999, 2 );

// Генерируем версию на основе времени изменения файла
function wpschool_asset_version( $src ) {
    $file_path = ABSPATH . str_replace( site_url(), '', $src );
    if ( file_exists( $file_path ) ) {
        return filemtime( $file_path );
    }
    return null;
}
