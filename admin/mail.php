<?php/* Towns4Admin, www.towns.cz    © Pavel Hejný | 2011-2014   _____________________________   admin/...   Towns4Admin - Nástroje pro správu Towns*///==============================?><h3>E-mail novinky</h3><?php$array=sql_array("SELECT post_title,post_content,guid,post_name FROM `".sql($GLOBALS['inc']['wp_posts'])."` WHERE `post_type` = 'post' AND `post_status` = 'publish' AND `post_password` = '' ORDER BY `ID` DESC LIMIT 1");list($post_title,$post_content,$guid,$post_name)=$array[0];$post_content=substr2($post_content,'width="','"',0, '100%');$post_content=substr2($post_content,'width="100%"','>',0, ' ');$subject=$post_title;$text="<p style=\"text-align: center\"><b>$post_title</b></p>".nl2br($post_content).lr('email_news_reply',x2xx($guid));$array=sql_array("SELECT `id`,`email`,`sendmail` FROM `[mpx]users` WHERE email!='' AND email!='@' AND sendmail=1 GROUP BY email ORDER BY id");   $i=0;$emails=array();$userids=array();foreach($array as $row){   list($userid,$email,$sendmail)=$row;   /*if($email and $mail!='@' and $sendmail){	   if($email and $mail!='@'){		if($sendmail){*/			$emails[]=$email;			$userids[]=$userid;}$total=0;$success=0;if($_GET['action']=='send'){	foreach($userids as $to){		$total++;		if(mailx($to,$subject,$text,'forum')){			$success++;		}	}	success(lr('admin_mail_send',$success.'/'.$total));	br();}if($_GET['action']=='test'){	mailx(6,$subject,$text,'forum');	success(lr('admin_mail_test_send'));}//-----------------------------------------------ZOBRAZOVACtextb('FROM: ');e(lr('email_from_forum'));br();textb('TO: ('.count($emails).') ');e(join(', ',$emails));br();textb('SUBJECT: ');e($subject);br();textb('TEXT: ');br();le('email_header');e($text);le('email_signature',1234);br(2);hr();?><h3><a href="?page=mail&amp;action=test">TEST 6</a></h3><br/><h3><a href="?page=mail&amp;action=send">SEND</a></h3>