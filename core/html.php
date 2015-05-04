<?php/* Towns4, www.towns.cz   © Pavel Hejný | 2011-2015   _____________________________   core/html.php   V tomto souboru je html "obal".*///==============================//--------------------------------------------------------------------Minifikacerequire_once 'lib/minify.php';function output($buffer){    $options=array(        //'jsMinifier'=> 'slib_compress_script'    );	$buffer=new Minify_HTML($buffer,$options);	return($buffer->process());}ob_start("output");//--------------------------------------------------------------------base HTML$app=array();$app['title']=lr('app_title');$app['description']=lr('app_title');$app['og_title']=lr('app_og_title');$app['og_description']=lr('app_og_description');$app['og_url']=url;$app['og_image']=imageurl('logo/1024xB.png');//--------------------------------------------------------------------Special permalink$GLOBALS['mapgtid']=false;$GLOBALS['mapgtx']=false;$GLOBALS['mapgty']=false;//   /^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/iif(preg_match('/^[0-9]{0,999}$/',$GLOBALS['object_permalink'])){//@todo Jak udělat 1-n znaků v RegExp?    //@todo možná? přesměřovat z id(a souřadnic?) na name    $GLOBALS['mapgtid']=intval($GLOBALS['object_permalink']);    //die($GLOBALS['mapgtid']);}elseif(preg_match('/^[0-9]{0,999}(-[0-9]{0,999}){1,3}$/',$GLOBALS['object_permalink'])){//[.0-9] - float coords    list($x,$y,$tmp)=explode('-',$GLOBALS['object_permalink'],3);//@todo zprovoznit souřadnice w a starttime    $GLOBALS['mapgtx']=intval($x);//@todo možná? pokud je na dane souradnici nejaky objekt, zobrazit i jeho profil    $GLOBALS['mapgty']=intval($y);}else{    //@todo co s objekty, které nejsou?    $GLOBALS['mapgtid']=sql_1number('SELECT id FROM [mpx]pos_obj WHERE permalink='.sqlx($GLOBALS['object_permalink']).' AND '.objt());}//die();if($GLOBALS['mapgtid']){//e('bbb');    $result = sql_row("SELECT `x`,`y`,`type`,`id`,`name`,`res` FROM `[mpx]pos_obj` WHERE id=".sqlx($GLOBALS['mapgtid']).' AND '.objt());    if($result['type']=='story') {//e('ccc');        $GLOBALS['open_content']='text-story';        //$GLOBALS['afterjs'] = "w_open('content','story','?id=" . $result['id'] . "');";        $GLOBALS['ss']["storyid"]=$result['id'];        $result['res'] = explode(':',$result['res'],2);        $result['res'] = $result['res'][1];        $app['description']=$result['res'];        $app['og_description']=$app['description']=strip_tags($app['description']);        if(strpos($result['res'],'<img')) {            $img = substr2($result['res'],'<img','>');            $img = substr2($img, 'src="', '"');            $img=html_entity_decode($img);            $img=imgresizewurl($img,450);            $app['og_image']=$img;        }    }    if($result['id']) {        $GLOBALS['mapgtx'] = $result['x'];        $GLOBALS['mapgty'] = $result['y'];        $app['og_title'] = $app['title'] = lr('apps_title', ucfirst(contentlang($result['name'])));        $app['og_url'] = url . $result['id'];    }}//--------------------------------------------------------------------/*<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="cz"><!DOCTYPE html><html lang="<?=$GLOBALS['ss']['lang'] ?>" dir="ltr">    <link rel="shortcut icon" href="../favicon.ico">    <link href="favicon.ico" rel="../favicon.ico" type="image/x-icon" />*/?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="cz"<head>    <title><?=$app['title']?></title>    <meta name="description" content="<?=addslashes($app['description'])?>" />    <meta charset="UTF-8">    <meta http-equiv="Content-language" content="<?=$GLOBALS['ss']['lang'] ?>">    <meta http-equiv="imagetoolbar" content="no" />    <meta property="fb:app_id" content="<?=fb_appid ?>" >    <meta property="og:site_name" content="<?=lr('app_og_name') ?>" >    <meta property="og:title" content="<?=addslashes($app['og_title'])?>" >    <meta property="og:description" content="<?=addslashes($app['og_description'])?>" >    <meta property="og:type" content="game" >    <meta property="og:url" content="<?=addslashes($app['og_url'])?>" >    <meta property="og:image" content="<?=addslashes($app['og_image'])?>" />    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0"/><?php//-------------------------------------------------Knihovnyhtmljscss();//Načtení knihoven//-------------------------------------------------Vlastní statistikaqlog('html');//-------------------------------------------------?></head><body><?php /*if($GLOBALS['inc']['google_page']){ ?><a href="https://plus.google.com/<?php e($GLOBALS['inc']['google_page']); ?>" rel="publisher"></a><?php }*/ //@todo: wtf??><div id="fb-root"></div><script type="text/javascript"></script><div style="width:100%;height:100%;" id="html_fullscreen"><table width="100%" height="100%"><tr><td align="center" valign="center"><?php include(root.core."/page/loading.php"); ?></td></tr></table></div><?php if(edit){ ?><iframe src="admin/?page=lang" width="100%" height="100%"></iframe><?php } ?><?phpif($_GET['ref']){$GLOBALS['ss']['ref']=$_GET['ref'];}//e('aaa');$baseurl='?token='.ssid        .'&e=-html_fullscreen'        .'&width=\' + ww + \'&height=\' + hh + \''        .($_GET['j']?'&j='.$_GET['j']:'')        .($_GET['addposition']?'&addposition='.$_GET['addposition']:'')        .($GLOBALS['mapgtx']?'&mapgtx='.$GLOBALS['mapgtx']:'')        .($GLOBALS['mapgty']?'&mapgty='.$GLOBALS['mapgty']:'')        .($GLOBALS['open_content']?'&open_content='.$GLOBALS['open_content']:'')        ;$logname=is_object($GLOBALS['ss']['log_object'])?$GLOBALS['ss']['log_object']->name:'';?><script type="text/javascript">token='<?=$_GET['token']?>';map_units_time=<?php e(time()); ?>;<?php /*----------------------------------------------------------------------------------------------------------------*/ ?>apptime=<?php e(filemtime(core.'/page/aac.php')); ?>;nacitacihtml=$('#html_fullscreen').html();logged=false;first=true;function reloc(first){if(first==undefined){first=0;}else{first=1;}<?php if($_GET['width']){e('ww='.$_GET['width'].';');}else{e('ww=$(window).width();');} ?><?php if($_GET['height']){e('hh='.$_GET['height'].';');}else{e('hh=$(window).height();');} ?>$('#html_fullscreen').html(nacitacihtml);_gaq.push(['_trackEvent', 'html', 'reloc', '<?=$logname ?>']);$.get('<?php e($baseurl); ?><?phpecho($_POST['write_text']?'&write_text='.urlencode($_POST['write_text']):'');?>&first='+first, function(vystup){    $('#html_fullscreen').html(vystup);});}<?php /*----------------------------------------------------------------------------------------------------------------*/ ?>x2xx = function(text){<?php    $i=0;    foreach($GLOBALS['ss']["vals_a"] as $val_a){        if($val_a!=nln and $val_a!='[' and $val_a!=']'){            e("text=text.split('".addslashes($val_a)."').join('".$GLOBALS['ss']["vals_bb"][$i]."');");        }        $i++;    }?>return(text);}<?php /*---------------------------------------------*/ ?>function register(username,password,email,sendmail){<?php if($_GET['width']){e('ww='.$_GET['width'].';');}else{e('ww=$(window).width();');} ?><?php if($_GET['height']){e('hh='.$_GET['height'].';');}else{e('hh=$(window).height();');} ?>register_username=$('#register_username').val();register_password=$('#register_password').val();register_email=$('#register_email').val();register_sendmail=$("#register_sendmail").is(':checked') ? true : false ;$('#html_fullscreen').html(nacitacihtml);username=x2xx(username);password=x2xx(password);email=x2xx(email);_gaq.push(['_trackEvent', 'html', 'register', username]);$.get('<?php e($baseurl); ?>&q=register,'+(username)+','+(password)+','+(email)+','+(sendmail)+'&register_try=1', function(vystup){    $('#html_fullscreen').html(vystup);        $('#register_username').val(register_username);    $('#register_password').val(register_password);    $('#register_email').val(register_email);    $("#register_sendmail").attr('checked', register_sendmail);    });}<?php /*----------------------------------------------------------------------------------------------------------------*/ ?>function logout(){logged=false;<?php if($_GET['width']){e('ww='.$_GET['width'].';');}else{e('ww=$(window).width();');} ?><?php if($_GET['height']){e('hh='.$_GET['height'].';');}else{e('hh=$(window).height();');} ?>$('#html_fullscreen').html(nacitacihtml);_gaq.push(['_trackEvent', 'html', 'logout', '<?=$logname ?>']);$.get('<?php e($baseurl); ?>&q=logout', function(vystup){$('#html_fullscreen').html(vystup);});}<?php /*----------------------------------------------------------------------------------------------------------------*/ ?><?php		$tdiff=time()-$GLOBALS['ss']['log_object']->t;        e('reloc(1);');?></script></body></html>