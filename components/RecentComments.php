<?php

namespace app\components;

use app\models\Comment;
use yii\base\Widget;

class RecentComments extends Widget
{
    public string $title = "Recent Comments";
    public int $maxComments = 10;

    public function getRecentComments(): array
    {
        return Comment::findRecentComments($this->maxComments);
    }

    public function run(): string
    {
        return $this->render('//layouts/recentComments', ['recentComments' => $this->getRecentComments()]);
    }
}