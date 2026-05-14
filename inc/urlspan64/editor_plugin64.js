(function() {
    tinymce.create('tinymce.plugins.urlspan64', {
        init : function(ed, url) {
            ed.addButton('urlspan64', {
                title : 'Сделать base64-ссылку',
                text : '🔒',
                //icon : 'admin-links',  // иконка из набора WordPress
                //image : url + '/link.png',  // вместо text //https://ваш-сайт/wp-content/uploads/link.png',
                onclick : function() {
                    // Получаем выделенный текст
                    var selectedText = ed.selection.getContent({format: 'text'});
                    var selectedHtml = ed.selection.getContent();
                    
                    // Если ничего не выделено, показываем предупреждение
                    if (!selectedText) {
                        alert('Выделите текст, который хотите сделать ссылкой');
                        return;
                    }
                    
                    // Запрашиваем URL
                    var url = prompt('Введите URL:', 'https://');
                    if (url && url.trim() !== '') {
                        // Создаём ссылку с выделенным текстом
                        var linkHtml = '[urlspan64]' + url + '|' + selectedHtml + '[/urlspan64]';
                        ed.execCommand('mceInsertContent', false, linkHtml);
                    }
                }
            });
        },
        getInfo : function() {
            return {
                longname : 'Persona Nexus Exeuntis cum Cryptographia Basis 64',
                author : 'Andreas Lysura',
                version : '1.0'
            };
        }
    });
    tinymce.PluginManager.add('urlspan64', tinymce.plugins.urlspan64);
})();