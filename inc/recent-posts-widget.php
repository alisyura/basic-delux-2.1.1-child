<?php
if (!defined('ABSPATH')) {
    exit;
}

class My_Recent_Posts_Widget extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'widget_my_recent_posts',
            'description' => 'Последние записи с миниатюрами',
        );
        parent::__construct('my_recent_posts', 'Свежие записи с миниатюрой', $widget_ops);
    }

    public function widget($args, $instance) {
        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        $title = (!empty($instance['title'])) ? $instance['title'] : 'Свежие записи';
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        $number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
        $show_date = isset($instance['show_date']) ? $instance['show_date'] : false;
        $show_thumb = isset($instance['show_thumb']) ? $instance['show_thumb'] : true;
        $thumb_size = (!empty($instance['thumb_size'])) ? $instance['thumb_size'] : 'thumbnail';
        $wrap_text = isset($instance['wrap_text']) ? (bool) $instance['wrap_text'] : false; // Новая опция

        $r = new WP_Query(array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        ));

        if (!$r->have_posts()) {
            return;
        }

        // Добавляем класс для обтекания если нужно
        $wrap_class = $wrap_text ? 'my-recent-posts-wrap' : '';
        
        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        echo '<ul class="my-recent-posts-list ' . $wrap_class . '">';
        while ($r->have_posts()) : $r->the_post();
            ?>
            <li class="my-recent-post-item">
                <?php if ($show_thumb && has_post_thumbnail()) : ?>
                    <div class="my-recent-post-thumb">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail($thumb_size); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="my-recent-post-content">
                    <a href="<?php the_permalink(); ?>" class="my-recent-post-title">
                        <?php get_the_title() ? the_title() : the_ID(); ?>
                    </a>
                    <?php if ($show_date) : ?>
                        <span class="my-recent-post-date"><?php echo get_the_date(); ?></span>
                    <?php endif; ?>
                </div>
            </li>
            <?php
        endwhile;
        echo '</ul>';

        echo $args['after_widget'];

        wp_reset_postdata();
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_date'] = isset($new_instance['show_date']) ? (bool) $new_instance['show_date'] : false;
        $instance['show_thumb'] = isset($new_instance['show_thumb']) ? (bool) $new_instance['show_thumb'] : true;
        $instance['thumb_size'] = sanitize_text_field($new_instance['thumb_size']);
        $instance['wrap_text'] = isset($new_instance['wrap_text']) ? (bool) $new_instance['wrap_text'] : false;
        return $instance;
    }

    public function form($instance) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : 'Свежие записи';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : false;
        $show_thumb = isset($instance['show_thumb']) ? (bool) $instance['show_thumb'] : true;
        $thumb_size = isset($instance['thumb_size']) ? $instance['thumb_size'] : 'thumbnail';
        $wrap_text = isset($instance['wrap_text']) ? (bool) $instance['wrap_text'] : false;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Заголовок:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>">Количество записей:</label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_date); ?> id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>">
            <label for="<?php echo $this->get_field_id('show_date'); ?>">Показывать дату</label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_thumb); ?> id="<?php echo $this->get_field_id('show_thumb'); ?>" name="<?php echo $this->get_field_name('show_thumb'); ?>">
            <label for="<?php echo $this->get_field_id('show_thumb'); ?>">Показывать миниатюру</label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('thumb_size'); ?>">Размер миниатюры:</label>
            <select id="<?php echo $this->get_field_id('thumb_size'); ?>" name="<?php echo $this->get_field_name('thumb_size'); ?>">
                <option value="thumbnail" <?php selected($thumb_size, 'thumbnail'); ?>>Миниатюра (150x150)</option>
                <option value="medium" <?php selected($thumb_size, 'medium'); ?>>Средний (300x300)</option>
                <option value="large" <?php selected($thumb_size, 'large'); ?>>Большой (1024x1024)</option>
                <option value="full" <?php selected($thumb_size, 'full'); ?>>Полный размер</option>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($wrap_text); ?> id="<?php echo $this->get_field_id('wrap_text'); ?>" name="<?php echo $this->get_field_name('wrap_text'); ?>">
            <label for="<?php echo $this->get_field_id('wrap_text'); ?>">Обтекать текст вокруг картинки</label>
            <br><small>Текст будет обтекать миниатюру, а не выстраиваться в колонку</small>
        </p>
        <?php
    }
}

function register_my_recent_posts_widget() {
    register_widget('My_Recent_Posts_Widget');
}
add_action('widgets_init', 'register_my_recent_posts_widget');