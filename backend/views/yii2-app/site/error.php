<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<section class="content">

    <div class="error-page">
        <h2 class="headline text-info"><i class="fa fa-warning text-yellow"></i></h2>

        <div class="error-content">
            <h3><?= $name ?></h3>

            <p>
                Сталася помилка під час обробки вашого запиту.
                Будь ласка, зв'яжіться з нами, якщо ви думаєте, що це помилка сервера.
                Дякую. У той же час, ви можете <a href='<?= Yii::$app->homeUrl ?>'>повернутися до панелі</a>.
            </p>
        </div>
    </div>

</section>
