<?php/* Towns4, www.towns.cz    © Pavol Hejný | 2011-2015   _____________________________   core/login/bot.php   Automatičtí hráči*///==============================window(lr('title_apps_control'));$q=submenu(array('content','apps-control'),array('apps_all','apps_my'),1);contenu_a();if($q==1){    //==================================================================================================================Všechny aplikace    $apps = sql_array("SELECT id,name,profile,permalink FROM [mpx]pos_obj WHERE ww='0' AND type='app' AND " . objt());//todo ww    foreach ($apps as $app) {        $app['profile']=str2list($app['profile']);        th(ahrefr(trr($app['name'],20),'e=apps-app;id='.$app['id']));        tr();        td($app['profile']['description'].brr(2));        tr();    }    br();    table('100%',array('center','top'),1);    //==================================================================================================================}elseif($q==2) {    //==================================================================================================================Moje aplikace    br();    ahref(buttonr(lr('app_create'), 20), 'e=content;ee=apps-create;id=0;submenu=1');    br();    if (isset($GLOBALS['get']['deleteid'])) {        $tmpobject = new object($GLOBALS['get']['deleteid']);        $tmpobject->deletex();        unset($tmpobject);    }    $apps = sql_array("SELECT id,name,permalink,ww FROM [mpx]pos_obj WHERE `own`=" . $GLOBALS['ss']['logid'] . " AND type='app' AND " . objt().' ORDER BY ww');    foreach ($apps as $app) {        $t = time() - 50;        br();        ahref(trr($app['name'],20),'e=apps-app;id='.$app['id']);        if($app['ww']==0) {}else{            ahref(buttonr(lr('app_edit'), 11), 'e=content;ee=apps-create;id=' . $app['id']);            ahref(buttonr(lr('app_delete'), 11), 'e=content;ee=apps-control;deleteid=' . $app['id'] . ';prompt='.lr('app_delete_prompt'));        }        br();        //e($app['ww']);        if($app['ww']==-1){            blue(lr('app_status_waiting'));        }elseif($app['ww']==-2){            error(lr('app_status_rejected'));        }elseif($app['ww']==0){            success(lr('app_status_ok'));        }    }    br();    table('100%',array('center','top'),1);    //==================================================================================================================}contenu_b();?>