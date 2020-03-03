<?php
/**
 * admin_delete_entry_after.php
 * Interface permettant à l'administrateur de supprimer des réservations après une date donnée
 * Ce script fait partie de l'application GRR
 * Dernière modification : $Date: 2018-08-31 14:00$
 * @author    JeromeB & Yan Naessens & Denis Monasse
 * @copyright Copyright 2003-2018 Team DEVOME - JeromeB
 * @link      http://www.gnu.org/licenses/licenses.html
 *
 * This file is part of GRR.
 *
 * GRR is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 */
 // page restant à internationaliser
$grr_script_name = "admin_delete_entry_after.php";

include "../include/admin.inc.php";

$back = '';
if (isset($_SERVER['HTTP_REFERER']))
	$back = htmlspecialchars($_SERVER['HTTP_REFERER']);
$_SESSION['chemin_retour'] = "admin_config.php";

if (!Settings::load()) {
    die('Erreur chargement settings');
}

# print the page header
start_page_w_header('', '', '', $type = 'with_session');
// Affichage de la colonne de gauche
include 'admin_col_gauche2.php';
// Affichage de la colonne de droite 
echo "<div class='col-md-9 col-sm-8 col-xs-12'>";
echo '<h3>Supprimer des réservations commençant après une date donnée</h3><hr />'.PHP_EOL;
if(isset($_POST['delete'])) {
    echo '<br />';
    $starttime=mktime(0, 0, 0, $_POST['beg_month'],$_POST['beg_day'],$_POST['beg_year']);
    $sql="select id, area_name from ".TABLE_PREFIX."_area order by order_display";
    $res = grr_sql_query($sql);
    if (! $res) fatal_error(0, grr_sql_error());
    $nb_areas=grr_sql_count($res);
    for($i=0;$i < $nb_areas;$i++)
       if(isset($_POST["area".$i])){
          // echo "domaine ".$_POST["area".$i]." sélectionné <br />".PHP_EOL;
            $rooms=grr_sql_query("SELECT id, room_name FROM `".TABLE_PREFIX."_room` WHERE `area_id`=".$_POST["area".$i]);
           for ($j = 0; ($row = grr_sql_row($rooms, $j)); $j++) 
              if(grr_sql_query("DELETE FROM `".TABLE_PREFIX."_entry` WHERE `start_time`>=".$starttime." AND `room_id`=".$row[0]))
                     echo "Les réservations postérieures au ".$_POST['beg_day']."/".$_POST['beg_month']."/".$_POST['beg_year']." à 00 heures 00 dans la salle ".$row[1]." ont été supprimées<br/>";
              else 
                    echo "Erreur dans la suppression des réservations dans la salle ".$row[1]."<br/>"; 
       }                    
 }
 else { //echo 'les paramètres ne sont pas acquis';
    echo '<p>
            Utiliser ce script pour supprimer des réservations dans des domaines donnés
            après une date donnée:<br />
            toutes les réservations commençant après la date donnée à 00 heures dans les domaines cochés seront supprimées
          </p>
          <hr />';
    echo '<p> Il est conseillé de procéder à la sauvegarde de la base de données avant la suppression</p>';
    echo '<form action="admin_save_mysql.php" method="get">
              <input type="hidden" name="flag_connect" value="yes" />
              <input type="submit" value="Lancer une sauvegarde" />
          </form>';
    echo '<hr />';
    echo '<form enctype="multipart/form-data" action="./admin_delete_entry_after.php" id="nom_formulaire" method="post" >'.PHP_EOL;
    echo '<input type="hidden" name="delete" value="1" />'.PHP_EOL;
    echo "Sélectionnez les domaines concernés par la suppression"."<br />".PHP_EOL;
    // affichage et sélection des domaines concernés par la suppression 
          $sql="select id, area_name from ".TABLE_PREFIX."_area order by order_display";
          $res = grr_sql_query($sql);
          if (! $res) fatal_error(0, grr_sql_error());

          if (grr_sql_count($res) != 0) {
                  echo '<table>';
                  for ($i = 0; ($row = grr_sql_row($res, $i)); $i++) {
                      echo '<tr><td>';
                       echo '<input type="checkbox" name="area'.$i.'" value="'.$row[0].'"  />';
                       echo "&nbsp;".htmlspecialchars($row[1]);
                       echo "</td></tr>";
                  }
                  echo "</table>\n";
           }
 	echo '<p><br />Jour de début de la suppression <i>(opération irréversible ! )</i></p>';
    $typeDate = 'beg_';
    $day   = date("d");
    $month = date("m");
    $year  = date("Y"); //par défaut on propose la date du jour
    echo '<div class="col-xs-12">'.PHP_EOL;
    echo '<div class="form-inline">'.PHP_EOL;
    genDateSelector('beg_', $day, $month, $year, 'more_years');
    echo '<input type="hidden" disabled="disabled" id="mydate_'.$typeDate.'">'.PHP_EOL;
    echo '</div>'.PHP_EOL;
    echo '</div>'.PHP_EOL;
    echo '<div class="center">'.PHP_EOL;
    echo '<input type="submit" id="delete" value=" Supprimer les réservations! " /></div>'.PHP_EOL;
    echo '</form>';
    // fin de l'affichage de la colonne de droite
    echo "</div>";
    // et de la page
    end_page();
 }
?>