<?php
/**
 * @var $this yii\web\View
 * @var $model backend\models\Template
 * @var $isMainPageUpperBlock bool
 */

?>

<div class="grid-item grid-item-big" data-item_class="news big-news">
    <i class="fa fa-plus-square fa-lg" aria-hidden="true"></i>
    <img src="/img/template.png" style="width: 100%"/>
    <div class="description">
        NEWS TITLE <br/>
        news text
    </div>
</div>

<div class="grid-item grid-item-tall" data-item_class="news full">
    <i class="fa fa-plus-square fa-lg" aria-hidden="true"></i>
    <img src="/img/template.png" style="width: 100%"/>
    <div style="height: 40%">
        NEWS TITLE <br/>
        news text
    </div>
</div>


<?php if (!$isMainPageUpperBlock) : ?>
    <div class="grid-item grid-item-wide" data-item_class="news long">
        <i class="fa fa-plus-square fa-lg" aria-hidden="true"></i>
        <img src="/img/template.png" style="height: 100%"/>
        <div class="description-wide">
            NEWS TITLE <br/>
            news text
        </div>
    </div>
<?php endif; ?>

<div class="grid-item grid-item-tall red" data-item_class="news full red">
    <i class="fa fa-plus-square fa-lg" aria-hidden="true"></i>
    <img src="/img/template.png" style="width: 100%"/>
    <div class="description" style="height: 50%">
        NEWS TITLE <br/>
        news text
    </div>
</div>

<div class="grid-item grid-item-md1" data-item_class="news">
    <i class="fa fa-plus-square fa-lg" aria-hidden="true"></i>
    <img src="/img/template.png" style="width: 100%"/>
    <div class="description" style="height: 40%">
        news title
    </div>
</div>

<!--<div class="grid-item grid-item-md2">-->
<!--    <i class="fa fa-plus-square fa-lg" aria-hidden="true"></i>-->
<!--    <img src="/img/template.png" style="width: 100%" />-->
<!--</div>-->


<!--<div class="grid-item grid-item-w2h4">-->
<!--    <i class="fa fa-plus-square fa-lg" aria-hidden="true"></i>-->
<!--    <img src="/img/template.png" />-->
<!--<div class="grid-item grid-item-w1h2">-->
<!--    <i class="fa fa-plus-square fa-lg" aria-hidden="true"></i>-->
<!--    <img src="/img/template.png" />-->
<!--</div>-->
<!--<div class="grid-item grid-item-w1h2">-->
<!--    <i class="fa fa-plus-square fa-lg" aria-hidden="true"></i>-->
<!--    <br/> Image on center <br/> Title on bottom-->
<!--</div>-->
