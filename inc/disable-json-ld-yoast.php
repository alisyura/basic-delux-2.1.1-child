<?php
if (!defined('ABSPATH')) {
    exit;
}

// Отключаем стандартную JSON-LD схему Yoast SEO
add_filter( 'wpseo_json_ld_output', '__return_false' );