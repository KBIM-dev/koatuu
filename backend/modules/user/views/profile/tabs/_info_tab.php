<?php use common\models\User;
	use yii\helpers\Html;
	/**
	 * @var \yii\web\View $this
	 * @var common\models\Profile $profile
	 */
?>
<div class="col-md-4 profile-info-block">
	<hr>
 <strong>
	 <i class="fa fa-birthday-cake margin-r-5"></i>


		<?= Yii::t('user-profile', 'Date of birth');?>
</strong>
<p class="text-muted">
	<?php if(!empty($profile->date_of_birth)): ?>
		<?=date('d.m.Y', strtotime($profile->date_of_birth))?>
	<?php else: ?>
		<?= Yii::t('user-profile','Not set') ?>
	<?php endif; ?>
</p>
</div>
<div class="col-md-4 profile-info-block">
	<hr>
	<strong><i
			class="fa fa-black-tie margin-r-5"></i> <?=Yii::t('user-profile', 'Profession');?>
	</strong>
	<p class="text-muted">
		<?php if(!empty($profile->user->professionName)): ?>
			<?=Html::encode($profile->user->professionName)?>
		<?php else: ?>
			<?= Yii::t('user-profile','Not set') ?>
		<?php endif; ?>
	</p>
</div>
<div class="col-md-4 profile-info-block">
	<hr>
	<strong><i class="fa fa-level-up margin-r-5"></i> <?=Yii::t('user-profile', 'Potential');?>
	</strong>
	<p class="text-muted">
		<?php if(!empty($profile->user->potentialName)): ?>
			<?=Html::encode($profile->user->potentialName)?>
		<?php else: ?>
			<?= Yii::t('user-profile','Not set') ?>
		<?php endif; ?>
	</p>
</div>
<div class="col-md-4 profile-info-block">
	<hr>
	<strong><i class="fa fa-clock-o margin-r-5"></i> <?=Yii::t('user-profile', 'Joined on');?>
	</strong>
	<p class="text-muted">
		<?php if(!empty($profile->user->created_at)): ?>
			<?=Yii::t('user', '{0, date}', $profile->user->created_at)?>
		<?php else: ?>
			<?= Yii::t('user-profile','Not set') ?>
		<?php endif; ?>
	</p>
</div>
<div class="col-md-4 profile-info-block">
	<hr>
	<strong><i class="fa fa-user-plus margin-r-5"></i> <?=Yii::t('user-profile', 'Recruiter');?>
	</strong>
	<p class="text-muted">
		<?php if(!empty($profile->user->recruiter_id)): ?>
			<?=$profile->user->recruiter->username?>
		<?php else: ?>
			<?= Yii::t('user-profile','Not set') ?>
		<?php endif; ?>
	</p>
</div>
<div class="clearfix"></div>
<div class="col-md-4 profile-info-block">
    <hr>
    <strong><i class="fa fa-venus-mars margin-r-5"></i> <?=Yii::t('user-profile', 'Sex');?>
    </strong>
    <p class="text-muted">
		<?php if(!empty($profile->user->sex)): ?>
			<span class="text-capitalize" >
				<?=Yii::t('user',$profile->user->sex)?>
			</span>
		<?php else: ?>
			<?= Yii::t('user-profile','Not set') ?>
		<?php endif; ?>
    </p>
</div>
<div class="col-md-4">
	<hr>
	<strong>
		<i class="fa fa-cogs margin-r-5"></i> <?=Yii::t('user-profile', 'Interests');?>
	</strong>
	<?php if(isset($profile->user->interests) && is_array($profile->user->interests) && count($profile->user->interests)): ?>
		<ul>
			<?php foreach($profile->user->interests as $interests) { ?>

				<li><?=$interests->name?></li>
			<?php } ?>
		</ul>
	<?php else: ?>
		<?= Yii::t('user-profile','Not set') ?>
	<?php endif; ?>
</div>
<div class="col-md-4">
	<hr>
	<strong>
		<i class="fa fa-comments-o margin-r-5"></i> <?=Yii::t('user-profile', 'Communication types');?>
	</strong>
	<?php if(isset($profile->user->communicationTypes) && is_array($profile->user->communicationTypes) && count($profile->user->communicationTypes)): ?>
		<ul>
			<?php foreach($profile->user->communicationTypes as $communicationType) { ?>

				<li><?=$communicationType->name?></li>
			<?php } ?>
		</ul>
	<?php else: ?>
		<?= Yii::t('user-profile','Not set') ?>
	<?php endif; ?>
</div>
<div class="col-md-4">

</div>
<div class="clearfix"></div>