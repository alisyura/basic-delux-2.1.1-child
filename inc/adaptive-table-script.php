<?php
if (!defined('ABSPATH')) {
    exit;
}

// Добавляем скрипт для автоматической простановки data-label
function adaptive_table_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Находим все таблицы с классом adaptive-table
        var containers = document.querySelectorAll('.adaptive-table');
        
        containers.forEach(function(container) {
            var table = container.querySelector('table');
            if (!table) return;
            
            // Собираем заголовки
            var headers = [];
            var headerCells = table.querySelectorAll('th');
            headerCells.forEach(function(th) {
                headers.push(th.innerText.trim());
            });
            
            // Если заголовков нет, пробуем взять из первой строки (если нет thead)
            if (headers.length === 0) {
                var firstRow = table.querySelector('tr');
                if (firstRow) {
                    var firstRowCells = firstRow.querySelectorAll('td');
                    if (firstRowCells.length > 0) {
                        // Это данные, а не заголовки — выходим
                        return;
                    }
                }
            }
            
            // Проставляем data-label каждой ячейке
            var rows = table.querySelectorAll('tbody tr');
            rows.forEach(function(row) {
                var cells = row.querySelectorAll('td');
                cells.forEach(function(cell, index) {
                    if (headers[index]) {
                        cell.setAttribute('data-label', headers[index]);
                    }
                });
            });
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'adaptive_table_script');
