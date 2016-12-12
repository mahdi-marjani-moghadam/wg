     <!-- section header -->
        <header class="header fixed">

            <!-- header-profile -->
            <?php if(admin_info!=-1){?>
            <div class="header-profile pull-left">

                <div class="profile-nav">
                    <a  class="dropdown-toggle" data-toggle="dropdown">
                        <span class="profile-username text-16">حساب کاربری</span>
                        <span class="fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu animated fadeInDown pull-right" role="menu">

                        <li><a href="<?php echo RELA_DIR ?>admin/?component=login&action=logout" class="text-16"><i class="fa fa-power-off"></i> خروج از حساب</a></li>
                    </ul>
                </div>
<!--                <div class="profile-picture">
                    <?php
/*                    $admin_id = $admin_info['admin_id'];
                    $filename = ROOT_DIR."statics/adminPics/".$admin_id.'.jpg';
                    $filename1= ROOT_DIR."statics/adminPics/".$admin_id.'.jpeg';
                    $filename2= ROOT_DIR."statics/adminPics/".$admin_id.'.png';

                    if(file_exists($filename))
                    {
                        $pic = $admin_id.'.jpg';
                    }
                    elseif (file_exists($filename1 ))
                    {
                        $pic = $admin_id.'.jpeg';

                    }
                    elseif(file_exists($filename2))
                    {
                        $pic = $admin_id.'.png';
                    }
                    else
                    {
                        $pic = 'No Image';
                    }

                    if($pic!='No Image')
                    {
                        */?>

                        <img alt="me" src="<?php /*echo RELA_DIR."statics/adminPics/".$pic;*/?>" >
                    <?php
/*                    }else
                    {
                        echo $pic;
                    }*/?>


                </div>-->
            </div><!-- header-profile -->
            <?php }?>

            <div class="pull-right logoHolder">
             <!--   <img src="<?php echo RELA_DIR;?>templates/<?php echo CURRENT_SKIN; ?>/images/logo-elin.png" alt="">-->
            </div>
            <a id="toggleSideBar"><i class="fa fa-bars"></i></a>
        </header><!--/header-->

        <!-- content section -->
        <section class="section">