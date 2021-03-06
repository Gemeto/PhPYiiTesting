<?php
/* @var $this IngestController */
/* @var $model Sharedingest */
/* @var $form CActiveForm */
?>
<div class="form">
    <?php
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'ingest-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
    ));
    ?>
    <p class="note">Fields with <span class="required">*</span> are required.</p>
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model,'usuario'); ?>
        <?php echo $form->ListBox($model,'user_id', User::listAll(), array('multiple' => 'multiple'), array('click' => "ListBoxClient_SelectionChanged(this, event);")) ?>
        <?php echo $form->error($model,'usuario'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Compartir'); ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->