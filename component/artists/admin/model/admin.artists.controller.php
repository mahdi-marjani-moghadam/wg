<?php
/**
 * Created by PhpStorm.
 * User: malekloo
 * Date: 3/6/2016
 * Time: 11:21 AM.
 */
include_once dirname(__FILE__).'/admin.artists.model.php';

/**
 * Class registerController.
 */
class adminArtistsController
{
    /**
     * Contains file type.
     *
     * @var
     */
    public $exportType;

    /**
     * Contains file name.
     *
     * @var
     */
    public $fileName;

    /**
     * registerController constructor.
     */
    public function __construct()
    {
        $this->exportType = 'html';
    }

    /**
     * call template.
     *
     * @param string $list
     * @param $msg
     *
     * @return string
     */
    public function template($list = [], $msg)
    {
        global $messageStack,$admin_info;

        switch ($this->exportType) {
            case 'html':

                include ROOT_DIR.'templates/'.CURRENT_SKIN.'/template_start.php';
                include ROOT_DIR.'templates/'.CURRENT_SKIN.'/template_header.php';
                include ROOT_DIR.'templates/'.CURRENT_SKIN.'/template_rightMenu_admin.php';
                include ROOT_DIR.'templates/'.CURRENT_SKIN."/$this->fileName";
                include ROOT_DIR.'templates/'.CURRENT_SKIN.'/template_footer.php';
                include ROOT_DIR.'templates/'.CURRENT_SKIN.'/template_end.php';
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
     * add Artists.
     *
     * @param $fields
     * @return int|mixed
     * @internal param $_input
     *
     * @author marjani
     * @date 2/27/2015
     *
     * @version 01.01.01
     */
    public function addArtists($fields)
    {
        global $messageStack;

        $artists = new adminArtistsModel();

        $fields['refresh_date'] = convertJToGDate($fields['refresh_date']);
        $fields['category_id'] = ','.implode(',',$fields['category_id']).',';

        $artists->setFields($fields);
        //$result = $artists->validator();

        /*if ($result['result'] == -1) {
            $this->showArtistsAddForm($fields, $result['msg']);
        }*/
        $result = $artists->save();
        $fields['Artists_id'] = $artists->fields['Artists_id'];

        if(file_exists($_FILES['logo']['tmp_name'])){
            $input['upload_dir'] = ROOT_DIR.'statics/files/'.$fields['Artists_id'].'/';
            $result = fileUploader($input,$_FILES['logo']);
            fileRemover($input['upload_dir'],$artists->fields['logo']);
            $artists->logo = $result['image_name'];
            $result = $artists->save();
        }
        //print_r_debug($_FILES);

        if ($result['result'] != '1') {
            $messageStack->add_session('register', $result['msg']);
            $this->showArtistsAddForm($fields, $result['msg']);
        }
        $msg = 'ثبت نام با موفقیت انجام شد.';
        $messageStack->add_session('register', $msg);

        redirectPage(RELA_DIR.'admin/?component=artists', $msg);
        die();
    }

    /**
     * call register form.
     *
     * @param $fields
     * @param $msg
     *
     * @return mixed
     *
     * @author malekloo
     * @date 14/03/2016
     *
     * @version 01.01.01
     */
    public function showArtistsAddForm($fields, $msg)
    {
        include_once ROOT_DIR.'component/category/admin/model/admin.category.model.php';
        $category = new adminCategoryModel();

        $resultCategory = $category->getCategoryOption();
        if ($resultCategory['result'] == 1) {
            $fields['category'] = $category->list;
        }

        /*include_once ROOT_DIR.'component/city/admin/model/admin.city.model.php';
        $city = new adminCityModel();
        $resultCity = $city->getCities();
        if ($resultCity['result'] == 1) {
            $fields['cities'] = $city->list;
        }*/

        include_once ROOT_DIR.'component/province/admin/model/admin.province.model.php';
        //$province = new adminProvinceModel();
        $province = adminProvinceModel::getAll()->getList();

        //$resultProvince = $province->getStates();
        if ($province['result'] == 1) {
            $fields['provinces'] = $province['export']['list'];
        }




        $this->fileName = 'admin.artists.addForm.php';
        $this->template($fields, $msg);
        die();
    }

    /**
     * @param $fields
     *
     * @return mixed
     *
     * @author malekloo
     * @date 3/16/2015
     *
     * @version 01.01.01
     */
    public function editArtists($fields)
    {
        //$artists = new adminArtistsModel();



        $artists = adminArtistsModel::find($fields['Artists_id']);

        $fields['refresh_date'] = convertJToGDate($fields['refresh_date']);
        $fields['birthday'] = convertJToGDate($fields['birthday']);

        $result = $artists->setFields($fields);

        $temp = implode(",",$artists->fields['category_id']);
        $artists->category_id = ','.$temp.',';

        if ($result['result'] != 1) {
            $this->showArtistsEditForm($fields, $result['msg']);
        }


        $result = $artists->save();

        //$result = $artists->edit();

        if ($result['result'] != '1') {
            $this->showArtistsEditForm($fields, $result['msg']);
        }

        if(isset($fields['showStatus']))
        {
            $action='&action='.$fields['showStatus'];
        }

        if(file_exists($_FILES['logo']['tmp_name'])){

            $input['upload_dir'] = ROOT_DIR.'statics/files/'.$fields['Artists_id'].'/';
            $result = fileUploader($input,$_FILES['logo']);
            fileRemover($input['upload_dir'],$artists->fields['logo']);
            $artists->logo = $result['image_name'];
            $result = $artists->save();
        }


        $msg = 'عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR.'admin/index.php?component=artists'.$action, $msg);
        die();
    }

    /**
     * @param $fields
     *
     * @return mixed
     *
     * @author malekloo
     * @date 3/6/2015
     *
     * @version 01.01.01
     */
    public function showArtistsEditForm($fields, $msg)
    {
        $showStatus=$fields['showStatus'];
        if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
            $artists = new adminArtistsModel();
            $result = $artists->getArtistsById($fields['Artists_id']);
            if ($result['result'] != '1') {
                $msg = $result['msg'];
                redirectPage(RELA_DIR.'admin/index.php?component=artists', $msg);
            }
            $export = $artists->fields;
        } else {
            $export = $fields;
        }



        include_once ROOT_DIR.'component/category/admin/model/admin.category.model.php';
        $category = new adminCategoryModel();

        $resultCategory = $category->getCategoryOption();

        if ($resultCategory['result'] == 1) {
            $export['category'] = $category->list;
        }


        include_once ROOT_DIR.'component/province/admin/model/admin.province.model.php';
        //$province = new adminProvinceModel();
        $province = adminProvinceModel::getAll()->getList();

        //$resultProvince = $province->getStates();
        if ($province['result'] == 1) {
            $export['cities'] = $province['export']['list'];
        }

        /*include_once ROOT_DIR.'component/city/admin/model/admin.city.model.php';
        $city = new adminCityModel();
        $resultCity = $city->getCities();
        if ($resultCity['result'] == 1) {
            $export['cities'] = $city->list;
        }*/

        /*include_once ROOT_DIR.'component/state/admin/model/admin.state.model.php';
        $state = new adminStateModel();
        $resultState = $state->getStates();
        if ($resultState['result'] == 1) {
            $export['states'] = $state->list;
        }

        include_once ROOT_DIR.'component/certification/admin/model/admin.certification.model.php';
        $certification = new adminCertificationModel();

        $resultCertification = $certification->getCertification();
        if ($resultCity['result'] == 1) {
            $export['certifications'] = $certification->list;
        }*/

        $export['showStatus']=$showStatus;
        $this->fileName = 'admin.artists.editForm.php';
        $this->template($export, $msg);
        die();
    }



    public function showList($msg)
    {
        $export['status']='showAll';
        $this->fileName = 'admin.artists.showList.php';
        $this->template($export);
        die();
    }

    /**
     * @param $fields
     *
     * @return mixed
     *
     * @author malekloo
     * @date 3/6/2015
     *
     * @version 01.01.01
     */
    public function search($fields)
    {

        /*echo '<pre/>';
        print_r($fields);
        die();*/

        $artists = new adminArtistsModel();

        include_once(ROOT_DIR . "model/datatable.converter.php");
        $i=0;
        $columns = array(
            array( 'db' => 'Artists_id', 'dt' =>$i++),
            array( 'db' => 'username', 'dt' =>$i++),
            array( 'db' => 'category_id', 'dt' =>$i++),
            array( 'db' => 'email', 'dt' =>$i++),
            array( 'db' => 'artists_phone1', 'dt' => $i++ ),
            array( 'db' => 'artists_name_fa',   'dt' => $i++),
            array( 'db' => 'artists_name_en', 'dt' => $i++ ),
            array( 'db' => 'site', 'dt' => $i++ ),
            array( 'db' => 'status', 'dt' => $i++ ),
            array( 'db' => 'logo', 'dt' => $i++ ),
            array( 'db' => 'Artists_id', 'dt' => $i++ )
        );
        $convert=new convertDatatableIO();
        $convert->input=$fields;
        $convert->columns=$columns;



        $searchFields= $convert->convertInput();

        //$date = date('Y-m-d', strtotime(COMPANY_EXPIRE_PERIOD));
        // print_r_debug($date);
        //$searchFields['where'] = 'where refresh_date < '."'$date'";
        //print_r_debug($searchFields);

        $result = $artists->getArtists($searchFields);

        if ($result['result'] != '1') {
            $this->fileName = 'admin.artists.showList.php';
            $this->template('', $result['msg']);
            die();
        }

        $list['list']=$artists->list;

        $list['paging']=$artists->recordsCount;

        /*$other['2']=array(
            'formatter' =>function($list)
            {
                $st='<div data-artists_id="'.$list['Artists_id'].'" class="artists_phone">'.$list['phone_number'].'</div>';
                return $st;
            }
        );*/
        $other['8']=array(
            'formatter' =>function($list)
            {
                if($list['status']==1) {
                    $st ='فعال';
                }else {
                    $st ='غیر فعال';
                }
                return $st;
            }
        );
        $other['9']=array(
            'formatter' =>function($list)
            {
                $st = "<img height='50' src='".RELA_DIR.'statics/files/'.$list['Artists_id'].'/'.$list['logo']."'>";

                return $st;
            }
        );
        $internalVariable['showstatus']=$fields['status'];
        $other[$i-1]=array(
            'formatter' =>function($list,$internal)
            {
                $st='a'.$list['showstatus'];
                $st='<a href="'. RELA_DIR.'admin/?component=artists&action=edit&id='.$list['Artists_id'].'&showStatus='.$internal['showstatus']
                    .'">ویرایش</a> <br/>
                        <a href="'.RELA_DIR.'admin/?component=product&id='.$list['Artists_id'].'">لیست کارها</a><br/>
                        <a href="'.RELA_DIR.'admin/?component=artists&action=delete&id='.$list['Artists_id'].$list['artists_name'].'">حذف</a>';
                return $st;
            }
        );

        $export= $convert->convertOutput($list,$columns,$other,$internalVariable);
        //print_r_debug($export);
        echo json_encode($export);
        die();
    }

    /**
     * @param $fields
     *
     * @return mixed
     *
     * @author malekloo
     * @date 3/6/2015
     *
     * @version 01.01.01
     */
    public function searchExpire($fields)
    {
        /*echo '<pre/>';
        print_r($fields);
        die();*/

        $artists = new adminArtistsModel();

        include_once(ROOT_DIR . "model/datatable.converter.php");
        $i=0;
        $columns = array(
            array( 'db' => 'Artists_id', 'dt' =>$i++),
            array( 'db' => 'artists_name', 'dt' =>$i++),
            array( 'db' => 'phone_number', 'dt' =>$i++),
            array( 'db' => 'refresh_date',   'dt' => $i++),
            array( 'db' => 'address_address', 'dt' => $i++ ),
            array( 'db' => 'email_email', 'dt' => $i++ ),
            array( 'db' => 'website_url', 'dt' => $i++ ),
            array( 'db' => 'status', 'dt' => $i++ ),
            array( 'db' => 'Artists_id', 'dt' => $i++ )
        );
        $convert=new convertDatatableIO();
        $convert->input=$fields;
        $convert->columns=$columns;
        $searchFields= $convert->convertInput();

        $date = date('Y-m-d', strtotime(COMPANY_EXPIRE_PERIOD));
        // print_r_debug($date);
        $searchFields['where'] = 'where refresh_date < '."'$date'";
        //print_r_debug($searchFields);

        $result = $artists->getArtists($searchFields);
        if ($result['result'] != '1') {
            $this->fileName = 'admin.artists.showList.php';
            $this->template('', $result['msg']);
            die();
        }
        $list['list']=$artists->list;
        $list['paging']=$artists->recordsCount;

        $other['2']=array(
            'formatter' =>function($list)
            {
                $st='<div data-artists_id="'.$list['Artists_id'].'" class="artists_phone">'.$list['phone_number'].'</div>';

                return $st;
            }

        );

        $other['3']=array(
            'formatter' =>function($list)
            {
                $st= convertDate($list['refresh_date']);
                return $st;
            }
        );
        $other['4']=array(
            'formatter' =>function($list)
            {
                $st=convertDate(date('Y-m-d',strtotime(COMPANY_EXPIRE_PERIOD,strtotime($list['refresh_date'])))) ;
                return $st;
            }
        );
        $other['7']=array(
            'formatter' =>function($list)
            {
                if($list['status']==1) {
                    $st ='فعال';
                }else {
                    $st ='غیر فعال';
                }
                return $st;
            }
        );

        $internalVariable['showstatus']=$fields['status'];
        $other[$i-1]=array(
            formatter =>function($list,$internal)
            {
                $st= 'a'.$list['showstatus'];
                $st='<a href="'. RELA_DIR.'admin/?component=artists&action=edit&id='.$list['Artists_id'].'&showStatus='.$internal['showstatus']
                    .'">ویرایش</a> <br/>
                        <a href="'.RELA_DIR.'admin/?component=product&id='.$list['Artists_id'].'">لیست محصولات</a><br/>
                        <a href="'.RELA_DIR.'admin/?component=honour&id='.$list['Artists_id'].'">لیست افتخارات</a><br/>
                        <a href="'.RELA_DIR.'admin/?component=licence&id='.$list['Artists_id'].'">لیست مجوز ها</a><br/>
                        <a href="'.RELA_DIR.'admin/?component=artists&action=delete&id='.$list['Artists_id'].$list['artists_name'].'">حذف</a>';
                return $st;
            }
        );
        $export= $convert->convertOutput($list,$columns,$other,$internalVariable);
        echo json_encode($export);
        die();
    }

    /**
     * @param $fields
     *
     * @return mixed
     *
     * @author malekloo
     * @date 3/6/2015
     *
     * @version 01.01.01
     */
    public function searchUnverified($fields)
    {
        /*echo '<pre/>';
        print_r($fields);
        die();*/

        $artists = new adminArtistsModel();

        include_once(ROOT_DIR . "model/datatable.converter.php");
        $i=0;
        $columns = array(
            array( 'db' => 'Artists_id', 'dt' =>$i++),
            array( 'db' => 'artists_name', 'dt' =>$i++),
            array( 'db' => 'phone_number', 'dt' =>$i++),
            array( 'db' => 'city_name',   'dt' => $i++),
            array( 'db' => 'address_address', 'dt' => $i++ ),
            array( 'db' => 'email_email', 'dt' => $i++ ),
            array( 'db' => 'website_url', 'dt' => $i++ ),
            array( 'db' => 'status', 'dt' => $i++ ),
            array( 'db' => 'logo', 'dt' => $i++ ),
            array( 'db' => 'Artists_id', 'dt' => $i++ )
        );
        $convert=new convertDatatableIO();
        $convert->input=$fields;
        $convert->columns=$columns;
        $searchFields= $convert->convertInput();

        //$date = date('Y-m-d', strtotime(COMPANY_EXPIRE_PERIOD));
        // print_r_debug($date);
        //$searchFields['where'] = 'where refresh_date < '."'$date'";
        //print_r_debug($searchFields);
        $searchFields['where'] = " WHERE  status = '0' ";
        $result = $artists->getArtists($searchFields);
        if ($result['result'] != '1') {
            $this->fileName = 'admin.artists.showList.php';
            $this->template('', $result['msg']);
            die();
        }
        $list['list']=$artists->list;
        $list['paging']=$artists->recordsCount;

        $other['2']=array(
            'formatter' =>function($list)
            {
                $st='<div data-artists_id="'.$list['Artists_id'].'" class="artists_phone">'.$list['phone_number'].'</div>';
                return $st;
            }
        );

        $other['7']=array(
            'formatter' =>function($list)
            {
                if($list['status']==1) {
                    $st ='فعال';
                }else {
                    $st ='غیر فعال';
                }
                return $st;
            }
        );
        $internalVariable['showstatus']=$fields['status'];
        $other[$i-1]=array(
            formatter =>function($list,$internal)
            {
                $st= 'a'.$list['showstatus'];
                $st='<a href="'. RELA_DIR.'admin/?component=artists&action=edit&id='.$list['Artists_id'].'&showStatus='.$internal['showstatus']
                    .'">ویرایش</a> <br/>
                        <a href="'.RELA_DIR.'admin/?component=product&id='.$list['Artists_id'].'">لیست محصولات</a><br/>
                        <a href="'.RELA_DIR.'admin/?component=honour&id='.$list['Artists_id'].'">لیست افتخارات</a><br/>
                        <a href="'.RELA_DIR.'admin/?component=licence&id='.$list['Artists_id'].'">لیست مجوز ها</a><br/>
                        <a href="'.RELA_DIR.'admin/?component=artists&action=delete&id='.$list['Artists_id'].$list['artists_name'].'">حذف</a>';
                return $st;
            }
        );
        $export= $convert->convertOutput($list,$columns,$other,$internalVariable);
        //print_r_debug($export);
        echo json_encode($export);
        die();
    }

    /**
     * @param $fields
     *
     * @return mixed
     *
     * @author malekloo
     * @date 3/6/2015
     *
     * @version 01.01.01
     */
    public function showExpiredList($msg)
    {

        $export['status'] = 'expired';
        $this->fileName = 'admin.artists.showExpireList.php';
        $this->template($export);
        die();
    }

    /**
     * @param $fields
     *
     * @return mixed
     *
     * @author malekloo
     * @date 3/6/2015
     *
     * @version 01.01.01
     */
    public function showUnverifiedList($msg)
    {

        $export['status'] = 'unverified';
        $this->fileName = 'admin.artists.showUnverifiedList.php';
        $this->template($export);
        die();
    }
    /**
     * importCompanies.
     *
     * @return redirectPage
     */
    public function updateCity()
    {
        include_once ROOT_DIR.'component/city/admin/model/admin.city.model.db.php';

        $cityList = adminCityModelDb::getAll()['export']['list'];

        foreach ($cityList as $key=>$fields)
        {

            $province_id= $fields['province_id'];

            echo $province_id;

            $conn = dbConn::getConnection();

            $sql = "
                UPDATE artists
                  SET
                    `state_id`             =   '" . $fields['province_id'] . "'
                    WHERE city_id = '" . $fields['City_id'] . "'
                    ";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            if (!$stmt)
            {
                $result['result'] = -1;
                $result['Number'] = 1;
                $result['msg'] = $conn->errorInfo();
                return $result;
            }





            //print_r_debug($fields);
            $city_id= $fields['City_id'];
            $province_id= $fields['province_id'];
            echo $province_id;
            //echo '<br/>';
            //echo '<br/>$city_id<br/>';
            //echo $city_id;

        }
        die();
        //print_r_debug($cityList);




    }
    /**
     * importCompanies.
     *
     * @return redirectPage
     */
    public function importCompanies()
    {
        include_once dirname(__FILE__).'/admin.artists.model.db.php';
        include_once ROOT_DIR.'component/city/admin/model/admin.city.model.db.php';
        $xml = (STATIC_ROOT_DIR.'/xml/companies.xml');
        $xmlDoc = new DOMDocument();
        $xmlDoc->load($xml);
        $wb = $xmlDoc->getElementsByTagName('Workbook')->item(0);

        $ws = $wb->getElementsByTagName('Worksheet')->item(0);
        $table = $ws->getElementsByTagName('Table')->item(0);
        $row = $table->getElementsByTagName('Row');
        $i = 1;

        foreach ($row as $rowkey => $rowValue) {
            $fields = array();
            $cell = $rowValue->getElementsByTagName('Cell');
            $fields['Artists_id'] = $i;
            $fields['artists_name'] = $cell[19]->getElementsByTagName('Data')[0]->nodeValue;
            $fields['meta_description'] = $cell[16]->getElementsByTagName('Data')[0]->nodeValue;
            $fields['description'] = $cell[16]->getElementsByTagName('Data')[0]->nodeValue;

            $g1 = $cell[6]->getElementsByTagName('Data')[0]->nodeValue;
            $g1s = $cell[5]->getElementsByTagName('Data')[0]->nodeValue;
            $g2 = $cell[4]->getElementsByTagName('Data')[0]->nodeValue;
            $g2s = $cell[3]->getElementsByTagName('Data')[0]->nodeValue;
            $g3 = $cell[2]->getElementsByTagName('Data')[0]->nodeValue;
            $g3s = $cell[1]->getElementsByTagName('Data')[0]->nodeValue;
            $fields['category_list'] = '';
            if ($g1 != '{-}') {
                $fieldsArray = explode(',', $fields['category_list']);
                if (!array_search(($g1 * 100), $fieldsArray)) {
                    $fields['category_list'] .= ','.($g1 * 100);
                }
                if (!array_search((($g1 * 100) + $g1s), $fieldsArray)) {
                    $fields['category_list'] .= ','.(($g1 * 100) + $g1s);
                }
            }
            if ($g2 != '{-}') {
                $fieldsArray = explode(',', $fields['category_list']);
                if (!array_search(($g2 * 100), $fieldsArray)) {
                    $fields['category_list'] .= ','.($g2 * 100);
                }
                if (!array_search((($g2 * 100) + $g2s), $fieldsArray)) {
                    $fields['category_list'] .= ','.(($g2 * 100) + $g2s);
                }
            }
            if ($g3 != '{-}') {
                $fieldsArray = explode(',', $fields['category_list']);
                if (!array_search(($g3 * 100), $fieldsArray)) {
                    $fields['category_list'] .= ','.($g3 * 100);
                }
                if (!array_search((($g3 * 100) + $g3s), $fieldsArray)) {
                    $fields['category_list'] .= ','.(($g3 * 100) + $g3s);
                }
            }
            $fields['category_list']=$fields['category_list'].',';
            //print_r_debug($fields['category_list']);

            $city_name = $cell[13]->getElementsByTagName('Data')[0]->nodeValue;
            $city_id = adminCityModelDb::getCityByName($city_name)['City_id'];
            if ($city_id == '') {
                $fieldsCity = array('city_name' => $city_name);
                //$resultInsetCity = adminCityModelDb::insert($fieldsCity);
                //$city_id = $resultInsetCity['export']['insert_id'];
            }
            $fields['city_id'] = $city_id;


            ///$result = adminArtistsModelDb::insert2($fields);

            // phone 1
            $code = $cell[21]->getElementsByTagName('Data')[0]->nodeValue;
            $number = $cell[22]->getElementsByTagName('Data')[0]->nodeValue;
            $until = $cell[23]->getElementsByTagName('Data')[0]->nodeValue;
            if ($code != '{-}') {
                $fieldsPhone['artists_id'] = $i;
                $fieldsPhone['subject'] = 'تلفن 1';
                $fieldsPhone['number'] = $number;
                if ($until != '{-}') {
                    $fieldsPhone['state'] = 'الی';
                    $fieldsPhone['value'] = $until;
                } else {
                    $fieldsPhone['state'] = 'سایر';
                    $fieldsPhone['value'] = '';
                }
                $result = adminArtistsModelDb::insertToPhones2($fieldsPhone);
            }
            // end phone 1

            // phone 2
            $code = $cell[24]->getElementsByTagName('Data')[0]->nodeValue;
            $number = $cell[25]->getElementsByTagName('Data')[0]->nodeValue;
            $until = $cell[26]->getElementsByTagName('Data')[0]->nodeValue;
            if ($code != '{-}') {
                $fieldsPhone['artists_id'] = $i;
                $fieldsPhone['subject'] = 'تلفن 2';
                $fieldsPhone['number'] = $number;
                if ($until != '{-}') {
                    $fieldsPhone['state'] = 'الی';
                    $fieldsPhone['value'] = $until;
                } else {
                    $fieldsPhone['state'] = 'سایر';
                    $fieldsPhone['value'] = '';
                }
                $result = adminArtistsModelDb::insertToPhones2($fieldsPhone);
            }
            // end phone 2

            // phone 3
            $code = $cell[27]->getElementsByTagName('Data')[0]->nodeValue;
            $number = $cell[28]->getElementsByTagName('Data')[0]->nodeValue;
            $until = $cell[29]->getElementsByTagName('Data')[0]->nodeValue;
            if ($code != '{-}') {
                $fieldsPhone['artists_id'] = $i;
                $fieldsPhone['subject'] = 'تلفن 3';
                $fieldsPhone['number'] = $number;
                if ($until != '{-}') {
                    $fieldsPhone['state'] = 'الی';
                    $fieldsPhone['value'] = $until;
                } else {
                    $fieldsPhone['state'] = 'سایر';
                    $fieldsPhone['value'] = '';
                }
                $result = adminArtistsModelDb::insertToPhones2($fieldsPhone);
            }
            // end phone 3

            // phone 4
            $code = $cell[30]->getElementsByTagName('Data')[0]->nodeValue;
            $number = $cell[31]->getElementsByTagName('Data')[0]->nodeValue;
            $until = $cell[32]->getElementsByTagName('Data')[0]->nodeValue;
            if ($code != '{-}') {
                $fieldsPhone['artists_id'] = $i;
                $fieldsPhone['subject'] = 'تلفن 4';
                $fieldsPhone['number'] = $number;
                if ($until != '{-}') {
                    $fieldsPhone['state'] = 'الی';
                    $fieldsPhone['value'] = $until;
                } else {
                    $fieldsPhone['state'] = 'سایر';
                    $fieldsPhone['value'] = '';
                }
                $result = adminArtistsModelDb::insertToPhones2($fieldsPhone);
            }
            // end phone 4

            // fax 1
            $code = $cell[34]->getElementsByTagName('Data')[0]->nodeValue;
            $number = $cell[35]->getElementsByTagName('Data')[0]->nodeValue;
            $until = $cell[36]->getElementsByTagName('Data')[0]->nodeValue;
            if ($code != '{-}') {
                $fieldsFax['artists_id'] = $i;
                $fieldsFax['subject'] = 'فکس 1';
                $fieldsFax['number'] = $number;
                if ($until != '{-}') {
                    $fieldsFax['state'] = 'الی';
                    $fieldsFax['value'] = $until;
                } else {
                    $fieldsFax['state'] = 'سایر';
                    $fieldsFax['value'] = '';
                }
                $result = adminArtistsModelDb::insertToPhones2($fieldsFax);
            }
            // end fax 1

            // fax 2
            $code = $cell[37]->getElementsByTagName('Data')[0]->nodeValue;
            $number = $cell[38]->getElementsByTagName('Data')[0]->nodeValue;
            $until = $cell[39]->getElementsByTagName('Data')[0]->nodeValue;
            if ($code != '{-}') {
                $fieldsFax['artists_id'] = $i;
                $fieldsFax['subject'] = 'فکس 2';
                $fieldsFax['number'] = $number;
                if ($until != '{-}') {
                    $fieldsFax['state'] = 'الی';
                    $fieldsFax['value'] = $until;
                } else {
                    $fieldsFax['state'] = 'سایر';
                    $fieldsFax['value'] = '';
                }
                $result = adminArtistsModelDb::insertToPhones2($fieldsFax);
            }
            // end fax 2

            // email
            $email = $cell[12]->getElementsByTagName('Data')[0]->nodeValue;
            if ($email != '{-}') {
                $fieldsEmail['artists_id'] = $i;
                $fieldsEmail['subject'] = 'ایمیل';
                $fieldsEmail['email'] = $email;
                $result = adminArtistsModelDb::insertToEmails2($fieldsEmail);
            }
            // end email

            // address
            $address = $cell[14]->getElementsByTagName('Data')[0]->nodeValue;
            if ($address != '{-}') {
                $fieldsAddresses['artists_id'] = $i;
                $fieldsAddresses['subject'] = 'آدرس';
                $fieldsAddresses['address'] = $address;
                $result = adminArtistsModelDb::insertToAddresses2($fieldsAddresses);
            }
            // end address

            // website
            $website = $cell[11]->getElementsByTagName('Data')[0]->nodeValue;
            if ($website != '{-}') {
                $fieldsWebsite['artists_id'] = $i;
                $fieldsWebsite['subject'] = 'وب سایت';
                $fieldsWebsite['website'] = $website;
                $result = adminArtistsModelDb::insertToWebsites2($fieldsWebsite);
            }
            // end website

            /*if ($i % 10 == 0) {
                echo $i;
                echo '<br>';
                die();
            }*/
            ++$i;
            //flush();
            //ob_flush();
            //ob_end_clean();
        }

        $msg = 'ایمپورت انجام شد';
        redirectPage(RELA_DIR.'admin/index.php?component=artists', $msg);
    }

    /**
     * importArtistsPhones.
     *
     * @return redirectPage
     */
    public function importArtistsPhones()
    {
        include_once dirname(__FILE__).'/admin.artists.model.db.php';
        $xml = (STATIC_ROOT_DIR.'/xml/artists-phones.xml');
        $xmlDoc = new DOMDocument();
        $xmlDoc->load($xml);
        $wb = $xmlDoc->getElementsByTagName('Workbook')->item(0);
        $ws = $wb->getElementsByTagName('Worksheet')->item(0);
        $table = $ws->getElementsByTagName('Table')->item(0);
        $row = $table->getElementsByTagName('Row');
        $i = 1;
        foreach ($row as $rowkey => $rowValue) {
            $fields = array();
            $cell = $rowValue->getElementsByTagName('Cell');
            $artistsId = $cell[0]->getElementsByTagName('Data')[0]->nodeValue;
            $fields['artists_id'] = $cell[0]->getElementsByTagName('Data')[0]->nodeValue;
            $fields['number'] = $cell[1]->getElementsByTagName('Data')[0]->nodeValue;
            $fields['state'] = $cell[2]->getElementsByTagName('Data')[0]->nodeValue;
            $fields['value'] = $cell[3]->getElementsByTagName('Data')[0]->nodeValue;
            $fields['subject'] = 'تلفن';
            $result = adminArtistsModelDb::insertToPhones2($fields);

            if ($i % 100 == 0) {
                echo $i;
                echo '<br>';
            }
            ++$i;
            flush();
            ob_flush();
            ob_end_clean();
        }

        $msg = 'ایمپورت انجام شد';
        redirectPage(RELA_DIR.'admin/index.php?component=artists', $msg);
    }
    /**
     * importArtistsEmails.
     *
     * @return redirectPage
     */
    public function importArtistsEmails()
    {
        include_once dirname(__FILE__).'/admin.artists.model.db.php';
        $xml = (STATIC_ROOT_DIR.'/xml/artists-emails.xml');
        $xmlDoc = new DOMDocument();
        $xmlDoc->load($xml);
        $wb = $xmlDoc->getElementsByTagName('Workbook')->item(0);
        $ws = $wb->getElementsByTagName('Worksheet')->item(0);
        $table = $ws->getElementsByTagName('Table')->item(0);
        $row = $table->getElementsByTagName('Row');
        $i = 1;
        foreach ($row as $rowkey => $rowValue) {
            ob_start();
            $fields = array();
            $cell = $rowValue->getElementsByTagName('Cell');
            $artistsId = $cell[0]->getElementsByTagName('Data')[0]->nodeValue;
            $fields['artists_id'] = $cell[0]->getElementsByTagName('Data')[0]->nodeValue;
            $fields['subject'] = 'ایمیل';
            $fields['email'] = $cell[1]->getElementsByTagName('Data')[0]->nodeValue;
            $result = adminArtistsModelDb::insertToEmails2($fields);

            echo $i;
            // if($i % 100 == 0){
            //     echo "<br>";
            // }
            ++$i;
            flush();
            ob_flush();
            ob_end_clean();
        }

        $msg = 'ایمپورت انجام شد';
        redirectPage(RELA_DIR.'admin/index.php?component=artists', $msg);
    }
    /**
     * importArtistsAddresses.
     *
     * @return redirectPage
     */
    public function importArtistsAddresses()
    {
        include_once dirname(__FILE__).'/admin.artists.model.db.php';
        $xml = (STATIC_ROOT_DIR.'/xml/artists-addresses.xml');
        $xmlDoc = new DOMDocument();
        $xmlDoc->load($xml);
        $wb = $xmlDoc->getElementsByTagName('Workbook')->item(0);
        $ws = $wb->getElementsByTagName('Worksheet')->item(0);
        $table = $ws->getElementsByTagName('Table')->item(0);
        $row = $table->getElementsByTagName('Row');
        $i = 1;
        foreach ($row as $rowkey => $rowValue) {
            $fields = array();
            $cell = $rowValue->getElementsByTagName('Cell');
            $artistsId = $cell[0]->getElementsByTagName('Data')[0]->nodeValue;
            $fields['artists_id'] = $cell[0]->getElementsByTagName('Data')[0]->nodeValue;
            $fields['subject'] = 'آدرس';
            $fields['address'] = $cell[1]->getElementsByTagName('Data')[0]->nodeValue;
            $result = adminArtistsModelDb::insertToAddresses2($fields);

            if ($i % 100 == 0) {
                echo $i;
                echo '<br>';
            }
            ++$i;
            flush();
            ob_flush();
            ob_end_clean();
        }

        $msg = 'ایمپورت انجام شد';
        redirectPage(RELA_DIR.'admin/index.php?component=artists', $msg);
    }
    /**
     * importArtistsWebsites.
     *
     * @return redirectPage
     */
    public function importArtistsWebsites()
    {
        include_once dirname(__FILE__).'/admin.artists.model.db.php';
        $xml = (STATIC_ROOT_DIR.'/xml/artists-websites.xml');
        $xmlDoc = new DOMDocument();
        $xmlDoc->load($xml);
        $wb = $xmlDoc->getElementsByTagName('Workbook')->item(0);
        $ws = $wb->getElementsByTagName('Worksheet')->item(0);
        $table = $ws->getElementsByTagName('Table')->item(0);
        $row = $table->getElementsByTagName('Row');
        $i = 1;
        foreach ($row as $rowkey => $rowValue) {
            $fields = array();
            $cell = $rowValue->getElementsByTagName('Cell');
            $artistsId = $cell[0]->getElementsByTagName('Data')[0]->nodeValue;
            $fields['artists_id'] = $cell[0]->getElementsByTagName('Data')[0]->nodeValue;
            $fields['subject'] = 'وب سایت';
            $fields['url'] = $cell[1]->getElementsByTagName('Data')[0]->nodeValue;
            $result = adminArtistsModelDb::insertToWebsites2($fields);

            if ($i % 100 == 0) {
                echo $i;
                echo '<br>';
            }
            ++$i;
            flush();
            ob_flush();
            ob_end_clean();
        }

        $msg = 'ایمپورت انجام شد';
        redirectPage(RELA_DIR.'admin/index.php?component=artists', $msg);
    }
    /**
     * delete deleteArtists by artists_id.
     *
     * @param $id
     *
     * @author malekloo
     * @date 2/24/2015
     *
     * @version 01.01.01
     */

    public function deleteArtists($id)
    {
        $artists = new adminArtistsModel();


        if (!validator::required($id) and !validator::Numeric($id)) {
            $msg = 'یافت نشد';
            redirectPage(RELA_DIR.'admin/index.php?component=artists', $msg);
        }
        $result = $artists->getArtistsById($id);
        $file = $result['export']['list']['logo'];

        if ($result['result'] != '1') {
            $msg = $result['msg'];
            redirectPage(RELA_DIR.'admin/index.php?component=artists', $msg);
        }

        include_once ROOT_DIR.'component/product/admin/model/admin.product.model.php';
        $product = adminProductModel::getBy_artists_id($id)->get();


        if ($product['export']['recordsCount'] > 0) {
            $msg = 'توجه : ابتدا محصولات این کمپانی را حذف تنایید.';
            redirectPage(RELA_DIR.'admin/index.php?component=artists', $msg);
        }

        $result = $artists->delete();
        fileRemover(ROOT_DIR.'statics/files/'.$id.'/',$file);

        include_once (ROOT_DIR.'component/product/admin/model/admin.product.model.php');



        if ($result['result'] != '1') {
            redirectPage(RELA_DIR.'admin/index.php?component=artists', $msg);
        }

        $msg = 'عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR.'admin/index.php?component=artists', $msg);
        die();
    }
    public function call($fields)
    {
        include_once dirname(__FILE__).'/php-ami-class.php';
        $conn = new AstMan();
        $ret = $conn->clickToCall($fields['number']);
        die();
    }

    public function getArtistsphone($input)
    {
        $artists_id =   $input['artists_id'];
        include_once dirname(__FILE__).'/admin.artists.model.php';
        $model = new adminArtistsModel();
        $result = $model->getArtistsphoneAll($artists_id);
        $phone='';
        foreach ($result['export']['list'] as $key => $value ){
            $phone .='<h4><a class="btn btn-default artists_allphone label label-default" href="#" role="button" data-myphonenumber="'.$value.'" data-myartistsid="'.$artists_id.'"><span class="glyphicon glyphicon-phone-alt"></span></a><span>'.$value.'</span></h4>';

        }
        echo $phone;
        //print_r_debug($result );
        //json_encode($result);
         die();

    }

    public function getCityAjax($input)
    {
        $province_id =$input['province_id'];
        include_once ROOT_DIR.'/component/city/admin/model/admin.city.model.php';
        $model = new adminCityModel();
        $result = $model->getCitiesByprovinceID($province_id);

        $option='';
        foreach ($result['export']['list'] as $key => $value){
            $option.="<option>".$value['name']."</option>";
        }
        echo $option;

        die();

    }

}
