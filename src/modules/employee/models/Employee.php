<?php

namespace app\modules\employee\models;

use app\modules\students\models\CuratorGroup;
use app\modules\students\models\Group;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "employee".
 * @property integer $id
 * @property integer $is_in_education
 * @property integer $position_id
 * @property integer $category_id
 * @property integer $type
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property integer $gender
 * @property integer $cyclic_commission_id
 * @property string $birth_date
 * @property string $passport
 * @property string $passport_issued_by
 */
class Employee extends ActiveRecord
{
    /** Types */
    const TYPE_NONE = 0;
    const TYPE_TEACHER = 1;
    const TYPE_OTHER = 2;

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%employee}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['id', 'position_id', 'category_id', 'is_in_education', 'gender', 'type', 'cyclic_commission_id'], 'integer'],
            [['last_name', 'first_name', 'middle_name', 'position_id', 'is_in_education',
                'gender', 'passport', 'birth_date', 'passport_issued_by'], 'required'],
            [['birth_date', 'cyclic_commission_id', 'passport', 'passport_issued_by'], 'safe'],
            [['passport', 'id'], 'unique'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'is_in_education' => Yii::t('app', 'Do teach?'),
            'position_id' => Yii::t('app', 'Position'),
            'category_id' => Yii::t('app', 'Category'),
            'type' => Yii::t('app', 'Type'),
            'first_name' => Yii::t('app', 'First Name'),
            'middle_name' => Yii::t('app', 'Middle Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'gender' => Yii::t('app', 'Gender'),
            'cyclic_commission_id' => Yii::t('app', 'Cyclic commission'),
            'birth_date' => Yii::t('app', 'Birth Day'),
            'passport' => Yii::t('app', 'Passport Code'),
            'passport_issued_by' => Yii::t('app', 'Passport issued'),
        ];
    }

    public function getFullName()
    {
        return trim("$this->last_name $this->first_name $this->middle_name");
    }

    public function getNameWithInitials()
    {
        $firstNameInitial = mb_substr($this->first_name, 0, 1, 'UTF-8');
        $middleNameInitial = mb_substr($this->middle_name, 0, 1, 'UTF-8');
        return trim("$this->last_name {$firstNameInitial}. {$middleNameInitial}.");
    }

    public function getShortName()
    {
        return $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
    }

    public static function getTypes()
    {
        return [
            self::TYPE_NONE => Yii::t('employee', 'Nobody'),
            self::TYPE_OTHER => Yii::t('employee', 'Another employee'),
            self::TYPE_TEACHER => Yii::t('employee', 'Teacher'),
        ];
    }

    public function getGenderName()
    {
        return $this->gender ? Yii::t('app', 'Female') : Yii::t('app', 'Male');
    }

    public function getIsInEducationName()
    {
        return $this->is_in_education ? Yii::t('app', 'Not take part in education') : Yii::t('app', 'Take part in education');
    }

    public static function getAllTeacher()
    {
        $query = self::find();
        $query->andWhere(['is_in_education' => 0]);
        $query->addOrderBy(['first_name' => SORT_ASC, 'middle_name' => SORT_ASC, 'last_name' => SORT_ASC]);
        return $query->all();
    }

    public static function getAllTeacherList()
    {
        return ArrayHelper::map(self::getAllTeacher(), 'id', 'fullName');
    }

    public function getGroupArray()
    {
        /**
         * @var $listRecord CuratorGroup[];
         */
        $listRecord = CuratorGroup::find()->andWhere(['teacher_id' => $this->id])->orderBy('id ASC')->all();
        $listGroup = [];
//        var_dump($listRecord);
        foreach ($listRecord as $item) {
            switch ($item->type) {
                case 1: {
                    array_push($listGroup, $item->group_id);
                    break;
                }
                case 2: {
                    if (($key = array_search($item->group_id, $listGroup)) !== false) {
                        unset($listGroup[$key]);
                    }

                }
            }
        }
        return $listGroup;
    }

    public function getGroups()
    {
        return Group::find()->where(['id' => $this->getGroupArray()])->all();
    }

    public function getLink()
    {
        return Html::a($this->getFullName(), Url::to(['/employee/default/view', 'id' => $this->id]));
    }
}
