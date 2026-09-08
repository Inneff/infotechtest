<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Модель поиска/фильтрации книг.
 */
class BookSearch extends Book
{
    public $authorName;

    public function rules(): array
    {
        return [
            [['year'], 'integer'],
            [['title', 'isbn', 'authorName'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * @param array $params параметры запроса (GET)
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Book::find()
            ->alias('b')
            ->with(['authors']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['title' => SORT_ASC],
                'attributes' => [
                    'title',
                    'year',
                    'isbn',
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'b.title', $this->title])
            ->andFilterWhere(['b.year' => $this->year])
            ->andFilterWhere(['like', 'b.isbn', $this->isbn]);

        if (!empty($this->authorName)) {
            $bookIds = (new Query())
                ->select('ba.book_id')
                ->from('{{%book_author}} ba')
                ->innerJoin('{{%author}} a', 'a.id = ba.author_id')
                ->where(['like', 'a.full_name', $this->authorName]);
            $query->andWhere(['b.id' => $bookIds]);
        }

        return $dataProvider;
    }
}
