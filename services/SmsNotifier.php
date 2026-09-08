<?php

namespace app\services;

use Yii;

/**
 * Отправка SMS через шлюз SMSPILOT (https://smspilot.ru/apikey.php).
 *
 * Для тестирования используется ключ-эмулятор: реальная отправка SMS не происходит.
 */
class SmsNotifier
{
    public const API_URL = 'https://smspilot.ru/api.php';

    /**
     * Отправляет SMS на номер телефона.
     *
     * @param string $phone номер в международном формате (7XXXXXXXXXX)
     * @param string $message текст сообщения
     * @return bool успешность отправки
     */
    public function send(string $phone, string $message): bool
    {
        $params = Yii::$app->params['smspilot'];

        $url = self::API_URL . '?' . http_build_query([
            'send' => $message,
            'to' => $phone,
            'from' => $params['from'] ?? null,
            'apikey' => $params['apiKey'],
            'format' => 'json',
        ]);

        try {
            $response = $this->request($url);
        } catch (\Throwable $e) {
            Yii::warning('SMS request failed: ' . $e->getMessage(), 'sms');
            return false;
        }

        if ($response === false) {
            Yii::warning('SMS request returned no response.', 'sms');
            return false;
        }

        $data = json_decode($response, true);

        if (!is_array($data)) {
            Yii::warning('SMS unexpected response: ' . $response, 'sms');
            return false;
        }

        if (isset($data['error'])) {
            Yii::warning(
                'SMS error: ' . ($data['error']['description_ru'] ?? $data['error']['description'] ?? 'unknown'),
                'sms'
            );
            return false;
        }

        return true;
    }

    /**
     * Выполняет HTTP GET-запрос. Возвращает тело ответа либо false.
     *
     * @return string|false
     */
    private function request(string $url)
    {
        if (!function_exists('curl_init')) {
            return @file_get_contents($url);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
