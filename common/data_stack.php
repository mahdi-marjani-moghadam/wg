<?php
/**
 * Created by PhpStorm.
 * User: FaridCS
 * Date: 12/31/2014
 * Time: 4:01 PM
 */

class dataStack {
    private $_data;

    /**
     * construct of class
     */
    public function __construct() {

        $this->_data = array();
    }

    public function add($class, $dataForm) {

        $this->_data = array('class' => $class, 'data' => $dataForm);
    }

    public function add_session($class, $dataForm) {

        if (isset($_SESSION['dataStack'])) {

            unset($_SESSION['dataStack']);
        }

        $dataStack = array('class' => $class, 'data' => $dataForm);

        $_SESSION['dataStack'] = $dataStack;

        $this->add($class, $dataForm);
    }

    public function output($class) {
        $dataForm = array();

        if ($_SESSION['dataStack']['class'] == $class) {

            $dataForm = $_SESSION['dataStack']['data'];
        }

        unset($_SESSION['dataStack']);

        return $dataForm;
    }


}
