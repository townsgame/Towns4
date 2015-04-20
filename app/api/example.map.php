<?php
/**
 * Ukázková Towns API Aplikace - Izometrická mapa
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */

if($_SESSION['page']=='map')
	echo '<div class="pageDescription">Zobrazení mapy pomocí vlastnosti resurl</div>';

//----------------------------------------------------------------Pozice na mapě a zoom


require('positions.php');


//----------------------------------------------------------------


$map=array();
for($y=0;$y<$_SESSION['zoom'];$y++){
	$map[$y]=array();
	for($x=0;$x<$_SESSION['zoom'];$x++){
		$map[$y][$x]=array();
	}
}


//----------------------------------------------------------------Černé podbarvení mapy

$left=560;

	$svgw=($_SESSION['zoom']+1)*65;
	?>

	<div style="position:absolute;z-index:1">
		<div style="position:relative;left:<?=intval($left-($svgw/2))?>px;top:10px;">
				 <svg width="<?=($svgw)+8?>" height="<?=($svgw/2)+8?>">

					  <defs>
						<filter id="f1" x="0" y="0">
						  <feGaussianBlur in="SourceGraphic" stdDeviation="4" />
						</filter>
					  </defs>
					<polygon points="<?=$svgw/2?>,0 <?=$svgw?>,<?=$svgw/4?> <?=$svgw/2?>,<?=$svgw/2?> 0,<?=$svgw/4?>" style="fill:#000000" filter="url(#f1)" />
				</svg> 

		</div>
	</div>


	<?php

$s=0.5;

//-------------------------Mapa
$result = TownsApi('list', 'id,x,y,type,resurl'.($_SESSION['page']=='attack'?',func':''), array("box({$_SESSION['x']},{$_SESSION['y']},".($_SESSION['x']+$_SESSION['zoom']).",".($_SESSION['y']+$_SESSION['zoom']).")"), 'y,x');
$objects = $result['objects'];





$cell=(254/4)*$s;

echo '<div style="position:relative;height:700px;width:1400px;">';

if($objects)
foreach($objects as $object){
	//print_r($object);

    $relative_x=$object['x']-$_SESSION['x'];
    $relative_y=$object['y']-$_SESSION['y'];


	$real_x=($relative_x-$relative_y)*$cell;
	$real_y=($relative_x+$relative_y)*0.5*$cell;

	$real_x+=$left-($cell*2);


	if($object['type']!='terrain'){

		$real_y-=$s*(254-(133/2));
		$real_x+=$s*(133/2);
	}



	?>

	<div style="position:absolute;z-index:<?=intval($object['type']=='terrain'?1:3000+$real_y)?>">
		<div style="position:relative;left:<?=intval($real_x)?>px;top:<?=intval($real_y)?>px;">
			<img width="<?=($object['type']!='rock'?(100*$s):(162*$s))?>%" src="<?=$object['resurl']?>"  >


		</div>
	</div>

	<?php

	
	if(in_array($_SESSION['page'],array('map','build')) and $object['type']=='terrain'){
	?>
	<div style="position:absolute;z-index:10000">
		<a href="?selected_x=<?=$object['x']?>&selected_y=<?=$object['y']?>" onclick="return loadSelectedMap(<?=$object['x'].','.$object['y']?>);">
		<div style="position:relative;left:<?=intval($real_x+45)?>px;top:<?=intval($real_y-10)?>px;width:40px;height:40px;background: rgba(0,0,0,0);border-radius:40px;">
		</div>
		</a>
	</div>
	<?php
	}


}

//----------------------------------------------------------------Ovládací prvky
?>

	<script type="text/javascript">

	function loadMap(xm,ym){
		return loadURL('page','?onlypage=1&xm='+xm+'&ym='+ym);
	}

	function loadSelectedMap(selected_x,selected_y){
		return loadURL('page','?onlypage=1&selected_x='+selected_x+'&selected_y='+selected_y);
	}
	</script>



	<?php

	$moveby=2;


	$middle=($_SESSION['zoom']/2);
	$max=$_SESSION['zoom']+2;
	$min=-2;

	$real_1_x=intval(($min-$middle)*$cell+$left-$cell);
	$real_1_y=intval(($min+$middle)*0.5*$cell+$cell);//Posun dolů o $cell


	$real_2_x=intval(($middle-$min)*$cell+$left-$cell);
	$real_2_y=intval(($middle+$min)*0.5*$cell+$cell);//Posun dolů o $cell


	$real_3_x=intval(($max-$middle)*$cell+$left-$cell);
	$real_3_y=intval(($max+$middle)*0.5*$cell);


	$real_4_x=intval(($middle-$max)*$cell+$left-$cell);
	$real_4_y=intval(($middle+$max)*0.5*$cell);


	?>


	<div style="position:absolute;z-index:10000">
		<div style="position:relative;left:<?=$real_1_x?>px;top:<?=$real_1_y?>px;">
			<a href="?xm=<?=-$moveby?>" onclick="return loadMap(<?=-$moveby?>,0);">
				 <svg height="29" width="54">
					<polygon points="2,2 50,2 2,25" style="fill:#cccccc;stroke:#000000;stroke-width:2" />
				</svg> 
			</a>
		</div>
	</div>


	<div style="position:absolute;z-index:10000">
		<div style="position:relative;left:<?=$real_2_x?>px;top:<?=$real_2_y?>px;">
			<a href="?ym=<?=-$moveby?>" onclick="return loadMap(0,<?=-$moveby?>);">
				 <svg height="29" width="54">
					<polygon points="2,2 50,2 50,25" style="fill:#cccccc;stroke:#000000;stroke-width:2" />
				</svg> 
			</a>
		</div>
	</div>


	<div style="position:absolute;z-index:10000">
		<div style="position:relative;left:<?=$real_3_x?>px;top:<?=$real_3_y?>px;">
			<a href="?xm=<?=$moveby?>" onclick="return loadMap(<?=$moveby?>,0);">
				 <svg height="29" width="54">
					<polygon points="50,25 50,2 2,25" style="fill:#cccccc;stroke:#000000;stroke-width:2" />
				</svg> 
			</a>
		</div>
	</div>


	<div style="position:absolute;z-index:10000">
		<div style="position:relative;left:<?=$real_4_x?>px;top:<?=$real_4_y?>px;">
			<a href="?ym=<?=$moveby?>" onclick="return loadMap(0,<?=$moveby?>);">
				 <svg height="29" width="54">
					<polygon points="2,2 2,25 50,25" style="fill:#cccccc;stroke:#000000;stroke-width:2" />
				</svg> 
			</a>
		</div>
	</div>
<?php
//----------------------------------------------------------------

echo '</div>';


?>



