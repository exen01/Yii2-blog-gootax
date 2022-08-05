<?php

use app\models\Lookup;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>Manage Posts</h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => 'yii\grid\DataColumn',
            'label' => 'Title',
            'attribute' => 'title',
            'value' => function($data){
                return Html::a(Html::encode($data->title), $data->getUrl());
            },
            'format' => 'html'
        ],
        [
            'class' => 'yii\grid\DataColumn',
            'label' => 'Status',
            'attribute' => 'status',
            'value' => function($data){
                return Lookup::item("PostStatus", $data->status);
            },
            'filter' => Lookup::items('PostStatus'),
        ],
        [
            'label' => 'Create time',
            'attribute' => 'create_time',
            'format' => 'date',
            'filter' => false
        ],
        [
            'class' => 'yii\grid\ActionColumn',
        ],
    ],
]);

?>
