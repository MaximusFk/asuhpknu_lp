<?php

use app\modules\timetable\models\timetables\Timetable;
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Timetables');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timetable-index">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>
    
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'format' => 'html',
                'label' => Yii::t('app', 'Title'),
                'value' => function ($data) {
                    return Html::a($data->title, ['timetable/view?group_id=' . $data->id]);
                }
            ],
            [
                'format' => 'html',
                'label' => Yii::t('app', 'Allowed timetables'),
                'value' => function ($data) {
                    $columns = Timetable::getTimetablesForGroup($data->id);
                    $string = "";
                    foreach($columns as $year => $semester) {
                        $string += '<span>' . $year . '(' . 
                                Html::a(Timetable::getSemesterRoman($semester[0]), ['timetable/view', 'group_id' => $data->id, 'year' => $year, 'semester' => $semester[0]]) . ', ' .
                                Html::a(Timetable::getSemesterRoman($semester[0]), ['timetable/view', 'group_id' => $data->id, 'year' => $year, 'semester' => $semester[1]]) . ') </span>';
                        
                    }
                    return $string;
                }
            ],
            [
                'format' => 'html',
               'value' => function ($data) {
                    return Html::a(Yii::t('app', 'Create'), ['timetable/create', 'group_id' => $data->id], ['class' => 'btn btn-success btn-sm']);
                }
            ]
        ]
    ]);
    
    ?>
</div>