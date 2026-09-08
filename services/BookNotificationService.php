<?php

namespace app\services;

use app\models\Book;
use app\models\Subscription;

/**
 * Рассылка уведомлений подписчикам о новой книге.
 */
class BookNotificationService
{
    /**
     * Отправляет SMS-уведомления подписчикам всех авторов книги.
     */
    public static function notifyNewBook(Book $book): void
    {
        $notifier = new SmsNotifier();

        foreach ($book->getAuthors()->all() as $author) {
            $subscriptions = Subscription::find()
                ->where(['author_id' => $author->id])
                ->all();

            $message = sprintf('Новая книга «%s» от %s', $book->title, $author->full_name);

            foreach ($subscriptions as $subscription) {
                $notifier->send($subscription->phone, $message);
            }
        }
    }
}
