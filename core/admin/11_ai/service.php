<?php
/* Towns4Admin, www.towns.cz 
   © Pavol Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================



?>
<h3>Chaos ve <?php echo(w); ?> </h3>

<?php

define('chaos_fall',30);//Počet dní, za které budova spadne
define('chaos_water',35);//% vody na mape
define('chaos_terrain',3);//% rozházených terénů
//define('chaos_terrain_range',gr);//min vzdalenost rozházených terénů od budov
define('chaos_tree',2);//% rozházených stromů
define('chaos_rock',2);//% rozházených skal

//$protection=1000;

if($_GET['action']=='test'){
	$test=true;
}else{
	$test=false;
}

set_time_limit(10000);


if($_GET['action']){
//=================================================Chátrání budov + Opravy 
//--------------------------Analýza
if($_GET['wtf']=='all' or $_GET['wtf']=='a'){

$buildingsall=array();
foreach(sql_array("SELECT id FROM `[mpx]pos_obj` WHERE $wwhere AND `type`='building' AND `name`!='".sql(mainname())."' AND fp>0 AND ".objt()) as $row){
    list($id)=$row;
    $buildingsall[$id]=true;
    
}
//print_r($buildingsall);
//$tmp=sql_query("UPDATE `[mpx]pos_obj` SET fp=CEIL(fp-(fs/".chaos_fall.")) WHERE $wwhere AND `type`='building' AND `name`!='".sql(mainname())."' AND fp>0 AND ".objt());


//print_r($tmp);


//sql_query("UPDATE `[mpx]pos_obj` SET fp=0 WHERE $wwhere AND `type`='building' AND fp<0 ");
//sql_query("UPDATE `[mpx]pos_obj` SET fp=fs WHERE $wwhere AND `type`='building' ");



//--------------------------Oprava budov
$tmp=0;
$towns=sql_array("SELECT id,name,`set` FROM `[mpx]pos_obj` WHERE (type='town' OR type='town2') AND ".objt());
foreach($towns as $town){
	list($townid,$townname,$townset)=$town;
	//textb($townname);br();
	if(strpos($townset,'global_repair=off')===false){
		
		$buidings=sql_array("SELECT id,name,fp,fs,`set` FROM `[mpx]pos_obj` WHERE own='".$townid."' AND ".objt()." AND `name`!='".sql(mainname())."' ORDER BY id DESC");
		$object=new object($townid);
		foreach($buidings as $buiding){
			list($id,$name,$fp,$fs,$set)=$buiding;
                        //--------------------virtuální $fp
                        if($buildingsall[$id]===true){
                            $fp=ceil($fp-($fs/chaos_fall));
                            //ebr("$fp / $fs");
                        }
                        
                        //--------------------
			if($fp!=$fs){
				if(strpos($set,'auto_repair=off')===false){
				$repair_fuel=repair_fuel($id);
				$repair_fuel=round((1-($fp/$fs))*$repair_fuel);
				//e($name.' - '.$repair_fuel);br();
				$hold=new hold('fuel='.$repair_fuel);
				if($object->hold->takehold($hold)){
                                        //Není potřeba chátrat v DB - chátrání je zatím pouze virtuální
					//sql_query('UPDATE `[mpx]pos_obj` SET fp=fs WHERE id='.$id);
                                        $buildingsall[$id]=false;
                                        $tmp++;
				}else{
					//error('nedostategg suregg');
				}
				unset($hold);
				}else{
					//blue('budova se neopravuje');
				}
			}
		}
		$object->update();
		unset($object);


	}else{
		//blue('auto opravy vypnute');
	}
    t('bot.php - 1 town');
}
ebr("Provedena virtuální oprava $tmp budov.");


//--------------------------Chátrání budov - Provedení
$tmp=0;
foreach($buildingsall as $id=>$yes){
    if($yes){
        $tmp++;
        trackobject($id);//záloha původního objektu, nastavení časů
        sql_query("UPDATE `[mpx]pos_obj` SET fp=CEIL(fp-(fs/".chaos_fall.")) WHERE id=".$id);//chátrání
    }
}

$tmp2=sql_query("UPDATE `[mpx]pos_obj` SET stoptime=".time()." WHERE $wwhere AND `type`='building' AND fp<0 AND ".objt());
ebr("Provedeno chátrání a záloha(bez opravy) $tmp budov.");
ebr("spadlo $tmp2 budov.");

}
//=================================================Rozhození terénů
if($_GET['wtf']=='all' or $_GET['wtf']=='b'){

die('nene'); //todo PH zprovoznit
$water=(sql_1data("SELECT count(1) FROM [mpx]pos_obj WHERE `type`='terrain' AND $wwhere AND (`id`=1001 or `id`=1011)",2)-1+1)/(mapsize*mapsize)*100;
if($test){success("water=$water , chaos_water=".chaos_water);br();}
//die();

$i=0;
$limit=(!$test)?(mapsize*mapsize*chaos_terrain/100):1;

//while($i<$limit and $ii<$protection){$ii++;$i++;

$subquery='0';
$array=array(array($x-1,$y-1),
			 array($x-1,$y-0),
			 array($x-1,$y+1),
			 array($x-0,$y-1),
			 //array($x-0,$y-0),
			 array($x-0,$y+1),
			 array($x+1,$y-1),
			 array($x+1,$y-0),
			 array($x+1,$y+1));
foreach($array as $tmp){
	list($tmpx,$tmpy)=$tmp;
	$subquery.=(" OR A.id!=(SELECT id FROM [mpx]pos_obj AS B WHERE B.`type`='terrain' AND A.ww=B.ww AND B.x=$tmpx AND B.y=$tmpy)");
}

$terrains=sql_array('SELECT `x`,`y`,`res` FROM [mpx]pos_obj AS A WHERE `type`=\'terrain\' AND '.$wwhere.' AND ('.$subquery.') ORDER BY RAND() LIMIT '.$limit,$test?2:0);
if($test)hr();

// AS A WHERE (SELECT count(1) FROM `[mpx]pos_obj` AS B WHERE POW(A.x-B.x,2)+POW(A.y-B.y,2)<POW('.sql(chaos_terrain_range).',2) )=0

foreach($terrains as $tmp){
	list($x,$y,$terrain)=$tmp;
	$x=$x-1+1;$y=$y-1+1;

	$budova=sql_1data("SELECT count(1) FROM `[mpx]pos_obj` WHERE $wwhere AND ROUND(x)=$x AND ROUND(y)=$y",$test?2:0);if($test)br();
	if($budova){
		if($test)e('!budova');
	}else{

	$array=array(array($x-1,$y-1),
				 array($x-1,$y-0),
				 array($x-1,$y+1),
				 array($x-0,$y-1),
				 //array($x-0,$y-0),
				 array($x-0,$y+1),
				 array($x+1,$y-1),
				 array($x+1,$y-0),
				 array($x+1,$y+1));
	shuffle($array);
	foreach($array as $tmp){
		list($tmpx,$tmpy)=$tmp;
        //@todo Funguje to?
		$terrain2=sql_1data("SELECT id FROM [mpx]pos_obj WHERE `type`='terrain' AND $wwhere AND x=$tmpx AND y=$tmpy",$test?2:0);
		if($test)br();
		if($terrain2 and $terrain!=$terrain2){
			//$budova=sql_1data("SELECT count(1) FROM `[mpx]pos_obj` WHERE ROUND(x)=$tmpx AND ROUND(y)=$tmpy",$test?2:0);
			if($test)br();
			if(/*!$budova*/true){

				$no=false;
				if(($terrain2==1001 or $terrain2==1011) and $water>chaos_water+5){$no=true;}
				if(($terrain==1001 or $terrain==1011) and $water<chaos_water-5){$no=true;}
				
				if($no==false){$i++;
					sql_query("UPDATE [mpx]pos_obj SET id='$terrain2' WHERE `type`='terrain' AND $wwhere AND x=$x AND y=$y",$test?2:0);
					if($test)br();
					changemap($x,$y,2);
					changemap($tmpx,$tmpy,2);
					//e("push ($x,$y,$terrain) to ($tmpx,$tmpy,$terrain2)");
					e("pushx ($tmpx,$tmpy,$terrain2) to ($x,$y,$terrain)");
					break;
				}else{
					if($test){e('!water');br();}
				}
				
			}else{
				if($test){e('!budova2');br();}
			}
		}else{
			if($test){e("!$terrain=$terrain2");br();}
		}
	}

	}
	if($test)hr();

}
e("Provedeno $i / $limit změn terénu");br();
}
//=================================================Rozhození stromů 110-tree 111-rock
if($_GET['wtf']=='all' or $_GET['wtf']=='c'){
    
die('nene'); //@todo PH zprovoznit a prevod Map do Object
/*foreach(array(110,111) as $origin){
	if($origin==110){$type='tree';$terrain='t10';e('Provádím stromy...');br();}
	if($origin==111){$type='rock';$terrain='t5';e('Provádím sklály');br();}

	
	if($test){
		$limit=1;
	}else{
		$limit=ceil(sql_1data("SELECT count(1) FROM `[mpx]pos_obj` WHERE $wwhere AND type='$type'")*(($type=='tree'?chaos_tree:chaos_rock)/100));
		//die($limit);
	}

	$array=sql_array('SELECT `x`,`y` FROM `[mpx]pos_obj` WHERE '.$wwhere.' AND origin LIKE \'%'.$origin.'%\' ORDER BY RAND() LIMIT '.$limit);
	//t('a');
	foreach($array as $row){
		list($x,$y)=$row;

		$id=sql_1data("SELECT `id` FROM `[mpx]pos_obj` WHERE $wwhere AND type='$type' ORDER BY POW(x-$x,2)+POW(y-$y,2) LIMIT 1");
		//t('b');

		$new=sql_array('SELECT `x`,`y` FROM [mpx]map WHERE '.$wwhere.' AND terrain=\''.$terrain.'\' ORDER BY RAND() LIMIT 1');
		//t('c');		
		//t10 je les
		list($nx,$ny)=$new[0];
		$nx-=rand(-100,100)/50;
		$ny-=rand(-100,100)/50;		

		sql_query("UPDATE `[mpx]pos_obj` SET `x`=$nx , `y`=$ny WHERE $wwhere AND `id`='$id' LIMIT 1");
		changemap($x,$y,1);
		changemap($nx,$ny,1);		
		//br();

	}
}*/
}
//=================================================
e('<b>hotovo</b>');br();
if(!$test and !$croncron){
	echo('<script language="javascript">
    window.location.replace("?world='.w.'&page=createtmp&onlyn=&start=1");
    </script>');
}

}



?>
<br>
<a href="?page=service&amp;action=1&amp;wtf=all">spustit</a><br>
<a href="?page=service&amp;action=$test&amp;wtf=a">spustit chátrání</a><br>
<a href="?page=service&amp;action=test&amp;wtf=b">testovat terény</a><br>
<a href="?page=service&amp;action=test&amp;wtf=c">testovat stromy/skály</a><br>

