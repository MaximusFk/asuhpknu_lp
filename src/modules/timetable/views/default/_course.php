<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<div>
    <?= Html::a($model->title, ['timetable/show?group=' . $model->id]); ?>
</div>