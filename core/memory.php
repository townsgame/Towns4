<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/memory.php

   Tento soubor slouží ke správě paměti sezení - tabulka [mpx]memory, (Towns nepoužívá PHP session, ale má vlastní systém).
*/
//==============================




/*NEJVĚTŠÍ PÍČOVINA NA SVĚTĚ!!!! define('memory_time',3600*5);*/
//================================================SESSIONSTART

//session_cache_expire(9999);
//session_start();
//if($_GET['s']){$ssid=$_GET['s'];}else{$ssid=rand(10000,99999);}

function randy(){
//return(rand(1000000000,9999999999));
return(md5($_SERVER["REMOTE_ADDR"].rand(1000000000,2147483647).uniqid(rand(), true)));
}




if($_GET['y']=='y'){header('location: http://'.$_SERVER['HTTP_HOST'].str_replace('y=y','y='.randy(),$_SERVER['REQUEST_URI']));exit;}
if($_GET['y']){
	//e('getssid');
	$ssid=$_GET['y'];
}else{
	//e('cookie');
	if($_COOKIE['TOWNSSESSID']){$ssid=$_COOKIE['TOWNSSESSID'];}else{$ssid=randy();setcookie("TOWNSSESSID", $ssid,time()+(3600*24*356*4));}
}
//e($ssid);
define('ssid',$ssid);
//$GLOBALS['ss']=$_SESSION['ss'];
$GLOBALS['ss']=array();
foreach(sql_array('SELECT `key`, `value` FROM [mpx]memory WHERE id=\''.ssid.'\'') as $row){
    list($key,$value)=$row;
    $GLOBALS['ss'][$key]=unserialize($value);
}
t("memory_load");
$GLOBALS['ss_']=$GLOBALS['ss'];
//unset($_SESSION['ss']);
//print_r($GLOBALS['ss']);
//---------------------------------
function exit2($e=false){
	//echo('exit2');
	if($e)echo($e);
	//print_r($GLOBALS['ss']);
	$values='';$tmp='';
	foreach($GLOBALS['ss'] as $key=>$value){
	    
	    if($GLOBALS['ss_'][$key]!=$value){
	       if(!is_object($value)){
                $value=addslashes(serialize($value));
                //if(strlen($value)>7000)$value=serialize('');
                if($value)$values.=$tmp."('".ssid."','$key','".($value)."','".time()."')";
                $tmp=',';
            }
        }
	}
	$deletes='';$tmp='';
	foreach($GLOBALS['ss_'] as $key=>$value){    
	    if($GLOBALS['ss'][$key]!=$value){
            $deletes=$deletes.$tmp." `key`='$key' ";
            $tmp='OR';
        }
	}
	//echo($values);
        
    sql_query(create_sql('memory'));
    
    
    
    
    if($deletes)sql_query('DELETE FROM [mpx]memory WHERE (`id`=\''.ssid.'\' AND ('.$deletes.'))'/*.' OR `time`<'.(time()-memory_time)*/);
    if($values)sql_query('INSERT INTO [mpx]memory (`id`, `key`, `value`, `time`) VALUES '.$values.';');	
	//e($values);
	//mysql_close();
	//$_SESSION['ss']=$GLOBALS['ss'];
	t("memory_save");
        //---------------------------------------------------------------------------------------------------timeplan
        //if(timeplan){
            $click=sql_1data('SELECT MAX(click) FROM `[mpx]timeplan`')-1+2;
            if($GLOBALS['timeplan2sql']!=array()){
                $sql='INSERT INTO `[mpx]timeplan` (`click`,`key`, `text`, `ms`, `uri`, `logid`, `useid`, `time`) VALUES';
                $separator=''; 
                foreach($GLOBALS['timeplan2sql'] as $row){
                    list($key,$text,$ms)=$row;
                    $sql.=$separator." ($click ,'".sql($key)."', '".sql($text)."', ".sql($ms).", '".sql($_SERVER['REQUEST_URI'])."', ".sql($GLOBALS['ss']['logid']).", ".sql($GLOBALS['ss']['useid']).", now())";
                    $separator=',';
                }
                $sql.=';';
                
                sql_query(create_sql('timeplan').';'.$sql);
                
            }
        //}
        
        //---------------------------------------------------------------------------------------------------
	exit;
}
//================================================
//GRM413
?>