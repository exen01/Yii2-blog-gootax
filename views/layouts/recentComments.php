<?php

use yii\helpers\Html;

/* @var $recentComments array */
?>

<ul class="list-group list-group-flush">
    <?php foreach ($recentComments as $comment): ?>
        <li class="list-group-item"><?= $comment->getAuthorLink(); ?> on
            <?= Html::a(Html::encode($comment->post->title), $comment->getUrl()); ?>
        </li>
    <?php endforeach; ?>
</ul>