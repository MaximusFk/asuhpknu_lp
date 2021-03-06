<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.03.2017
 * Time: 8:56
 */
use app\modules\students\models\Exemption;
use yii\widgets\MaskedInput;

?>
<div class="row">
    <div class="col-sm-6">
        <?= $form->field($model, 'photo')->fileInput();
        ?>
        <?= $model->getThumbFileUrl('photo', 'thumb'); ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'student_code')->widget(\yii\widgets\MaskedInput::className(), ['mask' => 'AA №99999999'], ['placeholder' => $model->getAttributeLabel('birth_day')]);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('last_name')]) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('first_name')]) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('middle_name')]) ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <?= $form->field($model, 'exemption_ids')->checkboxList(Exemption::getList(), ['class' => 'list-inline']); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?= $form->field($model, 'gender')->dropDownList([0 => Yii::t('app', 'Male'), 1 => Yii::t('app', 'Female')], ['prompt' => Yii::t('app', 'Select gender')]); ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'birth_day')->widget(dosamigos\datepicker\DatePicker::className(), [
            'language' => 'uk',
            'clientOptions' => [
                'autoclose' => true,

            ]
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
        <?= $form->field($model, 'passport_code')->widget(MaskedInput::className(), ['mask' => 'AA №999999']) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'passport_issued')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('passport_issued')]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'passport_issued_date')->widget(dosamigos\datepicker\DatePicker::className(), [
            'language' => 'uk',
            'clientOptions' => [
                'autoclose' => true,

            ]
        ]); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'birth_certificate')->widget(MaskedInput::className(), ['mask' => 'AA №999999']);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?= $form->field($model, 'tax_id')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'sseed_id')->textInput() ?>
    </div>
</div>
