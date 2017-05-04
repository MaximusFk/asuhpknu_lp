<?php
namespace app\modules\timetable\models\timetables;

use yii\db\ActiveRecord;
use Yii;

use app\modules\directories\models\subject\Subject;
use app\modules\directories\models\audience\Audience;
use app\modules\employee\models\Employee;
use app\modules\students\models\Group;
/**
 * Модель таблицы timetable. Описывает единицу расписания
 *
 * 
 */
class Timetable extends ActiveRecord {
    
    public static function tableName() {
        return 'timetables';
    }
    
    public static function getWeek5Days() {
        return ['Mon','Tue','Wed','Thu','Fri'];
    }
    
    public static function getWeek7Days() {
        return ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    }
    
    public static function getWeekDayNum($weekDay) {
        return date('N', strtotime($weekDay));
    }

    public static function getWeek5DaysT() {
        return [
            ['name' => 'Mon', 'text' => Yii::t('app', 'Mon')],
            ['name' => 'Tue', 'text' => Yii::t('app', 'Tue')],
            ['name' => 'Wed', 'text' => Yii::t('app', 'Wed')],
            ['name' => 'Thu', 'text' => Yii::t('app', 'Thu')],
            ['name' => 'Fri', 'text' => Yii::t('app', 'Fri')]
        ];
    }
    
    public static function getWeek7DaysT() {
        return [
            ['name' => 'Mon', 'text' => Yii::t('app', 'Mon')],
            ['name' => 'Tue', 'text' => Yii::t('app', 'Tue')],
            ['name' => 'Wed', 'text' => Yii::t('app', 'Wed')],
            ['name' => 'Thu', 'text' => Yii::t('app', 'Thu')],
            ['name' => 'Fri', 'text' => Yii::t('app', 'Fri')],
            ['name' => 'Sat', 'text' => Yii::t('app', 'Sat')],
            ['name' => 'Sun', 'text' => Yii::t('app', 'Sun')]
        ];
    }
    
    /*
     * parameter - Number of month
     * return - Semester number (1 or 2)
     */
    public static function getSemester($monthNum) {
        return $monthNum >= 8 ? 1 : 2;
    }
    
    public static function getSemesterRoman($month) {
        if(!is_numeric($month)) {
            $month = self::getSemester($month);
        }
        if($month === 1) {
            return 'I';
        }
        else if($month === 2) {
            return 'II';
        }
        else {
            return '';
        }
    }
    
    public static function getCurrentStudyYear() {
        return self::getSemester(date('m')) === 1 ? date('Y') : date('Y') - 1;
    }

    public static function explodeToSortedArray($timetables) {
        foreach ($timetables as $timetable) {
            $result[$timetable->pair_num][$timetable->day_week] = $timetable;
        }
        foreach ($result as &$times) {
            uksort($times, function ($a, $b) {
                $fdn = self::getWeekDayNum($a);
                $sdn = self::getWeekDayNum($b);
                return $fdn !== $sdn ? $fdn < $sdn ? -1 : 1 : 0;
            });
        }
        return $result;
    }
    
    public static function getSubject($id) {
        return Subject::findOne($id);
    }
    
    public static function getAudience($id) {
        return Audience::findOne($id);
    }
    
    public static function getGroup($id) {
        return Group::findOne($id);
    }
    
    public static function getTimetablesForGroup($group_id) {
        return self::find('year', 'semester')->where(['group_id' => $group_id])->indexBy('year')->all();
    }

        public function getSubjectName() {
        $ob = Subject::findOne($this->subject_id);
        return $ob && !is_array($ob) ? $ob->title : '';
    }
    
    public function getAudienceNumber() {
        $ob = Audience::findOne($this->audience_id);
        return $ob && !is_array($ob) ? $ob->number : '';
    }
    
    function get_enum_values($table, $column) {
        $result = $this->findBySql("SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'")->all();
        $type = $result ? $result->fetch_assoc()['Type'] : null;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }
    
    public function rules() {
        return [
            [['subject_id', 'group_id', 'teacher_id', 'semester', 'audience_id', 'pair_num', 'year'], 'required'],
            [['subject_id', 'group_id', 'teacher_id', 'semester', 'audience_id', 'pair_num', 'year'], 'integer'],
            ['is_lab', 'default', 'value' => false],
            ['is_lab', 'boolean'],
            [['type', 'day_week'], 'required'],
            [['type', 'day_week'], 'string', 'length' => [3, 5]],
            ['day_week', 'in', 'range' => self::getWeek7Days()],
            ['type', 'default', 'value' => 'All'],
            ['type', 'in', 'range' => ['Num', 'Denom', 'All']],
            [['teacher2_id', 'audience2_id'], 'default', 'value' => null],
            [['teacher2_id', 'audience2_id'], 'integer']
        ];
    }

}
