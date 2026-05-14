<?php
if (!defined('ABSPATH')) {
    exit;
}

// Подключаем модальное окно для lightbox modal только на нужных страницах
function add_lightbox_modal() {
    // Массив слагов страниц, где нужна модалка
  //   $allowed_pages = ['o-minye', 'ob-avtore', 'certificates', 'about']; // замените на свои слагы
  //  $current_slug = get_post_field('post_name', get_the_ID());

    // Получаем контент страницы
    global $post;
    if (!$post) return;
    
//    if (in_array($current_slug, $allowed_pages) && !is_admin()) { // проверка что slug разрешен для включения модалки
// Проверяем, есть ли в контенте класс certificate-link
    if (strpos($post->post_content, 'lightbox-link') !== false) {
        ?>
        <!-- Модальное окно для сертификатов -->
        <div id="lightboxModal" class="lightbox-modal">
            <span class="lightbox-modal-close">&times;</span>
            <img class="lightbox-modal-content" id="lightboxModalImg">
            <div id="lightboxModalCaption"></div>
        </div>
                
        <script>
        (function() {
            var modal = document.getElementById('lightboxModal');
            var modalImg = document.getElementById('lightboxModalImg');
            var captionText = document.getElementById('lightboxModalCaption');
            var closeBtn = document.querySelector('.lightbox-modal-close');
            
            if (!modal) return;
            
            // Находим все ссылки с классом certificate-link
            var certLinks = document.querySelectorAll('.lightbox-link');
            
            function openModal(imgSrc, imgAlt) {
                modal.style.display = 'block';
                modalImg.src = imgSrc;
                if (captionText) captionText.innerHTML = imgAlt || '';
                document.body.style.overflow = 'hidden';
            }
            
            function closeModal() {
                modal.style.display = 'none';
                modalImg.src = '';
                if (captionText) captionText.innerHTML = '';
                document.body.style.overflow = '';
            }
            
            // Вешаем обработчики на все сертификаты
            if (certLinks.length > 0) {
                certLinks.forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        var imgSrc = this.href;
                        var imgAlt = this.querySelector('img')?.alt || '';
                        openModal(imgSrc, imgAlt);
                    });
                });
            }
            
            // Закрытие по клику на фон
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
            
            // Закрытие по ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === 'block') closeModal();
            });
            
            if (closeBtn) closeBtn.addEventListener('click', closeModal);
        })();
        </script>
        <?php
    }
}
add_action('wp_footer', 'add_lightbox_modal');