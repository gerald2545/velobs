<?php header('Content-Type: text/html; charset=UTF-8');
	session_start();
	include '../key.php';
	
	if (isset($_SESSION['user'])) {
		switch (SGBD) {
			case 'mysql':
				$link = mysql_connect(HOST,DB_USER,DB_PASS);
				mysql_select_db(DB_NAME);
				mysql_query("SET NAMES 'utf8'");
				
				if (isset($_GET['type'])) {
					switch ($_GET['type']) {
						case 'category':
							$file = "category_".date('Y-m-d').".csv";
							$fh = fopen("../../../resources/csv/".$file, 'w');
							if (!$fh) {
								echo '{"success": false}';
							} else {
								$sql = "SELECT * FROM category ORDER BY id_category ASC";
								$result = mysql_query($sql);
								$csv = '"id_category";"lib_category";"display_category"';
								$csv .= "\r\n";
								while ($row = mysql_fetch_array($result)) {
									$csv .= $row['id_category'].';"'.$row['lib_category'].'";'.$row['display_category'];
									$csv .= "\r\n";
								}
								fputs($fh, $csv);
								fclose($fh);
								echo '{"success": true, "file": "'.$file.'"}';
							}
							break;
							
						case 'subcategory':
							$file = "subcategory_".date('Y-m-d').".csv";
							$fh = fopen("../../../resources/csv/".$file, 'w');
							if (!$fh) {
								echo '{"success": false}';
							} else {
								$sql = "SELECT * FROM subcategory INNER JOIN category ON (category.id_category = subcategory.category_id_category) ORDER BY id_subcategory ASC";
								$result = mysql_query($sql);
								$csv = '"id_subcategory";"lib_subcategory";"lib_category";"display_subcategory"';
								$csv .= "\r\n";
								while ($row = mysql_fetch_array($result)) {
									$csv .= $row['id_subcategory'].';"'.$row['lib_subcategory'].'";"'.$row['lib_category'].'";'.$row['display_subcategory'];
									$csv .= "\r\n";
								}
								fputs($fh, $csv);
								fclose($fh);
								echo '{"success": true, "file": "'.$file.'"}';
							}
							break;	
												
						case 'poi':
							$file = "velobs_admin_poi_".date('Y-m-d').".csv";
							$fh = fopen("../../../resources/csv/".$file, 'w');
							if (!$fh) {
								echo '{"success": false}';
							} else {
								$sql = "SELECT poi.*, priorite.lib_priorite, pole.lib_pole, quartier.lib_quartier, x(poi.geom_poi) AS X, y(poi.geom_poi) AS Y, lib_category, lib_subcategory, lib_commune FROM poi INNER JOIN subcategory ON (subcategory.id_subcategory = poi.subcategory_id_subcategory) INNER JOIN category ON (subcategory.category_id_category = category.id_category) INNER JOIN commune ON (commune.id_commune = poi.commune_id_commune) INNER JOIN quartier ON (quartier.id_quartier = poi.quartier_id_quartier) INNER JOIN pole ON (pole.id_pole = poi.pole_id_pole) INNER JOIN priorite ON (priorite.id_priorite = poi.priorite_id_priorite) WHERE delete_poi = FALSE ORDER BY id_poi ASC";
								$result = mysql_query($sql);
								$csv = '"Identifiant";"Commentaire final de l\'association";"Réponse du Grand Toulouse";"Observation terrain";"Priorité";"Pôle";"Quartier";"Adhérent";"Libellé enregistrement";"Catégorie";"Sous-catégorie";"Repère";"Rue";"Commune";"Description";"Proposition";"Modération";"Affichage sur la carte";"Latitude";"Longitude";"Date création";"Mode géolocalisation";"Email"';
								$csv .= "\r\n";
								while ($row = mysql_fetch_array($result)) {
									$csv .= stripslashes($row['id_poi'].';"'.str_replace('"', "", $row['commentfinal_poi']).'";"'.str_replace('"', "", $row['reponsegrandtoulouse_poi']).'";"'.str_replace('"', "", $row['observationterrain_poi']).'";"'.$row['lib_priorite'].'";"'.$row['lib_pole'].'";"'.$row['lib_quartier'].'";"'.$row['adherent_poi'].'";"'.str_replace('"', "", $row['lib_poi']).'";"'.stripslashes($row['lib_category']).'";"'.stripslashes($row['lib_subcategory']).'";"'.str_replace('"', "", $row['num_poi']).'";"'.str_replace('"', "", $row['rue_poi']).'";"'.$row['lib_commune'].'";"'.str_replace('"', "", $row['desc_poi']).'";"'.str_replace('"', "", $row['prop_poi']).'";'.$row['moderation_poi'].';'.$row['display_poi'].';'.$row['Y'].';'.$row['X'].';"'.$row['datecreation_poi'].'";'.$row['geolocatemode_poi'].';"'.$row['mail_poi'].'"');
									$csv .= "\r\n";
								}
								fputs($fh, $csv);
								fclose($fh);
								echo '{"success": true, "file": "'.$file.'"}';
							}
							break;

					    case 'poimodo':
                            $file = "velobs_pole-".$_SESSION['pole']."_poi_".date('Y-m-d').".csv";
                            $fh = fopen("../../../resources/csv/".$file, 'w');
                            if (!$fh) {
                                echo '{"success": false}';
                            } else {
                                $sql = "SELECT poi.*, priorite.lib_priorite, pole.lib_pole, quartier.lib_quartier, x(poi.geom_poi) AS X, y(poi.geom_poi) AS Y, lib_category, lib_subcategory, lib_commune FROM poi INNER JOIN subcategory ON (subcategory.id_subcategory = poi.subcategory_id_subcategory) INNER JOIN category ON (subcategory.category_id_category = category.id_category) INNER JOIN commune ON (commune.id_commune = poi.commune_id_commune) INNER JOIN quartier ON (quartier.id_quartier = poi.quartier_id_quartier) INNER JOIN pole ON (pole.id_pole = poi.pole_id_pole) INNER JOIN priorite ON (priorite.id_priorite = poi.priorite_id_priorite) WHERE pole_id_pole = ".$_SESSION['pole']." WHERE delete_poi = FALSE ORDER BY id_poi ASC";
                                $result = mysql_query($sql);
                                $csv = '"Identifiant";"Commentaire final de l\'association";"Réponse du Grand Toulouse";"Observation terrain";"Priorité";"Pôle";"Quartier";"Adhérent";"Libellé enregistrement";"Catégorie";"Sous-catégorie";"Repère";"Rue";"Commune";"Description";"Proposition";"Modération";"Affichage sur la carte";"Latitude";"Longitude";"Date création";"Mode géolocalisation";"Email"';
                                $csv .= "\r\n";
                                while ($row = mysql_fetch_array($result)) {
                                    $csv .= stripslashes($row['id_poi'].';"'.str_replace('"', "", $row['commentfinal_poi']).'";"'.str_replace('"', "", $row['reponsegrandtoulouse_poi']).'";"'.str_replace('"', "", $row['observationterrain_poi']).'";"'.$row['lib_priorite'].'";"'.$row['lib_pole'].'";"'.$row['lib_quartier'].'";"'.$row['adherent_poi'].'";"'.str_replace('"', "", $row['lib_poi']).'";"'.stripslashes($row['lib_category']).'";"'.stripslashes($row['lib_subcategory']).'";"'.str_replace('"', "", $row['num_poi']).'";"'.str_replace('"', "", $row['rue_poi']).'";"'.$row['lib_commune'].'";"'.str_replace('"', "", $row['desc_poi']).'";"'.str_replace('"', "", $row['prop_poi']).'";'.$row['moderation_poi'].';'.$row['display_poi'].';'.$row['Y'].';'.$row['X'].';"'.$row['datecreation_poi'].'";'.$row['geolocatemode_poi'].';"'.$row['mail_poi'].'"');
                                    $csv .= "\r\n";
                                }
                                fputs($fh, $csv);
                                fclose($fh);
                                echo '{"success": true, "file": "'.$file.'"}';
                            }
                            break;
							
						case 'poicomcom':
							$file = "velobs_poi_comcom_".date('Y-m-d').".csv";
							$fh = fopen("../../../resources/csv/".$file, 'w');
							if (!$fh) {
								echo '{"success": false}';
							} else {
								$sql = "SELECT poi.*, priorite.lib_priorite, pole.lib_pole, x(poi.geom_poi) AS X, y(poi.geom_poi) AS Y, lib_commune, lib_subcategory FROM poi INNER JOIN subcategory ON (subcategory.id_subcategory = poi.subcategory_id_subcategory) INNER JOIN category ON (subcategory.category_id_category = category.id_category) INNER JOIN commune ON (commune.id_commune = poi.commune_id_commune) INNER JOIN quartier ON (quartier.id_quartier = poi.quartier_id_quartier) INNER JOIN pole ON (pole.id_pole = poi.pole_id_pole) INNER JOIN priorite ON (priorite.id_priorite = poi.priorite_id_priorite) WHERE moderation_poi = 1 AND priorite.id_priorite <> 7 AND priorite.id_priorite <> 15 AND commune_id_commune IN (".str_replace(';',',',$_SESSION['territoire']).") AND pole_id_pole <> 9 AND delete_poi = FALSE ORDER BY id_poi ASC";
								$result = mysql_query($sql);
								$csv = '"Identifiant";"Commentaire final de l\'association";"Réponse du Grand Toulouse";"Observation terrain";"Priorité";"Pôle";"Type d\'observation";"Repère";"Rue";"Commune";"Description";"Proposition";"Latitude";"Longitude";"Date de création"';
								$csv .= "\r\n";
								while ($row = mysql_fetch_array($result)) {
									$csv .= stripslashes($row['id_poi'].';"'.str_replace('"', "", $row['commentfinal_poi']).'";"'.str_replace('"', "", $row['reponsegrandtoulouse_poi']).'";"'.str_replace('"', "", $row['observationterrain_poi']).'";"'.$row['lib_priorite'].'";"'.str_replace('"', "", $row['lib_pole']).'";"'.stripslashes($row['lib_subcategory']).'";"'.str_replace('"', "", $row['num_poi']).'";"'.str_replace('"', "", $row['rue_poi']).'";"'.$row['lib_commune'].'";"'.str_replace('"', "", $row['desc_poi']).'";"'.str_replace('"', "", $row['prop_poi']).'";'.$row['Y'].';'.$row['X'].';"'.$row['datecreation_poi'].'";');
									$csv .= "\r\n";
								}
								fputs($fh, $csv);
								fclose($fh);
								echo '{"success": true, "file": "'.$file.'"}';
							}
							break;		
							
						case 'commune':
							$file = "commune_".date('Y-m-d').".csv";
							$fh = fopen("../../../resources/csv/".$file, 'w');
							if (!$fh) {
								echo '{"success": false}';
							} else {
								$sql = "SELECT * FROM commune ORDER BY id_commune ASC";
								$result = mysql_query($sql);
								$csv = '"id_commune";"lib_commune";';
								$csv .= "\r\n";
								while ($row = mysql_fetch_array($result)) {
									$csv .= $row['id_commune'].';"'.$row['lib_commune'].'";';
									$csv .= "\r\n";
								}
								fputs($fh, $csv);
								fclose($fh);
								echo '{"success": true, "file": "'.$file.'"}';
							}
							break;
							
						case 'pole':
							$file = "pole_".date('Y-m-d').".csv";
							$fh = fopen("../../../resources/csv/".$file, 'w');
							if (!$fh) {
								echo '{"success": false}';
							} else {
								$sql = "SELECT * FROM pole ORDER BY id_pole ASC";
								$result = mysql_query($sql);
								$csv = '"id_pole";"lib_pole";';
								$csv .= "\r\n";
								while ($row = mysql_fetch_array($result)) {
									$csv .= $row['id_pole'].';"'.$row['lib_pole'].'";';
									$csv .= "\r\n";
								}
								fputs($fh, $csv);
								fclose($fh);
								echo '{"success": true, "file": "'.$file.'"}';
							}
							break;
							
						case 'quartier':
							$file = "quartier_".date('Y-m-d').".csv";
							$fh = fopen("../../../resources/csv/".$file, 'w');
							if (!$fh) {
								echo '{"success": false}';
							} else {
								$sql = "SELECT * FROM quartier ORDER BY id_quartier ASC";
								$result = mysql_query($sql);
								$csv = '"id_quartier";"lib_quartier";';
								$csv .= "\r\n";
								while ($row = mysql_fetch_array($result)) {
									$csv .= $row['id_quartier'].';"'.$row['lib_quartier'].'";';
									$csv .= "\r\n";
								}
								fputs($fh, $csv);
								fclose($fh);
								echo '{"success": true, "file": "'.$file.'"}';
							}
							break;
							
						case 'priorite':
							$file = "priorite_".date('Y-m-d').".csv";
							$fh = fopen("../../../resources/csv/".$file, 'w');
							if (!$fh) {
								echo '{"success": false}';
							} else {
								$sql = "SELECT * FROM priorite ORDER BY id_priorite ASC";
								$result = mysql_query($sql);
								$csv = '"id_priorite";"lib_priorite";';
								$csv .= "\r\n";
								while ($row = mysql_fetch_array($result)) {
									$csv .= $row['id_priorite'].';"'.$row['lib_priorite'].'";';
									$csv .= "\r\n";
								}
								fputs($fh, $csv);
								fclose($fh);
								echo '{"success": true, "file": "'.$file.'"}';
							}
							break;
					}
				} else {
					echo '{"success": false}';
				}
							
				mysql_free_result($result);
				mysql_close($link);	
				break;
			case 'postgresql':
				// TODO
				break;
		}

	}
?>