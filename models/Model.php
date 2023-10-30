<?php


namespace app\models;


use yii\db\ActiveRecord;

class Model extends \yii\base\Model
{
    public $isNewRecord = true;

    /** @var  ActiveRecord */
    public $_entity = false;

    /**
     * @param ActiveRecord $activeRecord
     */
    public function __construct(ActiveRecord $activeRecord = null)
    {
        if ($activeRecord) {
            $this->_entity     = $activeRecord;
            $this->isNewRecord = $this->_entity->isNewRecord;
        }
        parent::__construct();
    }
}