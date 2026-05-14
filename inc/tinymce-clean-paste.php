<?php
if (!defined('ABSPATH')) {
    exit;
}

// очищаем форматирование
function force_clean_paste_in_editor() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        if (typeof tinymce !== 'undefined') {
            
            function transformTableToPricingFormat(table) {
                var rows = table.querySelectorAll('tr');
                if (rows.length === 0) return table;
                
                var headerCells = [];
                var startRowIndex = 0;
                
                // 1. Определяем количество столбцов (максимальное во всей таблице)
                var maxCols = 0;
                for (var r = 0; r < rows.length; r++) {
                    var cellsCount = rows[r].querySelectorAll('td, th').length;
                    maxCols = Math.max(maxCols, cellsCount);
                }
                
                // 2. Определяем заголовки
                var firstRow = rows[0];
                var thCells = firstRow.querySelectorAll('th');
                
                if (thCells.length > 0) {
                    // Есть th - используем их
                    for (var k = 0; k < maxCols; k++) {
                        if (k < thCells.length) {
                            headerCells.push(thCells[k].innerText.trim() || 'Столбец ' + (k + 1));
                        } else {
                            headerCells.push('Столбец ' + (k + 1));
                        }
                    }
                    startRowIndex = 1;
                } else {
                    var firstRowCells = firstRow.querySelectorAll('td');
                    var hasHeaderStyle = false;
                    
                    // Проверяем жирный шрифт в первой строке
                    for (var c = 0; c < firstRowCells.length; c++) {
                        if (firstRowCells[c].querySelector('strong, b')) {
                            hasHeaderStyle = true;
                            break;
                        }
                    }
                    
                    if (hasHeaderStyle && firstRowCells.length > 0) {
                        // Первая строка - заголовок
                        for (var idx = 0; idx < maxCols; idx++) {
                            if (idx < firstRowCells.length) {
                                headerCells.push(firstRowCells[idx].innerText.trim() || 'Столбец ' + (idx + 1));
                            } else {
                                headerCells.push('Столбец ' + (idx + 1));
                            }
                        }
                        startRowIndex = 1;
                    } else {
                        // Нет заголовков - создаём по максимальному количеству столбцов
                        for (var num = 0; num < maxCols; num++) {
                            headerCells.push('Столбец ' + (num + 1));
                        }
                        startRowIndex = 0;
                    }
                }
                
                // 3. Создаём новую таблицу
                var newTable = document.createElement('table');
                newTable.className = 'adaptive-table';
                
                // Создаём thead
                var thead = document.createElement('thead');
                var headerRow = document.createElement('tr');
                
                for (var i = 0; i < headerCells.length; i++) {
                    var th = document.createElement('th');
                    th.textContent = headerCells[i];
                    headerRow.appendChild(th);
                }
                thead.appendChild(headerRow);
                newTable.appendChild(thead);
                
                // Создаём tbody
                var tbody = document.createElement('tbody');
                
                for (var j = startRowIndex; j < rows.length; j++) {
                    var originalRow = rows[j];
                    if (!originalRow) continue;
                    
                    var cells = originalRow.querySelectorAll('td, th');
                    var newRow = document.createElement('tr');
                    
                    // Проходим по всем столбцам (от 0 до maxCols-1)
                    for (var cellIdx = 0; cellIdx < maxCols; cellIdx++) {
                        var td = document.createElement('td');
                        
                        // Добавляем data-label с названием столбца
                        var label = headerCells[cellIdx];
                        td.setAttribute('data-label', label);
                        
                        // Если ячейка существует - копируем содержимое
                        if (cellIdx < cells.length) {
                            var originalCell = cells[cellIdx];
                            var cellContent = originalCell.innerHTML;
                            cellContent = cleanTableCellContent(cellContent);
                            td.innerHTML = cellContent;
                        } else {
                            // Если ячейки нет - ставим пусто
                            td.innerHTML = '';
                        }
                        
                        newRow.appendChild(td);
                    }
                    tbody.appendChild(newRow);
                }
                
                newTable.appendChild(tbody);
                return newTable;
            }
            
            function cleanTableCellContent(html) {
                var temp = document.createElement('div');
                temp.innerHTML = html;
                
                var allowedInline = ['strong', 'b', 'em', 'i', 'a', 'span', 'br'];
                var elements = temp.querySelectorAll('*');
                
                for (var i = elements.length - 1; i >= 0; i--) {
                    var el = elements[i];
                    var tagName = el.tagName.toLowerCase();
                    
                    if (allowedInline.indexOf(tagName) === -1) {
                        var textNode = document.createTextNode(el.innerText || el.textContent);
                        el.parentNode.replaceChild(textNode, el);
                    } else if (tagName === 'a') {
                        var href = el.getAttribute('href');
                        var linkText = el.innerText || el.textContent;
                        var newLink = document.createElement('a');
                        if (href) newLink.setAttribute('href', href);
                        newLink.textContent = linkText;
                        el.parentNode.replaceChild(newLink, el);
                    } else {
                        var attrs = el.attributes;
                        for (var j = attrs.length - 1; j >= 0; j--) {
                            var attrName = attrs[j].name;
                            if (attrName !== 'href') {
                                el.removeAttribute(attrName);
                            }
                        }
                    }
                }
                
                return temp.innerHTML;
            }
            
            function findAndTransformTables(html) {
                var tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                var tables = tempDiv.querySelectorAll('table');
                for (var t = 0; t < tables.length; t++) {
                    var oldTable = tables[t];
                    var newTable = transformTableToPricingFormat(oldTable);
                    oldTable.parentNode.replaceChild(newTable, oldTable);
                }
                
                return tempDiv.innerHTML;
            }
            
            function cleanPastedContent(html) {
                // Удаляем CSS-стили и комментарии
                html = html.replace(/<style[^>]*>[\s\S]*?<\/style>/gi, '');
                html = html.replace(/<!--[\s\S]*?-->/g, '');
                
                var temp = document.createElement('div');
                temp.innerHTML = html;
                
                var allowedTags = ['a', 'strong', 'b', 'em', 'i', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ul', 'ol', 'li', 'p', 'br', 'span'];
                
                var allElements = temp.querySelectorAll('*');
                for (var i = allElements.length - 1; i >= 0; i--) {
                    var el = allElements[i];
                    var tagName = el.tagName.toLowerCase();
                    
                    if (tagName === 'table' || tagName === 'tr' || tagName === 'td' || tagName === 'th' || 
                        tagName === 'thead' || tagName === 'tbody' || tagName === 'tfoot') {
                        continue;
                    }
                    
                    if (allowedTags.indexOf(tagName) === -1) {
                        var textNode = document.createTextNode(el.innerText || el.textContent);
                        el.parentNode.replaceChild(textNode, el);
                    } else {
                        var attrs = el.attributes;
                        for (var j = attrs.length - 1; j >= 0; j--) {
                            el.removeAttribute(attrs[j].name);
                        }
                    }
                }
                
                var cleanHtml = temp.innerHTML;
                cleanHtml = findAndTransformTables(cleanHtml);
                cleanHtml = cleanHtml.replace(/&amp;nbsp;/g, ' ');
                cleanHtml = cleanHtml.replace(/&nbsp;/g, ' ');
                
                return cleanHtml;
            }
            
            function handleCleanPaste(e) {
                var editor = this;
                var clipboard = e.clipboardData;
                var html = clipboard.getData('text/html');
                var text = clipboard.getData('text/plain');
                
                e.preventDefault();
                
                var cleanContent = '';
                if (html) {
                    cleanContent = cleanPastedContent(html);
                } else if (text) {
                    cleanContent = text
                        .replace(/&amp;nbsp;/g, ' ')
                        .replace(/&nbsp;/g, ' ')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/\n\n/g, '</p><p>')
                        .replace(/\n/g, '<br>');
                    
                    cleanContent = '<p>' + cleanContent + '</p>';
                }
                
                setTimeout(function() {
                    editor.focus();
                    editor.execCommand('mceInsertContent', false, cleanContent);
                }, 10);
            }
            
            function bindPasteHandler(editor) {
                if (editor && !editor._hasCleanPaste) {
                    editor.on('paste', handleCleanPaste);
                    editor._hasCleanPaste = true;
                }
            }
            
            if (tinymce.editors) {
                for (var i = 0; i < tinymce.editors.length; i++) {
                    bindPasteHandler(tinymce.editors[i]);
                }
            }
            
            tinymce.on('AddEditor', function(e) {
                bindPasteHandler(e.editor);
            });
        }
    });
    </script>
    <?php
}
add_action('admin_footer', 'force_clean_paste_in_editor');