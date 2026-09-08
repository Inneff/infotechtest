<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Каталог книг',

    // Настройки SMS-шлюза SMSPILOT (https://smspilot.ru/apikey.php).
    // apiKey — ключ-эмулятор: реальная отправка SMS не происходит.
    'smspilot' => [
        'apiKey' => 'XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ',
        'from' => 'KATALOG',
    ],
];
