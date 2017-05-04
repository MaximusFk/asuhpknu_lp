<?php
use app\modules\timetable\models\timetables\Timetable;
use yii\helpers\Html;
use app\modules\timetable\assets\TTAsset;

$this->title = Yii::t('app', 'Create') . ' ' . Yii::t('app', 'Timetable');
$this->params['breadcrumbs'][] = $this->title;
TTAsset::register($this);
?>
<div class="timetable-index">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>
    
    <?php require '_cform.php'; ?>
    
</div>