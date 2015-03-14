<?php/* Towns4Admin, www.towns.cz    © Pavel Hejný | 2011-2014   _____________________________   admin/...   Towns4Admin - Nástroje pro správu Towns*///==============================?><h3>SQL Struktura</h3>Tato funkce slouží k aktualizaci struktury databáze (Tabulky a pohledy).<br/><b>Upozornění: </b>Tato funkce může při nesprávném použití poškodit data na serveru!<br /><h2><a href="?page=sql_structure&amp;start=1">Aktualizovat!</a></h2><?php$create_sql=create_sql();$newmd5=md5($create_sql);$lastsqlfile=tmpfile2('create.sql','md5','sql');//------------------------------------------------------------------------Spustitif($_GET['start']){    //------------------------------------Vytvoření pomocného prefixu    $newprefix='new'.time().'_';    //------------------------------------Vytvoření new tabulek    $create_sql=str_replace('`[mpx]','`'.$newprefix.'[mpx]',$create_sql);    $create_sql=str_replace('CREATE VIEW `'.$newprefix.'[mpx]','CREATE OR REPLACE VIEW `[mpx]',$create_sql);    sql_query($create_sql);    //exit();    //------------------------------------Zjištění tabulek    $tables=sql_array("SHOW FULL TABLES LIKE '".$newprefix."%'");        foreach($tables as $table){        list($table_new,$table_type)=$table;        $table_old=str_replace($newprefix,'',$table_new);                if($table_type=='BASE TABLE'){            //------------------------------------Porovnání sloupců            $columns_old=sql_array("SHOW COLUMNS FROM `$table_old`");            $columns_new=sql_array("SHOW COLUMNS FROM `$table_new`");            //print_r($columns_old);            $insert=array();            $select=array();            foreach($columns_new as $column_new){                $column_new=$column_new[0];                foreach($columns_old as $column_old){                    $column_old=$column_old[0];                    //ebr("$column_new==$column_old");                    if($column_new==$column_old){                        $insert[]=$column_new;                        $select[]=$column_old;                    }                }                      }            //------------------------------------Vyčištění nové tabulky            sql_query("TRUNCATE  TABLE `$table_new`");            //------------------------------------Přehrání dat            $insert=implodex($insert);            $select=implodex($select);            $sql="INSERT INTO `$table_new` ($insert) SELECT $select FROM `$table_old`".(strpos($select,'`id`')!==false?' ORDER BY `id`':'');            //ebr($sql);            sql_query($sql);            //------------------------------------Konrlola počtu dat            $count_new=sql_1number("SELECT count(*) FROM `$table_new`");            $count_old=sql_1number("SELECT count(*) FROM `$table_old`");            e($table_old."($count_new) - ");            if($count_new==$count_old){                ebr("OK");            }else{                ebr('Data error ('.$count_new.'!='.$count_old.') =&gt; WARNING!');            }            //------------------------------------Optimalizace            //PH - BLBNE//sql_query("OPTIMIZE TABLE `$table_new`");            //------------------------------------Smazání staré tabulky            sql_query("DROP TABLE `$table_old`");            //------------------------------------Přejmenování nové tabulky            sql_query("RENAME TABLE `$table_new` TO `$table_old`");            //------------------------------------                    }else{            ebr($table_old." - View");        }        //------------------------------------    }    //------------------------------------Uložení času aktualizace    fpc($lastsqlfile, $newmd5);}//------------------------------------Zjištění aktuálnosti$oldmd5=fgc($lastsqlfile);if($oldmd5!=$newmd5){    error('Struktura databáze není aktuální!');}else{    success('Struktura databáze je aktuální.');     }//------------------------------------------------------------------------?>