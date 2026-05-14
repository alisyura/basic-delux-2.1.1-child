<?php
if (!defined('ABSPATH')) {
    exit;
}

// Функция вывода похожих записей (по тэгам и рубрикам)
function custom_related_posts($atts = []) {
    // Параметры шорткода
    $atts = shortcode_atts([
        'limit' => 4,
        'title' => 'Тоже интересно',
    ], $atts);
    
    $current_post_id = get_the_ID();
    if (!$current_post_id) {
        return '';
    }
    
    // Получаем рубрики
    $categories = get_the_category($current_post_id);
    $category_ids = [];
    if ($categories) {
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }
    }
    
    // Получаем тэги
    $tags = get_the_tags($current_post_id);
    $tag_ids = [];
    if ($tags) {
        foreach ($tags as $tag) {
            $tag_ids[] = $tag->term_id;
        }
    }
    
    // Формируем запрос
    $args = [
        'post_type' => 'post',
        'posts_per_page' => intval($atts['limit']),
        'post__not_in' => [$current_post_id],
        'orderby' => 'rand',
        'ignore_sticky_posts' => true,
    ];
    
    // Приоритет: тэги, потом рубрики
    if (!empty($tag_ids)) {
        $args['tag__in'] = $tag_ids;
    } elseif (!empty($category_ids)) {
        $args['category__in'] = $category_ids;
    } else {
        return ''; // Нет ни тэгов, ни рубрик
    }
    
    $related_query = new WP_Query($args);
    
    if (!$related_query->have_posts()) {
        return '<p class="related-posts-none">Пока нет похожих статей. Загляните позже!</p>';
    }
    
    ob_start();
    ?>
    <section class="custom-related-posts">
        <h3><?php echo esc_html($atts['title']); ?></h3>
        <div class="related-posts-grid">
            <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                <article class="related-post-item">
                    <a href="<?php the_permalink(); ?>" class="related-post-link">
                        <h4 class="related-post-title"><?php the_title(); ?></h4>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="related-post-thumb">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="related-post-excerpt">
                            <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                        </div>
                    </a>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('related_posts', 'custom_related_posts');

// Также создаём функцию для прямого вызова в шаблоне (без шорткода)
function display_related_posts($limit = 4, $title = 'Тоже интересно') {
    echo custom_related_posts(['limit' => $limit, 'title' => $title]);
}