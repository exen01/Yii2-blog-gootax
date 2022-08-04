<?php use yii\helpers\Html;

/* @var $post app\models\Post */
/* @var $comments */
/* @var $comment app\models\Comment */

?>

<?php
foreach ($comments as $comment): ?>
    <div class="card mb-3" id="c<?= $comment->id; ?>">
        <div class="card-header">
            <?= Html::a("#{$comment->id}", $comment->getUrl($post), [
                'class' => 'cid',
                'title' => 'Permalink to this comment',
            ]); ?>
        </div>
        <div class="card-body">
            <div class="card-title">
                <?= $comment->getAuthorLink(); ?> says:
            </div>
            <div class="card-text">
                <?= nl2br(Html::encode($comment->content)); ?>
            </div>
        </div>
        <div class="card-footer">
            <?= date('F j, Y \a\t h:i a', $comment->create_time); ?>
        </div>
    </div>
<?php endforeach; ?>
