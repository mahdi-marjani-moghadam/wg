<div class="content-control">
  <!--control-nav-->
  <ul class="control-nav pull-right">
    <li><a class="rtl text-24">  <i class="sidebar-icon fa fa-advetisepaper-o"></i></a></li>
  </ul><!--/control-nav-->
</div><!-- /content-control -->

<div class="content-body">
  <div class="col-md-3">
    <div id="overall-visitor" class="panel panel-animated panel-primary bg-primary">
      <div class="panel-body">
        <div class="panel-actions-fly">
          <button data-refresh="#overall-visitor" data-error-place="#error-placement" title="refresh" class="btn-panel">
            <i class="glyphicon glyphicon-refresh"></i>
          </button><!--/btn-panel-->
          <a href="#" title="Go to system stats page" class="btn-panel">
            <i class="glyphicon glyphicon-stats"></i>
          </a><!--/btn-panel-->
        </div><!--/panel-action-fly-->

        <p class="lead"></p><!--/lead as title-->

          <ul class="list-percentages row ">

            <li class="col-xs-12">
              <p class="text-ellipsis">هنرمندان</p>
              <p class="text-lg"><strong><?php echo $list['artists_count'];?></strong></p>
            </li>


          </ul><!--/list-percentages-->
        <p class="helper-block">

        </p><!--/help-block-->
      </div><!--/panel-body-->
    </div><!--/panel overal-visitor-->
  </div><!--/cols-->
  <div class="col-md-3">
    <div id="overall-visitor" class="panel panel-animated panel-success bg-success">
      <div class="panel-body">
        <div class="panel-actions-fly">
          <button data-refresh="#overall-visitor" data-error-place="#error-placement" title="refresh" class="btn-panel">
            <i class="glyphicon glyphicon-refresh"></i>
          </button><!--/btn-panel-->
          <a href="#" title="Go to system stats page" class="btn-panel">
            <i class="glyphicon glyphicon-stats"></i>
          </a><!--/btn-panel-->
        </div><!--/panel-action-fly-->

        <p class="lead"></p><!--/lead as title-->

        <ul class="list-percentages row ">

          <li class="col-xs-12">
            <p class="text-ellipsis">محصولات</p>
            <p class="text-lg"><strong><?php echo $list['artists_products_count'];?></strong></p>
          </li>

        </ul><!--/list-percentages-->
        <p class="helper-block">

        </p><!--/help-block-->
      </div><!--/panel-body-->
    </div><!--/panel overal-visitor-->
  </div>
  <div class="col-md-3">
    <div id="overall-visitor" class="panel panel-animated panel-danger bg-danger">
      <div class="panel-body">
        <div class="panel-actions-fly">
          <button data-refresh="#overall-visitor" data-error-place="#error-placement" title="refresh" class="btn-panel">
            <i class="glyphicon glyphicon-refresh"></i>
          </button><!--/btn-panel-->
          <a href="#" title="Go to system stats page" class="btn-panel">
            <i class="glyphicon glyphicon-stats"></i>
          </a><!--/btn-panel-->
        </div><!--/panel-action-fly-->

        <p class="lead"></p><!--/lead as title-->

        <ul class="list-percentages row ">
          <li class="col-xs-12">
            <p class="text-ellipsis">رویدادها</p>
            <p class="text-lg"><strong><?php echo $list['event_count'];?></strong></p>
          </li>


        </ul><!--/list-percentages-->
        <p class="helper-block">

        </p><!--/help-block-->
      </div><!--/panel-body-->
    </div><!--/panel overal-visitor-->
  </div>

</div><!--/content-body -->
