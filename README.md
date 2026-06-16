# basic-delux-2.1.1-child

Дочерняя тема для Wordpress

## Описание темы

1. В панель редактора добавляется кнопка для кодирования исходящих ссылок в base64
2. Если таблице `<table>` поставить class='adaptive-table', таблица на мобильных будет выводиться в виде карточек, что исключает горизонтальную прокрутку
3. Убирает лишнее из кода, блокирует доступ к rest api и к данным пользователей
4. Убирает комментарии
5. Создает возможность lightbox без плагина
6. Создает связанные посты по категориям и тэгам
7. Создает виджет свежих записей в сайдбаре с иконкой.
8. При вставке картинок из медиатеки создает для них относительные пути от корня сайта
9. Убирает мусор при вставке форматированного текста через буфер в редактор статьи. Оставляет только необходимые тэги.
10. В оплзовательском профиле добавляет русские соцсети и их выводит в блоке про автора в постах и статьях. Для EEAT
11. Добавляет свою кнопку вставки видео через кастомный диалог

## Структура темы

```
basic-delux-2.1.1-child/
├── assets/ 
│ ├── icons/                         # Иконки для соцсетей в блоке автора
│ │ ├── dzen.png                     # Иконка Дзен
│ │ ├── max.png                      # Иконка Max
│ │ ├── tg.png                       # Иконка Telegram
│ │ ├── vk.png                       # Иконка VKontakte
│ │ └── youtube.png                  # Иконка YouTube
│ └── js/ 
│ └── video-strategy.js              # Скрипт для видео-стратегий
├── inc/ 
│ ├── urlspan64/                     # Плагин/компонент для TinyMCE (URL Span)
│ │ ├── langs/ 
│ │ │ └── ru_RU.js                   # Русская локализация
│ │ ├── editor_plugin64.js           # Плагин для TinyMCE редактора
│ │ └── link.png                     # Иконка плагина
│ ├── adaptive-table-script.php      # Адаптивные таблицы
│ ├── author-box.php                 # Блок автора под постом
│ ├── base64-links.php               # Конвертер ссылок в base64
│ ├── breadcrump.php                 # Хлебные крошки
│ ├── cleanup-head.php               # Очистка `<head>` от лишнего
│ ├── disable-jquery.php             # Отключение jQuery
│ ├── disable-json-ld-yoast.php      # Отключение JSON-LD от Yoast
│ ├── footer-widgets.php             # Виджеты в футере
│ ├── has-menu-items.php             # Проверка наличия пунктов меню
│ ├── hide-comments.php              # Скрытие комментариев
│ ├── highlight-menu-item.php        # Подсветка активного пункта меню
│ ├── howto-schema.php               # Микроразметка HowTo
│ ├── keywords_rankmath_enable.php   # Включение ключевых слов RankMath
│ ├── lightbox-modal.php             # Модальное окно Lightbox
│ ├── post-notes.php                 # Заметки к постам
│ ├── recent-posts-widget.php        # Виджет свежих записей
│ ├── related-posts.php              # Похожие записи
│ ├── relative-paths.php             # Относительные пути вместо абсолютных
│ ├── remove-query-strings.php       # Удаление параметров запроса из URL
│ ├── rest-api-security.php          # Безопасность REST API
│ ├── setup.php                      # Настройки и инициализация темы
│ ├── tinymce-clean-paste.php        # Очистка вставляемого текста в TinyMCE
│ ├── tinymce-table-button.php       # Кнопка таблиц в TinyMCE
│ ├── user-profile.php               # Дополнительные поля в профиле пользователя
│ ├── video-embed.php                # Встраивание видео
│ └── xmlrpc-security.php            # Безопасность XML-RPC
├── js/ 
│ └── video-button.js                # Кнопка видео в редакторе
├── footer.php                       # Шаблон подвала
├── functions.php                    # Главный файл функций темы
├── single.php                       # Шаблон одиночной записи
└── style.css                        # Основной файл стилей темы
```

## Установка

1. Скопируйте папку темы в `/wp-content/themes/`.
2. Активируйте тему в админке WordPress.

## Требования

- WordPress 5.0+
- PHP 7.4+
- Тема basic-delux-2.1.1

## Описание основных файлов и папок

### 📁 Папка `assets/`
Статические файлы темы:
- `icons/` - Иконки социальных сетей (Дзен, Telegram, VK, YouTube и др.)
- `js/`    - JavaScript файлы для работы темы

### 📁 Папка `inc/`
Основной функционал темы, разбитый по файлам:

| Категория        | Файлы |
|------------------|-------|
| **Безопасность** | `rest-api-security.php`, `xmlrpc-security.php`, `remove-query-strings.php` |
| **SEO**          | `howto-schema.php`, `disable-json-ld-yoast.php`, `keywords_rankmath_enable.php` |
| **Контент**      | `related-posts.php`, `author-box.php`, `breadcrump.php`, `recent-posts-widget.php` |
| **Редактор**     | `tinymce-clean-paste.php`, `tinymce-table-button.php`, `video-embed.php` |
| **Оптимизация**  | `cleanup-head.php`, `disable-jquery.php`, `relative-paths.php` |
| **Интерфейс**    | `lightbox-modal.php`, `adaptive-table-script.php`, `footer-widgets.php` |
| **Прочее**       | `post-notes.php`, `hide-comments.php`, `user-profile.php`, `base64-links.php` |

### 📄 Основные файлы
- `footer.php` - Подвал сайта
- `functions.php` - Главный файл функций и настроек темы
- `single.php` - Шаблон для одиночных записей
- `style.css` - Основные стили темы

## Автор

Андрей Лисюра https://vk.com/alisyura

## Лицензия

GPL v2 