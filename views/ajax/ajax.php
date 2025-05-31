<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Зависимые выпадающие списки (JSON + GET)</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        select, #results {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        #results {
            min-height: 50px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Зависимые выпадающие списки (JSON + GET)</h1>
    
    <label for="firstSelect">Выберите категорию:</label>
    <select id="firstSelect" name="firstSelect">
        <option value="">-- Выберите категорию --</option>
        <option value="fruits">Фрукты</option>
        <option value="vegetables">Овощи</option>
        <option value="drinks">Напитки</option>
    </select>
    
    <label for="secondSelect">Выберите подкатегорию:</label>
    <select id="secondSelect" name="secondSelect" disabled>
        <option value="">-- Сначала выберите категорию --</option>
    </select>
    
    <div id="results">
        Выберите значения в выпадающих списках, чтобы увидеть результат
    </div>

    <script>
        $(document).ready(function() {
            // Обработчик изменения первого выпадающего списка
            $('#firstSelect').change(function() {
                var selectedCategory = $(this).val();
                
                if (selectedCategory) {
                    // Активируем второй список
                    $('#secondSelect').prop('disabled', false);
                    
                    // Отправляем GET-запрос для получения подкатегорий
                    $.ajax({
                        url: 'ajax/get_subcategories',
                        type: 'GET',
                        data: { category: selectedCategory },
                        dataType: 'json',
                        success: function(data) {
                            var options = '<option value="">-- Выберите подкатегорию --</option>';
                            $.each(data.items, function(index, item) {
                                options += '<option value="' + item.value + '">' + item.name + '</option>';
                            });
                            $('#secondSelect').html(options);
                            updateResults();
                        },
                        error: function() {
                            $('#secondSelect').html('<option value="">Ошибка загрузки данных</option>');
                        }
                    });
                } else {
                    // Деактивируем второй список, если категория не выбрана
                    $('#secondSelect').prop('disabled', true).html('<option value="">-- Сначала выберите категорию --</option>');
                    updateResults();
                }
            });
            
            // Обработчик изменения второго выпадающего списка
            $('#secondSelect').change(function() {
                updateResults();
            });
            
            // Функция для обновления результатов
            function updateResults() {
                var category = $('#firstSelect').val();
                var subcategory = $('#secondSelect').val();
                
                if (category && subcategory) {
                    $.ajax({
                        url: 'ajax/get_results',
                        type: 'GET',
                        data: { 
                            category: category,
                            subcategory: subcategory 
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('#results').html(
                                '<h3>' + data.title + '</h3>' +
                                '<p><strong>Категория:</strong> ' + data.category + '</p>' +
                                '<p><strong>Подкатегория:</strong> ' + data.subcategory + '</p>' +
                                '<p>' + data.message + '</p>'
                            );
                        },
                        error: function() {
                            $('#results').html('Ошибка при получении результатов');
                        }
                    });
                } else {
                    $('#results').html('Выберите значения в выпадающих списках, чтобы увидеть результат');
                }
            }
        });
    </script>
</body>
</html>