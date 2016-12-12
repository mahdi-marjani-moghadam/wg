

<div class="content-control">
    <!--control-nav-->
    <ul class="control-nav pull-right">
        <li><a class="rtl text-24"><i class="sidebar-icon fa fa-adn"></i> لیست نمونه کارها</a></li>
    </ul>
    <!--/control-nav-->
</div>
<!-- /content-control -->

<div class="content-body">
    <!-- separator -->
    <div class="row xsmallSpace"></div>
    <div id="panel-1" class="panel panel-default border-blue">
        <div class="panel-heading bg-blue">
            <h3 class="panel-title rtl ">لیست نمونه کارها</h3>
            <div class="panel-actions">
                <button data-expand="#panel-1" title="نمایش" class="btn-panel"><i class="fa fa-expand"></i></button>
                <button data-collapse="#panel-1" title="بازکردن" class="btn-panel"><i class="fa fa-caret-down"></i>
                </button>
            </div>
        </div>
        <div class="panel-body">
            <div class="pull-right"><a href="<?= RELA_DIR ?>admin/?component=portfolio&action=addPortfolio"
                                       class="btn btn-primary btn-sm btn-icon text-13"><i class="fa fa-plus"></i> افزودن نمونه کار</a></div>


            <!-- separator -->
            <div class="row smallSpace"></div>
            <div class="table-responsive table-responsive-datatables">
                <table id="example" class="companyTable table table-striped table-bordered rtl" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>عنوان</th>
                        <th>آدرس اینترنتی</th>
                        <th>توضیحات</th>
                        <th>دسته بندی ها</th>
                        <th>عکس اصلی</th>
                        <th>ابزار</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?
                    $cn = 1;
                    if (is_array($list)) {
                        foreach ($list as $id => $fields) {
                            ?>
                            <tr>
                                <td><?php echo $cn++; ?></td>
                                <td><?php echo $fields['title']; ?></td>
                                <td><?php echo $fields['url']; ?></td>
                                <td><?php echo $fields['description']; ?></td>
                                <td><?php echo $fields['categoryName'];?></td>
                                <td><img height="60px" src="<?=RELA_DIR."statics/files/portfolio/".$fields['originPic']?>"/> </td>
                                <td>
                                    <a href="<?= RELA_DIR ?>admin/?component=portfolio&action=showOtherPic&id=<?php echo $fields['Portfolio_id']; ?>">مشاهده تصاویر دیگر نمونه کار </a>
                                    <a href="<?= RELA_DIR ?>admin/?component=portfolio&action=edit&id=<?php echo $fields['Portfolio_id']; ?>">/ویرایش </a>
                                    <a href="<?= RELA_DIR ?>admin/?component=portfolio&action=delete&id=<?php echo $fields['Portfolio_id']; ?>">/حذف </a>
                                </td>
                            </tr>
                            <?
                        }
                    }
                    else
                    {
                        echo $export;
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <th><input type="text" name="search_1" class="search_init form-control"/></th>
                    <th><input type="text" name="search_2" class="search_init form-control"/></th>
                    <th><input type="text" name="search_3" class="search_init form-control"/></th>
                    <th><input type="text" name="search_4" class="search_init form-control"/></th>
                    <th><input type="text" name="search_5" class="search_init form-control"/></th>
                    <th><input type="text" name="search_6" class="search_init form-control"/></th>
                    <th><input type="text" name="search_7" class="search_init form-control"/></th>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="panel-footer clearfix"></div>
    </div>
</div>
<!--/content-body -->

<div class="modal fade customMobile" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <p class="phoneHolder"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->