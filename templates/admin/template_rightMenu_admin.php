
        <aside class="side-left" id="side-left">
            <ul class="sidebar">
                <!--/sidebar-item-->
                <li>
                    <a href="<?php print RELA_DIR; ?>admin/index.php">
                        <i class="sidebar-icon fa fa-home"></i>
                        <span class="sidebar-text">خانه</span>
                    </a>
                </li><!--/sidebar-item-->
                <li>
                    <a href="#">
                        <i class="sidebar-icon fa fa-tasks"></i>
                        <span class="sidebar-text">دسته بندی</span>
                        <b class="fa fa-angle-left"></b>

                    </a>
                    <ul class="sidebar-child animated fadeInRight">
                        <li>
                            <a href="<?=RELA_DIR; ?>admin/?component=category">
                                <span class="sidebar-text text-16">لیست دسته بندی</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?=RELA_DIR; ?>admin/?component=category&action=add">
                                <span class="sidebar-text text-16">افزودن دسته بندی جدید</span>
                            </a>
                        </li><!--/child-item-->
                    </ul><!--/sidebar-child-->
                </li><!--/sidebar-item-->


                <li>
                    <a href="#">
                        <i class="sidebar-icon fa fa-adn"></i>
                        <span class="sidebar-text"> هنرمندان</span>
                        <b class="fa fa-angle-left"></b>
                    </a>
                    <ul class="sidebar-child animated fadeInRight">
                        <li>
                            <a href="<?=RELA_DIR; ?>admin/?component=artists">
                                <span class="sidebar-text text-16">لیست هنرمندان</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="sidebar-icon fa fa-adn"></i>
                        <span class="sidebar-text">رویدادها</span>
                        <b class="fa fa-angle-left"></b>
                    </a>
                    <ul class="sidebar-child animated fadeInRight">
                        <li>
                            <a href="<?=RELA_DIR; ?>admin/?component=event">
                                <span class="sidebar-text text-16">لیست رویدادها</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="sidebar-icon fa fa-image"></i>
                        <span class="sidebar-text">بنر</span>
                        <b class="fa fa-angle-left"></b>
                    </a>
                    <ul class="sidebar-child animated fadeInRight">
                        <li>
                            <a href="<?=RELA_DIR; ?>admin/?component=banner">
                                <span class="sidebar-text text-16">لیست بنر</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="sidebar-icon fa fa-list"></i>
                        <span class="sidebar-text">خدمات</span>
                        <b class="fa fa-angle-left"></b>
                    </a>
                    <ul class="sidebar-child animated fadeInRight">
                        <li>
                            <a href="<?=RELA_DIR; ?>admin/?component=services">
                                <span class="sidebar-text text-16">ویرایش خدمات</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="sidebar-icon fa fa-list"></i>
                        <span class="sidebar-text">نمونه کار</span>
                        <b class="fa fa-angle-left"></b>
                    </a>
                    <ul class="sidebar-child animated fadeInRight">
                        <li>
                            <a href="<?=RELA_DIR; ?>admin/?component=portfolio">
                                <span class="sidebar-text text-16">نمونه کار</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="sidebar-icon fa fa-info"></i>
                        <span class="sidebar-text">درباره ما</span>
                        <b class="fa fa-angle-left"></b>
                    </a>
                    <ul class="sidebar-child animated fadeInRight">
                        <li>
                            <a href="<?=RELA_DIR; ?>admin/?component=aboutus&action=addAboutus">
                                <span class="sidebar-text text-16"> ویرایش</span>
                            </a>
                        </li><!--/child-item-->
                    </ul><!--/sidebar-child-->
                </li><!--/sidebar-item-->

                <li>
                    <a href="<?=RELA_DIR; ?>admin/?component=contactus">
                        <i class="sidebar-icon fa fa-envelope"></i>
                        <span class="sidebar-text">تماس با ما</span>
                    </a>
                </li><!--/sidebar-item-->
            </ul><!--/sidebar-->
        </aside><!--/side-left-->

        <div class="content">
