<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/html_fullscreen.php

   V tomto souboru je html vnitřku stránky a správa oken.
*/
//==============================
//e('{a} b {towns}');
//die();





if($_GET['scale']){
	$scale=$_GET['scale'];
}else{
	$scale=100;
}

//print_r($_GET);
$screenwidth=$_GET['width'];
$screenheight=$_GET['height'];
$first=$_GET['first'];
if(!$screenwidth)$screenwidth=$GLOBALS['ss']['screenwidth'];
if(!$screenheight)$screenheight=$GLOBALS['ss']['screenheight'];
if(!$screenwidth)$screenwidth=800;
if(!$screenheight)$screenheight=600;
if(!$first)$first=0;
if(!logged())$first=1;
$GLOBALS['screenheight']=$screenheight;
$GLOBALS['screenwidth']=$screenwidth;

$GLOBALS['ss']['screenheight']=$screenheight;
$GLOBALS['ss']['screenwidth']=$screenwidth;
//die($GLOBALS['screenwidth']);



if(!$first){
	e('<script>logged=true;</script>');
}else{
	e('<script>logged=false;</script>');
}


/*if($_GET["q"]=='logout'){
	reloc();
	exit2();		
}*/


//echo($first);
?><div style="width: <?php e($scale); ?>%;height:<?php e(!$first?$scale.'%':'1080px'); ?>;background-color:#<?php /*43a1f7,458dde,458dfe,689afe,:-) 699cfe*/e('699cfe'); ?>;overflow: hidden;z-index:1000;"><?php

//------------------------------------------------------------
if($GLOBALS['mobile'] and !(!$first)){
	eval(subpage('login-mobile'));
	//e('</div>');
}else{
//------------------------------------------------------------




/*if($GLOBALS['mobile']){

eval(subpage("mobile"));

exit2();
}*/
?>
<div id="windows" style="position:relative;top:0px;left:0px;width:100%;height:100%;">
<?php



    if(!$first){


    $windows=array(
    
    'chat'=>false,
    'write'=>false,
    'quest-mini'=>false,
    'topinfo'=>false,
    'towns'=>false,
    'cache_loading'=>false,
	'login-use'=>false,
    //'tabs'=>false,
    //'miniprofile'=>false,
    //'surkey'=>false,
    'topcontrol'=>false,
    );
    //----------------
    $interface=str2list(xx2x($GLOBALS['ss']["log_object"]->set->val("interface")));
    foreach($interface as $w_name=>$tmp){
        list($w_content,$w_x,$w_y)=explode(",",$tmp);
        $windows[$w_name][0]=$w_content;
        $windows[$w_name][1]=$w_x;
        $windows[$w_name][2]=$w_y;
        $windows[$w_name][5]=array(1,1,1,1);
    }
    //----------------



    if(!$GLOBALS['mobile']){
        
        //if(chat){
		    $windows['chat']=array("chat",0,/*-107*/-155,449,130,array(1,2,1,1),0);
        //}
		$windows['write']=array("write",0,-155-185+(chat?0:140),449,130,array(1,2,1,1),0);
		$windows['quest-mini']=array("quest-mini",0,/*-107*/-155-150+(chat?0:105)-($screenheight>800?185:0),449,130,array(1,2,1,1),0);
		$windows['quest-mobile']=false;

		//$count=sql_1data('SELECT COUNT(`id`) FROM [mpx]objects WHERE `own`=\''.(logid).'\' AND (`type`=\'town\' OR `type`=\'town2\') ')-1+1;
		//if($count>1){
			$usenotclose=true;
			$windows['login-use']=array("login-use",-41,100,35,35,array(0,1,1,1),1);
		//}

    }else{
		$windows['chat']=false;
		$windows['quest-mini']=false;
		$windows['quest-mobile']=array("quest-mobile",0,-25,'100%',130,array(0,1,1,1),1);
		//$GLOBALS['mcontent']=$windows['content'][1];
		$windows['content']=false;
    }
    //$windows['mine']=array("mine",0,0,0,0,array(0,1,1,1),1);
    $windows['towns']=array("towns",1,1,"100%",30,array(0,1,1,1),1);
    $windows['cache_loading']=array("cache_loading",-300,1,200,20,array(0,1,1,1),1);
    //$indows['tabs']=array("tabs","%%",-141+13,"103%",0,array(0,1,1,1),1);
    //$windows['miniprofile']=array("miniprofile","%%",/*-120*/-107,"103%",130,array(0,1,1,1),1);
    //$windows['surkey']=array("surkey","%%",-25,0,0,array(0,1,1,1),1);

    /**if($windows['topcontrol']){
        $x=$windows['topcontrol'][1];
        $y=$windows['topcontrol'][2];
    }else{
        $x=-188;
        $y=1;//-164;//5;
    }
    $windows['topcontrol']=array("topcontrol",$x,$y,204,0,array(0,0,1,1),4);/**/
    
    /*if(!$windows['content'])
    $windows=array_merge($windows,array('content'=>array("help",1,1,contentwidth,0,array(1,1,1,1),0)));*/
    

    //----------------TUTORIAL
    //f($GLOBALS['ss']["log_object"]->set->val("tutorial")/* and !$windows['help']*/){
     //   $GLOBALS['ss']["log_object"]->set->delete("tutorial");
   // }//ZATím VYPNUTO



    //====================================================================TOPINFO=NOPASSWORD=
    $rating=$GLOBALS['ss']['log_object']->set->val('rating');
    //e($rating);
    if(!$rating or $rating-(-3600*24*30)<time()){
        $GLOBALS['topinfo']=lr('rating');
        //$url='https://docs.google.com/forms/d/16bRzuR9Tw7dQuJrc_Th855Nvz6rjQyuGBN9TpaQbACs/viewform?embedded=true#start=embed';
        $GLOBALS['topinfo_url']='e=content;ee=rating;';//.js2('window.open("'.$url.'", "rating", "location=yes,status=no,width=600,height=500,resizable")');
	    $windows["topinfo"]=array("topinfo",'0',(!$GLOBALS['mobile']?38:29),'100%',0,array(0,1,1,1),1);
    }
    //====================================================================TOPINFO=NOPASSWORD=
    if(nopass/* and nofb*/){
        $GLOBALS['topinfo']=lr('register_nopassword'.($GLOBALS['mobile']?'_short':''));
        $GLOBALS['topinfo_url']='e=content;ee=settings;submenu=2;';
        //"topinfo"=>array("topinfo",'%%',-161+13,'103%',0,array(0,1,1,1),1),
	    $windows["topinfo"]=array("topinfo",'0',(!$GLOBALS['mobile']?38:29),'100%',0,array(0,1,1,1),1);
       //$_GLOBALS['noxmap']=true;
    }
    //====================================================================TOPINFO=APPAAC=
    if(true){
        $GLOBALS['topinfo']=lr('application_aac'.($GLOBALS['mobile']?'_short':''));
        $GLOBALS['topinfo_url']=js2('location.reload();');
        //"topinfo"=>array("topinfo",'%%',-161+13,'103%',0,array(0,1,1,1),1),
	    $windows["topinfo"]=array("topinfo",'0',(!$GLOBALS['mobile']?38:29),'100%',0,array(0,1,1,1),1,1);
       //$_GLOBALS['noxmap']=true;
    }
    //====================================================================
 
 
    
    
    if($GLOBALS['mobile']){
	$windows["mobilecontent"]=array("mobilecontent",'0',0,'100%','0',array(0,1,1,1),1);
    }
    

   /*if(sql_1data('SELECT count(1) FROM [mpx]objects WHERE own='.useid.'')-1+1<=4){
        $GLOBALS['ss']["page"]='tutorial';
        $windows=array_merge($windows,array('content'=>array("help",1,1,contentwidth,0,array(1,1,1,1),0)));
    }*/
    /*if(sql_1data('SELECT count(1) FROM [mpx]objects WHERE own='.useid.'')-1+1<=4){
        $windows=array_merge($windows,array('content'=>array("quest",1,1,contentwidth,0,array(1,1,1,1),0)));
    }*/



    //---------------- NOLOGIN     
    }else{



    $windows=array(
    'login-wall'=>false,
    'login-login'=>false,
    'login-fb_select'=>false,
    'plus-ad'=>false,
    );
    if($screenheight>800){
    	//$windows['plus-ad']=array("plus-ad",1,-16,'100%',90,array(0,0,1,1),1);
    }


    $windows['login-wall']=array("login-wall",'0','0','100%',1,array(0,1,1,1),1);
    $windows['login-login']=array("login-login",'0','0','100%',1,array(0,1,1,1),1);
    $windows['login-fb_select']=array("login-fb_select",'%%','%%',300,200,array(1,1,1,1),0);

    $windows=array_merge(
    $windows,
    array(
    //'langcontrol'=>array("langcontrol",97,1,62,27,array(0,0,1,1),4),
    //'fblike'=>array("fblike",170,1,120,27,array(0,0,1,1),4)
    ));


    }

    $windows=array_merge(
    $windows,
    array(
    'copy'=>array("copy",-50,-25,500,0,array(0,1,1,1),1),
    'name'=>array("none",'[xx]','[yy]','[ww]','[hh]',array(1,1,1,1),0)
    //'langcontrol'=>array("langcontrol",97,1,62,27,array(0,0,1,1),4),
    //'fblike'=>array("fblike",170,1,120,27,array(0,0,1,1),4)
    ));
    if($GLOBALS['mobile']){$windows['copy']=false;}


    if(debug){
        $windows=array_merge(
        $windows,
        array(
        "debug"=>array("debug",10,10,70,0,array(0,1,1,1),1)
        ));
    }
    
    if($_GET['unsubscribe']){
        $windows=array_merge(
        $windows,
        array(
        "text-unsubscribe"=>array("text-unsubscribe",200,200,200,50,array(1,1,1,1),0)
        ));
    }
    
//----------------
$windows['minimenu']=false;
$windows['map_context']=false;
//----------------

foreach($windows as $w_name=>$window){
//r($w_name);
$w_content=$window[0];
$w_x=$window[1];if(!$w_x)$w_x=0;
$w_y=$window[2];if(!$w_y)$w_y=0;
$w_w=$window[3];if(!$w_w)$w_w=0;
$w_h=$window[4];if(!$w_h)$w_h=0;
$w_rights=$window[5];//print_r($w_rights);
$w_noborders=$window[6];
$w_hide=$window[7];
if($w_content and $w_name){
if($w_name=="name")echo("<div id=\"window\" style=\"display:none;\">");
t("window_$w_name>>");
?>
<div id="window_<?php echo($w_name); ?>" style="position:relative; <?php if($w_x==="%"){$w_x=0;echo("left:40%;");}if($w_y==="%"){$w_y=0;echo("top:40%;");}if($w_x==="%%"){$w_x=0;echo("left:50%;");}if($w_y==="%%"){$w_y=0;echo("top:50%;");}if($w_x<0){echo("left:100%; ");}if($w_y<0){echo("top:100%; ");} ?>width:100%; height:0px; overflow:visible;z-index:1000;">
<div id="window_sub_<?php echo($w_name); ?>" <?php  if($w_rights[0]){ ?>class="window"<?php  } ?> style="position:relative; left:<?php echo(is_numeric($w_x)?$w_x-2:$w_x); ?>px; top:<?php echo(is_numeric($w_y)?$w_y-2:$w_y); ?>px; width:<?php echo($w_w); ?>;">
  <table id="window_table_<?php echo($w_name); ?>" width="<?php echo($w_w); ?>" height="<?php echo($w_h); ?>" <?php  if(!$w_noborders or $w_noborders==3){ ?> style="border: 2px solid #222222;border-radius: 5px; " cellpadding="3" cellspacing="0" <?php  }elseif($w_noborders==4){ ?> style="border: 2px solid #222222;border-radius: 5px; " cellpadding="0" cellspacing="0" <?php } ?> >
  	<?php
  	 if(!$w_noborders/* and $w_name!="content"*/){
  	?>
    <tr>
      <td height="19" style="background: rgba(22,22,22,0.97);" class="dragbar" valign="center">
		 <?php
		 $js="w_close('window_$w_name')";
         //$js="$('#window_$w_name').remove();";
		 if($w_rights[1]==1)icon(js2($js),"close",lr('close'),18);
		 if($w_rights[1]==2)icon(js2('$(\'#window_'.$w_name.'\').css(\'display\',\'none\');'),"close",lr('close'),18);
		 ?>
          <span id="window_title_<?php echo($w_name); ?>"  style="font-size: 17 px;"><?php echo($w_title); ?></span>
	  </td>
    </tr>
	<?php  } ?>
    <tr>
      <td <?php /*e(($w_name=="content")?'class="dragbar"':'');*/ ?> align="left" valign="top" <?php if(!$w_noborders or $w_noborders==2 or $w_noborders==3 or $w_noborders==4){e('style="background: rgba(7,7,7,0.97);"');/*e("background=\"");imageurle("design/windowbg.png");e("\"");*/} ?>>
      
      
	<?php /*if($w_name=="content"){ ?>

		 <?php
         $q=true;
		 $js="w_close('window_$w_name')";
		 moveby(iconr(js2($js),"close","{close}",18),contentwidth-20,0);
		  
		 ?>

	<?php  }*/ ?>
      
      <?php
        //r("t");
     //if($w_name=="content")contenu_a();
		if($w_content!="none"){
        if($w_name=="content")xreport();
		eval(subpage($w_name,$w_content));
		}else{
		echo("innercontent");
		}
		//if($w_name=="content")contenu_b();
        //r("t");
		?>
	</td>
    </tr>
  </table>

</div></div>
<?php
t("<<window_$w_name");
if($w_name=="name")echo("</div>");


if($w_hide)js("$('#window_$w_name').css('display','none');");
}}
?>



<script type="text/javascript">

   $('#window_table_content').height($(window).height()-118);
   $(window).resize(function(){
      $('#window_table_content').height($(window).height()-118);
   });

	




	w_drag();

</script>


</div>

<?php
//-------------------------------------------------------------------------------------------------DOCK

if(!$GLOBALS['mobile'] and !onlymap){

    if(!$first){
    /*?>
    <div style="position:absolute;top:0px;left:0px;width:100%;height:100%;overflow:scroll;">
    <?php*/

    eval(subpage('dockbuttons'));
//e('</div>');



//-------------------------------------------------------------------------------------------------
    ?>




    <script type="text/javascript">
	$('#window_chat').css('display','none');

        $('#window_chat').css('display','none');
        <?php if(!$_GET["write_text"]){ ?>$('#window_write').css('display','none');<?php } ?>
        
        
        
         /*$(":text" ).focus(function() {
          chating=true;
          alert("focus");
        });*/

        
        
    </script>
    
    <?php
}
if(onlymap){
    ?>
    
    <script type="text/javascript">
        $('#window_towns').css('display','none');
        $('#window_topinfo').css('display','none');
        $('#window_tutorial').css('display','none');
        $('#window_quest-mini').css('display','none');
        $('#window_chat').css('display','none');
        $('#window_write').css('display','none');
        $('.saybox').css('display','none');
        
        /*setTimeout(function(){
            w_close('chat');
        },1000);*/
    </script>
    
    <?php
    
    //-------------------------------------------
    
    }else{
        
        /*dockbutton(0,-155-(0*185)+5,-12,'{title_chat}',showhide('window_chat'),4);
        dockbutton(0,-155-(1*185)+5,-12,'{title_write}',showhide('window_write'),4);
        dockbutton(0,-155-(2*185)+5,-12,'{title_tutorial}',showhide('window_quest-mini'),4,'dockbutton_tutorial');*/
        
            
    }
}
/*-------------------------------------------------------------------------------------------------MAPA*/
?>



<div style="position:relative;top:-100%;left:0px;width:100%;height:100%;z-index:2;">
<?php

    //eval(subpage('map_units'));
    /*if(logged()){
	$_GLOBALS['map_night']=false;
    }else{
	$_GLOBALS['map_night']=true;
    }*/
    /*if(!logged()){
	//$GLOBALS['mapzoom']=pow(gr,(1/4));
    }else{


    }*/

    /*if(logged()){
    	eval(subpage("javascript"));
    }*/

    /*if(!$first){
        eval(subpage("map"));
        ?><script type="text/javascript">parseMap();</script><?php
    }else{*/

    subempty('map');

    if(!logged() or $GLOBALS['ss']["log_object"]->set->val('map_xc')){
        
    $js='setTimeout(function() {
        $.get(\'?y=&e=map\', function(vystup){
            $(\'#map\').html(vystup);
        });
    },40);';
    
    /*$js='$( document ).ready(function() {
        $.get(\'?y=&e=map\', function(vystup){
            $(\'#map\').html(vystup);
        });
    });';*/
    
    js($js);
        
    }

   

?>


</div>


<?php /*==================================================================================================map_context=*/ ?>

<?php /*if(!logged() and 0){ ?>
<div style="position:absolute;top:0%;left:0px;width:100%;height:100%;background: rgba(0,0,0,0.2);background-image: url('<?php imageurle('design/mainbg.png'); ?>'); z-index:3;"></div>
<?php }*/ ?>

<div id="map_context" style="position:absolute; top:100; left:100; display:none;  background: rgba(0,0,0,0.75); border-width: 2px; border-style: solid; border-color: #222222; border-radius: 2px; padding: 4px;z-index:40000;">
</div>


<?php
/*==================================================================================================cache=*/
if(!$first){
    
    
    subempty('cache');
    
    
    //zbytek v use
    
    
    //subdelay('cache',1);
    //eval(subpage('cache'));

}

/*==================================================================================================loading=*/



?>


<div id="loading" style="position:absolute; top:100; left:100; display:none;z-index:50000;">
<?php imge('design/loading.gif',lr('loading'),30,30); ?>
</div>


<?php /*==================================================================================================addend=*/ ?>


<?php
}
?>
<?php
if($GLOBALS['addend']){
?>
<div id="addend" style="position:absolute; top:100; left:100; background: rgba(0,0,0,0.75); border-width: 2px; border-style: solid; border-color: #222222; border-radius: 2px; padding: 4px;z-index:9990000;">
	<?php e($GLOBALS['addend']); ?>
</div>
<?php
}

?>
<!--================================-->
</div>
<?php
//-------------------statistika
if($first){
    $GLOBALS['ss']["log_object"]->t=time();
    //sql_query('UPDATE [mpx]objects SET t='.time().' WHERE id='.logid);
}
?>
