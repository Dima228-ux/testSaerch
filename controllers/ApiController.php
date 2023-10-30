<?php


namespace app\controllers;


use app\models\Lib\Crypt;
use app\models\Search;
use app\models\SearchForm;
use Yii;
use yii\helpers\Json;
use yii\base\BaseObject;
use yii\rest\ActiveController;

/**
 * Class ApiController
 * @package app\controllers
 */
class ApiController extends BasikApiController
{
    public $modelClass = 'app\models\Search';

    private $tokens = ['dsadadadsadasdadsa', 'dqrdcaeffewfwfwfeqf']; // заглушка для токенов

    /**
     * @throws \Exception
     */
    public function actionFind()
    {
        $token = $this->getParam('token');


        if (!in_array($token, $this->tokens)) {
            echo json_encode(['message:' => 'error token']);
            exit();
        }

        if ($this->isPost()) {
            $search_string = json_decode(\Yii::$app->request->getRawBody(), true)['search_string'];

            if (empty($search_string)) {
                echo json_encode(['message:' => 'error']);
                return;
            }
            $itendity = Crypt::encryptIndeditySearch($token, $search_string);
            $this->postSearch($token, $search_string, $itendity);
        }

        if ($this->isGet()) {
            $this->getSearch($token);
        }

        if ($this->isDelete()) {
            $this->delete($token);
        }
        echo json_encode(['message:' => 'error request']);
        exit();
    }

    /**
     * @param $token //токен поьзователя
     * @param $search_string
     * @param $itendity //индефикатор каждого поискового запроса
     */
    private function postSearch($token, $search_string, $itendity)
    {
        $session = Yii::$app->session;

        if (!$session->isActive) {
            $session->open();
        }

        $model = new SearchForm(new Search());
        $array = $session[$token];
        $is_exist = Search::find()->where(['search_string' => $search_string])->exists();

        if ($is_exist) {
            $id_search = Search::find()->select(['id'])->where(['search_string' => $search_string])->one();

            if (empty($array)) {
                $array[$itendity] = ['itendity' => $itendity, 'id' => $id_search['id']];
                $session[$token] = $array;
                echo json_encode(['message:' => 'success']);
                exit();
            }
            $search[$itendity] = ['itendity' => $itendity, 'id' => $id_search['id']];
            $array = array_merge($array, $search);
            $session[$token] = $array;
            echo json_encode(['message:' => 'success']);
            exit();
        }

        if ($model->preloadSave($search_string)) {
            $id_search = Search::find()->select(['id'])->where(['search_string' => $search_string])->one();

            if (empty($array)) {

                $array[$itendity] = ['itendity' => $itendity, 'id' => $id_search['id']];
                $session[$token] = $array;
                echo json_encode(['message:' => 'success']);
                exit();
            }
            $search[$itendity] = ['itendity' => $itendity, 'id' => $id_search['id']];
            $array = array_merge($array, $search);
            $session[$token] = $array;
            echo json_encode(['message:' => 'success']);
            exit();

        }
        echo json_encode(['message:' => 'empty result']);
        exit();
    }

    /**
     * @param $token
     */
    private function delete($token)
    {
        $session = Yii::$app->session;
        $itendity = $this->getParam('in');
        if (empty($itendity)) {
            echo json_encode(['message:' => 'error itendity ']);
            exit();
        }

        if ($session->isActive) {
            unset($_SESSION[$token][$itendity]);
            echo json_encode(['message:' => 'success delete']);
            exit();
        }
    }

    /**
     * @param $token
     */
    private function getSearch($token)
    {
        $session = Yii::$app->session;
        if ($session->isActive) {
            $result = [];
            $id_searchs = $session[$token];

            if (empty($id_searchs)) {
                echo json_encode(['message:' => 'error no search']);
                exit;
            }
            foreach ($id_searchs as $id) {

                $this->getSearches($result, $id);
            }

            echo json_encode($result);
            exit;
        }
        exit;
    }

    /**
     * @param $result
     * @param $id
     */
    private function getSearches(&$result, $id)
    {
        $is_exist = Search::find()->where(['id' => $id['id']])->exists();

        if (!$is_exist) {
            return;
        }

        $search = Search::find()->where(['id' => $id['id']])->one();
        $search['json_result'] = json_decode($search['json_result'], true);
            foreach ($search['json_result']['items'] as $item) {
                $result[$id['itendity']][$search['search_string']][] = [
                    'name' => $item['name'],
                    'auth' => $item["owner"]['login'],
                    'stargazers' => $item["stargazers_count"],
                    'watchers_count' => $item['watchers_count'],
                ];

            }

    }
}