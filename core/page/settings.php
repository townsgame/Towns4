<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/page/settings.php


   Nastavení - spojení profile_edit a password_edit
*/
//==============================




window(lr('title_settings'));

$q=submenu(array('content','settings'),array('settings_town','settings_user','settings_description','settings_image'),1);


contenu_a();
if($q==1){
//===============================================================================TOWN
	//if($GLOBALS['get']['id'])$GLOBALS['ss']['profile_edit_id']=$GLOBALS['get']['id'];
	//if(!$GLOBALS['ss']['profile_edit_id'])$GLOBALS['ss']['profile_edit_id']=$GLOBALS['ss']['useid'];
	//infob('{town_profile_info}');
	$id=$GLOBALS['ss']['useid'];//$GLOBALS['ss']['profile_edit_id'];

	    $info=array();
	    $tmpinfo=xquery("info",$id);
	    $info["profile"]=new profile($tmpinfo["profile"]);
	    $info["name"]=$tmpinfo["name"];
	    $p=$info["profile"]->vals2list();
	    if(!$p["color"])$p["color"]='000000';

		if($_GET["profile_edit"]){
			//xreport();
			//$response=xquery("move",$_POST["move_x"],$_POST["move_y"]);  
			if($_POST["name"] and $info["name"]!=$_POST["name"]){
			xquery("profile_edit",$id,"name",$_POST["name"]);
			//print_r($GLOBALS['ss']["xresponse"]);
			xreport();
			$info["name"]=$_POST["name"];
	    }
	    //if($post["realname"]){xquery("profile_edit","realname",$post["realname"]);}
	    //if($post["gender"]){xquery("profile_edit","gender",$post["gender"]);}
	    //if($post["showmail"]){xquery("profile_edit","showmail",$post["showmail"]);}
	    //if($post["web"]){xquery("profile_edit","web",$post["web"]);}
	    //if($post["image"]){xquery("profile_edit","image",$post["image"]);}
	    if($_POST["description"] and $p["description"]!=$_POST["description"]){

            $description=remove_javascript($_POST["description"]);//@todo funkce pro zpracovávání TinyMCE
			xquery("profile_edit",$id,"description",$description);
			xreport();
			$p["description"]=$description;
		}
	    if($_POST["color"] and $p["color"]!=$_POST["color"]){
			xquery("profile_edit",$id,"color",$_POST["color"]);
			xreport();
			$p["color"]=$_POST["color"];
		}	    
	    
	}


	
	//realname,gender,age,showmail,web,description

	//print_r($array);
	?>

	<?php

	infob(lr('settings_town_info'));

	form_a(urlr('profile_edit=1'),'profile_edit');
	//<form id="login" name="login" method="POST" action="">
	?>

                


	<table>


	<tr><td><b><?php le('name'); ?>:</b></td><td><?php input_text("name",(!is_numeric($info["name"])?$info["name"]:'')); ?></td></tr>

	
	<?php /*
<tr><td><b>{color}:</b></td><td><?php input_color("color",$info["color"]); ?></td></tr>

 ?>
	<tr><td><b><?php le("realname"); ?>:</b></td><td><?php input_text("realname",$p["realname"]); ?></td></tr>
	<tr><td><b><?php le("gender"); ?>:</b></td><td><?php input_select("gender",$p["gender"],array(" "=>"---", "male"=>"Muž", "female"=>"Žena")); ?></td></tr>
	<tr><td><b><?php le("showmail"); ?>:</b></td><td><?php input_checkbox("showmail",$p["showmail"]);  le("Mail můžete změnit v nastavení."); ?></td></tr>
	<tr><td><b><?php le("web"); ?>:</b></td><td>http://<?php input_text("web",$p["web"]); ?></td></tr>
	<tr><td><b><?php le("image"); ?>:</b></td><td><?php input_text("image",$p["image"]); ?></td></tr>
	<?php */ ?>
	    <tr><td colspan="2"><b><?php le('description'); ?>:</b></td></tr>
        <tr><td colspan="2"><?php input_tinymce("description",$p["description"],450,200,1); ?></td></tr>

        <tr><td colspan="2"><b><?php le('color'); ?>:</b></td></tr>

<tr><td colspan="2" align="center">

<input type="hidden" name="color" id="color" value="<?php e($p["color"]); ?>" />
<p id="colorpickerHolder"></p>
	<script type="text/javascript">
	$('#colorpickerHolder').ColorPicker({
	flat: true,
	color: '#<?php e($p["color"]); ?>',
	onChange: function (hsb, hex, rgb) {
		
		$('#color').val(hex);
	}});
	</script>
</td></tr>



	<tr><td colspan="2"><input type="submit" value="<?php le('submit_town_settings') ?>" /></td>
	</tr></table>

	<?php
	form_b();
	form_js('content','?e=settings&submenu=1&profile_edit=1',array('name','description','color'));
//======================================================================================
}elseif($q==2){
//==============================================================================
    
    
    //e($GLOBALS['ss']['useid'].','.$GLOBALS['ss']['logid']);
    
        //---------------------------------------------------------------------------------------------USER on World
	if($_POST["name"] AND $GLOBALS['ss']['log_object']->name!=$_POST["name"]){
		$q=name_error($_POST["name"]);
                //e($info["name"].'!='.$_POST["name"].' '.$q);
		if(!$q){
		    $GLOBALS['ss']['log_object']->name=$_POST["name"];
		    $GLOBALS['ss']['log_object']->update();
		    
			success(lr('profile_username').' '.lr('settings_changed')); 
			if(is_numeric($GLOBALS['ss']['use_object']->name)){
	
                                //e($_POST["name"]);
				$GLOBALS['ss']['use_object']->name=$_POST["name"];
				$GLOBALS['ss']['use_object']->update();
				success(lr('profile_townname').' '.lr('settings_created')); 
			}
		   	
		}else{
                   //if($GLOBALS['ss']['use_object']->name!=$_POST["name"]){
                        error($q);
                   //}
		}        

		//xquery("profile_edit",$GLOBALS['ss']['useid'],"name",$_POST["name"]);
		//xquery("profile_edit",$GLOBALS['ss']['logid'],"name",$_POST["name"]);
		xreport();
	}else{
		if(is_numeric($GLOBALS['ss']['log_object']->name)){
			$q=true;
		}else{
			$q=false;
		}
	}
        //---------------------------------------------------------------------------------------------Password
	if($_POST["oldpass"] or $_POST["newpass"] or $_POST["newpass2"]){
		   
             xreport();
             //$oldpass=sql_1data("SELECT password FROM `[mpx]users` WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1"); 
             if(/*(!$oldpass or $_POST["oldpass"]) and */$_POST["newpass"] and $_POST["newpass2"]){
                 
                if($_POST["newpass"]==$_POST["newpass2"]){
                    //if($oldpass==md5($_POST["oldpass"])){
                        //print_r($_POST['newpass']);
                        xquery("register",' ',$_POST['newpass'],'','','',$_POST['oldpass']);
                        //success(lr('password_changed'),2);
                    //}else{
                    //    alert(lr('password_change_oldpass_error'),2);
                    //}
                    
                }else{
                    alert(lr('password_change_newpass2_error'),2);
                }
                

		xreport();
	    }else{
		alert(lr('password_change_noall_error'),2);
	    }
	}

        //---------------------------------------------------------------------------------------------Email
	//print_r($_POST);



	if($_POST["email"] or $_POST["username"]){
		    //e(111);
		    //$GLOBALS['ss']['log_object']->profile->add('mail',$_POST["mail"]);
		    //$GLOBALS['ss']['log_object']->profile->add('sendmail',$_POST["sendmail"]);
		    xquery("register",$_POST["username"],'',$_POST["email"],$_POST["sendmail"]?1:0);
			xreport();
		    //xquery("profile_edit",$GLOBALS['ss']['logid'],"sendmail",$_POST["sendmail"]?'1':'0');
		    xquery("profile_edit",$GLOBALS['ss']['logid'],"sendmail2",$_POST["sendmail2"]?'1':'0');
		    xquery("profile_edit",$GLOBALS['ss']['logid'],"sendmail3",$_POST["sendmail3"]?'1':'0');
		    xquery("profile_edit",$GLOBALS['ss']['logid'],"sendmail4",$_POST["sendmail4"]?'1':'0');
		    xquery("profile_edit",$GLOBALS['ss']['logid'],"sendmail5",$_POST["sendmail5"]?'1':'0');
		    //$GLOBALS['ss']['log_object']->update();
		   //success(lr('namecreated'));
            $username=$_POST["username"];
            $email=$_POST["email"];

		}else{
            $username=sql_1data("SELECT name FROM `[mpx]users` WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1");
            $email=sql_1data("SELECT email FROM `[mpx]users` WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1");
        }

		//xquery("profile_edit",$GLOBALS['ss']['useid'],"name",$_POST["name"]);
		//xquery("profile_edit",$GLOBALS['ss']['logid'],"name",$_POST["name"]);
		xreport();
	//realname,gender,age,showmail,web,description
	//print_r($array);
        //---------------------------------------------------------------------------------------------Load
                
         $sendmail=sql_1data("SELECT sendmail FROM `[mpx]users` WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1");
        
        //---------------------------------------------------------------------------------------------Form
	?>
	<form id="changepass" name="changepass" method="POST" action="" onsubmit="return false">
        <?php /*input_hidden("send",1);*/ ?>
	<table>


	<?php /*if(true/*is_numeric($GLOBALS['ss']['log_object']->name)* /){*/ ?>
        <tr><td><b><?php e('*');le('loginname'); ?>:</b></td><td><?php input_text("username",$username); ?></td></tr>
	<tr><td><b><?php e('*');le('nameonworld'); ?>:</b></td><td><?php input_text("name",$_POST["name"]?$_POST["name"]:(!is_numeric($GLOBALS['ss']['log_object']->name)?$GLOBALS['ss']['log_object']->name:'')); ?></td></tr>

        <tr><td><b><?php le("email"); ?>:</b></td><td><?php input_text("email",$email); ?></td></tr>
	
	
	<tr><td colspan="2"><?php input_checkbox("sendmail",$sendmail); ?><b><?php le("sendmail"); ?></b></td></tr>
	<tr><td colspan="2"><?php input_checkbox("sendmail2",$GLOBALS['ss']['log_object']->profile->ifnot('sendmail2','1')); ?><b><?php le("sendmail2"); ?></b></td></tr>
	<tr><td colspan="2"><?php input_checkbox("sendmail3",$GLOBALS['ss']['log_object']->profile->ifnot('sendmail3','1')); ?><b><?php le("sendmail3"); ?></b></td></tr>
	
	<?php if(!is_numeric($GLOBALS['ss']['log_object']->name)){ ?>
	<tr><td colspan="2"><?php input_checkbox("sendmail4",$GLOBALS['ss']['log_object']->profile->ifnot('sendmail4','1')); ?><b><?php le("sendmail4"); ?></b></td></tr>
	<tr><td colspan="2"><?php input_checkbox("sendmail5",$GLOBALS['ss']['log_object']->profile->ifnot('sendmail5','1')); ?><b><?php le("sendmail5"); ?></b></td></tr>
	
	
	<tr><td colspan="2"><?php br();info(lr("pass_info")); ?></td></tr>
	
	<?php } ?>
	
	<?php if(!nopass){ ?>
	<tr><td><b><?php e('*');le('oldpass'); ?>:</b></td><td><?php input_pass("oldpass",$_POST["oldpass"]); ?></td></tr>
	<?php } ?>

	
	<tr><td><b><?php e('*');le('newpass'); ?>:</b></td><td><?php input_pass("newpass",$_POST["newpass"]); ?></td></tr>
	<tr><td><b><?php e('*');le('newpass2'); ?>:</b></td><td><?php input_pass("newpass2",$_POST["newpass2"]); ?></td></tr>
	<tr><td colspan="2">*<?php le('required'); ?></td></tr>

	</table>
	<input type="submit" value="<?php le('submit_user_settings') ?>" />
	</form>
	<script type="text/javascript">
	//alert(1);
	
	$("#changepass").submit(function() {
	    //alert(1);
	    $.post('?token=<?php e($_GET['token']); ?>&e=settings&submenu=2',
		{   username: $('#username').val(),
                    name: $('#name').val(),
		    email: $('#email').val(),
		    sendmail: $('input[name=sendmail]').attr('checked'), 
		    sendmail2: $('input[name=sendmail2]').attr('checked'),
		    sendmail3: $('input[name=sendmail3]').attr('checked'), 
		    <?php if(!is_numeric($GLOBALS['ss']['log_object']->name)){ ?>
    		    sendmail4: $('input[name=sendmail4]').attr('checked'), 
    		    sendmail5: $('input[name=sendmail5]').attr('checked'), 
		    <?php }else{ ?>
    		    sendmail4: 1, 
    		    sendmail5: 1, 
		    <?php } ?>
		    oldpass: $('#oldpass').val(),
		    newpass: $('#newpass').val(),
		    newpass2: $('#newpass2').val()
		},
		function(vystup){$('#content').html(vystup);}
	    );
	    return(false);
	});
	</script>

	<?php
	//if(!is_numeric($GLOBALS['ss']['log_object']->name)){
    	if($GLOBALS['get']['fb_disconnect']){
    		a_register('','','','','',array());
    	}
    
    
    	if(sql_1data("SELECT fbid FROM [mpx]users WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1")){
    		$data=unserialize(sql_1data("SELECT fbdata FROM [mpx]users WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1"));
    	
    		le('fb_connected',$data['name']);br();
    		ahref(trr(lr('fb_disconnect'),15,3,'style="background: rgba(30,30,30,0.9);border: 2px solid #222222;border-radius: 2px;"'),'e=content;ee=settings;submenu=2;fb_disconnect=1');
    	}else{
    
    		if(defined('fb_appid') and defined('fb_secret'))
    		eval(subpage('login-fb_login'));
    
    	}
	blue(lr('fb_form_warning'));
	//}

//======================================================================================
}elseif($q==3){
//==============================================================================


    if(!is_numeric($GLOBALS['ss']['log_object']->name)){
    	//------------------------------------------------description
    	    $info=array();
    	    $tmpinfo=xquery("info",$GLOBALS['ss']['logid']);
    	    $info["profile"]=new profile($tmpinfo["profile"]);
    	    $info["name"]=$tmpinfo["name"];
    	    $p=$info["profile"]->vals2list();
    
    	if($_GET["profile_edit"]){
    
    	    /*if($_GET["description"] and $p["description"]!=$_GET["description"]){
    			xquery("profile_edit",$GLOBALS['ss']['logid'],"description",$_GET["description"]);xreport();
    			$p["description"]=$_GET["description"];
    		}*/
    
    	    //if($_POST["description"] and $p["description"]!=$_POST["description"]){

                $description=remove_javascript($_POST["description"]);//@todo funkce pro zpracovávání TinyMCE
    			xquery("profile_edit",$GLOBALS['ss']['logid'],"description",$description);xreport();
    			$p["description"]=$_POST["description"];
    		//}

            //if($_POST["signature"] and $p["signature"]!=$_POST["signature"]){
                $signature=remove_javascript($_POST["signature"]);//@todo funkce pro zpracovávání TinyMCE
                xquery("profile_edit",$GLOBALS['ss']['logid'],"signature",$signature);xreport();
                $p["signature"]=$_POST["signature"];
            //}

        }

        $rand=rand(11111,99999);


    	form_a(urlr('profile_edit=1'),'profile_edit');

        form_send(lr('submit_description'),'font-size:18px;width:100%;color: #cccccc;border: 2px solid #555555; background-color: rgba(40,20,40,1);');


        infob(textbr(lr('description')).br.lr('description_text'));

        input_tinymce('description'.$rand,$p["description"],450,200,1);


        infob(textbr(lr('signature')).br.lr('signature_text'));
        input_tinymce('signature'.$rand,$p["signature"],450,200,1);

        form_send(lr('submit_description'),'font-size:18px;width:100%;color: #cccccc;border: 2px solid #555555; background-color: rgba(40,20,40,1);');

    	form_b();
    	form_js('content','?e=settings&submenu=2&profile_edit=1',array('description','signature'),1,$rand);
    }
//======================================================================================
}elseif($q==4){
//==============================================================================

    $email=sql_1data("SELECT email FROM `[mpx]users` WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1");

    br();
    le('settings_gravatar_top');
    br(2);
    e('<div style="width: 100%;text-align: center;"><img src="'.gravatar($email,350).'" border="2"></div>');

    br();
    le('settings_gravatar_info',$email);
    br();
    tfont('<a href="http://cs.gravatar.com/" target="_blank">gravatar.com</a>',18);


//======================================================================================
}
contenu_b(true);
?>
