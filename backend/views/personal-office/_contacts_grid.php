<?php
	/**
	 * @var $model \common\models\User
	 */
use yii\bootstrap\Html;

?>
<div class="user-block">
	<span class="description" style="margin-left: 5px;">
		<?php if(isset($model->email)): ?>
		<?=Html::a("$model->email","mailto:$model->email")?>
		<?php endif; ?>
		<br/>
		<?php if(isset($model->phone)): ?>
		<?=Html::a("+$model->phone","tel:+$model->phone")?>
		<?php endif; ?>
	</span>
</div>

