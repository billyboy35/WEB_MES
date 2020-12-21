<?php 
	//phpinfo();
	use \App\Autoloader;
	use \App\SQL;
	use \App\Language;
	use \App\COMPANY\Company;
	use \App\COMPANY\CompanyManager;
	use \App\CallOutBox;

	// include for the constants
	require_once '../include/include_recup_config.php';
	//auto load class
	require_once '../app/Autoload.class.php';
	Autoloader::register();

	session_start();
	header( 'content-type: text/html; charset=utf-8' );

	//open sql connexion
	$bdd = SQL::getInstance();
	//load company vairiable
	$CompanyManager = new CompanyManager($bdd);
	$donneesCompany = $CompanyManager->getDb();
	$Company = new Company($donneesCompany);
	// include for functions
	require_once '../include/include_fonctions.php';
	//session checking  user
	require_once '../include/include_checking_session.php';
	//init xml for user language
	$langue = new Language('lang', 'profil', $UserLanguage);
	//init call out box for notification
	$CallOutBox = new CallOutBox();

	//If user make change profil value
	if(isset($_POST['ProfilSpeudo']) AND !empty($_POST['ProfilName'])){

		$UpdateProfilName = $_POST['ProfilName'];
		$UpdateProfilSurName = $_POST['ProfilSurName'];
		$UpdateProfilSpeudo = $_POST['ProfilSpeudo'];
		$UpdateProfilDate = strtotime($_POST['ProfilDate']);
		$UpdateProfilDate = date('Y-m-d H:i:s', $UpdateProfilDate);
		$UpdateProfilMDP = $_POST['ProfilMDP'];
		$UpdateProfilMail = $_POST['ProfilMAIL'];
		$UpdateProfilInternalNumber = $_POST['ProfilInternalNumber'];
		$UpdateProfilPersonnalNumber = $_POST['ProfilPersonnalNumber'];
		$UpdatePhotoProfil = $dossier.$fichier;

		//if picture is updated
		$dossier = 'images/Profils/';
		$fichier = basename($_FILES['PhotoProfil']['name']);
		move_uploaded_file($_FILES['PhotoProfil']['tmp_name'], $dossier . $fichier);
		If(empty($fichier)){
			$AddSQL = '';
		}
		else{
			$AddSQL = 'IMAGE_PROFIL = \''. addslashes($UpdatePhotoProfil) .'\',';
		}

		//update database
		$bdd->exec('UPDATE `'. TABLE_ERP_EMPLOYEES .'` SET  NOM = \''. addslashes($UpdateProfilName) .'\',
																PRENOM = \''. addslashes($UpdateProfilSurName) .'\',
																DATE_NAISSANCE = \''. addslashes($UpdateProfilDate) .'\',
																MAIL = \''. addslashes($UpdateProfilMail) .'\',
																NUMERO_PERSO = \''. addslashes($UpdateProfilPersonnalNumber) .'\',
																NUMERO_INTERNE = \''. addslashes($UpdateProfilInternalNumber) .'\',
																'. $AddSQL .'
																NAME = \''. addslashes($UpdateProfilSpeudo) .'\',
																PASSWORD = \''. addslashes($UpdateProfilMDP) .'\'
																WHERE IdUser=\''. $_SESSION['id'] .'\'');

		$CallOutBox->add_notification(array('3', $i . $langue->show_text('UpdateProfilNotification')));
	}

	//If user make change setting profil
	if(isset($_POST['Language']) AND !empty($_POST['Language'])){
		$UpdateProfilLang = $_POST['Language'];

		//update database	
		$bdd->exec('UPDATE `'. TABLE_ERP_EMPLOYEES .'` SET  LANGUAGE = \''. addslashes($UpdateProfilLang) .'\'
																WHERE IdUser=\''. $_SESSION['id'] .'\'');

		stop($langue->show_text('SystemInfoLanguageDone'),	300,	'index.php?page=profil');
	}

	//load new value
	$req = $bdd -> query('SELECT idUSER,
								NOM,
								PRENOM,
								DATE_NAISSANCE,
								MAIL,
								NUMERO_PERSO,
								NUMERO_INTERNE,
								IMAGE_PROFIL,
								STATU,
								CONNEXION,
								NAME,
								PASSWORD,
								FONCTION,
								RIGHT_NAME,
								LANGUAGE
							FROM `'. TABLE_ERP_EMPLOYEES .'` LEFT JOIN `'. TABLE_ERP_RIGHTS .'` ON `'. TABLE_ERP_EMPLOYEES .'`.`FONCTION` = `'. TABLE_ERP_RIGHTS .'`.`id` WHERE IdUser=\''. $_SESSION['id'] .'\'');

	$donnees_membre = $req->fetch();

	$profil .='
	<form method="post" action="index.php?page=profil" class="content-form" enctype="multipart/form-data" >
		<table class="content-table">
			<thead>
				<tr>
					<th colspan="2">
						'. $langue->show_text('TitreTable1') .'
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						'. $langue->show_text('TableNameUser') .'
					</td>
					<td>
						<input type="text" name="ProfilName" value="'. $donnees_membre['NOM'] .'">
					</td>
				</tr>
				<tr>
					<td>
						'. $langue->show_text('TableSurNameUser') .'
					</td>
					<td>
						<input type="text" name="ProfilSurName" value="'. $donnees_membre['PRENOM'] .'" >
					</td>
				</tr>
				<tr>
					<td>
						'. $langue->show_text('TableDateBornUser') .'
					</td>
					<td>
						<input type="date" name="ProfilDate" value="'. $donnees_membre['DATE_NAISSANCE'] .'" >
					</td>
				</tr>
				<tr>
					<td>
						'. $langue->show_text('TableSpeudoUser') .'
					</td>
					<td>
						<input type="text" name="ProfilSpeudo" value="'. $donnees_membre['NAME'] .'" >
					</td>
				</tr>
				<tr>
					<td>
						'. $langue->show_text('TablePasswordUser') .'
					</td>
					<td>
						<input type="password" name="ProfilMDP" value="" id="ProfilMDP" required>
					</td>
				</tr>
				<tr>
					<td>
						'. $langue->show_text('TableConfirmPasswordUser') .'
					</td>
					<td>
						<input type="password" name="ProfilMDPComfirm" id="ProfilMDPComfirm" value="" required>
					</td>
				</tr>
				<tr>
					<td>
						'. $langue->show_text('TableMailUser') .'
					</td>
					<td>
						<input type="text" name="ProfilMAIL" value="'. $donnees_membre['MAIL'] .'">
					</td>
				</tr>
				<tr>
					<td>
						'. $langue->show_text('TableInternalNumberUser') .'
					</td>
					<td>
						<input type="text" name="ProfilInternalNumber" value="'. $donnees_membre['NUMERO_INTERNE'] .'">
					</td>
				</tr>
				<tr>
					<td>
						'. $langue->show_text('TablePersonnalNumberUser') .'
					</td>
					<td>
						<input type="text" name="ProfilPersonnalNumber" value="'. $donnees_membre['NUMERO_PERSO'] .'" >
					</td>
				</tr>
				<tr>
					<td colspan=2">
						'. $langue->show_text('TablePictureUser') .'
					</td>
				</tr>
				<tr>
					<td colspan=2" ><input type="file" name="PhotoProfil" /></td>
				</tr>
				<tr>
					<td colspan=2">
						<img src="'. $donnees_membre['IMAGE_PROFIL'] .'" title="Photo profil" alt="Photo" />
					</td>
				</tr>
				<tr>
					<td colspan="2" >
						<br/>
						<input type="submit" class="input-moyen" value="'. $langue->show_text('TableUpdateButton') .'" /> <br/>
						<br/>
					</td>
				</tr>
			</tbody>
		</table>
	</form>';

	 $setting ='
	<form method="post" action="index.php?page=profil" class="content-form" >
		 <table class="content-table">
		 	<thead>
				 <tr>
					 <th colspan="2">
						'. $langue->show_text('TitreTable2') .'
					 </th>
				 </tr>
			 </thead>
			 <tbody>
					<tr>
						<td>'. $langue->show_text('TableLanguageUser') .'</td>
						<td>
							<select name="Language">
								<option value="fr" '. selected($donnees_membre['LANGUAGE'], 'fr') .'>'. $langue->show_text('french') .'</option>
								<option value="en" '. selected($donnees_membre['LANGUAGE'], 'en') .'>'. $langue->show_text('english') .'</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" >
							<br/>
							<input type="submit" class="input-moyen" value="'. $langue->show_text('TableUpdateButton') .'" /> <br/>
							<br/>
						</td>
					</tr>	
			</tbody>
		</table>
	</form>';
?>
	<div class="tab">
		<button class="tablinks" onclick="openDiv(event, 'div1')" id="defaultOpen"><?=$langue->show_text('Titre1'); ?></button>
		<button class="tablinks" onclick="openDiv(event, 'div2')" id="defaultOpen"><?=$langue->show_text('Titre2'); ?></button>
		<button class="tablinks" onclick="window.location.href = 'http://localhost/erp/public/index.php?page=login&action=deconnexion';"><?=$langue->show_text('Titre3'); ?></button>
	</div>
	<div id="div1" class="tabcontent" >
<?php
	echo $profil;
?>
	</div>
	<div id="div2" class="tabcontent" >
<?php
	echo $setting;
?>
	</div>