<?php

use yii\helpers\Html;
use yii\helpers\Markdown;

/* @var $model app\models\Post */
?>
<div class="card mb-3">
    <div class="card-header">
        <?= Html::a(Html::encode($model->title), $model->getUrl()); ?>
    </div>
    <div class="card-body">
        <div class="card-title">
            Posted by <?= $model->author->username . ' on ' . date('F j, Y', $model->create_time); ?>
        </div>
        <div class="card-text">
            <?= Markdown::process($model->content); ?>
        </div>
    </div>
    <div class="card-footer">
        <nav class="list-group list-group-horizontal">
            <div class="list-group-item">
                <b>Tags:</b>
                <?= implode(", ", $model->getTagLinks()) ?>
            </div>
            <?= Html::a('Permalink', $model->getUrl(), ['class' => 'list-group-item']); ?>
            <?= Html::a("Comments ({$model->getCommentsCount()})", $model->getUrl() . '#comments', ['class' => 'list-group-item']); ?>
            <div class="list-group-item">
                Last updated on <?= date('h:i A, F j, Y', $model->update_time); ?>
            </div>
        </nav>
    </div>
</div>