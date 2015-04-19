<?php
/* @var $this CustomerInfoController */
/* @var $model CustomerInfo */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customer-info-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">带<span class="required">*</span>未必填项</p>

	<?php echo $form->errorSummary($model); ?>

	<table class="table table-bordered">
        <tr>
            <td width="10%"><?php echo $form->labelEx($model,'cust_name'); ?></td> 
            <td>
                <?php echo $form->textField($model,'cust_name',array('size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($model,'cust_name'); ?>
            </td>
        </tr> 
    

	<tr>
		<td><?php echo $form->labelEx($model,'shop_name'); ?></td>
		<td>
			<?php echo $form->textField($model,'shop_name',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'shop_name'); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'corp_name'); ?></td>
		<td>
			<?php echo $form->textField($model,'corp_name',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'corp_name'); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'shop_url'); ?></td>
		<td>
		<?php echo $form->textField($model,'shop_url',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'shop_url'); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'shop_addr'); ?></td>
		<td>
		<?php echo $form->textField($model,'shop_addr',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'shop_addr'); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'phone'); ?></td>
		<td>
		<?php echo $form->textField($model,'phone',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'phone'); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'qq'); ?></td>
		<td>
		<?php echo $form->textField($model,'qq',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'qq'); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'mail'); ?></td>
		<td>
		<?php echo $form->textField($model,'mail',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'mail'); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'datafrom'); ?></td>
		<td>
		<?php echo $form->textField($model,'datafrom',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'datafrom'); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'category'); ?></td>
		<td>
		<?php echo $form->dropDownList($model, 'category',$this->genCategoryArray(), array('style' => "height:34px;")); ?>
		<?php echo $form->error($model,'category'); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'cust_type'); ?></td>
		<td>
		<?php echo $form->dropDownList($model, 'cust_type',$this->genCustTypeArray(), array('style' => "height:34px;")); ?>
		<?php echo $form->error($model,'cust_type'); ?>
		</td>
	</tr>
	<tr>
		<td><?php echo $form->labelEx($model,'iskey'); ?></td>
		<td>
		<?php echo $form->textField($model,'iskey'); ?>
		<?php echo $form->error($model,'iskey'); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'eno'); ?></td>
		<td>
		<span><?=$model->eno?></span>
		</td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'assign_eno'); ?></td>
		<td><span><?=$model->assign_eno?></span></td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'assign_time'); ?></td>
		<td><span><?=date('Y-m-d H:i:s',$model->assign_time)?></span></td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'next_time'); ?></td>
		<td>
		<?php echo $form->textField($model,'next_time',array('class'=>"Wdate", 'onClick'=>"WdatePicker()",'style'=>'height:30px;')); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'memo'); ?></td>
		<td><?php echo $form->textField($model,'memo',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'memo'); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'create_time'); ?></td>
		<td><?php echo $form->textField($model,'create_time'); ?>
		<?php echo $form->error($model,'create_time'); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model,'creator'); ?></td>
		<td>
		<?php echo $form->textField($model,'creator'); ?>
		<?php echo $form->error($model,'creator'); ?>
		</td>
	</tr>

	<tr>
		<td colspan="2"><?php echo CHtml::submitButton('提 交',array('class'=>'btn btn-primary')); ?></td>
	</tr>
</table>  
<?php $this->endWidget(); ?>

</div><!-- form -->