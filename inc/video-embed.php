<?php
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================
// ВИДЕО ДЛЯ TINYMCE С BOOTSTRAP
// ============================================================

// Добавляем кнопку в TinyMCE
add_action('admin_init', 'add_video_tinymce_button');
function add_video_tinymce_button() {
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }
    
    // НЕ загружаем на страницах рубрик, меток и таксономий
    global $pagenow;
    if ($pagenow === 'term.php' || $pagenow === 'edit-tags.php') {
        return;
    }

    if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_external_plugins', 'register_video_plugin');
        add_filter('mce_buttons_3', 'register_video_button');
    }
}

function register_video_button($buttons) {
    $buttons[] = 'customvideo';
    return $buttons;
}

function register_video_plugin($plugin_array) {
    $plugin_array['customvideo'] = get_stylesheet_directory_uri() . '/js/video-button.js';
    return $plugin_array;
}

// Создаём JS-файл с Bootstrap модалкой
add_action('admin_init', 'create_video_plugin_file');
function create_video_plugin_file() {
    $js_dir = get_stylesheet_directory() . '/js';
    $js_file = $js_dir . '/video-button.js';
    
    if (!file_exists($js_dir)) {
        wp_mkdir_p($js_dir);
    }
    
    if (!file_exists($js_file)) {
        $js_content = <<<'JS'
// Модальное окно (Bootstrap)
let videoModal = null;

function createVideoModal() {
    if (document.getElementById('videoEmbedModal')) return;
    
    const modalHTML = `
    <div class="modal fade" id="videoEmbedModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">🎬 Вставка видео с хостинга</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <p class="mb-1 fw-semibold text-body">
                            Вставьте ссылку на видео (YouTube, Rutube и др.). Система автоматически создаст код плеера.
                        </p>
                    </div>
                    
                    <div class="mt-3">
                        <div class="pt-2">
                            <div class="mb-2 fw-bold">Поддерживается:</div>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge text-bg-danger fs-6">YouTube</span>
                                <span class="badge text-bg-primary fs-6">Rutube</span>
                                <span class="badge text-bg-info text-white fs-6">Vimeo</span>
                                <span class="badge text-bg-primary fs-6">VK</span>
                                <span class="badge text-bg-warning fs-6">OK</span>
                                <span class="badge text-bg-info text-white fs-6">Mail.ru</span>
                            </div>
                            
                            <div class="alert alert-info border-0 shadow-sm">
                                <div class="fs-6">
                                    <strong>💡 Для Mail.ru:</strong> копируйте «Код для вставки» (iframe) на сайте видеохостинга и вставляйте его целиком.
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <div class="form-group">
                        <label for="video-url" class="form-label fw-bold">URL видео:</label>
                        <input type="text" class="form-control form-control-lg" id="video-url" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary px-4" id="video-modal-insert">Вставить</button>
                </div>
            </div>
        </div>
    </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    const modalEl = document.getElementById('videoEmbedModal');
    videoModal = new bootstrap.Modal(modalEl);
    
    const urlInput = document.getElementById('video-url');
    const insertBtn = document.getElementById('video-modal-insert');
    
    function checkSupport() {
        const url = urlInput.value.trim();
        insertBtn.disabled = !isUrlSupported(url);
    }
    
    urlInput.addEventListener('input', checkSupport);
    
    window.openVideoModal = function(callback) {
        urlInput.value = '';
        insertBtn.disabled = true;
        
        // Убираем старый обработчик
        const newInsertBtn = document.getElementById('video-modal-insert');
        newInsertBtn.onclick = () => {
            const url = urlInput.value.trim();
            const html = getVideoHtml(url);
            if (html) {
                callback(html);
                videoModal.hide();
            } else {
                alert('Этот видеохостинг не поддерживается');
            }
        };
        
        videoModal.show();
        urlInput.focus();
    };
}

function isUrlSupported(url) {
    const patterns = [
        /youtube\.com|youtu\.be/,
        /rutube\.ru/,
        /vimeo\.com/,
        /vk\.com\/video|vkvideo\.ru\/video/,
        /ok\.ru\/video/,
        /my\.mail\.ru/
    ];
    return patterns.some(p => p.test(url));
}

function getVideoHtml(url) {
    // YouTube
    if (url.includes('youtube.com') || url.includes('youtu.be')) {
        const match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&?#]+)/);
        if (match) {
            return '<p><iframe style="width:100%;height:auto;aspect-ratio:16/9;object-fit:contain;background:#000;border:0" src="https://www.youtube.com/embed/' + match[1] + '" frameborder="0" allowfullscreen></iframe></p>';
        }
    }
    // Rutube
    if (url.includes('rutube.ru')) {
        const match = url.match(/video\/([a-z0-9]+)/i);
        if (match) {
            return '<p><iframe style="width:100%;height:auto;aspect-ratio:16/9;object-fit:contain;background:#000;border:0" src="https://rutube.ru/play/embed/' + match[1] + '" frameborder="0" allowfullscreen></iframe></p>';
        }
    }
    // Vimeo
    if (url.includes('vimeo.com')) {
        const match = url.match(/vimeo\.com\/(\d+)/);
        if (match) {
            return '<p><iframe style="width:100%;height:auto;aspect-ratio:16/9;object-fit:contain;background:#000;border:0" src="https://player.vimeo.com/video/' + match[1] + '" frameborder="0" allowfullscreen></iframe></p>';
        }
    }
    // VK
    if (url.includes('vk.com/video') || url.includes('vkvideo.ru/video')) {
        const match = url.match(/video(-?\d+)_(\d+)/);
        if (match) {
            return '<p><iframe style="width:100%;height:auto;aspect-ratio:16/9;object-fit:contain;background:#000;border:0" src="https://vkvideo.ru/video_ext.php?oid=' + match[1] + '&id=' + match[2] + '&hash=0" frameborder="0" allowfullscreen></iframe></p>';
        }
    }
    // OK
    if (url.includes('ok.ru/video/')) {
        const match = url.match(/video\/(\d+)/);
        if (match) {
            return '<p><iframe style="width:100%;height:auto;aspect-ratio:16/9;object-fit:contain;background:#000;border:0" src="https://ok.ru/videoembed/' + match[1] + '" frameborder="0" allowfullscreen></iframe></p>';
        }
    }
    // Mail.ru (iframe или ссылка)
    if (url.includes('my.mail.ru')) {
        if (url.includes('<iframe')) {
            const srcMatch = url.match(/src=["']([^"']+)["']/);
            if (srcMatch) {
                return '<p><iframe style="width:100%;height:auto;aspect-ratio:16/9;object-fit:contain;background:#000;border:0" src="' + srcMatch[1] + '" frameborder="0" allowfullscreen></iframe></p>';
            }
        }
        const match = url.match(/video\/(.*)\.html/);
        if (match) {
            return '<p><iframe style="width:100%;height:auto;aspect-ratio:16/9;object-fit:contain;background:#000;border:0" src="https://my.mail.ru/video/embed/' + match[1] + '" frameborder="0" allowfullscreen></iframe></p>';
        }
        // Если вставили готовый iframe код целиком
        if (url.includes('iframe')) {
            return '<p>' + url + '</p>';
        }
    }
    return null;
}

tinymce.PluginManager.add('customvideo', function(editor) {
    if (!document.getElementById('videoEmbedModal')) {
        createVideoModal();
    }
    
    editor.addButton('customvideo', {
        title: 'Вставить видео',
        text: '🎬',
        onclick: function() {
            window.openVideoModal(function(html) {
                editor.insertContent(html);
            });
        }
    });
});
JS;
        file_put_contents($js_file, $js_content);
    }
}

// Подключаем Bootstrap (если ещё не подключён)
add_action('admin_enqueue_scripts', 'enqueue_bootstrap_for_admin');
function enqueue_bootstrap_for_admin($hook) {
    if ($hook === 'post.php' || $hook === 'post-new.php') {
        wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
        wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', [], '5.3.0', true);
    }
}

// Разрешаем iframe и стили
add_filter('wp_kses_allowed_html', function($tags, $context) {
    if ($context === 'post') {
        $tags['iframe'] = ['src' => true, 'style' => true, 'frameborder' => true, 'allowfullscreen' => true];
        $tags['p'] = [];
    }
    return $tags;
}, 10, 2);
