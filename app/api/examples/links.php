<?php
/**
 * Ukázková Towns API Aplikace - Odkazy na budovy, terény
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */

if(!isset($_GET['onlypage']))
echo '<div class="pageDescription">Zobrazení náhodných objektů na mapě a odkazů na ně</div>';

//----------------------------------------------------------------Náhodné budovy
echo '<p><b>Budovy:</b><br>';

$buildings = TownsApi('list', 'id,name,permalink,resurl,x,y,ww','building', 'rand',100);
$buildings = $buildings['objects'];

if($buildings)
foreach($buildings as $building){
?>
<a href="<?=($_SESSION['townsapi_url'].'/'.$building['permalink'])?>" target="_blank">
<img width="50" src="<?=$building['resurl']?>" ></a>
	
<?php
}
echo '</p>';



//----------------------------------------------------------------Náhodné terény
echo '<p><b>Terény:</b><br>';

$buildings = TownsApi('list', 'id,name,resurl,x,y,ww','terrain', 'rand',100);
$buildings = $buildings['objects'];

if($buildings)
foreach($buildings as $building){
?>
<a href="<?=$_SESSION['townsapi_url'].'/'.$building['x'].'-'.$building['y'].'-'.$building['ww']?>" target="_blank">
<img width="50" src="<?=$building['resurl']?>" ></a>
	
<?php
}
echo '</p>';

//----------------------------------------------------------------


