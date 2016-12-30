<?php

/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:24 AM.
 */
class categoryModel
{
    /**
     * @var
     */
    private $TableName;
    /**
     * @var
     */
    private $fields;  // other record fields
    /**
     * @var
     */
    private $list;  // other record fields
    /**
     * @var
     */
    private $recordsCount;  // other record fields
    /**
     * @var
     */
    public $listCat;
    /**
     * @var int
     */
    public $level = 0;

    /**
     * @var
     */
    private $result;

    /**
     *
     */
    public function __construct()
    {
        /* $this->fields = array(
                                 'title'=>  '',
                                 'brif_description'=>  '',
                                 'description'=>  '',
                                 'meta_keyword'=>  '',
                                 'meta_description'=>  '',
                                 'image'=>  '',
                                 'date'=>  ''
                                 );*/
    }

    /**
     * @param $field
     *
     * @return mixed
     */
    public function __get($field)
    {
        if ($field == 'result') {
            return $this->result;
        } elseif ($field == 'fields') {
            return $this->fields;
        } elseif ($field == 'list') {
            return $this->list;
        } elseif ($field == 'recordsCount') {
            return $this->recordsCount;
        } else {
            return $this->fields[$field];
        }
    }

    /**
     * @param $input
     *
     * @return mixed
     */
    public function setFields($input)
    {
        foreach ($input as $field => $val) {
            $funcName = '__set'.ucfirst($field);
            if (method_exists($this, $funcName)) {
                $result = $this->$funcName($val);
                if ($result['result'] == 1) {
                    $this->fields[$field] = $val;
                } else {
                    return $result;
                }
            }
        }
        $result['result'] = 1;

        return $result;
    }

    /**
     * @param $input
     *
     * @return mixed
     */
    private function __setTitle($input)
    {
        if (!Validator::required($input)) {
            $result['result'] = -1;
            $result['msg'] = 'pleas enter title';
        } else {
            $result['result'] = 1;
        }

        return $result;
    }

    /**
     * @param $input
     *
     * @return mixed
     */
    private function __setBrif_description($input)
    {
        if ($input == '') {
            $result['result'] = 1;
        } elseif (!Validator::required($input)) {
            $result['result'] = -1;
            $result['msg'] = 'pleas enter Brif description';
        } else {
            $result['result'] = 1;
        }

        return $result;
    }

    /**
     * @param $input
     *
     * @return mixed
     */
    private function __setDescription($input)
    {
        if ($input == '') {
            $result['result'] = 1;
        } elseif (!Validator::required($input)) {
            $result['result'] = -1;
            $result['msg'] = 'pleas enter Description';
        } else {
            $result['result'] = 1;
        }

        return $result;
    }

    /**
     * @param $input
     *
     * @return mixed
     */
    private function __setMeta_keyword($input)
    {
        if ($input == '') {
            $result['result'] = 1;
        } elseif (!Validator::required($input)) {
            $result['result'] = -1;
            $result['msg'] = 'pleas enter Meta_keyword';
        } else {
            $result['result'] = 1;
        }

        return $result;
    }

    /**
     * @param $input
     *
     * @return mixed
     */
    private function __setMeta_description($input)
    {
        if ($input == '') {
            $result['result'] = 1;
        } elseif (!Validator::required($input)) {
            $result['result'] = -1;
            $result['msg'] = 'pleas enter Meta_description';
        } else {
            $result['result'] = 1;
        }

        return $result;
    }

    /**
     * @param $input
     *
     * @return mixed
     */
    private function __setDate($input)
    {
        if ($input == '') {
            $result['result'] = 1;
        } elseif (!Validator::required($input)) {
            $result['result'] = -1;
            $result['msg'] = 'pleas enter Date';
        } else {
            $result['result'] = 1;
        }

        return $result;
    }

    /**
     * @param $input
     *
     * @return mixed
     */
    private function __setImage($input)
    {
        if ($input == '') {
            $result['result'] = 1;
        } elseif (!Validator::required($input)) {
            $result['result'] = -1;
            $result['msg'] = 'pleas enter Image';
        } else {
            $result['result'] = 1;
        }

        return $result;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getCategoryById($id)
    {
        include_once dirname(__FILE__).'/category.model.db.php';

        $result = categoryModelDb::getCategoryById($id);

        if ($result['result'] != 1) {
            return $result;
        }

        $this->fields = $result['list'];

        return $result;
    }

    /**
     * @param $_input
     *
     * @return mixed
     */
    public function convert($_input, $temp, $space = '-')
    {
        static $mainMenu = '';
        //echo $this->level;
        foreach ($_input as $key => $val) {
            $mainMenu[$val['Category_id']]['export'] = $temp.$val['title'];
            $mainMenu[$val['Category_id']]['title'] = $val['title'];
            $mainMenu[$val['Category_id']]['level'] = $this->level;

            $temp = $temp.$space;
            ++$this->level;
            if (isset($this->listCat[$val['Category_id']])) {
                $this->convert($this->listCat[$val['Category_id']], $temp, $space);
            }
            --$this->level;
            $len = strlen($space);
            $temp = substr($temp, 0, -($len));
        }

        return $mainMenu;
    }

    /**
     * @param $fields
     *
     * @return mixed
     */
    public function getCategoryUlLi($parent_id = 0)
    {
        $result = $this->getCategoryTree();
        $mainMenu = $this->convertTreetoLiUl($this->list[$parent_id], 1, $this->list);
        //$mainMenu = "<ul>\n".$mainMenu ."</ul>";

        $result['result'] = 1;
        $result['export']['list'] = $mainMenu;

        return $result;
    }


    public function getCategoryUlLiSearch($CategoryTree,$parent_id = 0)
    {


        $mainMenu = $this->convertTreetoLiUlSearch($CategoryTree[$parent_id], 1, $CategoryTree);
        //$mainMenu = "<ul>\n".$mainMenu ."</ul>";
        $result['result'] = 1;
        $result['export']['list'] = $mainMenu;

        return $result;
    }    

    public function convertTreetoLiUl($array, $root = 0, $all)
    {
        static $mainMenu = '';
        static $mainList;
        if ($root == 1) {
            $mainList = $all;
        }

        $mainMenu .= "<ul>\n";
        foreach ($array as $key => $val) {
            $cityStr = '';
            if (isset($_SESSION['city'])) {
                $cityStr = $_SESSION['city'].'/';
            }

            if (is_array($mainList[$val['Category_id']])) {
                $mainMenu .= '
                    <li>
                        <a href="'.RELA_DIR.$cityStr.'artists/'.(strlen($val['url']) ? $val['Category_id'].'/'.$val['url'] : '#').'">'.$val['title'].'</a>';
                $this->convertTreetoLiUl($mainList[$val['Category_id']]);
                $mainMenu .= '</li>';
            } else {
                $mainMenu .= "\t".'
                    <li>
                        <a href="'.RELA_DIR.$cityStr.'artists/'.(strlen($val['url']) ? $val['Category_id'].'/'.$val['url'] : '#').'">'.$val['title'].'</a>
                    </li>
                '."\n";
            }
            //$mainMenu .= "</ul>\n";
            --$this->level;
        }
        $mainMenu .= "</ul>\n";
        return $mainMenu;

    }
    public function convertTreetoLiUlSearch($array, $root = 0, $all)
    {
        static $mainMenu = '';
        static $mainList;
        if ($root == 1) {
            $mainList = $all;
        }

        $mainMenu .= "<ul>\n";
        foreach ($array as $key => $val) {


            if (is_array($mainList[$val['Category_id']])) {
                $mainMenu .= '
                    <li>
                        <a class="company-name"><span>('.$val['count'].')</span>
                            <label for="category-'.$val['Category_id'] .'" class="company-name">'.$val['title'].
                                '<input type="checkbox" name="category[]" id="category-'.$val['Category_id'] .'" value="'.$val['Category_id'].'">
                            </label>
                        </a>';
                    $this->convertTreetoLiUlSearch($mainList[$val['Category_id']]);
                $mainMenu .= '</li>';

            } else {
                $mainMenu .= "\t".'
                     <li>
                        <a class="company-name"><span>('.$val['count'].')</span>
                            <label for="category-'.$val['Category_id'] .'" class="company-name">'.$val['title'].
                    '<input type="checkbox" name="category[]" id="category-'.$val['Category_id'] .'" value="'.$val['Category_id'].'">
                            </label> </a></li>'."\n";
            }
            //$mainMenu .= "</ul>\n";
            --$this->level;
        }
        $mainMenu .= "</ul>\n";
        return $mainMenu;

        /*


                $mainMenu .= "<ul>\n";

                foreach ($array as $key => $val) {
                    $cityStr = '';
                    if (isset($_SESSION['city'])) {
                        $cityStr = $_SESSION['city'].'/';
                    }

                    if (is_array($mainList[$val['Category_id']])) {
                        $mainMenu .= "\t".'<li class="pull-right fa fa-angle-left transition">'."\n";
                        $mainMenu .= '<a class="hyperLink text-center" href="'.RELA_DIR.$cityStr.'company/'.(strlen($val['url']) ? $val['Category_id'].'/'.$val['url'] : '#').'"><i class="fa fa-link"></i></a>';
                        $mainMenu .= '<span class="link text-right transition text-light">'.$val['title'].'</span>';
                        $mainMenu .= '<div class="mp-level">';
                        $mainMenu .= '<h2 class="text-right transition">';
                        $mainMenu .= '<a class="link text-regular transition" href="'.RELA_DIR.$cityStr.'company/'.(strlen($val['url']) ? $val['Category_id'].'/'.$val['url'] : '#').'">لیست همه '.$val['title'].'</a>';
                        $mainMenu .= '</h2>';
                        $mainMenu .= '<a class="mp-back text-right transition" href="#">بازگشت</a>';
                        $this->convertTreetoLiUl($mainList[$val['Category_id']]);
                        $mainMenu .= "</div>\n";
                        $mainMenu .= "</li>\n";
                    } else {
                        $mainMenu .= "\t".'<li class="pull-right transition">';
                        $mainMenu .= '<a class="link text-right transition" href="'.RELA_DIR.$cityStr.'company/'.(strlen($val['url']) ? $val['Category_id'].'/'.$val['url'] : '#').'">';
                        $mainMenu .= '<span class="hyperLink text-center"><i class="fa fa-link"></i></span>';
                        $mainMenu .= $val['title'];
                        $mainMenu .= '</a>';
                        $mainMenu .= "</li>\n";
                    }
                    //$mainMenu .= "</ul>\n";
                    --$this->level;
                }
                $mainMenu .= "</ul>\n";
                return $mainMenu;
        */

        /*foreach($array as $item){
            if(is_array($item) && isset($item['name']))
            {
                echo "<ul>\n";

                if(is_array($item['children'])){
                    echo "<li>".$item['name'];
                    getulli($item);
                    echo "</li>\n";
                } else {
                    echo "<li>".$item['name']."</li>\n";
                }

                echo "</ul>\n\n";
            }
        }*/
    }

    public function getCategoryTree($fields='')
    {
        include_once dirname(__FILE__).'/category.model.db.php';
        $result = categoryModelDb::tree_set();
        $this->list = $result['export']['list'];
        $this->recordsCount = $result['export']['recordsCount'];

        return $result;
    }

    public function allCategory()
    {

        include_once dirname(__FILE__).'/category.model.db.php';
        $result = categoryModelDb::getCategoryAll();
        $this->list = $result['export']['list'];
        $this->recordsCount = $result['export']['recordsCount'];

        return $result;

    }
    public function getCategoryOption($parent_id = 0, $space = '-')
    {
        include_once dirname(__FILE__).'/category.model.db.php';

        $result = categoryModelDb::tree_set();
        if ($result['result'] != 1) {
            return $result;
        }
        $fields = $result['export']['list'];
        $this->listCat = $fields;

        $export = $this->convert($fields[$parent_id], '', $space);
        $count = 1;
        foreach ($export as $key => $val) {
            $export[$key]['dataTableCount'] = $count;
            ++$count;
        }

        $this->list = $export;
        $this->recordsCount = count($export);

        $result['result'] = 1;
        $result['export']['list'] = $export;
        $result['export']['recordsCount'] = $this->recordsCount;

        return $result;
    }
    //*******************************
    public function getCategoryByfor($fields)
    {
        include_once dirname(__FILE__).'/admin.category.model.db.php';

        $result = adminCategoryModelDb::tree_set();
        $fields = $result['export']['list'];
        $this->listCat = $fields;
        $result = $this->convert($fields['1']);

        $config['root']['list']['open'] = '<ul>';

        $config['1']['list']['open'] = '<ul>';

        $config['all']['list']['open'] = '<ul>';
        $config['all']['list']['close'] = '</ul>';

        $config['all']['node']['open'] = '<li>';
        $config['all']['node']['close'] = '</li>';

        $config['all']['node-noChild']['open'] = '<li>no';
        $config['all']['node-noChild']['close'] = '</li>';

        echo '<pre>';
        $st = '<ul>';
        //print_r($result);
        //$next = (next($result));

        $key_list = array_keys($result);
        $key_count = 0;
        foreach ($result as $key => $val) {
            ++$key_count;
            $next_key = $key_list[$key_count];
            //echo '<br/>key=' . $key . '<br/>next=' . $next_key;
            //echo $val['title'];
            if ($next_key == '') {
                $next = -1;
            } else {
                $next = $result[$next_key];
            }

            //print_r($result[$next]['level']);
            if ($next['level'] > $val['level'] and $next != -1) {
                $open_list = 'true';

                if (isset($config[$val['level']]['node']['open'])) {
                    $st = $st.$config[$val['level']]['node']['open'].PHP_EOL;
                } else {
                    $st = $st.$config['all']['node']['open'].PHP_EOL;
                }

                $st = $st.$val['title'].PHP_EOL;
                $st = $st.$config['all']['list']['open'].PHP_EOL;
            } elseif ($next['level'] < $val['level'] and $next != -1) {
                $open_list = 'false';

                $st = $st.$config['all']['node-noChild']['open'].PHP_EOL;
                $st = $st.$val['title'].PHP_EOL;

                for ($i = 1; $i <=  ($val['level'] - $next['level']); ++$i) {
                    $st = $st.$config['all']['node']['close'].PHP_EOL;
                    $st = $st.$config['all']['list']['close'].PHP_EOL;
                }
                $st = $st.$config['all']['node']['close'].PHP_EOL;
            } elseif ($val['level'] == $next['level'] and $next != -1) {
                $open_list = '';

                if (isset($config[$val['level']]['node-noChild']['open'])) {
                    $st = $st.$config[$val['level']]['node-noChild']['open'].PHP_EOL;
                    $st = $st.$val['title'].PHP_EOL;
                    $st = $st.$config['all']['node-noChild']['close'].PHP_EOL;
                } else {
                    $st = $st.$config['all']['node-noChild']['open'].PHP_EOL;
                    $st = $st.$val['title'].PHP_EOL;
                    $st = $st.$config['all']['node-noChild']['close'].PHP_EOL;
                }
            } elseif ($next == -1) {
                $open_list = '';

                if (isset($config[$val['level']]['node']['open'])) {
                    $st = $st.$config[$val['level']]['node-noChild']['open'].PHP_EOL;
                    $st = $st.$val['title'].PHP_EOL;
                    //$st = $st . $config['all']['node']['close'] . PHP_EOL;
                    //$st = $st . $config['all']['list']['close'] . PHP_EOL;
                } else {
                    $st = $st.$config['all']['node-noChild']['open'].PHP_EOL;
                    $st = $st.$val['title'].PHP_EOL;
                    //$st = $st . $config['all']['node']['close'] . PHP_EOL;
                    //$st = $st . $config['all']['list']['close'] . PHP_EOL;
                }
                for ($i = 1; $i <=  ($val['level'] - $next['level']); ++$i) {
                    $st = $st.$config['all']['node']['close'].PHP_EOL;
                    $st = $st.$config['all']['list']['close'].PHP_EOL;
                }
                $st = $st.$config['all']['node']['close'].PHP_EOL;
            }
        }
        $st = $st.'</ul>';

        echo '<br/>start<br/>'.$st, '<br/>close<br/>';

        //**************

        //print_r($result);

        die();

        if ($result['result'] != 1) {
            return $result;
        }
        $this->list = $result['export']['list'];
        $this->recordsCount = $result['export']['recordsCount'];

        return $result;
    }

    public function getCategoryParents($parentId)
    {
        include_once dirname(__FILE__).'/category.model.db.php';
        $result = categoryModelDb::getCategoryParents($parentId);
        $this->list = array_reverse($result['export']['list']);

        return $result;
    }

    public function getCategoryChildes($categoryId)
    {
        include_once dirname(__FILE__).'/category.model.db.php';
        $result = categoryModelDb::getCategoryChildes($categoryId);
        $this->list = $result['export']['list'];

        return $result;
    }
}
