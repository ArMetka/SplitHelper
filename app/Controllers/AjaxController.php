<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Secure;
use App\View;

class AjaxController
{
    #[Get('/ajax')]
    #[Secure]
    public function index(): View
    {
        return View::make('ajax/ajax');
    }

    #[Get('/ajax/get_results')]
    #[Secure]
    public function get_results()
    {
        header('Content-Type: application/json; charset=utf-8');

        // Получаем параметры из GET-запроса
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : null;

        // Маппинг категорий для отображения
        $categoryNames = [
            'fruits' => 'Фрукты',
            'vegetables' => 'Овощи',
            'drinks' => 'Напитки'
        ];

        // Маппинг подкатегорий для отображения
        $subcategoryNames = [
            'apples' => 'Яблоки',
            'bananas' => 'Бананы',
            'oranges' => 'Апельсины',
            'pears' => 'Груши',
            'carrots' => 'Морковь',
            'potatoes' => 'Картофель',
            'cucumbers' => 'Огурцы',
            'tomatoes' => 'Помидоры',
            'juices' => 'Соки',
            'sodas' => 'Газированные напитки',
            'tea' => 'Чай',
            'coffee' => 'Кофе'
        ];

        if ($category && $subcategory) {
            $categoryDisplay = isset($categoryNames[$category]) ? $categoryNames[$category] : $category;
            $subcategoryDisplay = isset($subcategoryNames[$subcategory]) ? $subcategoryNames[$subcategory] : $subcategory;
            
            echo json_encode([
                'status' => 'success',
                'title' => 'Результаты выбора',
                'category' => $categoryDisplay,
                'subcategory' => $subcategoryDisplay,
                'message' => "Вы выбрали $subcategoryDisplay из категории $categoryDisplay"
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Необходимо выбрать значения в обоих списках'
            ]);
        }
    }

    #[Get('/ajax/get_subcategories')]
    #[Secure]
    public function get_subcategories()
    {
        header('Content-Type: application/json; charset=utf-8');

        // Массив с данными (в реальном приложении можно получать из БД)
        $data = [
            'fruits' => [
                ['value' => 'apples', 'name' => 'Яблоки'],
                ['value' => 'bananas', 'name' => 'Бананы'],
                ['value' => 'oranges', 'name' => 'Апельсины'],
                ['value' => 'pears', 'name' => 'Груши']
            ],
            'vegetables' => [
                ['value' => 'carrots', 'name' => 'Морковь'],
                ['value' => 'potatoes', 'name' => 'Картофель'],
                ['value' => 'cucumbers', 'name' => 'Огурцы'],
                ['value' => 'tomatoes', 'name' => 'Помидоры']
            ],
            'drinks' => [
                ['value' => 'juices', 'name' => 'Соки'],
                ['value' => 'sodas', 'name' => 'Газированные напитки'],
                ['value' => 'tea', 'name' => 'Чай'],
                ['value' => 'coffee', 'name' => 'Кофе']
            ]
        ];

        // Получаем параметр из GET-запроса
        $category = isset($_GET['category']) ? $_GET['category'] : null;

        if ($category && array_key_exists($category, $data)) {
            echo json_encode([
                'status' => 'success',
                'items' => $data[$category]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Категория не найдена'
            ]);
        }
    }
}