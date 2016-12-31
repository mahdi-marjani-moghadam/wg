<?php

include_once dirname(__FILE__).'/model/portfolio.controller.php';
//include_once ROOT_DIR.'common/looeic.php';

global $PARAM , $admin_info;

$portfolio = new portfolioController();


$portfolio->showAll();