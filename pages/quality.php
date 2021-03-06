<?php 
	//phpinfo();
	use \App\Autoloader;
	use \App\Companies\Companies;
	use \App\COMPANY\Employees;
	use \App\COMPANY\Numbering;
	use \App\Methods\Ressource;
	use \App\Methods\Section;
	use \App\Quality\QL_Action;
	use \App\Quality\QL_Causes;
	use \App\Quality\QL_Defaut;
	use \App\Quality\QL_Derogation;
	use \App\Quality\QL_Corrections;
	use \App\Quality\QL_Devices;
	use \App\Quality\QL_NFC;
	use \App\UI\Document;
	use \App\UI\Form;
	use \App\UI\UI;

	//auto load class
	require_once '../app/Autoload.class.php';
	Autoloader::register();
	
	session_start();
	header( 'content-type: text/html; charset=utf-8' );
	//init form class
	$Form = new Form($_POST);
	$Companies = new Companies();
	$Employees = new Employees();
	$Numbering = new Numbering();
	$Ressource = new Ressource();
	$Section = new Section();
	$QL_Action = new QL_Action();
	$QL_Causes = new QL_Causes();
	$QL_Defaut = new QL_Defaut();
	$QL_Derogation = new QL_Derogation();
	$QL_Corrections = new QL_Corrections();
	$QL_Devices = new QL_Devices();
	$QL_FNC = new QL_NFC();
	$Document = new Document();
	$UI = new UI();

	//Check if the user is authorized to view the page
	if($_SESSION['page_6'] != '1'){
		stop($langue->show_text('SystemInfoAccessDenied'), 161, 'index.php?page=login');
	}

	if(isset($_GET['FNC']) AND !empty($_GET['FNC'])){
		$ParDefautDiv1 = 'id="defaultOpen"';
		if(isset($_POST['id']) AND !empty($_POST['id'])){
			//update FNC
			$bdd->GetUpdatePOST(TABLE_ERP_NFC, $_POST, 'WHERE id IN ('. $_POST['id'] . ')');
			$IdQL_FNC = $_GET['FNC'];
			$CallOutBox->add_notification(array('3', $langue->show_text('UpdateFNC')));
		}elseif(isset($_POST['CODE']) AND !empty($_POST['CODE'])){	
			//insert in DB new FNC
			$IdQL_FNC = $QL_FNC->NewQL_FNC($_POST['CODE'], $User->idUSER,$_POST['COMPANY_ID'] );
			$Numbering->getIncrementNumbering(11);
			$CallOutBox->add_notification(array('2',  $langue->show_text('AddFNC')));
		}else{
			$IdQL_FNC = $_GET['FNC'];
		}
	}
	elseif(isset($_GET['action']) AND !empty($_GET['action'])){
		$ParDefautDiv2 = 'id="defaultOpen"';
		if(isset($_POST['id']) AND !empty($_POST['id'])){
			//update Action
			$bdd->GetUpdatePOST(TABLE_ERP_QL_ACTION, $_POST, 'WHERE id IN ('. $_POST['id'] . ')');
			$IdQL_Action = $_GET['action'];
			$CallOutBox->add_notification(array('3', $langue->show_text('UpdateAction')));
		}elseif(isset($_POST['CODE']) AND !empty($_POST['CODE'])){	
			//insert in DB new Action
			$IdQL_Action = $QL_Action->NewQL_Action($_POST['CODE'], $User->idUSER);
			$CallOutBox->add_notification(array('2',  $langue->show_text('AddAction')));
		}else{
			$IdQL_Action = $_GET['action'];
		}
	}
	elseif(isset($_GET['derogation']) AND !empty($_GET['derogation'])){
		$ParDefautDiv3 = 'id="defaultOpen"';	
		if(isset($_POST['id']) AND !empty($_POST['id'])){
			//Update in DB derogation
			$bdd->GetUpdatePOST(TABLE_ERP_DEROGATION, $_POST, 'WHERE id IN ('. $_POST['id'] . ')');
			$IdQL_Derogation = $_GET['derogation'];
			$CallOutBox->add_notification(array('3', $langue->show_text('UpdateDerogation')));
		}elseif(isset($_POST['CODE']) AND !empty($_POST['CODE'])){	
			//insert in DB new derogation
			$IdQL_Derogation = $QL_Derogation->NewQL_Derogation($_POST['CODE'], $User->idUSER);
			$CallOutBox->add_notification(array('32',  $langue->show_text('AddDerogation')));
		}else{
			$IdQL_Derogation = $_GET['derogation'];
		}
		
	}
	elseif(isset($_GET['device']) AND !empty($_GET['device'])){
		$ParDefautDiv4 = 'id="defaultOpen"';
		
		//if picture is updated
		if ( isset( $_FILES["PICTURE_DEVICES"] ) && !empty( $_FILES["PICTURE_DEVICES"]["name"] ) ) {	

				$dossier = PICTURE_FOLDER.QUALITY_DEVICES_FOLDER;
				$fichier = $_FILES['PICTURE_DEVICES']['name'];
				
				if (move_uploaded_file($_FILES['PICTURE_DEVICES']['tmp_name'], $dossier . $fichier)){

					$bdd->GetUpdatePOST(TABLE_ERP_QL_APP_MESURE, array("PICTURE_DEVICES" => $fichier ), 'WHERE id='. $_GET['device'] . '');
					$CallOutBox->add_notification(array('3', $i . $langue->show_text('UpdateDeviceNotification')));
				}
				else{
					$CallOutBox->add_notification(array('3', $langue->show_text('ErrorDeviceNotification')) );
				}	
		}
	}
	else{
		$ParDefautDiv1 = 'id="defaultOpen"';
	}
?>
	<div class="tab">
		<button class="tablinks" onclick="openDiv(event, 'div1')" <?= $ParDefautDiv1 ?>><?=$langue->show_text('Title1'); ?></button>
		<button class="tablinks" onclick="openDiv(event, 'div2')" <?= $ParDefautDiv2 ?>><?=$langue->show_text('Title2'); ?></button>
		<button class="tablinks" onclick="openDiv(event, 'div3')" <?= $ParDefautDiv3 ?>><?=$langue->show_text('Title3'); ?></button>
		<button class="tablinks" onclick="openDiv(event, 'div4')" <?= $ParDefautDiv4 ?>><?=$langue->show_text('Title4'); ?></button>
		<?php if(isset($_GET['FNC']) or isset($_GET['action']) or isset($_GET['derogation']) or isset($_GET['device'])){?>
		<button class="tablinks" onclick="openDiv(event, 'div5')" <?= $ParDefautDiv5 ?>><?=$langue->show_text('Title5'); ?></button>
		<?php } ?>
	</div>
	<div id="div1" class="tabcontent" >
		<div class="row">
			<div class="column-menu">
				<?php echo $UI->GetSearchMenu($QL_FNC->GETQL_NFCList('', false), 'index.php?page=quality&FNC', $langue->show_text('FindNFC') ); ?>
			</div>
			<?php if(isset($_GET['FNC']) AND !empty($_GET['FNC'])):
				$DocumentType = 'NC_ID';
				$Data= $QL_FNC->GETQL_FNC($IdQL_FNC);?>
			<div class="column-large">
				<form method="POST" action="index.php?page=quality&FNC=<?= $Data->id ?>" class="content-form">
					<div class="row">
						<div class="column">
							<table class="content-table">
									<thead>
										<tr>
											<th colspan="2"><?= $Form->input('hidden', 'id',  $Data->id) ?><br/></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><?= $langue->show_text('TableCODE') ?></td>
											<td><?= $Form->input('text', 'CODE',  $Data->CODE) ?></td>
										</tr>
										<tr>
											<td><?= $langue->show_text('TableLabel') ?></td>
											<td><?= $Form->input('text', 'LABEL',  $Data->LABEL) ?></td>
										</tr>
										<tr>
											<td><?= $langue->show_text('TableCREATED') ?><?= $Data->CREATED ?></td>
											<td><?= $langue->show_text('TableAuteur') ?><?= $Data->NOM_CREATOR ?> <?= $Data->PRENOM_CREATOR ?></td>
										</tr>
										<tr>
											<td><?= $langue->show_text('TableMODIFIED') ?><?= $Data->MODIFIED ?></td>
											<td><?= $langue->show_text('TableModified') ?><?= $Data->NOM_MODIFIED ?> <?= $Data->PRENOM_MODIFIED ?></td>
										</tr>
										<tr>
											<td>
												<?= $langue->show_text('TableType') ?>
												<select name="TYPE">
													<option value="1" <?= selected($Data->TYPE, 1) ?>><?= $langue->show_text('SelectInternal') ?></option>
													<option value="2" <?= selected($Data->TYPE, 2) ?>><?= $langue->show_text('SelectExternal') ?></option>
												</select>
											</td>
											<td>
												<?= $langue->show_text('TableEtat') ?>
												<select name="ETAT">
													<option value="1" <?= selected($Data->ETAT, 1) ?>><?= $langue->show_text('SelectInProgess') ?></option>
													<option value="2" <?= selected($Data->ETAT, 2) ?>><?= $langue->show_text('SelectWaitingCustomerData') ?></option>
													<option value="3" <?= selected($Data->ETAT, 3) ?>><?= $langue->show_text('SelectValidate') ?></option>
													<option value="4" <?= selected($Data->ETAT, 4) ?>><?= $langue->show_text('SelectCanceled') ?></option>
												</select>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<?= $langue->show_text('TableCausedBy') ?>
												<select name="CAUSED_BY_ID">
													<?=$Employees->GETEmployeesList($Data->CAUSED_BY_ID) ?>
												</select>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<?= $langue->show_text('TableSection') ?>
												<select name="SECTION_ID">
													<?= $Section->GetSectionList($Data->SECTION_ID) ?>
												</select>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<?= $langue->show_text('TableRessource') ?>
												<select name="RESSOURCE_ID">
													<?= $Ressource->GETRessourcesList($Data->RESSOURCE_ID) ?>
												</select>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<?= $langue->show_text('TableCompany') ?>
												<select name="COMPANY_ID">
													<?= $Companies->GetCustomerList($Data->COMPANY_ID) ?>
												</select>
											</td>
										</tr>
										<tr>
											<td colspan="2" >
												<input type="submit" class="input-moyen" value="<?= $langue->show_text('TableUpdateButton') ?>" />
											</td>
										</tr>
									</tbody>
								</table>
						</div>
						<div class="column">
							<div class="card">
								<p><?= $langue->show_text('TableCause') ?></p>
								<select name="CAUSE_ID">
									<?=  $QL_Causes->GETQL_CausesList($Data->CAUSE_ID) ?>
								</select>
								<textarea class="Comment" name="CAUSE_COMMENT" rows="10" ><?=  $Data->CAUSE_COMMENT ?></textarea>.
							</div>
							<div class="card">
								<p><?= $langue->show_text('TableDefaut') ?></p>
								<select name="DEFAUT_ID">
									<?=  $QL_Defaut->GETQL_DefautList($Data->DEFAUT_ID) ?>
								</select>
								<textarea class="Comment" name="DEFAUT_COMMENT" rows="10" ><?=  $Data->DEFAUT_COMMENT ?></textarea>
							</div>
						</div>
						<div class="column">
							<div class="card">
								<p><?= $langue->show_text('TableCorrection') ?></p>
								<select name="CORRECTION_ID">
								<?=  $QL_Corrections->GETQL_CorrectionsList($Data->CORRECTION_ID) ?>
								</select>
								<textarea class="Comment" name="CORRECTION_COMMENT" rows="10" ><?=  $Data->CORRECTION_COMMENT ?></textarea>
							</div>
							<div class="card">
								<p><?= $langue->show_text('TableComment') ?></p>
								<textarea class="Comment" name="COMMENT" rows="10" ><?=  $Data->COMMENT ?></textarea>
							</div>
						</div>
					</div>
				</form>
			</div>
			<?php else: ?>
				<div class="column">
					<form method="POST" action="index.php?page=quality&FNC=new" class="content-form">
						<?php $UI->GetNewDocument($langue->show_text('TableNewFNC'), $langue->show_text('TableNewFNCFor'), $Companies->GetCustomerList(), $Form->input('text', 'CODE', $Numbering->getCodeNumbering(11)), $Form->submit($langue->show_text('TableNewButtonFNC'))); ?>
					</form>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div id="div2" class="tabcontent" >
		<div class="row">
			<div class="column-menu">
				<?php echo $UI->GetSearchMenu($QL_Action->GETQL_ActionList('', false), 'index.php?page=quality&action', $langue->show_text('FindAction') ); ?>
			</div>
			<?php if(isset($_GET['action']) AND !empty($_GET['action'])):
						$DocumentType = 'ACTION_ID';
						$Data= $QL_Action->GETQL_Action($IdQL_Action);?>
			
			<div class="column-large">
				<form method="POST" action="index.php?page=quality&action=<?= $Data->id ?>" class="content-form">
					<div class="row">
						<div class="column">
							<table class="content-table">
									<thead>
										<tr>
											<th colspan="2"><?= $Form->input('hidden', 'id',  $Data->id) ?><br/></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><?= $langue->show_text('TableCODE') ?></td>
											<td><?= $Form->input('text', 'CODE',  $Data->CODE) ?></td>
										</tr>
										<tr>
											<td><?= $langue->show_text('TableLabel') ?></td>
											<td><?= $Form->input('text', 'LABEL',  $Data->LABEL) ?></td>
										</tr>
										<tr>
											<td><?= $langue->show_text('TableDATE') ?><?= $Data->DATE ?></td>
											<td><?= $langue->show_text('TableAuteur') ?><?= $Data->NOM ?> <?= $Data->PRENOM ?></td>
										</tr>
										<tr>
											<td>
												<?= $langue->show_text('TableType') ?>
												<select name="TYPE">
													<option value="1" <?= selected($Data->TYPE, 1) ?>><?= $langue->show_text('SelectPreventive') ?></option>
													<option value="2" <?= selected($Data->TYPE, 2) ?>><?= $langue->show_text('SelectCorrective') ?></option>
													<option value="2" <?= selected($Data->TYPE, 2) ?>><?= $langue->show_text('SelectImprovement') ?></option>
													<option value="2" <?= selected($Data->TYPE, 2) ?>><?= $langue->show_text('SelectOther') ?></option>
												</select>
											</td>
											<td>
												<?= $langue->show_text('TableEtat') ?>
												<select name="ETAT">
													<option value="1" <?= selected($Data->ETAT, 1) ?>><?= $langue->show_text('SelectInProgess') ?></option>
													<option value="2" <?= selected($Data->ETAT, 2) ?>><?= $langue->show_text('SelectWaitingCustomerData') ?></option>
													<option value="3" <?= selected($Data->ETAT, 3) ?>><?= $langue->show_text('SelectValidate') ?></option>
													<option value="4" <?= selected($Data->ETAT, 4) ?>><?= $langue->show_text('SelectCanceled') ?></option>
												</select>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<?= $langue->show_text('TableResp') ?>
												<select name="RESP_ID">
													<?=$Employees->GETEmployeesList($Data->RESP_ID) ?>
												</select>
											</td>
										</tr>
										<tr>
											<td>
												<?= $langue->show_text('Title1') ?>
												<select name="NFC_ID">
													<?=  $QL_FNC->GETQL_NFCList($Data->NFC_ID)?>
												</select>
											</td>
											<td >
												<?= $langue->show_text('TableColor') ?>
												<?= $Form->input('color', 'COLOR',  $Data->COLOR) ?>
											</td>
										</tr>
										<tr>
											<td colspan="2" >
												<input type="submit" class="input-moyen" value="<?= $langue->show_text('TableUpdateButton') ?>" />
											</td>
										</tr>
									</tbody>
								</table>
						</div>
						<div class="column">
							<div class="card">
								<p><?= $langue->show_text('TableLabelProb') ?></p>
								<textarea class="Comment" name="PB_DESCP" rows="10" ><?=  $Data->PB_DESCP ?></textarea>.
							</div>	
							<div class="card">
								<p><?= $langue->show_text('TableCause') ?></p>
								<textarea class="Comment" name="CAUSE" rows="10" ><?=  $Data->CAUSE ?></textarea>
							</div>
							<div class="card">
								<p><?= $langue->show_text('TableAction') ?></p>
								<textarea class="Comment" name="ACTION" rows="10" ><?=  $Data->ACTION ?></textarea>
							</div>
						</div>
					</div>
				</form>
			</div>
			<?php else: 
				//make num sequence
				$CODE = $Numbering->getCodeNumbering(0, 'SELECT MAX(id) AS max_id FROM '. TABLE_ERP_QL_ACTION .'', 'ACT<I>' , 6); ?>
				<div class="column">
					<form method="POST" action="index.php?page=quality&action=new" class="content-form">
						<?php $UI->GetNewDocument('', $langue->show_text('TableNewAct') , null, $Form->input('text', 'CODE',  $CODE), $Form->submit($langue->show_text('TableNewButtonAct'))); ?>
					</form>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div id="div3" class="tabcontent" >
		<div class="row">
			<div class="column-menu">
				<?php echo $UI->GetSearchMenu($QL_Derogation->GETQL_DerogationListList('', false), 'index.php?page=quality&derogation', $langue->show_text('FindDerogation') ); ?>
			</div>
			<?php if(isset($_GET['derogation']) AND !empty($_GET['derogation'])):
						$DocumentType = 'DEROGATION_ID';
						$Data= $QL_Derogation->GETQL_Derogation($IdQL_Derogation);?>
			<div class="column-large">
				<form method="POST" action="index.php?page=quality&derogation=<?= $Data->id ?>" class="content-form">
					<div class="row">
						<div class="column">
							<table class="content-table">
									<thead>
										<tr>
											<th colspan="2"><?= $Form->input('hidden', 'id',  $Data->id) ?><br/></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><?= $langue->show_text('TableCODE') ?></td>
											<td><?= $Form->input('text', 'CODE',  $Data->CODE) ?></td>
										</tr>
										<tr>
											<td><?= $langue->show_text('TableLabel') ?></td>
											<td><?= $Form->input('text', 'LABEL',  $Data->LABEL) ?></td>
										</tr>
										<tr>
											<td><?= $langue->show_text('TableDATE') ?><?= $Data->DATE ?></td>
											<td><?= $langue->show_text('TableAuteur') ?><?= $Data->NOM ?> <?= $Data->PRENOM ?></td>
										</tr>
										<tr>
											<td>
												<?= $langue->show_text('TableType') ?>
												<select name="TYPE">
													<option value="1" <?= selected($Data->TYPE, 1) ?>><?= $langue->show_text('SelectInternal') ?></option>
													<option value="2" <?= selected($Data->TYPE, 2) ?>><?= $langue->show_text('SelectExternal') ?></option>
												</select>
											</td>
											<td>
												<?= $langue->show_text('TableEtat') ?>
												<select name="ETAT">
													<option value="1" <?= selected($Data->ETAT, 1) ?>><?= $langue->show_text('SelectInProgess') ?></option>
													<option value="2" <?= selected($Data->ETAT, 2) ?>><?= $langue->show_text('SelectWaitingCustomerData') ?></option>
													<option value="3" <?= selected($Data->ETAT, 3) ?>><?= $langue->show_text('SelectValidate') ?></option>
													<option value="4" <?= selected($Data->ETAT, 4) ?>><?= $langue->show_text('SelectCanceled') ?></option>
												</select>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<?= $langue->show_text('TableResp') ?>
												<select name="RESP_ID">
													<?=$Employees->GETEmployeesList($Data->RESP_ID) ?>
												</select>
											</td>
										</tr>
										<tr>
											<td>
												<?= $langue->show_text('Title1') ?>
												<select name="NFC_ID">
													<?=  $QL_FNC->GETQL_NFCList($Data->NFC_ID)?>
												</select>
											</td>
											<td >
												<?= $langue->show_text('TableReply') ?>
												<select name="REPLY">
													<option value="1" <?= selected($Data->REPLY, 1) ?>><?= $langue->show_text('NoReply') ?></option>
													<option value="2" <?= selected($Data->REPLY, 2) ?>><?= $langue->show_text('Accepted') ?></option>
													<option value="3" <?= selected($Data->REPLY, 3) ?>><?= $langue->show_text('Refuse') ?></option>
												</select>
											</td>
										</tr>
										<tr>
											<td colspan="2" >
												<input type="submit" class="input-moyen" value="<?= $langue->show_text('TableUpdateButton') ?>" />
											</td>
										</tr>
									</tbody>
								</table>
						</div>
						<div class="column">
							<div class="card">
							<p><?= $langue->show_text('TableLabelProb') ?></p>
								<textarea class="Comment" name="PB_DESCP" rows="10" ><?=  $Data->PB_DESCP ?></textarea>.
							</div>	
							<div class="card">
								<p><?= $langue->show_text('TableProposal') ?></p>
								<textarea class="Comment" name="PROPOSAL" rows="10" ><?=  $Data->PROPOSAL ?></textarea>
							</div>
						</div>
						<div class="column">
							<div class="card">
								<p><?= $langue->show_text('TableComment') ?></p>
								<textarea class="Comment" name="COMMENT" rows="10" ><?=  $Data->COMMENT ?></textarea>
							</div>
							<div class="card">
								<p><?= $langue->show_text('TableDecision') ?></p>
								<textarea class="Comment" name="DECISION" rows="10" ><?=  $Data->DECISION ?></textarea>
							</div>
						</div>
					</div>
				</form>
			</div>
			<?php else: 

				//make num sequence
				$CODE = $Numbering->getCodeNumbering(0, 'SELECT MAX(id) AS max_id FROM '. TABLE_ERP_DEROGATION .'', 'DER<I>' , 6) ?>
				<div class="column">
					<form method="POST" action="index.php?page=quality&derogation=new" class="content-form">
						<?php $UI->GetNewDocument('', $langue->show_text('TableNewDerog') , null, $Form->input('text', 'CODE',  $CODE), $Form->submit($langue->show_text('TableNewButtonDerog'))); ?>
					</form>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div id="div4" class="tabcontent" >
		<div class="row">
			<div class="column-menu">
				<?php echo $UI->GetSearchMenu($QL_Devices->GETQL_DevicesList('', false), 'index.php?page=quality&device', $langue->show_text('Finddevice') ); ?>
			</div>
			<?php if(isset($_GET['device']) AND !empty($_GET['device'])):
					$DocumentType = 'MESURING_DEVICE_ID';
					$Data= $QL_Devices->GETQL_Devices($_GET['device']);?>
			<div class="column">
				<div class="card">
					<h3><?=$langue->show_text('TableCODE'); ?> <?= $Data->CODE ?></h3>
					<h2><?=$langue->show_text('TableLabel'); ?> <?= $Data->LABEL ?></h2>
					<p><strong><?=$langue->show_text('TableRessource'); ?></strong> : <?= $Data->LABEL_RESSOURCE ?></p>
					<p><strong><?=$langue->show_text('TableUser'); ?></strong> : <?= $Data->NOM_USER ?> <?= $Data->PRENOM_USER ?></p>
					<p><strong><?=$langue->show_text('TableImatNumber'); ?></strong> : <?= $Data->SERIAL_NUMBER  ?></p>
					<p><strong><?=$langue->show_text('TableEndDate'); ?></strong> : <?= $Data->DATE ?></p>
				</div>
			</div>
			<div class="column">
				<div class="card">
					<form method="POST" action="index.php?page=quality&device=<?= $data->id ?>" class="content-form" enctype="multipart/form-data" >
						<p><img src="<?= PICTURE_FOLDER.QUALITY_DEVICES_FOLDER.$Data->PICTURE_DEVICES ?>" title="Picture quality devices" alt="Picture quality devices" class="Image-Aricle"/></p>
						<p><input type="file" name="PICTURE_DEVICES" id="PICTURE_DEVICES"  /></p>
						<p><?= $Form->submit($langue->show_text('TableUpdateButton')) ?><br/><br/></p>
					</form>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<div id="div5" class="tabcontent">
		<?php 
			echo $Document->GETAjaxScript($DocumentType, $Data->id); 
			echo $Document->GETDropZone(); 
			$TitreTable = array($langue->show_text('TableArticle'), $langue->show_text('TableLabel'),  $langue->show_text('TableLabel'), $langue->show_text('TableQty'), $langue->show_text('TableUnit'));
			echo $Document->GETDocumentList($DocumentType, $_GET['page'], $Data->id, $TitreTable ); 
		?>
	</div>