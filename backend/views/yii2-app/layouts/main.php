<?php
use yii\helpers\Html;
use light\widgets\LockFormAsset;
/* @var $this \yii\web\View */
/* @var $content string */

LockFormAsset::register($this);

	//начало многосточной строки, можно использовать любые кавычки
	$script = <<< JS
        var buttons = document.getElementsByTagName("button");
        var inputs = document.getElementsByTagName("input");
        if(buttons.constructor.name === 'HTMLCollection' && buttons.length > 0){           
            for (var i = 0; i < buttons.length; i++) {
                 if("undefined" !== typeof buttons[i].type && buttons[i].type === 'submit'){
                    buttons[i].setAttribute("data-loading-text", "<i class='fa fa-spinner fa-spin '></i>");
                 }    
            }
        }
        if(inputs.constructor.name === 'HTMLCollection' && inputs.length > 0){           
            for (var s = 0; s < inputs.length; s++) {
                 if("undefined" !== typeof inputs[s].type && inputs[s].type === 'submit'){
                    inputs[s].setAttribute("data-loading-text", "<i class='fa fa-spinner fa-spin '></i>");
                 }    
            }
        }
           
JS;
	//маркер конца строки, обязательно сразу, без пробелов и табуляции
	$this->registerJs($script, yii\web\View::POS_READY);

if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
