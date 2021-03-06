<?php
/* @var $this IngestController */
/* @var $model Ingest */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ingest-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
        <?php echo $form->labelEx($model,'food_id'); ?>
        <?php
        $foods = Food::model()->findAll();
        $listFood=CHtml::listData($foods,'id','Nombre'); ?>
        <?php echo $form->dropDownList($model, 'food_id', $listFood);?>
        <?php echo $form->error($model,'food_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'unidades'); ?>
        <?php echo $form->textField($model,'unidades'); ?>
        <?php echo $form->error($model,'unidades'); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'hora'); ?>
		<?php echo $form->textField($model,'hora'); ?>
		<?php echo $form->error($model,'hora'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'publicar comida'); ?>
        <?php echo $form->checkBox($model,'public'); ?>
        <?php echo $form->error($model,'estado'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'usuario'); ?>
        <?php echo $form->ListBox(new Sharedingest(),'user_id', User::listAll(), array('multiple' => 'multiple'), array('click' => "ListBoxClient_SelectionChanged(this, event);")) ?>
        <?php echo $form->error($model,'usuario'); ?>
    </div>

    <?php echo $form->hiddenField($model,'user_id',array('value'=>Yii::app()->user->id)); ?>

    <div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->