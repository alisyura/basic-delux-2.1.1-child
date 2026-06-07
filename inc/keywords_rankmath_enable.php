<?php
if (!defined('ABSPATH')) {
    exit;
}

add_filter( 'rank_math/frontend/show_keywords', '__return_true' );