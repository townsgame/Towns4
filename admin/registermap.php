<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>Registace míst na mapě</h3>
<?php
require2("/func_map.php");

$scale=2;

/*if($_GET['refresh']){
	$outimg=tmpfile2("worldmap,$width,$w,$minsize".(serialize($worldmap_red)).t_,"png","map");

}*/


$file=tmpfile2("registerx_list","txt","text");
$array=unserialize(file_get_contents($file));
//print_r($array);

//-----------
$change=false;
//--------------------------
if($_GET['add']){
	$add=$_GET['add'];
	$add=str_replace('?','',$add);
	$array2=array();
	$array2=explode(',',$add);
	$array2[0]=round($array2[0]/$scale);
	$array2[1]=round($array2[1]/$scale);

	$q=true;
	foreach($array as $row){
		if($row[0]==$array2[0] and $row[1]==$array2[1]){
			error('Pozice už existuje!');
			$q=false;
		}else{
			
		}
	}
	if($q){
		$array2['x']=$array2[0];
		$array2['y']=$array2[1];
		$array2=array($array2);
		if($array and $array!=array()){
			$array=array_merge($array2,$array);
		}else{
			$array=$array2;
		}
		$change=true;
	}
}
//--------------------------
if($_GET['delete']){
	array_splice($array,$_GET['delete']-1,1);
	$change=true;
}
//--------------------------
if($change){
	
	file_put_contents2($file,serialize($array));
}
//-------


$i=0;
while($array[$i]){
 	list($x,$y)=$array[$i];
		//e("($x,$y)");
		//e('<div style="position:absolute"><div style="position:relative;top:'.(($y*$scale)-5).';left:'.(($x*$scale)-5).';">');

		for($yy=0;$yy<3;$yy++){
			for($xx=0;$xx<3;$xx++){
				
				e('<div style="position:absolute;z-index:'.(($xx==1 and $yy==1)?20:1).';"><div style="position:relative;top:'.(($y*$scale)+$yy-8).';left:'.(($x*$scale)+$xx-11).';width:20px;text-align:center;font-size:11px;">');
				e('<a style="color:#'.(($xx==1 and $yy==1)?'ffffff':'000000').';" href="?page=registermap&delete='.($i+1).'">');
				e('<b>'.($i+1).'</b>');
				e('</a></div></div>');
			}
		}

		//if($i<5){
			e('<div style="position:absolute;"><div style="position:relative;top:'.(($y*$scale)-9).';left:'.(($x*$scale)-9).';">');
			e('<a href="?page=registermap&delete='.($i+1).'">'.imgr('design/register'.(($i<5)?'2':'').'.png','',18,18).'</a>');
			e('</div></div>');
		//}

		//br();
	$i++;
}


e('<a href="?page=registermap&add="><img id="minimap" src="../../'.worldmap(mapsize*$scale,50,false,true).'" ismap /></a>');//24.12.2014
?>
<!--<a href="?page=registermap&amp;type=1">náhodně</a><br>
<a href="?page=registermap&amp;type=2">opuštěná místa</a><br>
<a href="?page=registermap&amp;type=3">vedle hráčů</a><br>-->
