<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $phones app\models\Phones */
/* @var $form yii\widgets\ActiveForm */
?>

<div id="phones-list">
    <p>Phone List</p>
    <?php
    if($model->phones){
        foreach ($model->phones as $key => $phone){
        ?>
        <?php // $form->field($phone, 'phone[]')->textInput(['maxlength' => true]) ?>
            <div class="form-group field-clients-phones">
                <input type="hidden" name="Phones[<?=$key?>][id]" value="<?= $phone->id?>">
                <input type="hidden" name="Phones[<?=$key?>][client_id]" value="<?= $model->id?>">
                <input type="text" class="form-control" name="Phones[<?=$key?>][phone]" value="<?= $phone->phone?>">
                <div class="help-block"></div>
            </div>
        <?php
        }
    } else {
    ?>
        <div class="form-group field-clients-phones">
            <input type="text" class="form-control" name="Phones[][phone]" value="">
            <div class="help-block"></div>
        </div>
    <?php
    }
    ?>
</div>