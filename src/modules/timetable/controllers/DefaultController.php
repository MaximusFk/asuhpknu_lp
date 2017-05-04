<?php
namespace app\modules\timetable\controllers;

use yii\web\Controller;
use nullref\core\interfaces\IAdminController;
use yii\data\ActiveDataProvider;

use app\modules\students\models\Group;

/**
 * Default controller for Timetable module
 *
 * 
 */
class DefaultController extends Controller implements IAdminController {
    
    public function actionIndex() {
        $query = Group::find();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50
            ]
        ]);
        return $this->render('index', [
            'dataProvider' => $provider
        ]);
    }
}
