<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/create/build.php

   Budova před postavením zpráva
*/
//==============================


$x=$_GET['xx'];
$y=$_GET['yy'];
$id=$_GET['id'];

$a='<div style="border-width: 2px; border-style: solid; border-color: #222222;text-align:center;width:300px;">';
$b='</div>';

a_create($id,$x,$y,NULL,true,$_GET['master']);
if(defined('create_error')){
e($a);error(create_error,false);e($b);
?><script type="text/javascript">$('#build_button').css('display','none');</script><?php
}else{

if(defined('create_ok')){e($a);success(create_ok,false);e($b);}
?><script type="text/javascript">$('#build_button').css('display','block');</script><?php
}


//e("($x,$y)");
?>
