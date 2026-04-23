<?php
/**
 * Задание 4: Валидация формы с использованием Cookies
 * 
 * Сервер: kubsu-dev.ru / u82255
 */

header('Content-Type: text/html; charset=UTF-8');

// Конфигурация полей
$fields = [
    'fio' => [
        'required' => true,
        'label' => 'ФИО',
        'pattern' => '/^[а-яА-Яa-zA-Z\s\-]+$/u',
        'allowed' => 'буквы, пробелы и дефисы'
    ],
    'email' => [
        'required' => true,
        'label' => 'Email',
        'pattern' => '/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
        'allowed' => 'формат email (user@example.com)'
    ],
    'phone' => [
        'required' => true,
        'label' => 'Телефон',
        'pattern' => '/^[\d\+\-\(\)\s]{10,20}$/',
        'allowed' => 'цифры, +, -, (, ), пробелы'
    ],
    'birthdate' => [
        'required' => true,
        'label' => 'Дата рождения',
        'pattern' => '/^\d{4}-\d{2}-\d{2}$/',
        'allowed' => 'формат ГГГГ-ММ-ДД'
    ],
    'gender' => [
        'required' => true,
        'label' => 'Пол',
        'pattern' => '/^(male|female)$/',
        'allowed' => 'male или female'
    ]
];

// GET-запрос: показываем форму
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $messages = [];
    $errors = [];
    $values = [];

    // Проверяем куку успеха
    if (!empty($_COOKIE['save_success'])) {
        setcookie('save_success', '', time() - 3600, '/');
        $messages[] = '<div class="message success">✅ Данные успешно сохранены в Cookies на год!</div>';
    }

    // Загружаем ошибки из cookies и удаляем их
    foreach ($fields as $field => $config) {
        $errors[$field] = !empty($_COOKIE[$field . '_error']);
        if ($errors[$field]) {
            setcookie($field . '_error', '', time() - 3600, '/');
            $required_text = $config['required'] ? 'обязательное поле' : 'поле';
            $messages[] = '<div class="message error">❌ Ошибка в поле "' . $config['label'] . '": ' 
                        . $required_text . '. Допустимо: ' . $config['allowed'] . '.</div>';
        }
    }

    // Загружаем значения полей из cookies
    foreach ($fields as $field => $config) {
        $values[$field] = $_COOKIE[$field . '_value'] ?? '';
    }

    include 'form.php';
} 
// POST-запрос: обрабатываем данные
else {
    $has_errors = false;

    foreach ($fields as $field => $config) {
        $value = trim($_POST[$field] ?? '');

        if ($config['required'] && empty($value)) {
            setcookie($field . '_error', '1', 0, '/'); // Кука до конца сессии
            $has_errors = true;
        } elseif (!empty($value) && !preg_match($config['pattern'], $value)) {
            setcookie($field . '_error', '1', 0, '/');
            $has_errors = true;
        }

        // Временное сохранение значения (30 дней)
        setcookie($field . '_value', $value, time() + 30 * 24 * 60 * 60, '/');
    }

    if ($has_errors) {
        header('Location: index.php');
        exit();
    }

    // Успех: удаляем ошибки и сохраняем данные на год
    foreach ($fields as $field => $config) {
        setcookie($field . '_error', '', time() - 3600, '/');
        if (!empty($_POST[$field])) {
            setcookie($field . '_value', $_POST[$field], time() + 365 * 24 * 60 * 60, '/');
        }
    }

    setcookie('save_success', '1', time() + 365 * 24 * 60 * 60, '/');
    header('Location: index.php');
    exit();
}