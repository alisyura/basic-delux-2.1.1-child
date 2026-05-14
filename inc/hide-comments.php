<?php
if (!defined('ABSPATH')) {
    exit;
}

// отключение комментариев. все три в одной функции
function hide_comments_for_guests() {   
	if ( is_user_logged_in() ) {
	        // если пользователь авторизован, комментарии для него доступны
		return;
	}
	                                
        // скрывает существующие комментарии
	add_filter( 'comments_array',      '__return_empty_array' );

	// скрывает количество комментариев
	add_filter( 'get_comments_number', '__return_zero' );

        // отключает форму комментирования
	add_filter( 'comments_open',       '__return_false' );
}
add_action( 'init', 'hide_comments_for_guests' );
