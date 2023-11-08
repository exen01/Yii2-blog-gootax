<?php

use app\models\Comment;
use yii\helpers\Html;

/* @var $model app\models\Comment */
?>
<div class="card mb-3" id="c<?= $model->id; ?>">
    <div class="card-header">
        <?= Html::a("#{$model->id}", $model->url, array(
            'class' => 'cid',
            'title' => 'Permalink to this comment',
        )); ?>
    </div>
    <div class="card-body">
        <div class="card-title">
            <?= $model->getAuthorLink(); ?> says on
            <?= Html::a(Html::encode($model->post->title), $model->post->getUrl()); ?>
        </div>
        <div class="card-text">
            <?= nl2br(Html::encode($model->content)); ?>
        </div>
    </div>
    <nav class="card-footer">
        <nav class="list-group list-group-horizontal">
            <?= Html::a('Update', ['comment/update', 'id' => $model->id], ['class' => 'list-group-item']); ?>
            <?= Html::a('Delete', ['comment/delete', 'id' => $model->id], [
                'class' => 'list-group-item',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]); ?>
            <div class="list-group-item">
                <?= date('F j, Y \a\t h:i a', $model->create_time); ?>
            </div>
            <?php if ($model->status == Comment::STATUS_PENDING) : ?>
                <div class="list-group-item">
                    <?= Html::beginForm(['comment/approve', 'id' => $model->id]) ?>
                    <span>Pending approval</span>
                    <?= Html::submitButton('Approve', ['class' => 'btn btn-primary']); ?>
                    <?= Html::endForm() ?>
                </div>
            <?php endif; ?>
        </nav>
</div>