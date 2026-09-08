<?php

namespace app\models;

use yii\base\Model;

/**
 * Форма отчёта "ТОП-10 авторов по количеству книг за год".
 */
class ReportForm extends Model
{
    public $year;

    public function rules(): array
    {
        return [
            [['year'], 'required'],
            [['year'], 'integer', 'min' => 1000, 'max' => 9999],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'year' => 'Год',
        ];
    }

    /**
     * Возвращает топ авторов за указанный год.
     */
    public function getTopAuthors(int $limit = 10): array
    {
        return Author::topByYear((int) $this->year, $limit);
    }
}
