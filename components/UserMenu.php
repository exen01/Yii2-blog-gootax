<?php

namespace app\components;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class UserMenu extends Widget
{
    public string $title;

    public function init()
    {
        $this->title = Html::encode(Yii::$app->user->identity->username);
        parent::init();
    }

    public function run(): string
    {
        return $this->render('//layouts/userMenu', ['title' => $this->title]);
    }

}