
<!-- Content
============================================= -->
<section  class="cloud1 clearfix    ">

    <div class="content-wrap">

        <div class="container clearfix">
    <div class="row">

        <? foreach($list as $k => $v):?>
            <div class="col-md-3">
                <h1><?=$v['title']?></h1>
                <?=$v['description']?>
            </div>
        <? endforeach;?>
    </div>
</div>
</div>
</section>