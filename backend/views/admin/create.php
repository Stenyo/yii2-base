<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\user\Admin */

$this->title = 'Create Admin';
$this->params['breadcrumbs'][] = ['label' => 'Admins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
