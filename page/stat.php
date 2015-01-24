<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/stat.php

   Seznam objektů pro statistiku
*/
//==============================



superown();


backup($GLOBALS['stattype'],"buildings");

if($GLOBALS['stattype']=='buildings'){
    $GLOBALS['where']="type='building' AND ww!=0 AND ww!=-1 ";
}elseif($GLOBALS['stattype']=='towns'){
    $GLOBALS['where']="(type='town' OR type='town2') AND ww!=0 AND ww!=-1 ";
    $ad1='SELECT count(1) FROM [mpx]objects as x WHERE x.own=[mpx]objects.id AND type=\'building\' AND '.objt('x');
    $ad2='SELECT sum(x.fs) FROM [mpx]objects as x WHERE x.own=[mpx]objects.id AND x.name!=\''.mainname().'\' AND type=\'building\' AND '.objt('x');
    $order="ad2";
}elseif($GLOBALS['stattype']=='users'){
    $GLOBALS['where']="type='user' AND ww!=0 AND ww!=-1 ";
    $ad3='SELECT count(1) FROM [mpx]objects as x WHERE x.own=[mpx]objects.id AND (type=\'town\' OR type=\'town2\') AND '.objt('x');
    
    $ad1='SELECT count(1) FROM [mpx]objects as x WHERE x.superown=[mpx]objects.id AND x.type=\'building\' AND '.objt('x');
    $ad2='SELECT sum(x.fs) FROM [mpx]objects as x WHERE x.superown=[mpx]objects.id AND x.type=\'building\' AND '.objt('x');
    
    //$ad1='SELECT count(1) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id AND (y.type=\'town\' OR y.type=\'town2\') LIMIT 1) AND type=\'building\'';
    //$ad2='SELECT sum(x.fs) FROM [mpx]objects as x WHERE x.name!=\''.mainname().'\' AND x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id AND (y.type=\'town\' OR y.type=\'town2\') LIMIT 1) AND type=\'building\'';
    
    $order="ad2";
}
if($ad1)$ad1=',('.$ad1.') as ad1';
if($ad2)$ad2=',('.$ad2.') as ad2';
if($ad3)$ad3=',('.$ad3.') as ad3';
if(!$order)$order="fs";

//r($GLOBALS['where']);

/*$adx=substr2($ad2,',(',') as');
if($GLOBALS['stattype']=='towns'){
	$d=sql_1data("SELECT COUNT(1) FROM `".mpx."objects` WHERE ".$GLOBALS['where'].' AND '.objt()." AND fs<(".str_replace('[mpx].id',$GLOBALS['ss']['useid'],$adx).")  ORDER BY ($adx) DESC");
}elseif($GLOBALS['stattype']=='users'){
	$d=sql_1data("SELECT COUNT(1) FROM `".mpx."objects` WHERE ".$GLOBALS['where'].' AND '.objt()." AND fs<(".str_replace('[mpx].id',$GLOBALS['ss']['useid'],$adx).")  ORDER BY ($adx) DESC");
}*/
$d=0;


$max=sql_1data("SELECT COUNT(1) FROM `".mpx."objects` WHERE ".$GLOBALS['where'].' AND '.objt());


//echo(($d.'/'.$max));
//br();echo("limit(stat,.' AND '.objt(),16,$max,$d)");br();

$limit=limit("stat",$GLOBALS['where'].' AND '.objt(),16,$max,$d);


//echo($limit);

$array=sql_array("SELECT `id`,`name`,`type`,`fs`,`fp`,`fr`,`fx`,`own`,`in`,`x`,`y`,`ww`$ad1$ad2$ad3 FROM `".mpx."objects` WHERE ".$GLOBALS['where']." AND ".objt()." ORDER BY $order DESC LIMIT $limit");
//r($limit);
//fs2lvl
?>
<table width="100%">
<tr>
<td width="20"><b>#</td>
<!--<td width="50">ID</td>-->
<td width="150"><b><?php le('stat_name'); ?></b></td>
<?php if($GLOBALS['stattype']!='users'){ ?>
<td width="80"><b><?php le('stat_owner'); ?></b></td>
<?php }else{ ?>
<td width="30"><b><?php le('stat_town_count'); ?></b></td>
<?php } ?>
<?php if($GLOBALS['stattype']=='buildings'){ ?>
<td width="80"><b><?php le('stat_fpfs'); ?></b></td>
<?php }else{ ?>
<td width="50"><b><?php le('stat_building_count'); ?></b></td>
<?php } ?>
<td align="center" width="40"><b><?php le('stat_level'); ?></b></td>
<td align="center"><b><?php le('stat_action'); ?></b></td>
</tr>

<?php
/**/
$i=$GLOBALS['ss']['ord'];
foreach($array as $row){$i++;
    list($id,$name,$type,$fs,$fp,$fr,$fx,$own,$in,$x,$y,$ww,$ad1,$ad2,$ad3)=$row;
    if(trim($name)){
    $hline=ahrefr(/*textcolorr(lr($type),$dev)." ".*/short(tr($name,true),15),"e=content;ee=profile;id=$id","none","x");
    
    if($GLOBALS['stattype']=='buildings'){
        $in=xyr($x,$y,$ww);
        $lvl=fs2lvl($fs);
        if($fp==$fs){
            $fpfs=round($fs);
        }else{
            $fpfs=round($fp).'/'.round($fs);
        }
    }elseif($GLOBALS['stattype']=='towns'){
        $in=xyr($x,$y,$ww);
        $lvl=fs2lvl($ad2);
        $fpfs=$ad1;
    }elseif($GLOBALS['stattype']=='users'){
        $lvl=fs2lvl($ad2);
        $fpfs=$ad1;
    }

    if($id==$GLOBALS['ss']['logid']){$adtr='bgcolor="#171717"';}else{$adtr='';}
    e("<tr $adtr>
    <td>$i</td>
    "./*<td>$id</td>*/"
    <td>$hline</td>");
    if($GLOBALS['stattype']!='users'){
        //e("<td>$in</td>");
	$hlinex=ahrefr(short(tr(id2name($own),true),15),"e=content;ee=profile;id=$own","none","x");
	e("<td>$hlinex</td>");
    }else{
        e("<td>$ad3</td>");
    }        
        
     e("<td>$fpfs</td>
    <td align=\"center\">$lvl</td>
    <td align=\"center\">");
    if($ww==0){
	$js="w_close('window_unique');build('$id');";
        icon(js2($js),"f_create_building_submit",lr('build_submit'),15);
    }

 	if($GLOBALS['stattype']=='users'){
 	    
		//$town=sql_1data('SELECT id FROM [mpx]objects WHERE own='.$id.' ORDER BY `type`');
		//list(list($id,$x,$y))=sql_array('SELECT id,x,y FROM [mpx]objects WHERE superown='.$town.' AND name=\''.id2name(register_building).'\'');
	    ahref(lr('send_message'),"e=content;ee=text-messages;submenu=5;to=$id",false);
	    
	}else{
	    
    	$url=centerurl($id,$x,$y,$ww);
    	ahref(lr('stat_center'),$url); 
	
	}
    //e("($x,$y)");


    e("</td>
    </tr>");
    //r($row);
}}/**/

?>

</table>
