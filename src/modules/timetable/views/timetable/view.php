<?php
use app\modules\timetable\models\timetables\Timetable;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Timetable') . ' ' . $group;
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
    
    <p>
        <?php
        if($dataProvider->getCount() <= 0) {
            echo Html::a(Yii::t('app', 'Create'), ['create?group_id=' . $group_id], ['class' => 'btn btn-success']), ' ';
        }
        else {
            echo Html::a(Yii::t('app', 'Update'), ['create'], ['class' => 'btn btn-primary']), ' ';
            echo Html::a(Yii::t('app', 'Delete'), ['delete?group_id=' . $group_id . '&year=' . $year . '&semester=' . $semester], ['class' => 'btn btn-danger']);
        }
        ?>
    </p>
    
    <?php if ($dataProvider->getCount() > 0) : ?>
    <table class="table table-bordered table-striped">
        <caption><?= Html::encode($year . ' ' . Yii::t('app', 'Year') . ' '
                . Timetable::getSemesterRoman($semester) . ' ' . Yii::t('app', 'Semester')); ?></caption>
        <thead>
        <tr>
        <th> </th>
        <?php foreach (Timetable::getWeek5DaysT() as $day) : ?>
        <th><?= Html::encode($day['text']) ?></th>
        <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php
            $timetables = Timetable::explodeToSortedArray($dataProvider->getModels());
        ?>
        <?php foreach ($timetables as $pair => $entry) : ?>
        <tr>
            <td><?= Html::encode($pair); ?></td>
            <?php foreach (Timetable::getWeek5Days() as $day) :?>
            <?php $val = isset($entry[$day]) ? $entry[$day] : null; ?>
            <td>
                <?php if($val) : ?>
                <span><?= Html::encode($val->getSubjectName()); ?></span>
                <br/>
                <small><i><?= Html::encode($val->getAudienceNumber()); ?></i></small>
                <?php endif; ?>
            </td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>