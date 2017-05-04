<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use Yii;
use app\modules\directories\models\subject\Subject;
use app\modules\directories\models\audience\Audience;
use app\modules\employee\models\Employee;
use app\modules\timetable\models\timetables\Timetable;
?>

<div class="timetable-from">

    <?php $form = ActiveForm::begin([
        'id' => 'timetable-form',
        'action' => '/timetable/timetable/confirm'
    ]); ?>

    <div class="row">

        <div class="row">
            <?= $form->field($model, 'group_id')->hiddenInput(['value' => $group_id])->label(false); ?>
            <div class="col-lg-2">
                <?=
                $form->field($model, 'year')->textInput([
                    'value' => Timetable::getCurrentStudyYear()
                ])->label(Yii::t('app', 'study_year'));
                ?>
            </div>
            <div class="col-lg-2">
                <?=
                        $form->field($model, 'semester')->dropDownList([1 => 'I', 'II'], [
                            'options' => [Timetable::getSemester(date('n')) => ['Selected' => true]]
                        ])
                        ->label(Yii::t('app', 'semester'));
                ?>
            </div>
        </div>
        <?php $counter = 0; ?>
<?php foreach (Timetable::getWeek5Days() as $day) : ?>
            <table class="table table-bordered table-striped" id="<?= $day; ?>">
                <caption><h3><?= Yii::t('app', $day); ?></h3></caption>
                <thead>
                <th>#</th>
                <th><?= Yii::t('app', 'Subject'); ?></th>
                <th><?= Yii::t('app', 'Audience'); ?></th>
                <th><?= Yii::t('app', 'Teacher'); ?></th>
                <th><?= Yii::t('app', 'Lab'); ?></th>
                <th> </th>
                </thead>
                <tbody>
    <?php for ($i = 1; $i <= 4; $i++) : ?>
                        <tr class="<?= $i; ?>" count="<?= $counter++; ?>">
                            <td class="pair"><?= $i; ?></td>
                            <td class="subject"></td>
                            <td class="audience"></td>
                            <td class="teacher"></td>
                            <td class="lab"></td>
                            <td class="controls">
                                <button day="<?= $day; ?>" pair="<?= $i; ?>"class="add-field btn btn-success btn-xs" type="button"><span class=" fa fa-plus"></span></button>
                            </td>
                        </tr>
    <?php endfor; ?>
                </tbody>
            </table>
<?php endforeach; ?>
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-xl']); ?>
    </div>

<?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">
    
var subject_list = <?= json_encode(Subject::find()->select(['title'])->indexBy('id')->orderBy('title')->column()) ?>;
var audience_list = <?= json_encode(Audience::find()->select(['number'])->indexBy('id')->orderBy('number')->column())?>;
var teacher_list = <?= json_encode(Employee::find()->select(['last_name'])->indexBy('id')->column()) ?>;

function remove_field() {
    var day = $(this).attr("day");
    var pair = $(this).attr("pair");
    $("table#" + day + ">tbody>tr." + pair + " td>select").remove();
    $("table#" + day + ">tbody>tr." + pair + " td>button").remove();
    $("table#" + day + ">tbody>tr." + pair + " td>input").remove();
    $("table#" + day + ">tbody>tr." + pair + ">input").remove();
    
    var ctrl_btn_add = $.parseHTML("<button day=\"" + day + "\" pair=\"" + pair + 
            "\" class=\"add-field btn btn-success btn-xs\" type=\"button\"><span class=\" fa fa-plus\"> </span></button>");
    $(ctrl_btn_add).click(add_field);
    $("table#" + day + ">tbody>tr." + pair + ">td." + "controls").append(ctrl_btn_add);
}

function add_field () {
    var day = $(this).attr("day");
    var pair = $(this).attr("pair");
    var counter = $("table#" + day + ">tbody>tr." + pair).attr("count");
    var subject = document.createElement("select");
    var audience = document.createElement("select");
    var teacher = document.createElement("select");
    var islab = document.createElement("input");
    var object = "Timetable[" + counter + "]";
    var h_pair = document.createElement("input");
    var h_day = document.createElement("input");
    var h_type = document.createElement("input");
    subject.name = object + "[subject_id]";
    subject.className += "form-control";
    audience.name = object + "[audience_id]";
    audience.className += "form-control";
    teacher.name = object + "[teacher_id]";
    teacher.className += "form-control";
    islab.name = object + "[is_lab]";
    islab.type = "checkbox";
    islab.value = 1;
    h_pair.name = object + "[pair_num]";
    h_pair.type = "hidden";
    h_pair.value = pair;
    h_day.name = object + "[day_week]";
    h_day.type = "hidden";
    h_day.value = day;
    h_type.name = object + "[type]";
    h_type.type = "hidden";
    h_type.value = "All";
    $.each(subject_list, function (id, value) {
        var option = document.createElement("option");
        option.value = id;
        option.textContent = value;
        subject.appendChild(option);
    });
    $.each(audience_list, function (id, value) {
        var option = document.createElement("option");
        option.value = id;
        option.textContent = value;
        audience.appendChild(option);
    });
    $.each(teacher_list, function (id, value) {
        var option = document.createElement("option");
        option.value = id;
        option.textContent = value;
        teacher.appendChild(option);
    });
    
    var ctrl_btn_dwn = $.parseHTML("<button day=\"" + day + "\" pair=\"" + pair + 
            "\" class=\"move-field-down btn btn-info btn-xs\" type=\"button\"><span class=\" fa fa-arrow-down\"> </span></button>");
    
    var ctrl_btn_up = $.parseHTML("<button day=\"" + day + "\" pair=\"" + pair + 
            "\" class=\"move-field-up btn btn-info btn-xs\" type=\"button\"><span class=\" fa fa-arrow-up\"> </span></button>");
    
    var ctrl_btn_rm = $.parseHTML("<button day=\"" + day + "\" pair=\"" + pair + 
            "\" class=\"remove-field btn btn-danger btn-xs\" type=\"button\"><span class=\" fa fa-remove\"> </span></button>");
    
    $(ctrl_btn_rm).click(remove_field);
    
    
    $("table#" + day + ">tbody>tr." + pair + ">td." + "subject").append(subject);
    $("table#" + day + ">tbody>tr." + pair + ">td." + "audience").append(audience);
    $("table#" + day + ">tbody>tr." + pair + ">td." + "teacher").append(teacher);
    $("table#" + day + ">tbody>tr." + pair + ">td." + "lab").append(islab);
    $("table#" + day + ">tbody>tr." + pair).append(h_pair);
    $("table#" + day + ">tbody>tr." + pair).append(h_day);
    $("table#" + day + ">tbody>tr." + pair).append(h_type);
    $("table#" + day + ">tbody>tr." + pair + ">td." + "controls").empty().append(ctrl_btn_up).append(ctrl_btn_dwn).append(ctrl_btn_rm);
}

$("button.add-field").click(add_field);

</script>
