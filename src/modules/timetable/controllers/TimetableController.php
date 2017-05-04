<?php

namespace app\modules\timetable\controllers;

use yii\web\Controller;
use nullref\core\interfaces\IAdminController;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use Yii;

use app\modules\timetable\models\timetables\Timetable;

/**
 * Description of TimetableController
 *
 * @author maximusfk
 */
class TimetableController extends Controller implements IAdminController {
   
    public function actionGetFirstCourse() {
        $query = Timetable::find()
                ->where([]);
        $provider = new ActiveDataProvider([
            'query' => $query
        ]);
        return $this->render('index', [
            'dataProvider' => $provider
        ]);
    }
    
    public function actionView($group_id, $year = null, $semester = null) {
        if(!$year) {
            $year = Timetable::getCurrentStudyYear();
        }
        if(!$semester) {
            $semester = Timetable::getSemester(date('m'));
        }
        $query = Timetable::find()
                ->where([
                    'group_id' => $group_id,
                    'year' => $year,
                    'semester' => $semester
                    ]);
        $provider = new ActiveDataProvider([
            'query' => $query
        ]);
        
        return $this->render('view', [
            'dataProvider' => $provider,
            'group' => Timetable::getGroup($group_id)->title,
            'group_id' => $group_id,
            'year' => $year,
            'semester' => $semester
        ]);
    }
    
    public function actionCreate($group_id) {
        $model = new Timetable();
        return $this->render('create', [
            'model' => $model,
            'group_id' => $group_id
        ]);
    }
    
    public function actionConfirm() {
        if(Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $year = $data['Timetable']['year']; unset($data['Timetable']['year']);
            $semester = $data['Timetable']['semester']; unset($data['Timetable']['semester']);
            $group = $data['Timetable']['group_id']; unset($data['Timetable']['group_id']);
            foreach ($data['Timetable'] as &$row) {
                $row['year'] = $year;
                $row['semester'] = $semester;
                $row['group_id'] = $group;
            }
            $timetables['Timetable'] = array_values($data['Timetable']);
            $count = count($timetables['Timetable']);
            $models = [new Timetable()];
            for($i = 1; $i < $count; $i++) {
                $models[] = new Timetable();
            }
            if (Timetable::loadMultiple($models, $timetables) && Timetable::validateMultiple($models)) {
                foreach ($models as $timetable) {
                    $timetable->save(false);
                    //$j[] = $timetable->getAttributes();
                }
                return $this->redirect(['view', 'group_id' => $group, 'year' => $year, 'semester' => $semester]);
            }
        }
        return $this->redirect(['create', 'group_id' => $group]);
    }
    
    public function actionDelete($group_id, $year, $semester) {
        $models = Timetable::find()->where(['group_id' => $group_id, 'year' => $year, 'semester' => $semester])->all();
        foreach ($models as $m) {
            $m->delete();
        }
        return $this->redirect(['view', 'group_id' => $group_id, 'year' => $year, 'semester' => $semester]);
    }

    public function search($params) {
        
    }
}
