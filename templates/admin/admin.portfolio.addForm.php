

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="../common/ckfinder/ckfinder.js"></script>
<script type="text/javascript">

    $( function() {
        $( "#datepicker" ).datepicker();
    } );

    function BrowseServer( startupPath, functionData )
    {
        var finder = new CKFinder();
        finder.basePath = '../';
        finder.startupPath = startupPath;
        finder.selectActionFunction = SetFileField;
        finder.selectActionData = functionData;

        finder.popup();
    }

    function SetFileField( fileUrl, data )
    {
        document.getElementById( data["selectActionData"] ).value = fileUrl;
    }
    function ShowThumbnails( fileUrl, data )
    {
        // this = CKFinderAPI

        var sFileName = this.getSelectedFile().name;
        document.getElementById( 'thumbnails' ).innerHTML +=
            '<div class="thumb">' +
            '<img src="' + fileUrl + '" />' +
            '<div class="caption">' +
            '<a href="' + data["fileUrl"] + '" target="_blank">' + sFileName + '</a> (' + data["fileSize"] + 'KB)' +
            '</div>' +
            '</div>';

        document.getElementById( 'preview' ).style.display = "";
        // It is not required to return any value.
        // When false is returned, CKFinder will not close automatically.
        return false;
    }
</script>


<div class="content-control">
    <!--control-nav-->
    <ul class="control-nav pull-right">
        <li><a class="rtl text-24">افزودن مورد جدید<i class="sidebar-icon fa fa-info"></i></a></li>
    </ul><!--/control-nav-->
</div><!-- /content-control -->

<div class="content-body">

    <div id="panel-tablesorter" class="panel panel-warning">
        <div class="panel-heading bg-white">
            <h3 class="panel-title rtl"></h3>
            <div class="panel-actions">
                <button data-expand="#panel-tablesorter" title="" class="btn-panel rtl" data-original-title="تمام صفحه">
                    <i class="fa fa-expand"></i>
                </button>
                <button data-collapse="#panel-tablesorter" title="" class="btn-panel rtl" data-original-title="باز و بسته شدن">
                    <i class="fa fa-caret-down"></i>
                </button>
            </div><!-- /panel-actions -->
        </div><!-- /panel-heading -->

        <?php if($msg!=null)
        { ?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-warning">
                <?= $msg ?>
            </div>
            <?php
        }
        ?>
        <div class="panel-body">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8  center-block">
                    <form name="queue" id="queue" role="form" data-validate="form" class="form-horizontal form-bordered"  novalidate="novalidate" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="col-xs-12 col-sm-4 pull-right control-label rtl" for="title">عنوان:</label>
                                    <div class="col-xs-12 col-sm-8 pull-right">
                                        <input type="text" class="form-control" name="title" id="title"  placeholder=" title " value="<?=$list['title']?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="col-xs-12 col-sm-4 pull-right control-label rtl" for="title">توضیحات :</label>
                                    <div class="col-xs-12 col-sm-8 pull-right">
                    <textarea name="description" class="form-control"
                              id="description" placeholder="description"><?=$list['description']?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row xsmallSpace hidden-xs"></div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="col-xs-12 col-sm-4 pull-right control-label rtl" for="category_id">دسته بندی:</label>
                                    <div class="col-xs-12 col-sm-8 pull-right">
                                        <select name="category[]" data-input="select2" placeholder="دسته بندی مورد نظر را انتخاب کنید." multiple>
                                            <?
                                            foreach($list['category'] as $category_id => $value)
                                            {
                                                ?>
                                                <option  <?php echo in_array($value['Category_id'],$list['category_id'] ) ? 'selected' : '' ?> value="<?=$value['Category_id']?>">
                                                    <?=$value['title']?>
                                                </option>
                                                <?
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="col-xs-12 col-sm-4 pull-right control-label rtl" for="title">آدرس اینترنتی:</label>
                                    <div class="col-xs-12 col-sm-8 pull-right">
                                        <input type="text" class="form-control" name="url" id="url"  placeholder="url" value="<?=$list['url']?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row xsmallSpace hidden-xs"></div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="ax" class="col-sm-3 control-label"> عکس اصلی: </label>
                                    <div class="col-sm-5">
                                        <div data-intro="" data-provides="fileinput" class="fileinput fileinput-new">
                                            <span class="btn btn-icon btn-icon-right btn-warning btn-file">
                                                <i class="fa fa-upload"></i>
                                                <span class="fileinput-new">انتخاب</span>
                                                <span class="fileinput-exists">تغییر</span>
                                                <input type="file" id="ax" name="originPic">
                                            </span>
                                            <span class="fileinput-filename"></span>
                                            <button style="float: none" data-dismiss="fileinput"
                                                    class="close fileinput-exists">×
                                            </button>
                                        </div>
                                <!--<div class="form-group">
                                    <label class="col-xs-12 col-sm-4 pull-right control-label rtl" for="xImagePath">عکس اصلی:</label>
                                    <div class="col-xs-12 col-sm-8 pull-right">
                                        <img id="img" name="originPic" class="boxBorder roundCorner " src="">
                                        <label for="upload">
                                            <input name="originPic" class="uploadFile" type="file" id="upload">
                                        </label>
                                    </div>-->
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="row xsmallSpace hidden-xs"></div>
                        <label class="col-xs-12 col-sm-12 col-md-12 pull-right control-label rtl" for="xImagePath">عکسهای دیگر:</label>
                        <div class="row" id="addNewPic">

                            <ul>
                                <li  style="list-style-type:none">
                                    <div class="col-xs-12 col-sm-12 col-md-6 pull-right">

                                        <div class="form-group">
                                            <?php //$i=1;?>
                                            <label class="col-xs-12 col-sm-4 control-label rtl pull-right" for="text1">عکس 1:</label>
                                            <div class="col-sm-5">
                                                <div data-intro="" data-provides="fileinput" class="fileinput fileinput-new">
                                                    <span class="btn btn-icon btn-icon-right btn-warning btn-file">
                                                        <i class="fa fa-upload"></i>
                                                        <span class="fileinput-new">انتخاب</span>
                                                        <span class="fileinput-exists">تغییر</span>
                                                        <input type="file" id="ax" name="otherPic[]">
                                                    </span>
                                                    <span class="fileinput-filename"></span>
                                                    <button style="float: none" data-dismiss="fileinput"
                                                            class="close fileinput-exists">×
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="row xsmallSpace hidden-xs"></div>

                        <div class="row">
                            <input name="action" type="hidden" id="action" value="add" />
                            <div class="col-md-12">
                                <p class="pull-right">
                                    <button type="submit" name="submit" id="addPic" class="btn btn-icon btn-success rtl">
                                        <i class="fa fa-plus"></i>
                                    افزودن
                                    </button>
                                </p>
                            </div>
                            <div class="col-md-12">
                                <p class="pull-right">
                                    <button type="submit" name="submit" id="submit" class="btn btn-icon btn-success rtl">
                                        <i class="fa fa-plus"></i>
                                        ثبت
                                    </button>
                                </p>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var num =1;
    $('#addPic').click(function(e){
        num = num+1;
        e.preventDefault();
       // var text = '<li  style="list-style-type:none"><div class="col-xs-12 col-sm-12 col-md-6 pull-right"><div class="form-group"><label class="col-xs-12 col-sm-4 control-label rtl pull-right" for="text1">عکس '+num+':</label><div class="col-xs-12 col-sm-8 pull-left"><label for="upload"><input name="otherPic[]" class="uploadFile" type="file" id="upload"></label></div></div></div></li>';
        var text = '<li  style="list-style-type:none"><div class="col-xs-12 col-sm-12 col-md-6 pull-right"> <div class="form-group"> <label class="col-xs-12 col-sm-4 control-label rtl pull-right" for="text1">عکس '+num+':</label><div class="col-sm-5"> <div data-intro="" data-provides="fileinput" class="fileinput fileinput-new"><span class="btn btn-icon btn-icon-right btn-warning btn-file"> <i class="fa fa-upload"></i><span class="fileinput-new">انتخاب</span><span class="fileinput-exists">تغییر</span> <input type="file" id="ax" name="otherPic[]"></span><span class="fileinput-filename"></span><button style="float: none" data-dismiss="fileinput" class="close fileinput-exists">×</button></div></div></div></div></li>';
        $('#addNewPic ul').append(text);
    })
</script>
