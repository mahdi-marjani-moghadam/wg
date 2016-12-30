<?php
/**
 * Created by PhpStorm.
 * User: marjani
 * Date: 2/27/2016
 * Time: 9:21 AM.
 */
include_once dirname(__FILE__).'/contactus.model.php';

/**
 * Class contactusController.
 */
class contactusController
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
     * contactusController constructor.
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
    public function template($list = array(), $msg)
    {
        global $messageStack;

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
     * add contactus.
     *
     * @param $_input
     *
     * @return int|mixed
     *
     * @author marjani
     * @date 2/27/2015
     *
     * @version 01.01.01
     */
    public function addContactus($_input)
    {
        global $messageStack;

        $contactus = new contactusModel();
        $result = $contactus->setFields($_input);
        if ($result['result'] == -1) {
            //$messageStack->add_session('contactus',$result['msg'] , 'error');
            $this->showContactusForm($_input,$result['msg']);
        }

        $result = $contactus->addContactus();

        if ($result['result'] != '1') {
            $this->showContactusForm($_input, $result['msg']);
        }
        $msg = 'عملیات با موفقیت انجام شد';
        $messageStack->add_session('contactus',$msg, 'success');
        redirectPage(RELA_DIR.'contactus', $msg);
        die();
    }

    /**
     * call contact us form.
     *
     * @author marjani
     * @date 2/27/2015
     *
     * @version 01.01.01
     */
    public function showContactusForm($_input, $msg)
    {
        // breadcrumb
        global $breadcrumb;
        $breadcrumb->reset();
        $breadcrumb->add('تماس با ما');
        $export['list'] = $_input;
        $export['breadcrumb'] = $breadcrumb->trail();

        $this->fileName = 'contactus.form.php';
        $this->template($export, $msg);
        die();
    }
}
