<?php
	/**
	 * @var $model \common\models\User
	 */
	use yii\helpers\Url;
?>
<div class="user-block">
	<span class="username" style="margin-left: 5px;">
		<a href="<?=Url::to(['user/profile/show', 'id'=> $model->id])?>" style="font-size: 14px;">
			<?php if(!empty($model->getFullName())){ ?>
				<?=$model->getFullName()?>
			<?php }elseif(!empty($model->username)){ ?>
				<?=$model->username?>
			<?php } ?>
		</a>
	</span>
    <span class="description" style="margin-left: 5px;">
		<?=$model->koatuus ? $model->koatuus->allAddressSting : ''?>
	</span>
</div>