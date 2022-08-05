<?php

use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $comment app\models\Comment */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>

<?= $this->render('_view', ['model' => $model]) ?>

<div id="comments">
    <?php if ($model->getCommentsCount() >= 1): ?>
        <h3>
            <?= $model->getCommentsCount() . ' comment(s)'; ?>
        </h3>

        <?= $this->render('_comments', [
            'post' => $model,
            'comments' => $model->comments,
        ]); ?>
    <?php endif; ?>

    <h3>Оставить комментарий</h3>

    <?php if (Yii::$app->session->hasFlash('commentSubmitted')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('commentSubmitted'); ?>
        </div>
    <?php else: ?>
        <?= $this->render('/comment/_form', array(
            'model' => $comment,
        )); ?>
    <?php endif; ?>

</div>
