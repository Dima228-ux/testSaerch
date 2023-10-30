<?php


namespace app\controllers;


use app\models\Search;
use app\models\SearchForm;
use yii\base\BaseObject;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Html;

/**
 * Class SearchController
 * @package app\controllers
 */
class SearchController extends BasickController
{
    /**
     * @return string
     * @throws \Exception
     */
    public function actionSearchProject()
    {
        $search = '';
        $model = new SearchForm(new Search());

        if ($this->isPost()) {
            $search_string = $this->post('search_string');
            $is_exist = Search::find()->where(['search_string' => $search_string])->exists();
            if ($is_exist) {
                $search = Search::find()->where(['search_string' => $search_string])->one();
                $search['json_result'] = json_decode($search['json_result'], true);

                $this->setFlash('success', 'You search successfully find');
                return $this->render('index', ['search' => $search]);
            }

            if ($model->preloadSave($search_string)) {
                $search = Search::find()->where(['search_string' => $search_string])->one();
                $search['json_result'] = json_decode($search['json_result'], true);
                $this->setFlash('success', 'You search successfully find');
                return $this->render('index', ['search' => $search]);
            }
            $this->setFlash('error', 'You search empty find');
            return $this->render('index', ['search' => $search]);
        }

        return $this->render('index', ['search' => $search, 'model' => $model]);
    }

}