<?php
/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:24 PM.
 */
include_once dirname(__FILE__).'/product.model.php';

/**
 * Class articleController.
 */
class productController
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
     *  get Product By Company Id.
     *
     * @param $id
     *
     * @author malekloo
     * @date 3/29/2016
     *
     * @version 01.01.01
     */
    public function getProductByCompanyId($id)
    {
        $product = new productModel();
        $result = $product->getProductByCompanyId($id);

        if ($result['result'] != '1') {
            $msg = 'not found';
            redirectPage(RELA_DIR, $msg);
        }

        // // breadcrumb
        // global $breadcrumb;
        // $breadcrumb->reset();
        // $breadcrumb->add('محصولات');
        // $export['breadcrumb'] = $breadcrumb->trail();

        $this->fileName = 'product.showMore.php';
        $this->template($product->fields);
        die();
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
    public function showProductDetail($id)
    {
        $product = new productModel();
        $result = $product->getProductById($id);
        if ($result['result'] != '1') {
            $msg = 'not found';
            redirectPage(RELA_DIR, $msg);
        }
        $export['list'] = $product->fields;

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




        // other products
        $otherProduct = new productModel();
        $resultOtherProducts = $otherProduct->getProductByArtistsId($product->fields['artists_id']);
//        print_r_debug($resultOtherProducts);
        if ($resultOtherProducts['result'] == 1) {
            $export['other_product_list'] = $resultOtherProducts['export']['list'];
        }

        // related products
        /*include_once ROOT_DIR.'component/product/model/product.model.php';
        $relatedProduct = new productModel();
        $resultRelatedProducts = $relatedProduct->getRelatedProducts($id);
        if ($resultRelatedProducts['result'] == 1) {
            $export['related_products_list'] = $resultRelatedProducts['export']['list'];
        }*/

        // breadcrumb
        global $breadcrumb;
        if (!isset($_SERVER['HTTP_REFERER'])) {
            unset($_SESSION['companyBreadcrumb']);
            unset($_SESSION['productBreadcrumb']);
            $breadcrumb->reset();
            $breadcrumb->add('هنرمند : '.$product->fields['artists_name'], 'artists/Detail/'.$product->fields['artists_id'].'/'.$product->fields['artists_name'], true);
        } else {
            $reqReferer = urldecode($_SERVER['HTTP_REFERER']);
            $reqRefererArray = explode('/', urldecode($_SERVER['HTTP_REFERER']));
            if (isset($_SESSION['companyBreadcrumb'])) {
                if ((array_search('artists', $reqRefererArray) && array_search('Detail', $reqRefererArray)) || array_search('product', $reqRefererArray)) {
                    $breadcrumb = unserialize($_SESSION['companyBreadcrumb']);
                } else {
                    $breadcrumb->reset();
                    $breadcrumb->add('هنرمند : '.$product->fields['artists_name'], 'artists/Detail/'.$product->fields['artists_id'].'/'.$product->fields['artists_name'], true);
                }
            } elseif (isset($_SESSION['productBreadcrumb'])) {
                if (array_search('product', $reqRefererArray)) {
                    $breadcrumb = unserialize($_SESSION['productBreadcrumb']);
                } else {
                    $breadcrumb->reset();
                    $breadcrumb->add('هنرمند : '.$product->fields['artists_name'], 'artists/Detail/'.$product->fields['artists_id'].'/'.$product->fields['artists_name'], true);
                }
            } else {
                $searchIndex = array_search('search', $reqRefererArray);
                if ($searchIndex) {
                    $qIndex = array_search('q', $reqRefererArray);
                    if ($qIndex) {
                        $breadcrumb->add('جست و جوی : '.$reqRefererArray[$qIndex + 1], $reqReferer, true);
                    } else {
                        $breadcrumb->add('جست و جو', $reqReferer, true);
                    }
                    unset($_SESSION['productBreadcrumb']);
                    $_SESSION['productBreadcrumb'] = serialize($breadcrumb);
                } else {
                    unset($_SESSION['companyBreadcrumb']);
                    $breadcrumb->reset();
                    $breadcrumb->add('هنرمند : '.$product->fields['artists_name'], 'artists/Detail/'.$product->fields['artists_id'].'/'.$product->fields['artists_name'], true);
                }
            }
        }
        $breadcrumb->add('محصول : '.$product->fields['title']);
        $export['breadcrumb'] = $breadcrumb->trail();

        $this->fileName = 'product.showDetail.php';
//        print_r_debug($export);
        $this->template($export);
        die();
    }

    public function pushRate($fields)
    {
        $product = new productModel();
        $result = $product->pushRate($fields);
        return $result;

    }
}
