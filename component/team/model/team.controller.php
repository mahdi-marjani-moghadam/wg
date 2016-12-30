<?php
/**
 * Created by PhpStorm.
 * User: marjani
 * Date: 3/10/2016
 * Time: 10:21 AM.
 */
include_once dirname(__FILE__).'/team.model.php';

/**
 * Class teamController.
 */
class teamController
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
     * teamController constructor.
     */
    public function __construct()
    {
        $this->exportType = 'html';
    }

    /**
     * call tempate.
     *
     * @param array $list
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
     * show all team.
     *
     * @param $_input
     *
     * @author marjani
     * @date 3/10/2015
     *
     * @version 01.01.01
     */
    public function showMore($_input)
    {
        if (!is_numeric($_input)) {
            $msg = 'یافت نشد';
            $this->fileName = 'team.showList.php';
            $this->template('', $msg);
            die();
        }
        $team = new teamModel();
        $result = $team->getTeamById($_input);

        if ($result['result'] != 1) {
            $this->fileName = 'team.showList.php';
            $this->template('', $result['msg']);
            die();
        }

        // breadcrumb
        global $breadcrumb;
        $breadcrumb->reset();
        $breadcrumb->add('بنر');
        $breadcrumb->add($team['list']['title']);
        $export['breadcrumb'] = $breadcrumb->trail();

        $this->fileName = 'team.showMore.php';
        $this->template($team->fields);
        die();
    }

    /**
     * @param $fields
     *
     * @author marjani
     * @date 3/10/2015
     *
     * @version 01.01.01
     */
    public function showALL($fields)
    {
        $team = new teamModel();
        $result = $team->getTeam($fields);
        if ($result['result'] != '1') {
            die();
        }
        $export['list'] = $team->list;
        $export['recordsCount'] = $team->recordsCount;
        $export['pagination'] = $team->pagination;

        // breadcrumb
        global $breadcrumb;
        $breadcrumb->reset();
        $breadcrumb->add('بنر');
        $export['breadcrumb'] = $breadcrumb->trail();

        $this->fileName = 'team.showList.php';
        $this->template($export);
        die();
    }
}
