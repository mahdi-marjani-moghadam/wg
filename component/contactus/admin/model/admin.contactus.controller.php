<?php
/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:24 PM
 */

include_once(dirname(__FILE__) . "/admin.contactus.model.php");

/**
 * Class contactusController
 */
class adminContactusController
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
     *
     */
    public function __construct()
    {
        $this->exportType = 'html';

    }

    /**
     * @param string $list
     * @param $msg
     * @return string
     */
    function template($list = array(), $msg)
    {
        // global $conn, $lang;


        switch ($this->exportType)
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
     * @param $_input
     *
     */
    public function showMore($_input)
    {
        if (!is_numeric($_input))
        {
            $msg = 'یافت نشد';
            $this->showList('', $msg);
        }
        $contactus = new adminContactusModel();
        $result = $contactus->getContactusById($_input);

        if ($result['result'] != 1)
        {
            $msg = 'یافت نشد';
            $this->showList('', $msg);
        }
        $this->fileName = "admin.contactus.showMore.php";
        $this->template($contactus->fields);
        die();
    }


    /**
     * @param $fields
     */
    public function showList($fields, $msg)
    {
        $contactus = new adminContactusModel();
        $result = $contactus->getContactus($fields);
        if ($result['result'] != '1')
        {
            $this->fileName = 'admin.contactus.showList.php';
            $this->template('', $result['msg']);
            die();
        }
        $export['list'] = $contactus->list;
        $export['recordsCount'] = $contactus->recordsCount;
        $this->fileName = 'admin.contactus.showList.php';

        $this->template($export, $msg);
        die();
    }

    /**
     * @param $fields
     * @param $msg
     */
    public function showContactusAddForm($fields, $msg)
    {

        $this->fileName = 'admin.contactus.addForm.php';
        $this->template($fields, $msg);
        die();
    }

    /**
     * @param $fields
     * @return mixed
     */
    public function addContactus($fields)
    {
        $contactus = new adminContactusModel();

        $result = $contactus->setFields($fields);

        if ($result['result'] == -1)
        {
            return $result;
        }
        $result = $contactus->add();

        if ($result['result'] != '1')
        {
            $this->showContactusAddForm($fields, $result['msg']);
        }
        $msg = 'عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR . "admin/index.php?component=contactus", $msg);
        die();
    }

    /**
     * @param $fields
     * @param $msg
     */
    public function showContactusEditForm($fields, $msg)
    {

        $contactus = new adminContactusModel();


        if (!validator::required($fields['Contactus_id']) and !validator::Numeric($fields['Contactus_id']))
        {
            $msg = 'یافت نشد';
            redirectPage(RELA_DIR . "admin/index.php?component=contactus", $msg);
        }
        $result = $contactus->getContactusById($fields['Contactus_id']);

        if ($result['result'] != '1')
        {
            $msg = $result['msg'];
            redirectPage(RELA_DIR . "admin/index.php?component=contactus", $msg);
        }

        $export = $contactus->fields;

        $this->fileName = 'admin.contactus.editForm.php';
        $this->template($export, $msg);
        die();
    }

    /**
     * @param $fields
     */
    public function editContactus($fields)
    {
        $contactus = new adminContactusModel();

        if (!validator::required($fields['Contactus_id']) and !validator::Numeric($fields['Contactus_id']))
        {
            $msg = 'یافت نشد';
            redirectPage(RELA_DIR . "admin/index.php?component=contactus", $msg);
        }
        $result = $contactus->getContactusById($fields['Contactus_id']);
        if ($result['result'] != '1')
        {
            $msg = $result['msg'];
            redirectPage(RELA_DIR . "admin/index.php?component=contactus", $msg);
        }


        $result = $contactus->setFields($fields);


        if ($result['result'] != 1)
        {
            $this->showContactusEditForm($fields, $result['msg']);
        }

        $result = $contactus->edit();

        if ($result['result'] != '1')
        {
            $this->showContactusEditForm($fields, $result['msg']);
        }
        $msg = 'عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR . "admin/index.php?component=contactus", $msg);
        die();
    }

    /**
     * delete contactus by contactus_id
     *
     * @param $fields
     * @author malekloo,marjani
     * @date 2/24/2015
     * @version 01.01.01
     */
    public function deleteContactus($fields)
    {
        $contactus = new adminContactusModel();

        if (!validator::required($fields['Contact_id']) and !validator::Numeric($fields['Contact_id']))
        {
            $msg = 'یافت نشد';
            redirectPage(RELA_DIR . "admin/index.php?component=contactus", $msg);
        }
        $result = $contactus->getContactusById($fields['Contact_id']);
        if ($result['result'] != '1')
        {
            $msg = $result['msg'];
            redirectPage(RELA_DIR . "admin/index.php?component=contact", $msg);
        }
        $result = $contactus->setFields($fields);

        if ($result['result'] != 1)
        {
            $this->showContactusEditForm($fields, $result['msg']);
        }
        $result = $contactus->delete();

        if ($result['result'] != '1')
        {
            $this->showContactusEditForm($fields, $result['msg']);
        }
        $msg = 'عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR . "admin/index.php?component=contactus", $msg);
        die();
    }
}

?>