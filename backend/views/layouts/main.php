<?php

use backend\assets\AppAsset;
use dmstr\web\AdminLteAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?> - <?= Html::encode(Yii::$app->name) ?></title>
        <?php $this->head() ?>
    </head>

    <body class="hold-transition skin-purple sidebar-mini">

    <?php $this->beginBody() ?>

    <div class="wrapper">
        <?= $this->render('_header', [
            'directoryAsset' => $directoryAsset
        ]) ?>

        <?= $this->render('_left') ?>

        <?= $this->render('_content', [
            'content' => $content
        ]) ?>

        <?= $this->render('_footer') ?>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>