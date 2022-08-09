<?php

use app\models\Comment;
use yii\helpers\Html;

/* @var $title string */
?>
<ul class="list-group list-group-flush">
    <li class="list-group-item"><?= $title ?></li>
    <li class="list-group-item"><?= Html::a('Создать новую запись', ['/post/create']); ?></li>
    <li class="list-group-item"><?= Html::a('Управление записями', ['/post/admin']); ?></li>
    <li class="list-group-item"><?= Html::a('Одобрение комментариев', ['/comment/index'])
        . ' (' . Comment::getpendingCommentCount() . ')'; ?></li>
</ul>
