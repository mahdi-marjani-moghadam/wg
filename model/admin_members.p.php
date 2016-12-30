<?php
include_once(ROOT_DIR . "model/admin_members.o.php");
/**
 * @author Malekloo Izadi Sakhamanesh <Izadi@dabacenter.ir>
 * @version 0.0.1 this is the beta version of News
 * @copyright 2015 The Imen Daba Parsian Co.
 */

class admin_member_presentation
{

    /**
     * Contains file type
     * @var
     */
    public $exportType;

    /**
     * Contains file name
     * @var
     */
    public $fileName;


    /**
     * @param string $list
     * @param $msg
     */
    function template($list=[],$msg)
    {
       // global $conn, $lang;

        switch($this->exportType)
        {
            case 'html':

                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_start.tpl");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_header.tpl");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_rightMenu_admin.tpl");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/$this->fileName");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_footer.tpl");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_end.tpl");
                break;

            case 'json':
                return;
                break;
            default:
                break;
        }

    }

    /**
     * Shows all the news
     * @param $get
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */

    public function searchCode($get)
    {
        include_once(ROOT_DIR . "model/datatable.converter.php");

        $product_id = $get['product_id'];

        $columns = array(
            array( 'db' => 'product_code_id', 'dt' => 0 ),
            array( 'db' => 'product_code', 'dt' => 1 ),
            array( 'db' => 'store',  'dt' => 2 ),
            array( 'db' => 'status',  'dt' => 3 ),
            array( 'db' => 'product_id',  'dt' => 4 )
        );
        //$primaryKey = 'id';
        $convert=new convertDatatableIO();
        $convert->input=$get;
        $convert->columns=$columns;
        $operationSearchFields= $convert->convertInput();
        //echo '<pre/>';
        //print_r($searchFields);
        //die();
        $operation=new admin_product_operation();

        $operationSearchFields['filter']['trash']= 0;
        $operation->getColorList($operationSearchFields,$product_id);
        //echo '<pre/>';
        //print_r($operation->list);
        //die();
        $list['list']=$operation->list;
        $list['paging']=$operation->paging;

        $other['4']=array(
            'formatter' =>function($list)
            {
                //$st = '<div class="nice-checkbox"><input type="checkbox" class="checkbox-o" name="box[' . $list['news_id'] . ']" value="' . $list['Title'] . '" id="checkbox-o-' . $i . '"><label for="checkbox-o-' . $i . '"></label></div>';

                $st ='<a href="'.RELA_DIR.'admin/product.php?action=editCode&code_id=' . $list['product_code_id'].'"  rel="tooltip" data-original-title="ویرایش">
                                            <i class="fa fa-pencil text-green"></i>
                                        </a>
                                        <a href="'.RELA_DIR.'admin/product.php?action=trashCompany&product_id='. $list['product_id'].'"  rel="tooltip" data-original-title="پاک کردن">
                                            <i class="fa fa-trash text-red"></i>
                                        </a>';
                return $st;
            }
        );

        $other['0']=array(

            'formatter' =>function($list)
            {
                $st =$list['datatable_i'];
                return $st;
            }
        );

        $other['3']=array(

            'formatter' =>function($list)
            {
                $st = ($list['status']== 0 ? 'غیر فعال' : 'فعال');
                return $st;
            }
        );

        //$other[2]='<div class="nice-checkbox"><input type="checkbox" class="checkbox-o" name="box[{$news_id}]" value="{$Title}" id="checkbox-o-'.$i.'"><label for="checkbox-o-'.$i.'"></label></div>';
        $export= $convert->convertOutput($list,$columns,$other);
        echo json_encode($export);
        die();
    }

    /**
     * @param $get
     */
    public function test($get)
    {
        $operation=new admin_member_operation();

        $operation->getMemberList($operationSearchFields);
        $list['list']=$operation->list;
        echo '<pre/>';
        print_r($list['list']);
        die();
    }

    public function search($get)
    {
        include_once(ROOT_DIR . "model/datatable.converter.php");
        $columns = array(
            array( 'db' => 'member_id', 'dt' => 0 ),
            array( 'db' => 'username', 'dt' => 1 ),
            array( 'db' => 'name', 'dt' => 2 ),
            array( 'db' => 'family',   'dt' => 3 ),
            array( 'db' => 'password',   'dt' => 4 ),
            array( 'db' => 'mobile',  'dt' => 5 ),
            array( 'db' => "phone",  'dt' => 6),
            array( 'db' => 'member_id',  'dt' => 7),
            array( 'db' => 'member_id',  'dt' => 8)

        );
        //$primaryKey = 'id';
        $convert=new convertDatatableIO();
        $convert->input=$get;
        $convert->columns=$columns;
        $operationSearchFields= $convert->convertInput();
        //echo '<pre/>';
        //print_r($searchFields);
        //die();
        $operation=new admin_member_operation();

        $operationSearchFields['filter']['trash']= 0;
        $operation->getMemberList($operationSearchFields);

        $list['list']=$operation->list;
        $list['paging']=$operation->paging;

        $other['7']=array(
            'formatter' =>function($list)
            {
                //$st = '<div class="nice-checkbox"><input type="checkbox" class="checkbox-o" name="box[' . $list['news_id'] . ']" value="' . $list['Title'] . '" id="checkbox-o-' . $i . '"><label for="checkbox-o-' . $i . '"></label></div>';
                $st ='
                <a href="'.RELA_DIR.'admin/members.php?action=editPrice&member_id='. $list['member_id'].'"  rel="tooltip" data-original-title="تعیین قیمت">
                      <i class="fa  text-red">تعیین قیمت</i>
                     </a>';

                return $st;
            }
        );
        $other['8']=array(
            'formatter' =>function($list)
            {
                //$st = '<div class="nice-checkbox"><input type="checkbox" class="checkbox-o" name="box[' . $list['news_id'] . ']" value="' . $list['Title'] . '" id="checkbox-o-' . $i . '"><label for="checkbox-o-' . $i . '"></label></div>';

                $st ='<a href="'.RELA_DIR.'admin/members.php?action=edit&member_id=' . $list['member_id'].'"  rel="tooltip" data-original-title="ویرایش">
                                            <i class="fa fa-pencil text-green"></i>
                    </a>
                    <a href="'.RELA_DIR.'admin/members.php?action=trashCompany&member_id='. $list['member_id'].'"  rel="tooltip" data-original-title="پاک کردن">
                        <i class="fa fa-trash text-red"></i>
                    </a>';
                return $st;
            }
        );
               // showCode&product_id=

        $other['0']=array(

            'formatter' =>function($list)
            {
                $st =$list['datatable_i'];
                return $st;
            }
        );
        /*$other['6']=array(

            'formatter' =>function($list)
            {
                $st =$list['phone'];
                return $st;
            }
        );*/

        //$other[2]='<div class="nice-checkbox"><input type="checkbox" class="checkbox-o" name="box[{$news_id}]" value="{$Title}" id="checkbox-o-'.$i.'"><label for="checkbox-o-'.$i.'"></label></div>';
        $export= $convert->convertOutput($list,$columns,$other);
        echo json_encode($export);
        die();
    }

    /**
     * Shows all the companies
     * @param  $msg
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function showAllCode($product_id,$msg='')
    {
        $this->exportType = 'html';
        $this->fileName = 'admin_product_color.show.php';
        $list['product_id']=$product_id;
        $this->template($list,$msg);
        die();
    }


    /**
     * Shows all the companies
     * @param  $msg
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function showAllMember($msg)
    {
        //global $conn, $lang;
	    $this->exportType = 'html';
        $this->fileName = 'admin_member.show.php';
        $this->template($msg);
        die();
    }



    /**
     * Add company
     * @param $fields
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function add($fields)
    {
        global $conn, $lang;
        $operation=new admin_member_operation();
        $result=$operation->set_Info($fields);

        if($result['result']!=1)
        {
            $this->addForm($fields,$result['msg']);
        }


        $result = $operation->insertProduct();
        if($result==-1)
        {
            $this->addForm($fields,$result['msg']);
        }
        else
        {

            $msg = "عملیات با موفقیت انجام شد.";
            redirectPage(RELA_DIR . "admin/members.php", $msg);
        }

        die();

    }


    /**
     * Add company form
     * @param $msg
     * @param $fields
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function addFormCode($fields,$msg)
    {
        global $conn, $lang;
        $this->exportType='html';
        $this->fileName='admin_product_code.add.form.php';
        $this->template($fields,$msg);
        die();

    }
    /**
     * Add company form
     * @param $msg
     * @param $fields
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function addForm($fields,$msg)
    {
        global $conn, $lang;
        $this->exportType='html';
        $this->fileName='admin_member.add.form.php';
        $this->template($fields,$msg);
        die();

    }

    /**
     * Edit company based on its ID
     * @param $fields
     * @param $msg
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function editCode($fields,$msg)
    {
        /*$operation = new admin_product_operation();
        $result    = $operation->getProductListById($fields['product_id']);
        if($result['result']=='0')
        {
            return $result['msg'];
        }
        $list = $operation->productInfo;*/
        /////
        $operation = new admin_product_operation();
        $result = $operation->set_codeInfo($fields);
        if($result['result']!=1)
        {
            $this->editFormCode($fields,$result['msg']);
        }

        $result = $operation->updateCode();

        if($result['result']==1)
        {
            $msg = "عملیات با موفقیت انجام شد.";
            redirectPage(RELA_DIR . "admin/product.php",$msg);
        }
        else
        {
            $this->editFormCode($fields,$msg);
        }
        die();
    }

    /**
     * Edit company based on its ID
     * @param $fields
     * @param $msg
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function editPrice($fields,$msg)
    {

        $operation = new admin_member_operation();
        $result = $operation->set_priceInfo($fields);
        if($result['result']!=1)
        {
            $this->editFormPrice($fields,$result['msg']);
        }

        $result = $operation->updatePrice();
        if($result['result']==1)
        {
            $msg = "عملیات با موفقیت انجام شد.";
            redirectPage(RELA_DIR . "admin/members.php",$msg);
        }
        else
        {
            $this->editFormPrice($fields,$msg);
        }
        die();
    }

    /**
     * Edit company based on its ID
     * @param $fields
     * @param $msg
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function edit($fields,$msg)
    {

        $operation = new admin_member_operation();
        $result = $operation->set_Info($fields);
        if($result['result']!=1)
        {
            $this->editForm($fields,$result['msg']);
        }

        $result = $operation->update();
        if($result['result']==1)
        {
            $msg = "عملیات با موفقیت انجام شد.";
            redirectPage(RELA_DIR . "admin/members.php",$msg);
        }
        else
        {
            $this->editForm($fields,$msg);
        }
        die();
    }

    /**
     * Show edit company form based on its ID
     * @param $compID
     * @param $msg
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function editFormCode($fields,$msg)
    {
        $operation = new admin_product_operation();
        $result    = $operation->getCodeListById($fields['product_code_id']);
        if($result['result']=='0')
        {
            return $result['msg'];
        }
        $list = $operation->codeInfo;
        //print_r($list);
        //die();
        $this->exportType='html';
        $this->fileName='admin_product_code.edit.form.php';
        $this->template($list,$msg);
        die();
    }

    /**
     * Show edit company form based on its ID
     * @param $compID
     * @param $msg
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function editFormPrice($fields,$msg)
    {


        include_once(ROOT_DIR . "model/admin_product.operation.class.php");

        $product_operation = new admin_product_operation();
        $result    = $product_operation->getProductMemberPrice($fields['member_id']);
        if($result['result']=='0')
        {
            return $result['msg'];
        }

        $list['product'] = $product_operation->list;
        $list['member_id'] = $fields['member_id'];

        // echo '<pre/>';
        //print_r($list);
        //die();


        $this->exportType='html';
        $this->fileName='admin_product_price.edit.form.php';
        $this->template($list,$msg);
        die();
    }
    /**
     * Show edit company form based on its ID
     * @param $compID
     * @param $msg
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function editForm($fields,$msg)
    {
        $operation = new admin_member_operation();
        $result    = $operation->getById($fields['member_id']);
        if($result['result']=='0')
        {
            return $result['msg'];
        }
        $list = $operation->Info;
        $this->exportType='html';
        $this->fileName='admin_member.edit.form.php';
        $this->template($list,$msg);
        die();
    }


}