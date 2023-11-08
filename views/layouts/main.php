<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\components\RecentComments;
use app\components\TagCloud;
use app\components\UserMenu;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title>
        <?= Html::encode($this->title) ?>
    </title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top'],
        ]);

        $menuItems = [
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
        ];

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
            'items' => $menuItems
        ]);

        if (Yii::$app->user->isGuest) {
            echo Html::tag('div', Html::a('Login', ['/site/login'], ['class' => ['btn btn-primary login text-decoration-none']]), ['class' => ['d-flex']]);
        } else {
            echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-primary logout text-decoration-none']
                )
                . Html::endForm();
        }

        NavBar::end();
        ?>
    </header>

    <main role="main" class="flex-shrink-0" role="main">
        <div class="container">
            <div class="row">
                <div class="list-group col-3" id="sidebar">
                    <div class="list-group-item">
                        <span>User Menu</span>
                        <?php if (!Yii::$app->user->isGuest) {
                            echo UserMenu::widget();
                        } ?>
                    </div>
                    <div class="list-group-item">
                        <span>Tags</span>
                        <?= TagCloud::widget() ?>
                    </div>
                    <div class="list-group-item">
                        <span>Recent Comments</span>
                        <?= RecentComments::widget() ?>
                    </div>
                </div>
                <div class="col-9">
                    <?php if (!empty($this->params['breadcrumbs'])): ?>
                        <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
                    <?php endif ?>
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </div>
            </div>
        </div>
    </main>

    <footer id="footer" class="mt-auto py-3 bg-light">
        <div class="container">
            <div class="row text-muted">
                <p class="col-md-6 text-center text-md-start">&copy; My Company
                    <?= date('Y') ?>
                </p>
                <p class="col-md-6 text-center text-md-end">
                    <?= Yii::powered() ?>
                </p>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>