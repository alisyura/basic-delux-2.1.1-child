<?php
if (!defined('ABSPATH')) {
    exit;
}

// Регистрация виджет-области в подвале
function my_footer_widgets_init() {
    register_sidebar( array(
        'name'          => 'Подвал',
        'id'            => 'footer-widgets',
        'description'   => 'Добавляйте виджеты в нижнюю часть сайта',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'my_footer_widgets_init' );
