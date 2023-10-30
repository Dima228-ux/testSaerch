<?php


namespace app\models;


use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Class Search
 * @package app\models
 *  @property int $id [int(11)]
 * @property string $search_string [text]
 * @property Json $json_result  [json]
 */
class Search extends  ActiveRecord
{
    public static function tableName(): string
    {
        return 'search';
    }
}