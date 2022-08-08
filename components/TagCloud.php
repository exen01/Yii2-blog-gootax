<?php

namespace app\components;

use app\models\Tag;
use yii\base\Widget;
use yii\helpers\Html;

class TagCloud extends Widget
{
    public string $title = 'Tags';
    public int $maxTags = 20;

    public function run()
    {
        $tags = Tag::findTagWeights($this->maxTags);

        foreach ($tags as $tag => $weight) {
            $link = Html::a(Html::encode($tag), ['post/index', 'tag' => $tag]);
            echo Html::tag('span', $link, [
                    'class' => 'tag',
                    'style' => "font-size:{$weight}pt",
                ]) . "\n";
        }
    }
}