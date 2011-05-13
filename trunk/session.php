<?php

 define('__ZBXE__', true);
 require_once('../../config/config.inc.php');
 $oContext = &Context::getInstance();
 $oContext->init();
    
 $logged_info = Context::get('logged_info'); 
 $id = $logged_info->user_id;
    
 if($logged_info)
    {
  echo("
   로그인 하셨네요.^^ 
   <br>$id 회원님이시네요.
  ");
 } 
 else
 {
  echo("
   로그인 해주셔야죠.^^    
  ");
 }
?>