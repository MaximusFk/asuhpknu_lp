<?php

namespace app\modules\timetable;

use nullref\core\interfaces\IAdminModule;
use Yii;
use yii\base\Module as BaseModule;

/**
 * Модуль работы с расписаниями
 *
 * 
 */
class Module extends BaseModule implements IAdminModule {
    
    public $controllerNamespace = 'app\modules\timetable\controllers';
    public $modelsNamespace = 'app\modules\timetable\models';

    public static function getAdminMenu() {
        return [
            'label' => Yii::t('app', 'Timetables'),
            'icon' => 'calendar-o',
            'items' => [
                [
                    'label' => Yii::t('app', 'List'),
                    'url' => ['/timetable'],
                    'icon' => 'list'
                ]
            ]
        ];
    }
}
