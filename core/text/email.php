<?php/* Towns4, www.towns.cz    © Pavel Hejný | 2011-2013   _____________________________   core/text/email.php   Rozesílání E-mailů*///==============================//---------------------------------------Zobrazení e-mailu - tzn. načtení obrázku logaif($_GET['action']=='logo'){	$id=sql_1number('SELECT id FROM [mpx]emails WHERE `key`='.sqlx($_GET['key']));	if($id){		//------------------INSERT emails_activity 		sql_insert('emails_activity',array( 			'id' => $id, 			'key' => 'view', 			'url' => ''		)); 		//------------------ 	}	header('Content-Type: image/png');	readfile('ui/image/logo/logo.png');	die();}//---------------------------------------Klikif($_GET['action']=='click'){	$id=sql_1number('SELECT id FROM [mpx]emails WHERE `key`='.sqlx($_GET['key']));	if($id){			//------------------INSERT emails_activity 			sql_insert('emails_activity',array( 				'id' => $id, 				'key' => 'click', 				'url' => sql($_GET['url'])			)); 			//------------------	}//if id	header('Location: '.$_GET['url']);	die();}//---------------------------------------Unsubscribeif($_GET['action']=='unsubscribe'){	$mailid=sql_1number('SELECT id FROM [mpx]emails WHERE `key`='.sqlx($_GET['key']));	if($mailid){			//------------------INSERT emails_activity 			sql_insert('emails_activity',array( 				'id' => $mailid, 				'key' => 'unsubscribe'			)); 			//-------------------------------Zjištění id uživatele			$id=sql_1number('SELECT `to` FROM [mpx]emails WHERE `key`='.sqlx($_GET['key']));			if($id){				//------------------UPDATE users 				sql_update('users',"id='$id' AND aac=1",array( 					'sendmail' => '0'				)); 				//------------------Zjištění všech hráčů k tomuto uživateli				foreach(sql_array('SELECT id,`profile` FROM [mpx]objects WHERE userid='.$id) as $user){					list($objectid,$profile)=$user;					//ebr($profile);hr();					$profile=new set($profile);					//print_r($profile->vals2list());					$profile->add('sendmail2','0');					$profile->add('sendmail3','0');					$profile=$profile->vals2str();					//------------------UPDATE objects 					sql_update('objects',"id='$objectid'",array( 						'profile' => $profile					)); 					//------------------				}//foreach			}//if id		//-------------------------------	}//if id ?>        <!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">        <html><head>        <title>World Not Found</title>        <meta http-equiv="refresh"   content="6; url=<?php e(url); ?>">		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />        </head><body>        <?php		success(lr('email_unsubscribed'));		e('<a href="'.url.'">'.lr('goto_towns').'</a>');		?>        </body></html>        <?php			die();}//----------------------------------------------------------------------------Odeslání e-mailuini_set("max_execution_time","1000");if($_GET['refresh'])header('refresh:'.($_GET['refresh'])); $array=sql_array('SELECT `id`,`to`,`subject`,`text`,`key`,`world` FROM [mpx]emails WHERE `stop` IS NULL AND `try`<5 ORDER BY try,start');foreach($array as $row){    list($id,$to,$subject,$text,$key,$world)=$row;    if(is_numeric($to)){        $to=sql_1data('SELECT email FROM [mpx]users WHERE id='.sql($to));    }    //$tmp=sql_array('SELECT email,sendmail FROM [mpx]users WHERE id='.sql($to).' LIMIT 1');    //$list($to,$sendmail)=$tmp[0];        //if()    	//	$unsubscribe=url."?e=text-email&key=$key&action=unsubscribe";    $headers  = "From: ".lr('email_from_'.$world)."\r\n";     $headers .= "Reply-to: ".lr('email_from_'.$world)."\r\n";     //NESMYSL//$headers .= "To: hejny.pavel@gmail.com\r\n";     $headers .= "List-Unsubscribe: <$unsubscribe>\r\n"; 	$headers .= "Content-Type: text/html; charset=\"utf-8\"\r\n";	$headers .= "Content-Transfer-Encoding: base64\r\n";	$headers .= "X-Mailer: PHP/" . phpversion()."\r\n";	$headers .= "MIME-Version: 1.0\r\n";	//$subject="Nejlepsi budova za posledni tyden – Velky stavitel a Sklad";	//$text="Vždy ve středu vyberu nejzajímavější kombinaci budov, která vznikla za poslední týden.  Autor budovy dostane 20 000 zlata.";	$text=lr('email_header').$text;    	//-------------------------------------------Nahrazení klikacích odkazů 	for($i=0;($tmp=substr2($text,'href="','"',$i) and $i<200);$i++){		    $tmp=urlencode($tmp);		    $tmp=url.'?e=text-email&amp;action=click&key='.$key.'&url='.$tmp;		    $text=substr2($text,'href="','"',$i,$tmp);    }	$logourl=url.'?e=text-email&amp;action=click&key='.$key.'&url='.urlencode(url.'#logo');	//-------------------------------------------Odhlašovací odkaz	$text.='<span style="color: #888888">';	$text.=lr('email_signature',x2xx($unsubscribe));	$text.='</span>';	//-------------------------------------------	ob_start();//===============================================================================================================================================================================================?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0"/><title><?php le('email_title'); ?></title><style type="text/css"><!--body {font-family: trebuchet ms;}body,td,th {color: #111111;text-decoration: none;}a:link {color: #111111;text-decoration: none;}a:visited {color: #111111;text-decoration: none;}a:hover {color: #111111;text-decoration: none;}a:active {color: #111111;text-decoration: none;}a {font-family: trebuchet ms;text-decoration: none;}--></style></head><body>  <table width="100%" border="0" cellpadding="0" cellspacing="0">  <tr>    <td </body></html><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td align="right" width="20"><a href="<?= $logourl ?>"><img src="<?php e(url); ?>?e=text-email&amp;key=<?= $key ?>&amp;action=logo" alt="" height="60" border="0" /></a></td><td><span style="font-size: 19px;"><b>&nbsp;&nbsp;&nbsp;<a href="<?= $logourl ?>"><?php le('email_top'); ?></a></b></span></td></tr></table><?php e($text); ?></body></html><?php	/*<tr>	<td colspan="2" bgcolor="#222222" height="2">	</td></tr>*///===============================================================================================================================================================================================	$text = ob_get_contents();    ob_end_clean();        //-----------------------------------------------ZOBRAZOVAC        textb('FROM: ');        e(lr('email_from_'.$world));        br();        textb('TO: ');        e($to);        br();        textb('SUBJECT: ');        e($subject);        br();        textb('TEXT: ');        hr();        e($text);		hr();        textb('HEADERS: ');        br();        e(nl2br(htmlspecialchars($headers)));        br(2);    //-----------------------------------------------        $text = base64_encode($text);	$subject = "=?utf-8?B?" . base64_encode($subject) . "?=";	//$to='hejny.pavel@gmail.com';    if(mail($to,$subject,$text,$headers)){    sql_query('UPDATE [mpx]emails SET stop=now(),try=try+1 WHERE id='.$id,2);    }else{    sql_query('UPDATE [mpx]emails SET try=try+1 WHERE id='.$id,2);    }	hr();}//-------------------------------------------------Vlastní statistika//qlog(0,0,0,'bot',NULL,NULL);//-------------------------------?>