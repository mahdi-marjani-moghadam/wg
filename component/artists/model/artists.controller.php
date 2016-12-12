<?php
/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:24 PM.
 */
include_once dirname(__FILE__).'/artists.model.php';


/**
 * Class articleController.
 */
class artistsController
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
     * articleController constructor.
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
        // global $conn, $lang;
        global $PARAM,$member_info;
        switch ($this->exportType) {
            case 'html':

                include ROOT_DIR.'templates/'.CURRENT_SKIN.'/title.inc.php';
                include ROOT_DIR.'templates/'.CURRENT_SKIN."/$this->fileName";
                include ROOT_DIR.'templates/'.CURRENT_SKIN.'/tail.inc.php';
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
     * show all article.
     *
     * @param $_input
     *
     * @author marjani
     * @date 2/28/2016
     *
     * @version 01.01.01
     */
    public function showDetail($id)
    {

        // get artists
        $artists = new artistsModel();
        $result = $artists->getArtistsById($id);

        if ($result['result'] == '1') {
            $export['list'] = $artists->fields;
        } else {
            $msg = 'not found';
            redirectPage(RELA_DIR, $msg);
        }

        // get artists certifications
        /*include_once ROOT_DIR.'component/certification/admin/model/admin.certification.model.php';
        $certification = new adminCertificationModel();
        $resultCertification = $certification->getCertificationByIdArray($export['list']['certification_id']);
        if ($resultCertification['result'] == 1) {
            $export['certification_list'] = $resultCertification['export']['list'];
        }*/

        // get related companies
        /*$resultRelatedCompanies = $artists->getRelatedCompanies($id);
        if ($resultRelatedCompanies['result'] == 1) {
            $export['related_companies_list'] = $resultRelatedCompanies['export']['list'];
        }*/

        // get artists products
        include_once ROOT_DIR.'component/product/model/product.model.php';
        $product = new productModel();

        $resultProduct = $product->getProductByArtistsId($id);

        if ($resultProduct['result'] == 1) {
            $export['product_list'] = $resultProduct['export']['list'];
        }

        //use category model func by getCategoryUlLi
        include_once ROOT_DIR.'component/category/model/category.model.php';
        $category = new categoryModel();
        $resultCategory = $category->getCategoryUlLi();
        if ($resultCategory['result'] == 1) {
            $export['category_list'] = $resultCategory['export']['list'];
        }

        $resultCategoryAll = $category->allCategory();
        if ($resultCategoryAll['result'] == 1) {
            $export['category_list_all'] = $resultCategoryAll['export']['list'];
        }

        // include artists licences
        /*include_once ROOT_DIR.'component/licence/admin/model/admin.licence.model.php';
        $licence = new adminLicenceModel();
        $resultLicence = $licence->getLicenceByArtistsId($id);
        if ($resultLicence['result'] == 1) {
            $export['licence_list'] = $resultLicence['export']['list'];
        }*/
        $this->fileName = 'artists.showDetail.php';
        $this->template($export);
        die();
    }

    /**
     * get all article and  show in list.
     *
     * @param $fields
     *
     * @author marjani
     * @date 2/28/2016
     *
     * @version 01.01.01
     */
    public function showALL($fields)
    {
        global $PARAM;


        include_once ROOT_DIR.'component/category/model/category.model.php';
        $category = new categoryModel();
         $category_id = $fields['chose']['category_id'];

        $resultCategory = $category->getCategoryChildes($category_id);
        //print_r_debug($resultCategory);
        if ($resultCategory['result'] != 1 and $resultCategory['no'] != '100') {
            $msg = 'not found';
            redirectPage(RELA_DIR, $msg);
        }
        $resultCategory2 = $category->getCategoryUlLi(0);

        $export['export']['category'] = $resultCategory2['export']['list'];
        //print_r_debug($resultCategory2);

        foreach ($resultCategory['export']['list'] as $key => $value) {
            $category_id .= ','.$key;
        }



        $fields['condition']['category_id'] = $category_id;
        //$fields['condition']['city_id'] = $fields['chose']['city_id'];

        $artists = new artistsModel();
        $result = $artists->getArtists($fields);
        if ($result['result'] != '1') {
            $msg = 'not found';
            redirectPage(RELA_DIR, $msg);
        }


        $export['list'] = $artists->list;
        $export['recordsCount'] = $artists->recordsCount;
        $export['pagination'] = $artists->pagination;
        if ($artists->recordsCount == '0') {
            $msg = 'رکوردی یافت نشد.';
        }


        ///////////////// article
        /*include_once ROOT_DIR.'/component/article/model/article.model.php';
        $article = new articleModel();

        $result = $article->getArticleByCategoryId($category_id);
        //echo "<pre>"; print_r($result); die();
        $export['article_list'] = $result['export']['list'];*/
        /////////////////////////

        // breadcrumb
        global $breadcrumb;
        $breadcrumb->reset();
        $resultCategoryParents = $category->getCategoryParents($fields['chose']['category_id']);


        if ($resultCategoryParents['result'] == 1) {
            foreach ($category->list as $key => $value) {
                $breadcrumb->add($value['title'], 'artists/'.$value['Category_id'].'/1', true);
            }
        }
        // print_r_debug($resultCategoryParents);
        // $breadcrumb->add($resultCategory['list']['title']);
        $export['breadcrumb'] = $breadcrumb->trail();


        $this->fileName = 'artists.showList.php';

        $this->template($export, $msg);
        die();
    }
}
