<?php
if (!defined('ABSPATH')) {
    exit;
}


// Добавляем метабокс для записей и страниц
function add_quick_notes_meta_box() {
    add_meta_box(
        'quick_notes_box',           // ID
        '📝 Быстрые заметки (черновик мыслей)', // Заголовок
        'render_quick_notes_box',    // Функция отображения
        ['post', 'page'],            // Где показывать (можно только 'post')
        'side',                      // Положение: 'side' (справа колонка) или 'normal' (как текст)
        'high'                       // Приоритет: высокий, чтобы был сверху
    );
}
add_action( 'add_meta_boxes', 'add_quick_notes_meta_box' );

function render_quick_notes_box( $post ) {
    // Получаем сохраненные заметки
    $notes = get_post_meta( $post->ID, '_quick_notes', true );
    wp_nonce_field( 'save_quick_notes', 'quick_notes_nonce' );

    // Поле ввода
    echo '<textarea style="width:100%; min-height: 120px; margin-top: 5px;" name="quick_notes" placeholder="Идеи, план, тезисы для этой статьи...">' . esc_textarea( $notes ) . '</textarea>';
    echo '<p style="color: #555; font-size: 12px; margin-top: 5px;">✏️ Заметки видны только вам в админке. <br>✔️ <strong>Не сбрасываются автоматически.</strong><br>🔄 Чтобы сохранить — <strong>нажмите «Обновить»</strong> на странице записи.</p>';
}

function save_quick_notes_box( $post_id ) {
    // Проверка безопасности
    if ( ! isset( $_POST['quick_notes_nonce'] ) || ! wp_verify_nonce( $_POST['quick_notes_nonce'], 'save_quick_notes' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Сохраняем заметки
    if ( isset( $_POST['quick_notes'] ) ) {
        update_post_meta( $post_id, '_quick_notes', sanitize_textarea_field( $_POST['quick_notes'] ) );
    } else {
        delete_post_meta( $post_id, '_quick_notes' );
    }
}
add_action( 'save_post', 'save_quick_notes_box' );