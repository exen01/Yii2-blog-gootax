<?php

use yii\helpers\Html;
use yii\helpers\Markdown;

/* @var $model app\models\Post */
?>
<div class="card mb-3">
    <div class="card-header">
        <?= Html::a(Html::encode($model->title), $model->getUrl()); ?>
        <div>
            Posted by
            <?= $model->author->username . ' on ' . date('F j, Y', $model->create_time); ?>
        </div>
    </div>
    <div class="card-body">
        <div class="card-text">
            <?= Markdown::process($model->content); ?>
        </div>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <?= Html::a('Permalink', $model->getUrl()); ?>
        </li>
        <li class="list-group-item">
            <?= Html::a("Comments ({$model->getCommentsCount()})", $model->getUrl() . '#comments'); ?>
        </li>
        <li class="list-group-item">
            Tags:
            <?= implode(", ", $model->getTagLinks()) ?>
        </li>
    </ul>
    <div class="card-footer">
        <div>
            Last updated on
            <?= date('h:i A, F j, Y', $model->update_time); ?>
        </div>
    </div>
</div>