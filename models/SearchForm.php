<?php


namespace app\models;

/**
 * Class SearchForm
 * @package app\models
 */
class SearchForm extends Model
{

    public const API_SEARCH = 'https://api.github.com/search/repositories?q=';

    public $search_string;
    public $json_result;


    /**
     *  constructor.
     *
     * @param Search $search
     */

    public function __construct(Search $search)
    {
        parent::__construct($search);
        $this->setAttributes($this->_entity->getAttributes(), false);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['search_string', 'json_result'], 'required'],
            [['search_string'], 'unique', 'targetAttribute' => 'search_string', 'targetClass' => Search::class,
                'message' => 'This {attribute} is already exists',
                'when' => function ($model) {
                    $count = Search::find()->where(['search_string' => $model->search_string])->count();

                    if ($count > 1 && !$this->isNewRecord) {
                        return true;
                    } elseif (Search::find()->where(['search_string' => $model->search_string])->exists() && $this->isNewRecord) {
                        return true;
                    }
                    return false;
                }
            ],
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var Search $search */
        $search = $this->_entity;
        $search->search_string = $this->search_string;
        $search->json_result = $this->json_result;

        if ($search->save()) {
            return true;
        }

        return false;
    }

    /**
     * @param $search_string
     * @return bool
     */
    public function preloadSave($search_string)
    {
        if (empty(trim($search_string))) {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, self::API_SEARCH . $search_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: https://api.github.com/meta'
        ));
        $result = curl_exec($ch);
        curl_close($ch);
        $tr=json_decode($result,true);

        if (empty(trim($result))||!json_decode($result,true)['incomplete_results']) {
            return false;
        }

        $this->search_string=$search_string;
        $this->json_result=$result;

        return $this->save();
    }
}