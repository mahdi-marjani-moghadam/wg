<?php

include_once dirname(__FILE__).'/model/admin.portfolio.controller.php';
include_once ROOT_DIR.'common/looeic.php';

global $PARAM , $admin_info;

$portfolio = new adminPortfolioController();

if( isset($_GET['action']) )
{
      if($_GET['action'] == 'addPortfolio')
      {
         if(isset($_POST['action']) && $_POST['action'] == 'add')
         {
              $portfolio->addPortfolio($_POST);
         }
          else
          {
              $portfolio->showPortfolioAddForm();
          }
      }
      elseif($_GET['action']=='edit')
      {
          if(isset($_POST['action']) && $_POST['action'] == 'edit')
          {
              $portfolio->editPortfolio($_POST);
          }
          else
          {
              $portfolio->showPortfolioEditForm($_GET);
          }
      }
      elseif($_GET['action']=='delete')
      {
          $portfolio->deletePortfolio($_GET['id']);

      }
      elseif($_GET['action'] == 'showOtherPic')
      {
          $portfolio->getPictureContent($_GET['id']);
      }
      elseif($_GET['action'] == 'getContent')
      {
          $portfolio->getContent();
      }
}

else
{

    //$portfolio->showPortfolioList();
    $portfolio->getContent();
}