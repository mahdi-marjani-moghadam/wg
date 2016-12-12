<?php
/**
 * Created by PhpStorm.
 * User: malekloo
 * Date: 3/6/2016
 * Time: 11:21 AM
 */

include_once(dirname(__FILE__)."/admin.portfolio.model.php");

/**
 * Class registerController
 */
class adminPortfolioController
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
     * registerController constructor.
     */
    public function __construct()
    {
        $this->exportType='html';

    }

    /**
     * call template
     *
     * @param string $list
     * @param $msg
     * @return string
     */
    function template($list=[], $msg)
    {
        global $messageStack;

        switch($this->exportType)
        {
            case 'html':
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_start.php");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_header.php");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_rightMenu_admin.php");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/$this->fileName");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_footer.php");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_end.php");
                break;

            case 'json':
                echo json_encode($list);
                break;

            case 'array':
                return $list;
                break;

            case 'serialize':
                 echo serialize($list);
                break;

            default:
                break;
        }

    }

    /**
     * add honour
     *
     * @param $_input
     * @return int|mixed
     * @author marjani
     * @date 2/27/2015
     * @version 01.01.01
     */
    public function addPortfolio($_input)
    {
        global $admin_info , $messageStack;
        $pub=new adminPortfolioModel;

        $saveOption = $_input;
        $saveOption['category'] = (implode(",",$_input['category']));

        $result=$pub->setFields($saveOption);

        if($result['result']==-1)
        {
            $this->showPortfolioAddForm($_input,$result['msg']);
            die();
        }
        $result = $pub->validator();

        $result=$pub->save();

       // print_r_debug($_FILES);
        foreach ($_FILES as $name=>$file)
        {
            if(is_array($file['tmp_name']))
            {
               for($i=0 ; $i<sizeof($file['tmp_name']) ; $i++)
               {
                   if($file['tmp_name'][$i] != '')
                   {
                       $type  = explode('/',$file['type'][$i]);
                       $input['max_size'] = $file['size'][$i];
                       $input['upload_dir'] = ROOT_DIR.'statics/files/portfolio/';
                       $uploadFile['name'] = $file['name'][$i];
                       $uploadFile['tmp_name'] = $file['tmp_name'][$i];
                       $uploadFile['size'] = $file['size'][$i];
                       $result = fileUploader($input,$uploadFile );

                       //fileRemover($input['upload_dir'],$pub->fields['file']);

                       $pub->file_type = $type[0];
                       $pub->extension = $type[1];
                       $picture[] = $result['image_name'];
                   }

               }
                $pub->otherPic = implode(",",$picture);
                $result = $pub->save();
            }
            else
            {
                if(file_exists($file['tmp_name']))
                {
                    if($file['tmp_name'] != '')
                    {
                        $type  = explode('/',$file['type']);
                        $input['max_size'] = $file['size'];
                        $input['upload_dir'] = ROOT_DIR.'statics/files/portfolio/';
                        $result = fileUploader($input,$file );

                        //fileRemover($input['upload_dir'],$pub->fields['file']);

                        $pub->file_type = $type[0];
                        $pub->extension = $type[1];
                        $pub->originPic = $result['image_name'];
                        $result = $pub->save();
                    }
                }
            }
        }

        if($result['result']!='1')
        {
            $messageStack->add_session('register',$result['msg']);
            $this->showProductAddForm($_input,$result['msg']);
        }

        $msg='عملیات با موفقیت انجام شد';

        redirectPage(RELA_DIR . "admin/?component=portfolio", $msg);
        die();
    }


    /**
     * call register form
     *
     * @param $fields
     * @param $msg
     * @return mixed
     * @author malekloo
     * @date 14/03/2016
     * @version 01.01.01
     */

    public function showPortfolioAddForm($fields ='' , $msg='' )
    {
        include_once(ROOT_DIR . "component/category/admin/model/admin.category.model.php");
        $category = new adminCategoryModel();
        $result = $category->getByFilter();
        if($result['result'] != 1)
        {
            $msg='مشکلی در نمایش بوجود آمده است.';

            redirectPage(RELA_DIR . "admin/?component=portfolio", $msg);
        }
        $fields['category'] = $result['export']['list'];
        
        $this->fileName='admin.portfolio.addForm.php';
        $this->template($fields,$msg);
        die();
    }


    /**
     * @param $fields
     * @return mixed
     * @author malekloo
     * @date 3/16/2015
     * @version 01.01.01
     */
    public function editPortfolio($fields)
    {
        $result=adminPortfolioModel::find($fields['Portfolio_id']);

        if (!is_object($result))
        {
            $msg='مشکلی در ثبت تغییرات بوجود آمده است.';

            redirectPage(RELA_DIR . "admin/?component=portfolio", $msg);
        }
        $otherPic = explode(',' , $result->fields['otherPic']);

        $result1=$result->setFields($fields);
        if($result1['result']!=1)
        {
            $this->showPortfolioEditForm($fields,$result['msg']);
            die();
        }

        $validate=$result->validator();

        if($validate['result']!='1')
        {
            $this->showPortfolioEditForm($fields,$result['msg']);
            die();
        }
        $saveTest= $result->save();
        if($saveTest['result']!=1)
        {
            $msg='مشکلی در ثبت تغییرات بوجود آمده است.';
            redirectPage(RELA_DIR . "admin/?component=portfolio", $msg);
        }

        foreach ($_FILES as $name=>$file)
        {
            if(is_array($file['tmp_name']))
            {
                for($i=0 ; $i<sizeof($file['tmp_name']) ; $i++)
                {
                    if($file['tmp_name'][$i] != '')
                    {
                        $type  = explode('/',$file['type'][$i]);
                        $input['max_size'] = $file['size'][$i];
                        $input['upload_dir'] = ROOT_DIR.'statics/files/portfolio/';
                        $uploadFile['name'] = $file['name'][$i];
                        $uploadFile['tmp_name'] = $file['tmp_name'][$i];
                        $uploadFile['size'] = $file['size'][$i];
                        $resultImage = fileUploader($input,$uploadFile );

                        $result->file_type = $type[0];
                        $result->extension = $type[1];
                        $picture[] = $resultImage['image_name'];
                    }
                }
                $result->otherPic = implode(",",$picture);
                $result12 = $result->save();
                for($i=0; $i<sizeof($otherPic) ; $i++)
                {
                    fileRemover(ROOT_DIR.'statics/files/portfolio/',$otherPic[$i]);
                }
            }
            else
            {
                if(file_exists($file['tmp_name']))
                {
                    if($file['tmp_name'] != '')
                    {
                        $type  = explode('/',$file['type']);
                        $input['max_size'] = $file['size'];
                        $input['upload_dir'] = ROOT_DIR.'statics/files/portfolio/';
                        $resultImage = fileUploader($input,$file );

                        fileRemover($input['upload_dir'],$result->fields['originPic']);

                        $result->file_type = $type[0];
                        $result->extension = $type[1];
                        $result->originPic = $resultImage['image_name'];
                        $result = $result->save();
                    }
                }
            }
        }

        $msg='ویرایش با موفقیت انجام شد.';
        redirectPage(RELA_DIR . "admin/?component=portfolio", $msg);
        die();
    }


    /**
     * @param $fields
     * @return mixed
     * @author malekloo
     * @date 3/6/2015
     * @version 01.01.01
     */
    public function showPortfolioEditForm($fields,$msg='')
    {

        include_once(ROOT_DIR . "component/category/admin/model/admin.category.model.php");
        $category = new adminCategoryModel();
        $result = $category->getByFilter();

        if($result['result'] != 1)
        {
            $msg='مشکلی در نمایش بوجود آمده است.';

            redirectPage(RELA_DIR . "admin/?component=portfolio", $msg);
        }
         $categoryList = $result['export']['list'];

        $result=adminPortfolioModel::find($fields['id']);

        if(!is_object($result))
        {
            $msg='مشکلی در نمایش بوجود آمده است.';

            redirectPage(RELA_DIR . "admin/?component=portfolio", $msg);
        }
        $export = $result->fields;
        $export['otherPic'] = explode(',' , $export['otherPic']);
        $export['categoryList'] = $categoryList;

        $this->fileName='admin.portfolio.editForm.php';
        $this->template($export,$msg);
        die();
    }



    /**
     * @param $fields
     * @return mixed
     * @author malekloo
     * @date 3/6/2015
     * @version 01.01.01
     */
    public function showPortfolioList($fields='')
    {
        $this->fileName='admin.portfolio.list.php';
        $this->template($fields);
        die();
    }
    /**
     * delete deleteCompany by company_id
     *
     * @param $id
     * @author malekloo
     * @date 2/24/2015
     * @version 01.01.01
     */
    public function deletePortfolio($id)
    {
        if (!validator::required($id) and !validator::Numeric($id)) {
            $msg = 'یافت نشد';
            redirectPage(RELA_DIR.'admin/index.php?component=portfolio', $msg);
        }

        $portfolio = adminPortfolioModel::find($id);

        if (!is_object($portfolio)) {
            $msg = $portfolio['msg'];
            redirectPage(RELA_DIR.'admin/index.php?component=portfolio', $msg);
        }

        $dir = ROOT_DIR.'statics/files/portfolio/';
        $removeOrigin = fileRemover($dir,$portfolio->fields['originPic']);

        if($removeOrigin['result'] == -1)
        {
            $msg='حذف به درستی انجام نشد.';
            redirectPage(RELA_DIR.'admin/index.php?component=portfolio', $msg);
        }
        $otherPic = explode(',' , $portfolio->fields['otherPic']);

        for ($i=0 ; $i<sizeof($otherPic) ; $i++)
        {
            $removeResult = fileRemover($dir,$otherPic[$i]);
            if($removeResult['result'] == -1)
            {
                $msg='حذف به درستی انجام نشد.';
                redirectPage(RELA_DIR.'admin/index.php?component=portfolio', $msg);
            }
        }

        $result = $portfolio->delete();
        if ($result['result'] != '1')
        {
            $msg='حذف به درستی انجام نشد.';
            redirectPage(RELA_DIR.'admin/index.php?component=portfolio', $msg);
        }

        $msg='عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR . "admin/index.php?component=portfolio", $msg);
        die();
    }


    public function getContent()
    {
        $portfolio = new adminPortfolioModel();
        $portfolioResult = $portfolio->getByFilter();

        if($portfolioResult['result'] !=1)
        {
            $msg = 'خطایی در نمایش داده ها وجود دارد.';
        }

        if($portfolioResult['export']['recordsCount'] == 0)
        {
            $export = 'هیچ رکوردی برای نمایش وجود ندارد.';
        }
        else
        {
            $export = $portfolioResult['export']['list'];
            foreach ($export as $keyExport=>$val)
            {
                include_once(ROOT_DIR . "component/category/admin/model/admin.category.model.php");
                $category = new adminCategoryModel();
                $catName = $category::query("SELECT * FROM category WHERE Category_id IN (".($val['category']).")");
                foreach ($catName['export']['list'] as $key=>$value)
                {
                    $categoryName[$keyExport][] = $value->fields['title'];
                }
                $export[$keyExport]['categoryName'] = implode(',' , $categoryName[$keyExport]);
            }
        }

        $this->fileName='admin.portfolio.list.php';
        $this->template($export);
        die();
    }

    public function getPictureContent($id)
    {
        $portfolio = adminPortfolioModel::find($id);
        if(!is_object($portfolio))
        {}
        $export = $portfolio->fields;
       
        $export['otherPic'] = explode(',' , $export['otherPic']);
        
        $this->fileName='admin.portfolio.showDetailPic.php';
        $this->template($export);
        die();
    }

}

?>
