<?php
return [
    'definitions' => [
        yii\grid\GridView::class => [
            'summaryOptions' => ['tag' => 'span', 'class' => 'pull-right'],
            'tableOptions' => [
                'class' => 'table table-striped table-bordered table-sm',
            ],
        ],
        richardfan\sortable\SortableGridView::class => [
            'summaryOptions' => ['tag' => 'span', 'class' => 'pull-right'],
            'tableOptions' => [
                'class' => 'table table-striped table-bordered table-sm',
            ],
        ],
        lo\widgets\modal\ModalAjax::class => [
            'header' => null,
            'options' => ['class' => 'header-primary'],
            'size' => lo\widgets\modal\ModalAjax::SIZE_LARGE,
        ],
    ],
];
