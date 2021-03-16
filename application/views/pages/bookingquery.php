<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php $data=$this->session->flashdata('key');if($data):// print_r($data); 

// foreach ($data['weekday'] as $each) { 
//    echo $each;
//  };
 endif; ?>
<?php 
// $stack = array(); foreach ($allBookingInfo as $each) { 
//     array_push($stack, $each['c_name'] );
//  };
// print_r($allBookingInfo); 
$conflictDates=$this->session->flashdata('conflictDates');
if(!empty($conflictDates)){// print_r($conflictDates);
//	echo count($conflictDates);
}?>
		 <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
		 	
<div class="modal" id="myModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tuvastatud aegade kattuvus</h5>
 
      </div>
      <div class="modal-body">
		<h6>Soovitud broneering kattub allolevate broneeringutega. Sellist broneeringut automaatselt ei kinnitata. </h6>
	
		<table id="myTable" class="table">
		<thead>	<tr><th>Nädalapäev&nbsp;</th><th>Kuupäev&nbsp;&nbsp;&nbsp;</th><th>Kellaaeg&nbsp;</th><th>Treening&nbsp;</th><th>Klubi&nbsp;</th><th>Ruum&nbsp;</th></tr>	
		</thead>
			<tbody>
		
			</tbody>
		</table>
      </div>
      <div class="modal-footer">
        <button type="button" id="submitWithConflicts" class="btn btn-secondary">Salvesta sellegipoolest</button>
        <button type="button" class="btn btn-custom text-white " data-dismiss="modal">Muudan broneeringu</button>
      </div>
    </div>
  </div>
</div>                     

<div class="container">
    <div id="forms" class="container-md">
        <div id="nav-tabs" class="mt-5 pb-5 form-bg">
            <div class="row d-flex mb-5">
                <ul class="nav nav-tabs nav-justified col-12 bg-grey">
                    <li class="nav-item"><a  class="nav-link link h-100 txt-lg <?php if(!isset($data['type'])){ echo 'active';$data['type']=1;}else if($data['type']==1){echo 'active';}; ?>" href="#mitmekordne" data-toggle="tab">Ruumipäring ühekordseks tegevuseks</a></li>
                    <li class="nav-item"><a  class="nav-link link h-100 txt-lg <?php if($data['type']==2){echo 'active';}; ?>" href="#hooajaline" data-toggle="tab">Ruumipäring hooajaliseks tegevuseks</a></li>
                    <li class="nav-item"><a  class="nav-link link h-100 txt-lg <?php if($data['type']==5){echo 'active';}; ?>" href="#syndmus" data-toggle="tab">Ruumipäring sündmuseks</a></li>
                </ul>
            </div>


			
            <div class="tab-content ">
                <div id="mitmekordne" class="tab-pane center  <?php if(!isset($data['type'])){ echo 'active';}else if($data['type']==1){echo 'active';}; ?>">
                    <?php echo form_open('booking/createOnce', array('id' => 'myOnceForm')); ?>
		
                

                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Asukoha ja treeningu info</h4>
						
						<div class="row d-flex mt-4 px-md-5 mx-md-5">
						<?php print_r($bookingformdatadetails[0]['intro']); ?>
						</div>
                        <div class="row d-flex mt-4 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label for="sport_facilityOnce">Asutus</label>
                                <input id="sport_facilityOnce" class="form-control" list="asutus" id="building" value="<?php print_r($selectedBuilding['name']); ?>" disabled>
                            </div>
                           <?php echo $this->input->get('roomId');?>
                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label for="roomOnce">Ruum*</label><?php if($this->session->flashdata('sportroomMissing')){  echo $this->session->flashdata('sportroomMissing');} ?>
								<select id="roomOnce" onchange="addRoom('roomOnce','selectedRooms')" list="saal" class="form-control arrow" >
								
								<option >Vali ruum</option>
                                    <?php foreach ($rooms as $each) {
										echo $each->id;
									
											echo '<option value="' . $each->id . '">' . $each->roomName . '</option>';
									}
										   ?>
										   	<option >Kõik ruumid</option>
                                </select>
							<div id="selectedRooms">	Valitud ruumid:
								<?php 

								if(isset($data['sportrooms'])){ 
									foreach($data['sportrooms'] as $value){
										foreach($rooms as $room){
											if($value== $room->id){
												echo '<p class="removeRoom mt-1 btn btn-success  text-light" value="' . $room->id . '">' . $room->roomName . '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='.$room->id.'></input></p>';
											}
										}
									}
								}
	
								else {
									foreach($rooms as $room){
									
										if( $this->uri->segment(3)== $room->id){
											echo '<p class="removeRoom mt-1 btn btn-success  text-light" value="' . $room->id . '">' . $room->roomName . '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='.$room->id.'></input></p>';
										}
									} 
									 } ?>
										</div>

                            </div>
                        </div>
                        <div class="row d-flex mt-2 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label>Ruumi kasutamise eesmärk (avalik info) <?php if($bookingformdata['type_user']==1){echo "*";} ?> <?php if($this->session->flashdata('type_flash')){  echo $this->session->flashdata('type_flash');} ?></label>
                                <input class="form-control" id="typePeriod" name="workoutType" placeholder="iluvõimlemine, võrkpall, male, tantsutund vms" value="<?php if(isset($data['workoutType'])){ echo $data['workoutType'];}?>">
							</div>
							<?php if($bookingformdatadetails[0]['groupsee']==1){ ?>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label >Vali sihtrühm <?php if($bookingformdatadetails[0]['grouprequired']==1){echo "*";} ?></label>
                                <select  class="form-control arrow">
								<option >Vali sihtrühm</option>
								<option >Koolinoored (alla 20a)</option>
								<option >Täiskasvanud</option>
								<option >Seeniorid (alates 63a)</option>
								<option >Erivajadustega </option>
								<option >Segarühm</option>
								</select>
                            </div>  
							<?php } ?>
						</div>
						<div class="row d-flex mt-2 px-md-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								<?php if($bookingformdatadetails[0]['maxpeaplenumbersee']==1){ ?>
                                <label for="typeClosed">Maksimaalne osalejate arv <?php if($bookingformdatadetails[0]['maxpeaplenumberrequired']==1){echo "*";} ?></label>
                                <input type="number"  min="0" class="form-control" id="typeClosed" name="workoutType">
							</div>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<?php } ?>
								<?php if($bookingformdatadetails[0]['publicsee']==1){ ?>
                                <label for="typeClosed">Treening on:  <?php if($bookingformdatadetails[0]['publicrequired']==1){echo "*";} ?></label>
							    <select  class="form-control arrow">
								<option >Privaatne</option>
								<option >Avalik</option>
								</select>
								<?php } ?>
                            </div>
						</div> 
					
                       
					

                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Kuupäev ja kellaaeg</h4>
                        <div class="mt-4 bg-grey py-2">
                            <div class="form-label-group px-md-5 mx-md-5" id="timestamp">
							
                                <div id="InputsWrapper" class="mb-3 p-md-0">
                                    <div class="row d-flex align-items-center mb-3 justify-content-between">
										<div class="col-sm-4 col-9 m-0 p-md-0">
											<label class="col-md-5 m-0 p-md-0" for="datetime">Kuupäev</label>
											<input class="datePicker col-11 form-control" id="datefield_1" data-toggle="datepicker" name="workoutDate[]" value="<?php  if(isset($data['workoutDate'])){ echo $data['workoutDate'][0];} else if(isset($_GET["startDate"])){echo $_GET["startDate"];}?>">
										</div>
                                        <a href="#" class="removeclass mt-3 col-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a>

                                        <div class="col-4 col-sm-3">
											<div>	
												<label class="col-2 m-0 p-md-0" for="from1">Alates</label>
												<input type="input" class="clock pl-2 form-control" name="timesStart[]" data-minimum="08:00" data-maximum="22:00" id="timestartfield_1" value="<?php if(isset($data['timesStart'][0])){ echo $data['timesStart'][0];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>">
											</div>
										</div>
                                        <div class="col-4 col-sm-3">
										<div>	
											<label class="col-2 m-0 p-md-0" for="until1">Kuni</label>
                                            <input type="input" class="clock pl-2 form-control" name="timeTo[]" data-minimum="08:00" data-maximum="22:00" id="timeendfield_1" value="<?php if(isset($data['timeTo'][0])){ echo $data['timeTo'][0];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>">
										</div>
										</div>
										<div class="col-4 col-sm-3">
									
										</div>
                                    </div>
                                    
                                    <?php if(isset($data['workoutDate'])){ for ($i = 1; $i<count($data['workoutDate']); $i++) { ?>
									    <div class="row d-flex align-items-center mb-3 justify-content-between">
                                        <div class="col-sm-4 col-9 m-0 p-md-0">
										<input class="datePicker col-11 form-control" id="datefield_<?php echo $i;?>" data-toggle="datepicker" name="workoutDate[]" value="<?php echo $data['workoutDate'][$i];?>">
										</div>


										<a class="removeclass1 col-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a>
										<div class="col-4 col-sm-2">
											<input type="text" class="clock pl-2 form-control" name="timesStart[]" data-minimum="08:00" data-maximum="22:00" id="timestartfield_<?php echo $i;?>" value="<?php if(isset($data['timesStart'][$i])){ echo $data['timesStart'][$i];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>">
										</div>
										<div class="col-4 col-sm-2">
											<input type="text" class="clock pl-2 form-control" name="timeTo[]" data-minimum="08:00" data-maximum="22:00" id="timeendfield_<?php echo $i;?>" value="<?php if(isset($data['timeTo'][$i])){ echo $data['timeTo'][$i];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>">
										</div>
							
								    	</div>
									<?php ;}}; ?>
								

                                </div>
                                <div id="AddMoreFileId" class="row d-flex p-md-0">
                                    <a id="AddMoreFileBox" class="btn btn-custom text-white text-center py-2 col-4 pluss ml-3 ml-md-0"><p class="m-0 px-0 txt-lg txt-strong text-center align-items-center">Lisa veel üks kuupäev</p></a>
                                </div>
                            </div>
						</div>
						<!-- <div id="preparingTime">
                                    <div class="row d-flex align-items-center">
										<div class="mt-2 txt-xl pl-md-5 ml-md-5">
											<label>Treeningu ettevalmistamiseks kuluv aeg: </label> <span data-tooltip="Treeninguks ruumi ette valmistamine (varustuse ülespanek, mattide maha panek jne. Kui ettevalmistust ei toimu, siis jätke mõlemasse lahtrisse nullid)"><img id="tool" src="<?php echo base_url(); ?>assets/img/icon-info.svg" width="6%"></span>
										</div>

                                        <div class="ml-4 mr-1 mt-3 txt-xl">
											<div>	
												<input  type="number" min="0" max="48" value="0" class="form-control">
												 
											</div>
										</div>
										<div class="mt-2">
											<p>tund(i)</p>
										</div>
                                        <div class="ml-3 mr-1 mt-3 txt-xl">
											<input  type="number" min="0" max="60" value="0" class="form-control">
										</div>
										<div class="mt-2">
											<p>minutit</p>
										</div>
							</div>
						</div>
						<div id="preparingTime">
                                    <div class="row d-flex align-items-center">
										<div class="mt-2 txt-xl pl-md-5 ml-md-5">
											<label>Treeningujärgsele koristamisele kuluv aeg:</label>
										</div>

                                        <div class="ml-3 mr-1 mt-3 txt-xl">
											<div>	
												<input  type="number" min="0" max="48" value="0" class="form-control">
												 
											</div>
										</div>
										<div class="mt-2">
											<p>tund(i)</p>
										</div>
                                        <div class="ml-3 mr-1 mt-3 txt-xl">
										<div>	
                                            <input  type="number" min="0" max="60" value="0" class="form-control">
										</div>
									</div>
									<div class="mt-2">
												<p>minutit</p>
											</div>
							</div>
						</div>
						<div id="preparingTime">
                                    <div class="row d-flex align-items-center">
										<div class="col-5 mt-2 pl-md-5 ml-md-5 m-0">
										<p>Treeningu ettevalmistamise algusaeg <span data-tooltip="Treeninguks ruumi ette valmistamine (varustuse ülespanek, mattide maha panek jne)"><img id="tool" src="<?php echo base_url(); ?>assets/img/icon-info.svg" width="6%"></span></p>
										</div>

                                        <div class="col-3 mr-1 mt-3 txt-xl m-0">
											<input type="input" class="clock pl-2 col-5 pl-3 form-control" data-minimum="08:00" data-maximum="22:00">
										</div>
								                                   
							</div>
						</div>
						<div id="preparingTime">
                                    <div class="row d-flex align-items-center">
									<div class="col-5 mt-2 pl-md-5 ml-md-5 m-0">
											<label>Treeningujärgselt ruumi vabastatakse hiljemalt:</label>
										</div>

										<div class="col-3 mr-1 mt-3 txt-xl m-0">
											<input type="input" class="clock pl-2 col-5 pl-3 form-control" data-minimum="08:00" data-maximum="22:00">
										</div>
									</div>
								
							</div> -->
							<h4 class="pt-2 txt-xl px-md-5 mx-md-5 ml-3 mt-5">Kontakt</h4>
							<div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								
								<label class=" col-7 col-sm-7 col-md-12  p-0" for="contact">Klubi nimi (avalik info) <?php if($bookingformdata['clubname_user']==1){echo "*";} ?> <b data-tooltip="Klubi puudumisel kirjuta kontaktisiku nime initsiaalid"><img id="tool" class="mr-5" src="<?php echo base_url(); ?>assets/img/icon-info.svg" width="7%"></b> <?php if($this->session->flashdata('validationErrorMessageForClubname')){  echo $this->session->flashdata('validationErrorMessageForClubname');} ?></label>
								<input class="form-control <?php if($this->session->flashdata('validationErrorMessageForClubname')){ echo 'is-invalid';}?>" id="clubnameForSingle" type="text" name="clubname" required value="<?php if(isset($data['clubname'])): echo $data['clubname'];endif; ?>">
							</div>
                            <input class="d-none" type="checkbox" id="typeOnce" name="type" value="1" checked>
                            <input class="d-none" type="checkbox" id="allowFormToSubmitAndNeverMindConflicts1" name="allowSave" value="0" checked>
                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label>Kontaktisik <?php if($bookingformdata['contactname_user']==1){echo "*";} ?><?php if($this->session->flashdata('validationErrorMessageContactPerson')){  echo $this->session->flashdata('validationErrorMessageContactPerson');} ?> </label>
                                <input class="form-control" id="contactForSingle" name="contactPerson" value="<?php if(isset($data['contactPerson'])){ echo $data['contactPerson'];} else if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('userName');}; ?>">
                            </div>
                        </div>
                        <div class="row d-flex mt-2 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label>Telefon <?php if($bookingformdata['phone_user']==1){echo "*";} ?><?php if($this->session->flashdata('phoneIsNotCorrect')){  echo $this->session->flashdata('phoneIsNotCorrect');} ?><?php if($this->session->flashdata('validationErrorMessageForPhone')){  echo $this->session->flashdata('validationErrorMessageForPhone');} ?></label>
                                <input class="form-control" id="phoneForSingle" name="phone" value="<?php if(isset($data['phone'])){ echo $data['phone'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('phone');}; ?>">
                            </div>

                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label>Email <?php if($bookingformdata['email_user']==1){echo "*";} ?> <?php if($this->session->flashdata('emailIsNotCorrect')){  echo $this->session->flashdata('emailIsNotCorrect');} ?><?php if($this->session->flashdata('email_flash')){  echo $this->session->flashdata('email_flash');} ?></label>
                                <input class="form-control" id="emailForSingle" name="email" value="<?php if(isset($data['email'])){ echo $data['email'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('email');}; ?>">
                            </div>
						</div>
						<?php if($bookingformdatadetails[0]['methodofpaymentsee']==1){?>
						<h4 class="pt-2 txt-xl px-md-5 mt-4 mx-md-5 ml-3">Eelistatud maksmisviis <?php if($bookingformdatadetails[0]['methodofpaymentrequired']==1){echo "*";} ?></h4>
							<div class="row mt-3 pl-4 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
									<select  onchange="showBillingInfo(this, 'checkmeifpayingwithinvoice1','div2')" id="invoice1" class="form-control arrow">
									<!-- <option >Vali maksmisviis</option> -->
									<option value="" disabled selected>Vali maksmisviis</option>
									<?php if($bookingformdatadetails[0]['methodofpaymentcash']==1){?>
										<option >sularaha kohapeal tasudes</option>
									<?php } ?>
									<?php if($bookingformdatadetails[0]['methodofpaymentcard']==1){?>
										<option >pangakaart kohapeal tasudes</option>
									<?php } ?>
									<?php if($bookingformdatadetails[0]['methodofpaymentbill']==1){?>
										<option value="1">arvega</option>
									<?php } ?>
									<?php if($bookingformdatadetails[0]['methodofpaymentprepayment']==1){?>
										<option >ettemaks</option>
									<?php } ?>
									<?php if($bookingformdatadetails[0]['methodofpaymentother']==1){?>
										<option >muu</option>
									<?php } ?>
									</select>
								</div>
							</div>
						<?php } ?>

						<?php if($bookingformdatadetails[0]['agreementsee']==1){ ?>
							<?php if($bookingformdatadetails[0]['agreementrequired']==0){?>
						<label class="p-md-0 col-1 mt-4 pl-md-5 ml-md-5"><input type="checkbox" onchange="showContractInfo('div1', 'sameasagreement1','div1')"><span></span></label> Soovin sõlmida lepingu 
								<?php } ?>
						<div id="div1" <?php if($bookingformdatadetails[0]['agreementrequired']==0){echo "style='display:none'";} ?> >
							<h4 class="pt-2 txt-xl px-md-5 mt-4 mx-md-5 ml-3">Lepingu andmed <?php if($bookingformdatadetails[0]['agreementrequired']==1){echo "*";} ?></h4>
							<div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Ettevõtte/eraisiku nimi <?php if($bookingformdatadetails[0]['agreementnamerequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text1">
								</div>
								<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Registrikood/isikukood <?php if($bookingformdatadetails[0]['agreementcoderequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text3">
								</div>
							</div>
							<div class="row d-flex mt-2 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Aadress <?php if($bookingformdatadetails[0]['agreementaddressrequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text5">
								</div>
								<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Kontaktisik <?php if($bookingformdatadetails[0]['agreementcontactrequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text7" value="<?php if(isset($data['contactPerson'])){ echo $data['contactPerson'];} else if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('userName');}; ?>">
								</div>     
							</div>
							<div class="row d-flex mt-2 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Telefon <?php if($bookingformdatadetails[0]['agreementphonerequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text9" value="<?php if(isset($data['phone'])){ echo $data['phone'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('phone');}; ?>">
								</div>
								<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Email <?php if($bookingformdatadetails[0]['agreementemailrequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text11" value="<?php if(isset($data['email'])){ echo $data['email'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('email');}; ?>">
								</div>     
							</div>
						</div>

						<?php } ?>

										<br>
							
							<?php if($bookingformdatadetails[0]['invoicesee']==1){ ?>
								<?php if($bookingformdatadetails[0]['invoicerequired']==0){?>
						<label class="p-md-0 col-1 mt-4 pl-md-5 ml-md-5"><input type="checkbox" id="checkmeifpayingwithinvoice1"  onchange="showContractInfo('div2', 'sameasagreement1','div1')"><span></span></label> Soovin saada arvet
						<?php } ?>
						<div  <?php if($bookingformdatadetails[0]['invoicerequired']==0){echo "style='display:none'";} ?>  id='div2'>
							<h4 class="pt-2 txt-xl px-md-5 mt-4 mx-md-5 ml-3">Arve saaja andmed <?php if($bookingformdatadetails[0]['invoicerequired']==1){echo "*";} ?></h4>
							<?php if($bookingformdatadetails[0]['agreementsee']==1){ ?>
								<div id="sameasagreement1" <?php if($bookingformdatadetails[0]['agreementrequired']==0){ echo 'style="display:none"';}?> >
							<label class="p-md-0 mt-3 col-1 pl-md-5 ml-md-5"><input type="checkbox" id="checkbox1"><span></span></label> Arve saaja andmed ühtivad lepingu sõlmija andmetega</div>
							<?php } ?>
							<div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Ettevõtte/eraisiku nimi <?php if($bookingformdatadetails[0]['invoicenamerequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text2">
								</div>
								<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Registrikood/isikukood <?php if($bookingformdatadetails[0]['invoicecoderequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text4">
								</div>
							</div>
							<div class="row d-flex mt-2 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Aadress <?php if($bookingformdatadetails[0]['invoiceaddressrequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text6">
								</div>
								<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Kontaktisik <?php if($bookingformdatadetails[0]['invoicecontactrequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text8" value="<?php if(isset($data['contactPerson'])){ echo $data['contactPerson'];} else if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('userName');}; ?>">
								</div>     
							</div>
							<div class="row d-flex mt-2 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Telefon <?php if($bookingformdatadetails[0]['invoicephonerequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text10" value="<?php if(isset($data['phone'])){ echo $data['phone'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('phone');}; ?>">
								</div>
								<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Email <?php if($bookingformdatadetails[0]['invoiceemailrequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text12" value="<?php if(isset($data['email'])){ echo $data['email'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('email');}; ?>">
								</div>     
							</div>
						</div>

						<?php } ?>

                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Lisainfo </h4>
						<div class="mt-4 px-md-5 mx-md-5">
                            <div class="form-label-group pb-2 px-md-0 px-2">
                             
								<textarea class="form-control" id="additional" name="comment2" rows="3" placeholder="nt palun võrkpalli trenni jaoks eelnevalt üles seada võrk"><?php if(isset($data['comment2'])): echo $data['comment2'];endif; ?></textarea>
								
							</div>
						
						</div>
						<div>
							<!-- <label class="p-md-0 mt-5 col-1 pl-md-5 ml-md-5"><input type="checkbox"><span></span></label> Olen lugenud ja nõustun <u class=text-primary>kasutustingimustega*</u> -->
						</div>
                        <div class="row d-flex justify-content-end mt-5 px-md-5 mx-md-5">
							
							  <a class="txt-xl link-deco align-self-center py-md-0 pr-md-5 mr-2" href="<?php echo base_url()?>fullcalendar?roomId=<?php foreach ($rooms as $each) {
									
									if(!empty($data['sportrooms'])){ 
										if($data['sportrooms'][0]== $each->id){ 
										echo $each->id;
									
									}
								}
									   else 
										   if( $this->uri->segment(3)== $each->id){
												echo $each->id;
											}
										} ?>&date=<?php if(isset($data['workoutDate'])){ echo $data['workoutDate'][0];} else if(isset($_GET["startDate"])){echo $_GET["startDate"];}else{ echo date("d.m.yy");}?>" >Katkesta</a>
							
							
							<input class="btn btn-custom col-12 col-sm-3 text-white txt-xl" type="submit" disabled id="checkForOnceConflicts" value="Esita päring">
						
						<button id="loadingTemporarlyButtonOnce" class="d-none btn btn-custom text-white txt-xl" type="button" disabled>
							<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
							Kontrollin kattuvusi...
							</button>

                        </div>
						<input type="hidden" name="current_url" value="<?php echo current_url(); ?>" />
                    </form>
                </div>

                <div id="hooajaline" class="tab-pane center <?php if($data['type']==2){echo 'active';}; ?>">
                    <?php echo form_open('booking/createClosed', array('id' => 'myPeriodicForm')); ?>
			
                 


                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Asukoha ja treeningu info</h4>
						<div class="row d-flex mt-4 px-md-5 mx-md-5">
						<?php print_r($bookingformdatadetails[1]['intro']); ?>
						</div>
                        <div class="row d-flex mt-4 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label for="sport_facility">Asutus</label>
                                <input id="sport_facility" class="form-control" list="asutus" id="building" value="<?php print_r($selectedBuilding['name']); ?>" disabled>
                            </div>

                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label for="roomPeriod">Ruum*</label><?php if($this->session->flashdata('sportroomMissing')){  echo $this->session->flashdata('sportroomMissing');} ?>
								<select id="roomPeriod"  onchange="addRoom('roomPeriod','selectedPeriodRooms')" list="saal" class="form-control arrow" >
								<option >Vali ruum</option>
								<?php 
								foreach ($rooms as $each) {
										echo $each->id;
										
											    echo '<option value="' . $each->id . '">' . $each->roomName . '</option>';
									
											} ?>
									<option >Kõik ruumid</option>
								</select>
								<div id="selectedPeriodRooms">		Valitud ruumid:
								<?php 

								if(isset($data['sportrooms'])){ 
									foreach($data['sportrooms'] as $value){
										foreach($rooms as $room){
											if($value== $room->id){
												echo '<p class="removeRoom  mt-1 btn btn-success  text-light" value="' . $room->id . '">' . $room->roomName . '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='.$room->id.'></input></p>';
											}
										}
									}
								}
	
								else {
									foreach($rooms as $room){
									
										if( $this->uri->segment(3)== $room->id){
											echo '<p class="removeRoom mt-1 btn btn-success  text-light" value="' . $room->id . '">' . $room->roomName . '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='.$room->id.'></input></p>';
										}
									} 
									 } ?>
										</div>

                            </div>
                        </div>

                        <div class="row d-flex mt-2 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label for="typeClosed">Ruumi kasutamise eesmärk (avalik info) <?php if($bookingformdata['type_user']==1){echo "*";} ?><?php if($this->session->flashdata('type_flash')){  echo $this->session->flashdata('type_flash');} ?></label>
                                <input class="form-control" id="typeClosed" name="workoutType" placeholder="iluvõimlemine, võrkpall, male, tantsutund vms"  value="<?php if(isset($data['workoutType'])): echo $data['workoutType'];endif; ?>">
                            </div>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label >Vali sihtrühm <?php if($bookingformdatadetails[1]['grouprequired']==1){echo "*";} ?></label>
                                <select  class="form-control arrow">
								<option >Vali sihtrühm</option>
								<option >Koolinoored (alla 20a)</option>
								<option >Täiskasvanud</option>
								<option >Seeniorid (alates 63a)</option>
								<option >Erivajadustega</option>
								<option >Segarühm</option>
								</select>
                         
                                <input class="d-none" type="checkbox" name="type" value="2" checked>
                                <input class="d-none" type="checkbox" id="allowFormToSubmitAndNeverMindConflicts2" name="allowSave" value="0" checked>
                            </div>
						</div>  
						<div class="row d-flex mt-2 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label for="typeClosed">Maksimaalne osalejate arv <?php if($bookingformdatadetails[1]['maxpeaplenumberrequired']==1){echo "*";} ?></label>
                                <input type="number"  min="0" class="form-control" id="typeClosed" name="workoutType">
							</div>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label for="typeClosed">Treening on:  <?php if($bookingformdatadetails[1]['publicrequired']==1){echo "*";} ?></label>
							    <select  class="form-control arrow">
								<option >Privaatne</option>
								<option >Avalik</option>
								</select>
                            </div>
						</div> 
			                    
                           
                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Kuupäev ja kellaaeg</h4>
                        <div class="mt-4 bg-grey py-2">
                            <div class="form-label-group px-md-5 mx-md-5"  id="InputsWrapper1">
                              
                                <div id="dateContainer">
									<div class="row d-flex align-items-center mb-3 justify-content-between">
										<div class="col-sm-4 col-9 m-0 p-md-0">
											<label class="col-md-5 m-0 p-md-0" for="periodicWeekDay">Nädalapäev <?php if($this->session->flashdata('weekDayMissing')){  echo $this->session->flashdata('weekDayMissing');} ?></label>
										<input class="form-control col-11 arrow <?php if($this->session->flashdata('weekDayMissing')){ echo 'is-invalid'; }?>" id="periodicWeekDay" list="weekdays" name="weekday[]" required  value="<?php if(isset($data['weekday'])){ echo $data['weekday'][0];} else if (null!==($this->input->get('startDate'))){echo $weekdays[date('N', strtotime($this->input->get('startDate')))];} else {echo $weekdays[date('N', strtotime(date("d.m.Y")))]; ;}?>">
										
										
										<datalist id="weekdays">
											<option data-value="1" <?php if(isset($data['weekday'][0])&&$data['weekday'][0]=="Esmaspäev"): echo 'selected';endif; ?> value="Esmaspäev"></option>
											<option data-value="2"<?php if(isset($data['weekday'][0])&&$data['weekday'][0]=="Teisipäev"): echo 'selected';endif; ?> value="Teisipäev"></option>
											<option data-value="3"<?php if(isset($data['weekday'][0])&&$data['weekday'][0]=="Kolmapäev"): echo 'selected';endif; ?> value="Kolmapäev"></option>
											<option data-value="4"<?php if(isset($data['weekday'][0])&&$data['weekday'][0]=="Neljapäev"): echo 'selected';endif; ?> value="Neljapäev"></option>
											<option data-value="5"<?php if(isset($data['weekday'][0])&&$data['weekday'][0]=="Reede"): echo 'selected';endif; ?> value="Reede"></option>
											<option data-value="6"<?php if(isset($data['weekday'][0])&&$data['weekday'][0]=="Laupäev"): echo 'selected';endif; ?> value="Laupäev"></option>
											<option data-value="7"<?php if(isset($data['weekday'][0])&&$data['weekday'][0]=="Pühapäev"): echo 'selected';endif; ?> value="Pühapäev"></option>       
										</datalist>
										</div>
                                        <a href="#" class="removeclass col-3 mt-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a>
										
                                        <div class="col-4 col-sm-3">
											<label class="col-2 m-0 p-md-0" for="from1">Alates</label>
                                            <input type="text" class="clock pl-2 form-control" name="timesStart[]" data-minimum="08:00" data-maximum="22:00" id="from1" value="<?php if(isset($data['timesStart'][0])){ echo $data['timesStart'][0];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>">
                                        </div>
										
                                        <div class="col-4 col-sm-3">
											<label class="col-2 m-0 p-md-0" for="until1">Kuni</label>
                                            <input type="text" class="clock pl-2 form-control" name="timeTo[]" data-minimum="08:00" data-maximum="22:00" id="until1" value="<?php if(isset($data['timeTo'][0])){ echo $data['timeTo'][0];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>">
										</div>   
								                                 
                                    </div>
                             
                                <?php if(isset($data['weekday'])){ for ($i = 1; $i<count($data['weekday']); $i++) { ?>
                                    
									<div class="row d-flex align-items-center mb-3 justify-content-between">

                                      <div class="col-sm-4 col-9 m-0 p-md-0">  <input class="form-control 	<?php if($this->session->flashdata('weekDayMissing')){ echo 'is-invalid';}?>  col-11 arrow" id="periodicWeekDay<?php echo $i;?>" list="weekdays<?php echo $i;?>" name="weekday[]" value="<?php if($data['weekday'][$i]): echo $data['weekday'][$i];endif; ?>">
                                        <datalist id="weekdays<?php echo $i;?>">
                                            <option data-value="1" <?php if($data['weekday'][$i]=="Esmaspäev"): echo 'selected';endif; ?> value="Esmaspäev"></option>
                                            <option data-value="2" <?php if($data['weekday'][$i]=="Teisipäev"): echo 'selected';endif; ?> value="Teisipäev"></option>
                                            <option data-value="3" <?php if($data['weekday'][$i]=="Kolmapäev"): echo 'selected';endif; ?> value="Kolmapäev"></option>
                                            <option data-value="4" <?php if($data['weekday'][$i]=="Neljapäev"): echo 'selected';endif; ?> value="Neljapäev"></option>
                                            <option data-value="5" <?php if($data['weekday'][$i]=="Reede"): echo 'selected';endif; ?> value="Reede"></option>
                                            <option data-value="6" <?php if($data['weekday'][$i]=="Laupäev"): echo 'selected';endif; ?> value="Laupäev"></option>
                                            <option data-value="7" <?php if($data['weekday'][$i]=="Pühapäev"): echo 'selected';endif; ?> value="Pühapäev"></option>       
                                      </datalist>
                                      </div>
                                    <a href="#" class="removeclass2 col-3 mt-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a>
                                    <div class="col-4 col-sm-2">
                                        <input type="text" class="clock pl-2 form-control" name="timesStart[]" data-minimum="08:00" data-maximum="22:00" id="from<?php echo $i;?>" value="<?php if(isset($data['timesStart'][$i])){ echo $data['timesStart'][$i];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>">
                                    </div>
                                    <div class="col-4 col-sm-2">
                                        <input type="text" class="clock pl-2 form-control" name="timeTo[]" data-minimum="08:00" data-maximum="22:00" id="until<?php echo $i;?>" value="<?php if(isset($data['timeTo'][$i])){ echo $data['timeTo'][$i];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>">
									</div>
							
                                </div>
                                
										<?php 	} };?>
										</div>
                                <div id="AddMoreFileId1" class="flex ">
                                    <a id="AddMoreFileBoxPeriod" class="btn btn-custom text-white col-4 py-2 px-4 pluss ml-3 ml-md-0"><p class="m-0 px-0 txt-lg txt-strong text-center align-items-center">Lisa nädalapäev</p></a>
								</div>
								

                            </div>
                        </div>

                        <div class="row d-flex px-md-5 mx-md-5 mt-4">                        
                            <div class="form-label-group m-0 pl-md-0 pr-3 col-md-3 col-6">
                                <label>Periood </label>
                              
								<?php if($this->session->flashdata('validationErrorMessageforPeriod')){  
							 ?>
						  <input class="datePicker form-control is-invalid" id="periodStart" data-toggle="datepicker" name="startingFrom" value="<?php if(isset($data['startingFrom'])){echo $data['startingFrom'];} else if (null!==$this->input->get('startDate')){echo$this->input->get('startDate');} else {echo '';}?>">
							<?php 
						} 
							else {

								?> 
								  <input class="datePicker form-control" id="periodStart" data-toggle="datepicker" name="startingFrom" value="<?php if(isset($data['startingFrom'])){echo $data['startingFrom'];} else if (null!==$this->input->get('startDate')){echo$this->input->get('startDate');} else {echo '';}?>">
								<?php } ?>
						
							</div>
							
                 

                            <div class="form-label-group m-0 pl-md-0 col-md-3 col-6">  
								<label class="invisible">Periood</label> 
								<?php if($this->session->flashdata('validationErrorMessageforPeriod')){  
							 ?>
							<input class="datepickerUntil form-control is-invalid" id="periodEnd" data-toggle="datepickerUntil" name="Ending">
							<?php 
						} 
							else {

								?> <input class="datepickerUntil form-control " id="periodEnd" data-toggle="datepickerUntil" name="Ending">
								<?php } ?>
                                
							</div>
						</div>
						      
						<?php if($this->session->flashdata('validationErrorMessageforPeriod')){  
							echo  '<div class="row d-flex ml-3 px-md-5 mx-md-5">'.	$this->session->flashdata('validationErrorMessageforPeriod').'	</div>';} ?>
					
					<h4 class="pt-2 txt-xl px-md-5 mx-md-5 ml-3 mt-5">Kontakt</h4>	<?php echo form_error('clubname'); ?>
                        <div class="row d-flex px-md-5 mx-md-5 mt-4">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
							<?php echo form_error('clubname'); ?>
							<label class=" col-7 col-sm-7 col-md-12  p-0" for="contact">Klubi nimi (avalik info) <?php if($bookingformdata['clubname_user']==1){echo "*";} ?><b data-tooltip="Klubi puudumisel kirjuta kontaktisiku nime initsiaalid"><img id="tool" class="mr-5" src="<?php echo base_url(); ?>assets/img/icon-info.svg" width="7%"></b> <?php if($this->session->flashdata('validationErrorMessageForClubname')){  echo $this->session->flashdata('validationErrorMessageForClubname');} ?></label>
								<input class="form-control <?php if($this->session->flashdata('validationErrorMessageForClubname')){  echo 'is-invalid';} ?>" id="clubname" type="text" name="clubname" required value="<?php if(isset($data['clubname'])): echo $data['clubname'];endif; ?>">
								
                            </div>

                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label>Kontaktisik <?php if($bookingformdata['contactname_user']==1){echo "*";} ?> <?php if($this->session->flashdata('validationErrorMessageContactPerson')){  echo $this->session->flashdata('validationErrorMessageContactPerson');} ?></label>
                                <input class="form-control" id="contact" name="contactPerson" value="<?php if(isset($data['contactPerson'])){ echo $data['contactPerson'];} else if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('userName');}; ?>" required>
                            </div>
                        </div>
                        <div class="row d-flex mt-2 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label>Telefon <?php if($bookingformdata['phone_user']==1){echo "*";} ?> <?php if($this->session->flashdata('phoneIsNotCorrect')){  echo $this->session->flashdata('phoneIsNotCorrect');} ?><?php if($this->session->flashdata('validationErrorMessageForPhone')){  echo $this->session->flashdata('validationErrorMessageForPhone');} ?></label>
                                <input class="form-control" id="phone" name="phone" value="<?php if(isset($data['phone'])){ echo $data['phone'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('phone');}; ?>">
                            </div>

                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label>Email <?php if($bookingformdata['email_user']==1){echo "*";} ?> <?php if($this->session->flashdata('emailIsNotCorrect')){  echo $this->session->flashdata('emailIsNotCorrect');} ?><?php if($this->session->flashdata('email_flash')){  echo $this->session->flashdata('email_flash');} ?></label>
                                <input class="form-control" id="email" name="email" value="<?php if(isset($data['email'])){ echo $data['email'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('email');}; ?>">
                            </div>
                        </div>
						<?php if($bookingformdatadetails[1]['methodofpaymentsee']==1){?>
						<h4 class="pt-2 txt-xl px-md-5 mt-4 mx-md-5 ml-3">Eelistatud maksmisviis <?php if($bookingformdatadetails[1]['methodofpaymentrequired']==1){echo "*";} ?></h4>
							
							<div class="row mt-3 pl-4 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
									<select  onchange="showBillingInfo(this, 'checkmeifpayingwithinvoice2','div4')" id="invoice2" class="form-control arrow">
									<!-- <option >Vali maksmisviis</option> -->
									<option value="" disabled selected>Vali maksmisviis</option>
									<?php if($bookingformdatadetails[1]['methodofpaymentcash']==1){?>
										<option >sularaha kohapeal tasudes</option>
									<?php } ?>
									<?php if($bookingformdatadetails[1]['methodofpaymentcard']==1){?>
										<option >pangakaart kohapeal tasudes</option>
									<?php } ?>
									<?php if($bookingformdatadetails[1]['methodofpaymentbill']==1){?>
										<option value="1">arvega</option>
									<?php } ?>
									<?php if($bookingformdatadetails[1]['methodofpaymentprepayment']==1){?>
										<option >ettemaks</option>
									<?php } ?>
									<?php if($bookingformdatadetails[1]['methodofpaymentother']==1){?>
										<option >muu</option>
									<?php } ?>
									</select>
								</div>
							</div>
							<?php } ?>
							<?php if($bookingformdatadetails[1]['agreementrequired']==0){?>
						<label class="p-md-0 col-1 mt-4 pl-md-5 ml-md-5"><input type="checkbox"  onchange="showContractInfo('div3', 'sameasagreement2','div3')"><span></span></label> Soovin sõlmida lepingu 
						<?php } ?>
					<div id="div3" <?php if($bookingformdatadetails[1]['agreementrequired']==0){echo "style='display:none'";} ?>>
						<h4 class="pt-2 txt-xl px-md-5 mt-4 mx-md-5 ml-3">Lepingu andmed  <?php if($bookingformdatadetails[1]['agreementrequired']==1){echo "*";} ?></h4>
						<div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
							
								<label class=" col-7 col-sm-7 col-md-12  p-0" >Ettevõtte/eraisiku nimi <?php if($bookingformdatadetails[1]['agreementnamerequired']==1){echo "*";} ?></label>
								<input class="form-control" id="text13">
							</div>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label class=" col-7 col-sm-7 col-md-12  p-0" >Registrikood/isikukood <?php if($bookingformdatadetails[1]['agreementcoderequired']==1){echo "*";} ?></label>
								<input class="form-control" id="text15">
							</div>
						</div>
						<div class="row d-flex mt-2 px-md-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								<label class=" col-7 col-sm-7 col-md-12  p-0" >Aadress <?php if($bookingformdatadetails[1]['agreementaddressrequired']==1){echo "*";} ?></label>
								<input class="form-control" id="text17">
							</div>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label class=" col-7 col-sm-7 col-md-12  p-0" >Kontaktisik  <?php if($bookingformdatadetails[1]['agreementcontactrequired']==1){echo "*";} ?></label>
								<input class="form-control" id="text19" value="<?php if(isset($data['contactPerson'])){ echo $data['contactPerson'];} else if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('userName');}; ?>">
							</div>     
						</div>
						<div class="row d-flex mt-2 px-md-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								<label class=" col-7 col-sm-7 col-md-12  p-0" >Telefon <?php if($bookingformdatadetails[1]['agreementphonerequired']==1){echo "*";} ?></label>
								<input class="form-control" id="text21" value="<?php if(isset($data['phone'])){ echo $data['phone'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('phone');}; ?>">
							</div>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label class=" col-7 col-sm-7 col-md-12  p-0" >Email <?php if($bookingformdatadetails[1]['agreementemailrequired']==1){echo "*";} ?></label>
								<input class="form-control" id="text23" value="<?php if(isset($data['email'])){ echo $data['email'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('email');}; ?>">
							</div>     
						</div>
					</div>

									<br>
									<?php if($bookingformdatadetails[1]['invoicesee']==1){ ?>
									<?php if($bookingformdatadetails[1]['invoicerequired']==0){?>
					<label class="p-md-0 col-1 mt-4 pl-md-5 ml-md-5"><input type="checkbox"  id="checkmeifpayingwithinvoice2"  onchange="showContractInfo('div4', 'sameasagreement2','div3')"><span></span></label> Soovin saada arvet
					<?php } ?>
					<div <?php if($bookingformdatadetails[1]['invoicerequired']==0){echo "style='display:none'";} ?>  id='div4'>
						<h4 class="pt-2 txt-xl px-md-5 mt-4 mx-md-5 ml-3">Arve saaja andmed <?php if($bookingformdatadetails[1]['invoicerequired']==1){echo "*";} ?></h4>
						<div id="sameasagreement2"  <?php if($bookingformdatadetails[1]['agreementrequired']==0){ echo 'style="display:none"';}?>>
						<label class="p-md-0 mt-3 col-1 pl-md-5 ml-md-5"><input type="checkbox" id="checkbox2"><span></span></label> Arve saaja andmed ühtivad lepingu sõlmija andmetega	</div>

						<div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
							
								<label class=" col-7 col-sm-7 col-md-12  p-0" >Ettevõtte/eraisiku nimi <?php if($bookingformdatadetails[1]['invoicenamerequired']==1){echo "*";} ?></label>
								<input class="form-control" id="text14">
							</div>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label class=" col-7 col-sm-7 col-md-12  p-0" >Registrikood/isikukood <?php if($bookingformdatadetails[1]['invoicecoderequired']==1){echo "*";} ?></label>
								<input class="form-control" id="text16">
							</div>
						</div>
						<div class="row d-flex mt-2 px-md-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								<label class=" col-7 col-sm-7 col-md-12  p-0" >Aadress <?php if($bookingformdatadetails[1]['invoiceaddressrequired']==1){echo "*";} ?></label>
								<input class="form-control" id="text18">
							</div>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label class=" col-7 col-sm-7 col-md-12  p-0" >Kontaktisik <?php if($bookingformdatadetails[1]['invoicecontactrequired']==1){echo "*";} ?></label>
								<input class="form-control" id="text20" value="<?php if(isset($data['contactPerson'])){ echo $data['contactPerson'];} else if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('userName');}; ?>">
							</div>     
						</div>
						<div class="row d-flex mt-2 px-md-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								<label class=" col-7 col-sm-7 col-md-12  p-0" >Telefon <?php if($bookingformdatadetails[1]['invoicephonerequired']==1){echo "*";} ?></label>
								<input class="form-control" id="text22" value="<?php if(isset($data['phone'])){ echo $data['phone'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('phone');}; ?>">
							</div>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label class=" col-7 col-sm-7 col-md-12  p-0" >Email <?php if($bookingformdatadetails[1]['invoiceemailrequired']==1){echo "*";} ?></label>
								<input class="form-control" id="text24"  value="<?php if(isset($data['email'])){ echo $data['email'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('email');}; ?>">
							</div>     
						</div>
					</div>
					<?php } ?>
                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Lisainfo </h4>
                        <div class="mt-4 px-md-5 mx-md-5">
                            <div class="form-label-group pb-2 px-md-0 px-2">
                           
                                <textarea class="form-control" id="comment2ForSingle" name="comment2" rows="3" placeholder="nt palun võrkpalli trenni jaoks eelnevalt üles seada võrk"><?php if(isset($data['comment2'])): echo $data['comment2'];endif; ?></textarea>
							</div>
						
						</div>
						<div>
							<!-- <label class="p-md-0 mt-5 col-1 pl-md-5 ml-md-5"><input type="checkbox"><span></span></label> Olen lugenud ja nõustun <u class=text-primary>kasutustingimustega*</u> -->
						</div>

                        <div class="row d-flex justify-content-end mt-5 px-md-5 mx-md-5">
						<a class="txt-xl link-deco align-self-center py-md-0 pr-md-5 mr-2" href="<?php echo base_url()?>fullcalendar?roomId=<?php foreach ($rooms as $each) {
									
									if(!empty($data['sportrooms'])){ 
										if($data['sportrooms'][0]== $each->id){ 
										echo $each->id;
									
									}
								}
									   else 
										   if( $this->uri->segment(3)== $each->id){
												echo $each->id;
											}
										} ?>&date=<?php if(isset($data['workoutDate'])){ echo $data['workoutDate'][0];} else if(isset($_GET["startDate"])){echo $_GET["startDate"];}else{ echo date("d.m.yy");}?>" >Katkesta</a>
                            <input class="btn btn-custom col-12 col-sm-3 text-white txt-xl" type="button" disabled id="checkForConflicts" value="Esita päring">
							
							<button id="loadingTemporarlyButton" class="d-none btn btn-custom text-white txt-xl" type="button" disabled>
							<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
							Kontrollin kattuvusi...
							</button>

                        </div>
						<input type="hidden" name="current_url" value="<?php echo current_url(); ?>" />
                    </form>
                </div>

				<div id="syndmus" class="tab-pane center  <?php if(!isset($data['type'])){ echo 'active';}else if($data['type']==5){echo 'active';}; ?>">
                    <?php echo form_open('booking/createOnce', array('id' => 'myOnceForm')); ?>
		
                

						<h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Asukoha ja sündmuse info</h4>
						<div class="row d-flex mt-4 px-md-5 mx-md-5">
						<?php print_r($bookingformdatadetails[2]['intro']); ?>
						</div>
                        <div class="row d-flex mt-4 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label for="sport_facilityOnce">Asutus</label>
                                <input id="sport_facilityOnce" class="form-control" list="asutus" id="building" value="<?php print_r($selectedBuilding['name']); ?>" disabled>
                            </div>
                           <?php echo $this->input->get('roomId');?>
                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label for="roomEvent">Ruum*</label><?php if($this->session->flashdata('sportroomMissing')){  echo $this->session->flashdata('sportroomMissing');} ?>
								<select id="roomEvent"  onchange="addRoom('roomEvent','selectedEventRooms')" list="saal" class="form-control arrow" >
								
								<option >Vali ruum</option>
                                    <?php foreach ($rooms as $each) {
										echo $each->id;
									
											echo '<option value="' . $each->id . '">' . $each->roomName . '</option>';
									}
										   ?>
										   	<option >Kõik ruumid</option>
                                </select>
							<div id="selectedEventRooms">	Valitud ruumid:	
								<?php 

								if(isset($data['sportrooms'])){ 
									foreach($data['sportrooms'] as $value){
										foreach($rooms as $room){
											if($value== $room->id){
												echo '<p class="removeRoom mt-1 btn btn-success  text-light" value="' . $room->id . '">' . $room->roomName . '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='.$room->id.'></input></p>';
											}
										}
									}
								}
	
								else {
									foreach($rooms as $room){
									
										if( $this->uri->segment(3)== $room->id){
											echo '<p class="removeRoom mt-1 btn btn-success  text-light" value="' . $room->id . '">' . $room->roomName . '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='.$room->id.'></input></p>';
										}
									} 
									 } ?>
										</div>

                            </div>
                        </div>
                        <div class="row d-flex mt-2 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label>Ruumi kasutamise eesmärk (avalik info) <?php if($bookingformdata['type_user']==1){echo "*";} ?> <?php if($this->session->flashdata('type_flash')){  echo $this->session->flashdata('type_flash');} ?></label>
                                <input class="form-control" id="typePeriod" name="workoutType" placeholder="nt Korvpalli võistlus" value="<?php if(isset($data['workoutType'])){ echo $data['workoutType'];}?>">
                            </div>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label >Vali sihtrühm <?php if($bookingformdatadetails[2]['grouprequired']==1){echo "*";} ?></label>
                                <select  class="form-control arrow">
								<option >Vali sihtrühm</option>
								<option >Koolinoored (alla 20a)</option>
								<option >Täiskasvanud</option>
								<option >Seeniorid (alates 63a)</option>
								<option >Erivajadustega </option>
								<option >Erinevad</option>
								</select>
                            </div>  
							
						</div>
						<div class="row d-flex mt-2 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label for="typeClosed">Maksimaalne osalejate arv <?php if($bookingformdatadetails[2]['maxpeaplenumberrequired']==1){echo "*";} ?></label>
                                <input type="number"  min="0" class="form-control" id="typeClosed" name="workoutType">
							</div>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label for="typeClosed">Sündmus on:  <?php if($bookingformdatadetails[2]['publicrequired']==1){echo "*";} ?></label>
							    <select  class="form-control arrow">
								<option >Avalik</option>
								<option >Privaatne</option>
								</select>
                            </div>
						</div> 
					
                       
					

                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Sündmuse kuupäev ja kellaaeg</h4>
                        <div class="mt-4 bg-grey py-2">
                            <div class="form-label-group px-md-5 mx-md-5" id="InputsWrapper2">
							
                                <div id="eventContainer" class="mb-3 p-md-0">
                                    <div class="row d-flex align-items-center mb-3 justify-content-between">
										<div class="col-sm-4 col-9 m-0 p-md-0">
											<label class="col-md-5 m-0 p-md-0" for="datetime">Kuupäev</label>
											<input class="datePicker col-11 form-control" id="datefield_1" data-toggle="datepicker" name="workoutDate[]" value="<?php  if(isset($data['workoutDate'])){ echo $data['workoutDate'][0];} else if(isset($_GET["startDate"])){echo $_GET["startDate"];}?>">
										</div>
                                        <a href="#" class="removeclass mt-3 col-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a>

                                        <div class="col-4 col-sm-3">
											<div>	
												<label class="col-2 m-0 p-md-0" for="from1">Alates</label>
												<input type="input" class="clock pl-2 form-control" name="timesStart[]" data-minimum="08:00" data-maximum="22:00" id="timestartfield_1" value="<?php if(isset($data['timesStart'][0])){ echo $data['timesStart'][0];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>">
											</div>
										</div>
                                        <div class="col-4 col-sm-3">
										<div>	
											<label class="col-2 m-0 p-md-0" for="until1">Kuni</label>
                                            <input type="input" class="clock pl-2 form-control" name="timeTo[]" data-minimum="08:00" data-maximum="22:00" id="timeendfield_1" value="<?php if(isset($data['timeTo'][0])){ echo $data['timeTo'][0];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>">
										</div>
										</div>
										<div class="col-4 col-sm-3">
									
										</div>
                                    </div>
                                    
                                    <?php if(isset($data['workoutDate'])){ for ($i = 1; $i<count($data['workoutDate']); $i++) { ?>
									    <div class="row d-flex align-items-center mb-3 justify-content-between">
                                        <div class="col-sm-4 col-9 m-0 p-md-0">
										<input class="datePicker col-11 form-control" id="datefield_<?php echo $i;?>" data-toggle="datepicker" name="workoutDate[]" value="<?php echo $data['workoutDate'][$i];?>">
										</div>


										<a class="removeclass3 col-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a>
										<div class="col-4 col-sm-2">
											<input type="text" class="clock pl-2 form-control" name="timesStart[]" data-minimum="08:00" data-maximum="22:00" id="timestartfield_<?php echo $i;?>" value="<?php if(isset($data['timesStart'][$i])){ echo $data['timesStart'][$i];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>">
										</div>
										<div class="col-4 col-sm-2">
											<input type="text" class="clock pl-2 form-control" name="timeTo[]" data-minimum="08:00" data-maximum="22:00" id="timeendfield_<?php echo $i;?>" value="<?php if(isset($data['timeTo'][$i])){ echo $data['timeTo'][$i];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>">
										</div>
							
								    	</div>
									<?php ;}}; ?>
								

                                </div>
                                <div id="AddMoreFileId" class="row d-flex p-md-0">
                                    <a id="AddMoreFileBoxEvent" class="btn btn-custom text-white text-center py-2 col-4 pluss ml-3 ml-md-0"><p class="m-0 px-0 txt-lg txt-strong text-center align-items-center">Lisa veel üks kuupäev</p></a>
                                </div>
                            </div>
						</div>
						<div id="preparingTime">
                                    <div class="row d-flex align-items-center">
										<div class="mt-2 txt-xl pl-md-5 ml-md-5">
											<label>Sündmuse ettevalmistamiseks kuluv aeg: </label> <span data-tooltip="Sündmuseks ruumi ette valmistamine (varustuse ülespanek, vaatajakohtade sättimine vms. Kui ettevalmistust ei toimu, siis jätke mõlemasse lahtrisse nullid)"><img id="tool" src="<?php echo base_url(); ?>assets/img/icon-info.svg" width="6%"></span>
										</div>

                                        <div class="ml-4 mr-1 mt-3 txt-xl">
											<div>	
												<input  type="number" min="0" max="48" value="0" class="form-control">
												 
											</div>
										</div>
										<div class="mt-2">
											<p>tund(i)</p>
										</div>
                                        <div class="ml-3 mr-1 mt-3 txt-xl">
											<input  type="number" min="0" max="60" value="0" class="form-control">
										</div>
										<div class="mt-2">
											<p>minutit</p>
										</div>
							</div>
						</div>
						<div id="preparingTime">
                                    <div class="row d-flex align-items-center">
										<div class="mt-2 txt-xl pl-md-5 ml-md-5">
											<label>Sündmusejärgse koristamisele kuluv aeg:</label>
										</div>

                                        <div class="ml-3 mr-1 mt-3 txt-xl">
											<div>	
												<input  type="number" min="0" max="48" value="0" class="form-control">
												 
											</div>
										</div>
										<div class="mt-2">
											<p>tund(i)</p>
										</div>
                                        <div class="ml-3 mr-1 mt-3 txt-xl">
										<div>	
                                            <input  type="number" min="0" max="60" value="0" class="form-control">
										</div>
									</div>
									<div class="mt-2">
												<p>minutit</p>
											</div>
							</div>
						</div>
						<!-- <div id="preparingTime">
                                    <div class="row d-flex align-items-center">
										<div class="col-5 mt-2 pl-md-5 ml-md-5 m-0">
										<p>Sündmuse ettevalmistamise algusaeg <span data-tooltip="Treeninguks ruumi ette valmistamine (varustuse ülespanek, mattide maha panek jne)"><img id="tool" src="<?php echo base_url(); ?>assets/img/icon-info.svg" width="6%"></span></p>
										</div>

                                        <div class="col-3 mr-1 mt-3 txt-xl m-0">
											<input type="input" class="clock pl-2 col-5 pl-3 form-control" data-minimum="08:00" data-maximum="22:00">
										</div>
								                                   
							</div>
						</div> -->
						<!-- <div id="preparingTime">
                                    <div class="row d-flex align-items-center">
									<div class="col-5 mt-2 pl-md-5 ml-md-5 m-0">
											<label>Sündmusejärgse koristamise lõppaeg:</label>
										</div>

										<div class="col-3 mr-1 mt-3 txt-xl m-0">
											<input type="input" class="clock pl-2 col-5 pl-3 form-control" data-minimum="08:00" data-maximum="22:00">
										</div>
									</div>
								
							</div> -->

						<h4 class="pt-2 txt-xl px-md-5 mx-md-5 ml-3 mt-5">Kontakt</h4>
                        <div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
							
								<label class=" col-7 col-sm-7 col-md-12  p-0" for="contact">Korraldaja (avalik info) <?php if($bookingformdata['clubname_user']==1){echo "*";} ?><b data-tooltip="Klubi puudumisel kirjuta kontaktisiku nime initsiaalid"><img id="tool" class="mr-5" src="<?php echo base_url(); ?>assets/img/icon-info.svg" width="7%"></b> <?php if($this->session->flashdata('validationErrorMessageForClubname')){  echo $this->session->flashdata('validationErrorMessageForClubname');} ?></label>
								<input class="form-control <?php if($this->session->flashdata('validationErrorMessageForClubname')){ echo 'is-invalid';}?>" id="clubnameForSingle" type="text" name="clubname" required value="<?php if(isset($data['clubname'])): echo $data['clubname'];endif; ?>">
							</div>
                            <input class="d-none" type="checkbox" id="typeOnce" name="type" value="1" checked>
                            <input class="d-none" type="checkbox" id="allowFormToSubmitAndNeverMindConflicts1" name="allowSave" value="0" checked>
                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label>Kontaktisik	<?php if($bookingformdata['contactname_user']==1){echo "*";} ?> <?php if($this->session->flashdata('validationErrorMessageContactPerson')){  echo $this->session->flashdata('validationErrorMessageContactPerson');} ?> </label>
                                <input class="form-control" id="contactForSingle" name="contactPerson" value="<?php if(isset($data['contactPerson'])){ echo $data['contactPerson'];} else if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('userName');}; ?>">
                            </div>
                        </div>
                        <div class="row d-flex mt-2 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label>Telefon <?php if($bookingformdata['phone_user']==1){echo "*";} ?> <?php if($this->session->flashdata('phoneIsNotCorrect')){  echo $this->session->flashdata('phoneIsNotCorrect');} ?><?php if($this->session->flashdata('validationErrorMessageForPhone')){  echo $this->session->flashdata('validationErrorMessageForPhone');} ?></label>
                                <input class="form-control" id="phoneForSingle" name="phone" value="<?php if(isset($data['phone'])){ echo $data['phone'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('phone');}; ?>">
                            </div>

                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label>Email <?php if($bookingformdata['email_user']==1){echo "*";} ?> <?php if($this->session->flashdata('emailIsNotCorrect')){  echo $this->session->flashdata('emailIsNotCorrect');} ?><?php if($this->session->flashdata('email_flash')){  echo $this->session->flashdata('email_flash');} ?></label>
                                <input class="form-control" id="emailForSingle" name="email" value="<?php if(isset($data['email'])){ echo $data['email'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('email');}; ?>">
                            </div>
						</div>
						
						<?php if($bookingformdatadetails[2]['methodofpaymentsee']==1){?>
						<h4 class="pt-2 txt-xl px-md-5 mt-4 mx-md-5 ml-3">Eelistatud maksmisviis <?php if($bookingformdatadetails[2]['methodofpaymentrequired']==1){echo "*";} ?></h4>
							<div class="row mt-3 pl-4 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
									<select  onchange="showBillingInfo(this, 'checkmeifpayingwithinvoice3','div6')" id="invoice3" class="form-control arrow">
									<option value="" disabled selected>Vali maksmisviis</option>
									<?php if($bookingformdatadetails[2]['methodofpaymentcash']==1){?>
										<option >sularaha kohapeal tasudes</option>
									<?php } ?>
									<?php if($bookingformdatadetails[2]['methodofpaymentcard']==1){?>
										<option >pangakaart kohapeal tasudes</option>
									<?php } ?>
									<?php if($bookingformdatadetails[2]['methodofpaymentbill']==1){?>
										<option value="1">arvega</option>
									<?php } ?>
									<?php if($bookingformdatadetails[2]['methodofpaymentprepayment']==1){?>
										<option >ettemaks</option>
									<?php } ?>
									<?php if($bookingformdatadetails[2]['methodofpaymentother']==1){?>
										<option >muu</option>
									<?php } ?>
									</select>
								</div>
							</div>
						<?php } ?>
							<?php if($bookingformdatadetails[2]['agreementrequired']==0){?>
						<label class="p-md-0 col-1 mt-4 pl-md-5 ml-md-5"><input type="checkbox" onchange="showContractInfo('div5', 'sameasagreement3','div5')"><span></span></label> Soovin sõlmida lepingu 
						<?php } ?>
						<div id="div5"<?php if($bookingformdatadetails[2]['agreementrequired']==0){echo "style='display:none'";} ?>>
							<h4 class="pt-2 txt-xl px-md-5 mt-4 mx-md-5 ml-3">Lepingu andmed  <?php if($bookingformdatadetails[2]['agreementrequired']==1){echo "*";} ?></h4>
							<div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Ettevõtte/eraisiku nimi <?php if($bookingformdatadetails[2]['agreementnamerequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text25">
								</div>
								<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Registrikood/isikukood <?php if($bookingformdatadetails[2]['agreementcoderequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text27">
								</div>
							</div>
							<div class="row d-flex mt-2 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Aadress <?php if($bookingformdatadetails[2]['agreementaddressrequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text29">
								</div>
								<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Kontaktisik  <?php if($bookingformdatadetails[2]['agreementcontactrequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text31" value="<?php if(isset($data['contactPerson'])){ echo $data['contactPerson'];} else if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('userName');}; ?>">
								</div>     
							</div>
							<div class="row d-flex mt-2 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Telefon <?php if($bookingformdatadetails[2]['agreementphonerequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text33" value="<?php if(isset($data['phone'])){ echo $data['phone'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('phone');}; ?>">
								</div>
								<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Email <?php if($bookingformdatadetails[2]['agreementemailrequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text35" value="<?php if(isset($data['email'])){ echo $data['email'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('email');}; ?>">
								</div>     
							</div>
						</div>

										<br>
										<?php if($bookingformdatadetails[2]['invoicesee']==1){ ?>
										<?php if($bookingformdatadetails[2]['invoicerequired']==0){?>
						<label class="p-md-0 col-1 mt-4 pl-md-5 ml-md-5"><input type="checkbox"  id="checkmeifpayingwithinvoice3"  onchange="showContractInfo('div6', 'sameasagreement3','div5')"><span></span></label> Soovin saada arvet
						<?php } ?>
						<div <?php if($bookingformdatadetails[2]['invoicerequired']==0){echo "style='display:none'";} ?>  id='div6'>
							<h4 class="pt-2 txt-xl px-md-5 mt-4 mx-md-5 ml-3">Arve saaja andmed <?php if($bookingformdatadetails[2]['invoicerequired']==1){echo "*";} ?></h4>
							<div id="sameasagreement3"  <?php if($bookingformdatadetails[2]['agreementrequired']==0){ echo 'style="display:none"';}?>>
							<label class="p-md-0 mt-3 col-1 pl-md-5 ml-md-5"><input type="checkbox" id="checkbox3"><span></span></label> Arve saaja andmed ühtivad lepingu sõlmija andmetega</div>

							<div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Ettevõtte/eraisiku nimi <?php if($bookingformdatadetails[2]['invoicenamerequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text26">
								</div>
								<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Registrikood/isikukood <?php if($bookingformdatadetails[2]['invoicecoderequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text28">
								</div>
							</div>
							<div class="row d-flex mt-2 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Aadress <?php if($bookingformdatadetails[2]['invoiceaddressrequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text30">
								</div>
								<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Kontaktisik <?php if($bookingformdatadetails[2]['invoicecontactrequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text32" value="<?php if(isset($data['contactPerson'])){ echo $data['contactPerson'];} else if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('userName');}; ?>">
								</div>     
							</div>
							<div class="row d-flex mt-2 px-md-5 mx-md-5">
								<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Telefon <?php if($bookingformdatadetails[2]['invoicephonerequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text34" value="<?php if(isset($data['phone'])){ echo $data['phone'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('phone');}; ?>">
								</div>
								<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
									<label class=" col-7 col-sm-7 col-md-12  p-0" >Email <?php if($bookingformdatadetails[2]['invoiceemailrequired']==1){echo "*";} ?></label>
									<input class="form-control" id="text36"  value="<?php if(isset($data['email'])){ echo $data['email'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('email');}; ?>">
								</div>     
							</div>
						</div>
						<?php } ?>
                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Lisainfo </h4>
						<div class="mt-4 px-md-5 mx-md-5">
                            <div class="form-label-group pb-2 px-md-0 px-2">
                               
								<textarea class="form-control" id="additional" name="comment2" rows="3" placeholder=""><?php if(isset($data['comment2'])): echo $data['comment2'];endif; ?></textarea>
								
							</div>
						
						</div>
						<div>
							<!-- <label class="p-md-0 mt-5 col-1 pl-md-5 ml-md-5"><input type="checkbox"><span></span></label> Olen lugenud ja nõustun <u class=text-primary>kasutustingimustega*</u> -->
						</div>
                        <div class="row d-flex justify-content-end mt-5 px-md-5 mx-md-5">
							
							  <a class="txt-xl link-deco align-self-center py-md-0 pr-md-5 mr-2" href="<?php echo base_url()?>fullcalendar?roomId=<?php foreach ($rooms as $each) {
									
									if(!empty($data['sportrooms'])){ 
										if($data['sportrooms'][0]== $each->id){ 
										echo $each->id;
									
									}
								}
									   else 
										   if( $this->uri->segment(3)== $each->id){
												echo $each->id;
											}
										} ?>&date=<?php if(isset($data['workoutDate'])){ echo $data['workoutDate'][0];} else if(isset($_GET["startDate"])){echo $_GET["startDate"];}else{ echo date("d.m.yy");}?>" >Katkesta</a>
							
							
							<input class="btn btn-custom col-12 col-sm-3 text-white txt-xl" type="submit" disabled  id="checkForOnceConflicts" value="Esita päring">
						
						<button id="loadingTemporarlyButtonOnce" class="d-none btn btn-custom text-white txt-xl" type="button" disabled>
							<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
							Kontrollin kattuvusi...
							</button>

                        </div>
						<input type="hidden" name="current_url" value="<?php echo current_url(); ?>" />
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<br/>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datepicker.js"></script>
<script>


    $(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();  
		$('#myModal').on('hidden.bs.modal', function (e) {
			$('#approvePeriodNow').prop('checked','<?php if(isset($data['approveNow'])){ if($data['approveNow']==1) echo  'checked'; } ?>');
			$('#approveNow').prop('checked', '<?php if(isset($data['approveNow'])){ if($data['approveNow']==1) echo  'checked'; } ?>');
			$("#myTable").find("tr:gt(0)").remove();
		});
		var days=['Pühapäev', 'Esmaspäev', 'Teisipäev', 'Kolmapäev', 'Neljapäev', 'Reede', 'Laupäev'];
        var today=new Date();
		var endOfPeriond=new Date('05/31/'+ new Date().getFullYear()); 
		var dateFormat = moment('<?php echo ($this->input->get('startDate')); ?>', "DD-MM-YYYY");
		var selectedWeekDay = new Date(dateFormat);
		
	//	console.log(days[selectedWeekDay.getDay()]);
	// 	if(days.includes(days[selectedWeekDay.getDay()])){
	// 	var list = $("#periodicWeekDay.form-control.col-5.arrow");
    //     var list2 =$("input:contains('closedWeekDay'):last");
		
	// 		for (var i = 0; i < list.length; i++) {
	// 		list[i].setAttribute("value", days[selectedWeekDay.getDay()]);
    //         list2[i].setAttribute("value", days[selectedWeekDay.getDay()]);

	// 		}
	// };
        var checkingDate = '<?php if(isset($data['Ending'])){echo $data['Ending'];}else {echo '';}?>';
    //    console.log("kuupäev on "+checkingDate);
        var dateToShow='';
		if('<?php if(isset($data['Ending'])){echo $data['Ending'];}else {echo '';}?>'){
			window.history.replaceState("", "",'<?php echo base_url(); ?>booking/create/<?php echo $this->uri->segment(3);// . "?startDate=".$data['startingFrom'] .'&start='.$data['timesStart'][0].'&end='.$data['timeTo'][0]; ?>');
			
		}
	
        if (checkingDate){
            dateToShow=moment(checkingDate, "DD-MM-YYYY");
			
        }
        else if (today<endOfPeriond){
            dateToShow=endOfPeriond;

        }else{
            dateToShow=new Date(endOfPeriond.setFullYear(endOfPeriond.getFullYear() + 1));  ;
        };
       

        $(".datepickerUntil").datepicker({
            language: "et-EE",
            autoHide: true,
            date: dateToShow,
            autoPick: true,
		});
		$(".datepickerClosedUntil").datepicker({
            language: "et-EE",
            autoHide: true,
			date: '<?php if (isset($data['Ending'])){echo $data['Ending'];} else echo $this->input->get('startDate') ? $this->input->get('startDate'):"new Date()";?>',
            autoPick: true,
        });

        $(".datePicker").datepicker({
            language: "et-EE",
            autoHide: true,
            autoPick: true,
        });

        $('.clock').clockTimePicker({
            duration: true,
            durationNegative: true,
            precision: 5,
            i18n: {
                cancelButton: 'Tühista'
            },
            onAdjust: function(newVal, oldVal) {
                //...
            }
        });

        var MaxInputs = 100; //maximum extra input boxes allowed
        var InputsWrapper = $("#InputsWrapper"); //Input boxes wrapper ID
        var AddButton = $("#AddMoreFileBox"); //Add button ID

        var x = InputsWrapper.children().length; //initlal text box count
        var FieldCount = 1; //to keep track of text box added

        //on add input button click
        $(AddButton).click(function(e) {
            //max input box allowed
            if (x <= MaxInputs) {
                FieldCount++; //text box added ncrement
                //add input box
                $('#InputsWrapper').append('<div class="row d-flex align-items-center mb-3 justify-content-between"><div class="col-sm-4 col-9 m-0 p-md-0"><input class="datePicker col-11 form-control" id="datefield_' + FieldCount + '" data-toggle="datepicker" name="workoutDate[]"></div><a class="removeclass1 mt-3 col-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a><div class="col-4 col-sm-3"><input type="text" class="clock pl-2 form-control" name="timesStart[]" data-minimum="08:00" data-maximum="22:00" id="timestartfield_' + FieldCount + '" value="<?php if(isset($data['timesStart'][0])){ echo $data['timesStart'][0];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>"></div><div class="col-4 col-sm-3"><input type="text" class="clock pl-2 form-control" name="timeTo[]" data-minimum="08:00" data-maximum="22:00" id="timeendfield_' + FieldCount + '" value="<?php if(isset($data['timeTo'][0])){ echo $data['timeTo'][0];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>"></div>	</div>');

                $(".datePicker").datepicker({
                    language: "et-EE", 
                    autoHide: true, 
                    autoPick: true
                });

                $(".datepickerUntil").datepicker({
                    language: "et-EE",
                    autoHide: true,
                    date: dateToShow,
                    autoPick: true,
                });

                $('.clock').clockTimePicker({
                    duration: true,
                    durationNegative: true,
                    precision: 5,
                    i18n: {
                        cancelButton: 'Võta tagasi'
                    },
                    onAdjust: function(newVal, oldVal) {
                        //...
                    }
                });

                x++; //text box increment

                $("#AddMoreFileId").show();
                // $('AddMoreFileBox').html("Add field");

                // Delete the "add"-link if there is 3 fields.
                if (x == MaxInputs) {
                    $("#AddMoreFileId").hide();
                    $("#lineBreak").html("<br>");
                }
            }
            return false;
        });

        $("#timestamp").on("click", ".removeclass1", function(e) { //user click on remove text
     
            if (x > 1) {
                $(this).parent('div').remove(); //remove text box
                x--; //decrement textbox
                $("#AddMoreFileId").show();
               // console.log(x);
                return x;
            }
            return false;
        });
        
        var maxPeriod = 100;
        var InputsWrapper1 = $("#InputsWrapper1 #dateContainer"); //Input boxes wrapper ID
        var AddButton1 = $("#AddMoreFileBoxPeriod"); //Add button ID

        var y = InputsWrapper1.children().length; //initlal text box count
	
        
        $("#AddMoreFileBoxPeriod").click(function(e) {
            //max input box allowed

            if (y <= maxPeriod) {
                FieldCount++; //text box added ncrement
                //add input box
                $('#dateContainer').append('<div class="row d-flex align-items-center mb-3 justify-content-between"><div class="col-sm-4 col-9 m-0 p-md-0"><input class="form-control col-11 arrow" id="periodicWeekDay' + FieldCount + '" list="weekdays" name="weekday[]"><datalist id="weekdays"><option data-value="1" value="Esmaspäev"></option><option data-value="2" value="Teisipäev"></option><option data-value="3" value="Kolmapäev"></option><option data-value="4" value="Neljapäev"></option><option data-value="5" value="Reede"></option><option data-value="6" value="Laupäev"></option><option data-value="7" value="Pühapäev"></option></datalist></div><a href="#" class="removeclass2 col-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a><div class="col-4 col-sm-3"><input type="text" class="clock pl-2 form-control" name="timesStart[]" data-minimum="08:00" data-maximum="22:00" id="from' + FieldCount + '" value="<?php if(isset($data['timesStart'][0])){ echo $data['timesStart'][0];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>"></div><div class="col-4 col-sm-3"><input type="text" class="clock pl-2 form-control" name="timeTo[]" data-minimum="08:00" data-maximum="22:00" id="until' + FieldCount + '" value="<?php if(isset($data['timeTo'][0])){ echo $data['timeTo'][0];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>"></div></div>');

                $('.clock').clockTimePicker({
                    duration: true,
                    durationNegative: true,
                    precision: 5,
                    i18n: {
                        cancelButton: 'Võta tagasi'
                    },
                    onAdjust: function(newVal, oldVal) {
                        //...
                    }
                });

                y++; //text box increment

                $("#AddMoreFileId1").show();

                // $('AddMoreFileBox').html("Add field");

                // Delete the "add"-link if there is 3 fields.
                if (y == maxPeriod) {
                    $("#AddMoreFileId1").hide();
                    $("#lineBreak").html("<br>");
                }
            }
            return false;
        });

        $("#dateContainer").on("click", ".removeclass2", function(e) { //user click on remove text
            if (y > 1) {
                $(this).parent('div').remove(); //remove text box
                y--; 
                $("#AddMoreFileId1").show();

               }
            return false;
        });


        var maxClosed = 100;
        var InputsWrapper2 = $("#InputsWrapper2 #eventContainer"); //Input boxes wrapper ID
        var AddButton2 = $("#AddMoreFileBoxEvent"); //Add button ID

        var z = InputsWrapper2.children().length; //initlal text box count


        $("#AddMoreFileBoxEvent").click(function(e) {
            //max input box allowed

            if (z <= maxClosed) {
                FieldCount++; //text box added ncrement
                //add input box
				$('#eventContainer').append('<div class="row d-flex align-items-center mb-3 justify-content-between"><div class="col-sm-4 col-9 m-0 p-md-0"><input class="datePicker col-11 form-control" id="datefield_' + FieldCount + '" data-toggle="datepicker" name="workoutDate[]"></div><a class="removeclass3 mt-3 col-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a><div class="col-4 col-sm-3"><input type="text" class="clock pl-2 form-control" name="timesStart[]" data-minimum="08:00" data-maximum="22:00" id="timestartfield_' + FieldCount + '" value="<?php if(isset($data['timesStart'][0])){ echo $data['timesStart'][0];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>"></div><div class="col-4 col-sm-3"><input type="text" class="clock pl-2 form-control" name="timeTo[]" data-minimum="08:00" data-maximum="22:00" id="timeendfield_' + FieldCount + '" value="<?php if(isset($data['timeTo'][0])){ echo $data['timeTo'][0];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>"></div>	</div>');
           
                $('.clock').clockTimePicker({
                    duration: true,
                    durationNegative: true,
                    precision: 5,
                    i18n: {
                        cancelButton: 'Võta tagasi'
                    },
                    onAdjust: function(newVal, oldVal) {
                        //...
                    }
                });

                z++; //text box increment

                $("#AddMoreFileId1").show();

                // $('AddMoreFileBox').html("Add field");

                // Delete the "add"-link if there is 3 fields.
                if (z == maxClosed) {
                    $("#AddMoreFileId2").hide();
                    $("#lineBreak").html("<br>");
                }
            }
            return false;
        });

        $('#eventContainer').on('click', '.removeclass3', function(e) { //user click on remove text
            if (z > 1) {
                $(this).parent('div').remove(); //remove text box
                z--; 
                $("#AddMoreFileId2").show();

               }
            return false;
        });





    $(".nav a").on("click", function() { // TAB'i active klassi toggle
        $(".nav a").removeClass("active");
        $(this).addClass("active");
	
		if($(this).prop('hash')=='#mitmekordne')
		{
			$("#syndmus").removeClass("active");
			$("#hooajaline").removeClass("active");
			$("#mitmekordne").addClass("active");
			
		}
		if($(this).prop('hash')=='#hooajaline')
		{
			$("#syndmus").removeClass("active");
			$("#mitmekordne").removeClass("active");
			$("#hooajaline").addClass("active");
			
		}
		if($(this).prop('hash')=='#syndmus')
		{
			$("#hooajaline").removeClass("active");
			$("#mitmekordne").removeClass("active");
			$("#syndmus").addClass("active");
		
		}

    });



	function checkOnceDates() {
    var dateArray = [];
	
	var startingDate;
	var endingDate;
	var weekDaySelected;
	
	$('[id^=datefield]').each(function(i, el) {
	
		startingDate = $('[id^=timestartfield]').val();
	
		if (isNaN(startingDate.substring(0, 2))) {
			startingDate = "0" + startingDate;
		};
		endingDate = $('[id^=timeendfield]').val();
		if (isNaN(endingDate.substring(11, 2) < 10)) {
			endingDate = "0" + endingDate;
		};
		
		var obj = {
			start : moment($(this).val(), 'DD.MM.YYYY').format('YYYY-MM-DD')+" "+startingDate+":00",
			end : moment($(this).val(), 'DD.MM.YYYY').format('YYYY-MM-DD')+" "+endingDate+":00"
			};
			
		dateArray.push( obj );

	});
//	console.log(dateArray);
    return dateArray;
	}
	




	$("#checkForOnceConflicts").click(function(e) {
		e.preventDefault();
		$(this).hide();
		$("#loadingTemporarlyButtonOnce").removeClass('d-none');
		$("#loadingTemporarlyButtonOnce").html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Kontrollin kattuvusi...');
	    $( "#myOnceForm" ).submit();

	});







	function toDate(str) {

	var [yyyy, MM, dd, hh, mm] = str.split(/[- :]/g);
	return new Date(`${MM}/${dd}/${yyyy} ${hh}:${mm}`);
	};

    //find all weekday matches
	function getDates(startDate, stopDate) {
    var dateArray = [];
	var weekdSelectedToArray=[];
	var beginTimedSelectedToArray=[];
	var untilTimeSelectedToArray=[];
	
	var startingDate;
	var endingDate;
	var weekDaySelected;
	
	$('[id^=periodicWeekDay]').each(function(i, el) {
		
		startingDate = $('[id^=from]').val();
		if (isNaN($('[id^=from]').val().substring(0, 2))) {
			startingDate = "0" + startingDate;
		};
		endingDate = $('[id^=until]').val();
		if (isNaN(endingDate.substring(11, 2) < 10)) {
			endingDate = "0" + endingDate;
		};

		beginTimedSelectedToArray.push(startingDate);
		untilTimeSelectedToArray.push(endingDate);
		weekdSelectedToArray.push( days.indexOf($(this).val()));
		
	});
	
    var stopDate = moment(stopDate);
    maximumToCheck= weekdSelectedToArray.length;
    //    console.log("maximumToCheck"+maximumToCheck);
	weekdSelectedToArray.forEach(function (item, index) {

        var checkHowMuchConflicts=0;
		var currentDate = moment(startDate);
    	while (currentDate <= stopDate) {

      
			if(item==7) {item=0};
			if(item == new Date(moment(currentDate).format('YYYY-MM-DD')).getDay() ){

            var obj = {
			start : moment(currentDate).format('YYYY-MM-DD')+" "+beginTimedSelectedToArray[index]+":00",
			end : moment(currentDate).format('YYYY-MM-DD')+" "+untilTimeSelectedToArray[index]+":00"
            };
			dateArray.push( obj );
			currentDate = moment(currentDate).add(6, 'days');
      		 };
          
		 currentDate = moment(currentDate).add(1, 'days');
        
         checkHowMuchConflicts++;
        
      
		
   
}
});
//    console.log(dateArray);
    return dateArray;
	}

function hasJsonStructure(str) {
    if (typeof str !== 'string') return false;
    try {
        const result = JSON.parse(str);
        const type = Object.prototype.toString.call(result);
        return type === '[object Object]' 
            || type === '[object Array]';
    } catch (err) {
        return false;
    }
}


	
var allConflictsFromBE=JSON.stringify(<?php echo json_encode($conflictDates);?>);


if(hasJsonStructure(allConflictsFromBE)){
	//console.log(allConflictsFromBE);

	var conflict = JSON.parse(allConflictsFromBE);
	conflict.forEach(function(item) {
	// console.log(item.public_info+":  "+item.startTime+"-"+item.endTime);
		$('#myTable > tbody:last-child').append('<tr><td>'+days[new Date(item.startTime.substring(0, 10)).getDay()]+'</td><td>'+moment(item.startTime.substring(0, 10), "YYYY-MM-DD").format("DD.MM.YY")+'</td><td>'+ item.startTime.substring(11, 16)+"-"+item.endTime.substring(11, 16)+'</td><td>'+item.workout+'</td><td>'+item.public_info+'</td><td>'+item.room+'</td></tr>');
	
		});
	$('#approvePeriodNow').prop('checked', false);//kinnitus võetakse automaatselt maha
	$('#approveNow').prop('checked', false);//kinnitus võetakse automaatselt maha
		$('#myModal').modal('show')
}



$( "#checkForConflicts" ).click(function() {
	
		$(this).hide();
		$("#loadingTemporarlyButton").removeClass('d-none');
		$("#loadingTemporarlyButton").html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Kontrollin kattuvusi...');

		var startingDate = $('#periodStart').val();
    	var startingDateConverted = moment(startingDate, "DD.MM.YYYY").format("YYYY-MM-DD");
		var endingDate = $('#periodEnd').val();
    	var endingDateConverted = moment(endingDate, "DD.MM.YYYY").format("YYYY-MM-DD");
        var getDateArray=getDates(startingDateConverted, endingDateConverted);
	//	console.log("konfliktide kontrollitav on " +(getDateArray).length);
		
		if((getDateArray.length)>300){
		
		if (!confirm("Soovid broneerida korraga üle 300 aja (täpsemalt "+getDateArray.length+" aega). Suur aegade hulk ühe broneeringu kohta võib süsteemi tööd aeglustada. Te saate broneeringut salvestada, kuid oleks parem, kui tükeldate broneeringut lühemateks perioodideks. Kas soovid siiski salvestada?")){
            $('#approvePeriodNow').prop('checked', false);//kinnitus võetakse automaatselt maha
        	$( "#checkForConflicts" ).show();
			$("#loadingTemporarlyButton").addClass('d-none');
			return true;
		}
        
	};
	$( "#myPeriodicForm" ).submit();

	});

	

	var whichFormToSubmit='<?php echo $data['type']; ?>';
//	console.log(whichFormToSubmit+"nr on");
$( "#submitWithConflicts" ).click(function() {
	
	if (whichFormToSubmit==1){
        $( "#allowFormToSubmitAndNeverMindConflicts1" ).val("1");
		$( "#myOnceForm" ).submit();

	}
	else if (whichFormToSubmit==2 ){
        $( "#allowFormToSubmitAndNeverMindConflicts2" ).val("1");
     
        $( "#myPeriodicForm" ).submit();

	}
	else if (whichFormToSubmit==4){
     
        $( "#allowFormToSubmitAndNeverMindConflicts3" ).val("1");
        $( "#myClosedForm" ).submit();

	}
	
 
});

$('div').on('click', '.removeRoom', function(e) { //user click on remove text
                $(this).remove(); //remove text box
        });
});


function showContractInfo(box, sameasagreement, checkwithdiv) {
		var chboxs = document.getElementById(box).style.display;
		var vis = "none";
			if(chboxs=="none"){
			vis = "block"; }
			if(chboxs=="block"){
				
			vis = "none"; }
		document.getElementById(box).style.display = vis;
	
			if(document.getElementById(checkwithdiv).style.display=="none"){
				document.getElementById(sameasagreement).style.display = 'none';
			}else{
				document.getElementById(sameasagreement).style.display = 'block';
			}
		
	}


	var oldvalue1;
	var oldvalue2;
	var oldvalue3;
	var oldvalue4;
	var oldvalue5;
	var oldvalue6;

	var oldvalue7;
	var oldvalue8;
	var oldvalue9;
	var oldvalue10;
	var oldvalue11;
	var oldvalue12;

	var oldvalue13;
	var oldvalue14;
	var oldvalue15;
	var oldvalue16;
	var oldvalue17;
	var oldvalue18;

$("#checkbox1").on("change",function(){
	if (this.checked ) {
		oldvalue1 = $("#text2").val();
		oldvalue2 = $("#text4").val();
		oldvalue3 = $("#text6").val();
		oldvalue4 = $("#text8").val();
		oldvalue5 = $("#text10").val();
		oldvalue6 = $("#text12").val();

			$("#text2").val($("#text1").val());
			$("#text4").val($("#text3").val());
			$("#text6").val($("#text5").val());
			$("#text8").val($("#text7").val());
			$("#text10").val($("#text9").val());
			$("#text12").val($("#text11").val());

		}   
		else{
			$("#text2").val(oldvalue1);
			$("#text4").val(oldvalue2);
			$("#text6").val(oldvalue3);
			$("#text8").val(oldvalue4);
			$("#text10").val(oldvalue5);
			$("#text12").val(oldvalue6);

		}

});

$("#checkbox2").on("change",function(){
	if (this.checked ) {
		
		oldvalue7 = $("#text14").val();
		oldvalue8 = $("#text16").val();
		oldvalue9 = $("#text18").val();
		oldvalue10 = $("#text20").val();
		oldvalue11 = $("#text22").val();
		oldvalue12 = $("#text24").val();
		
			$("#text14").val($("#text13").val());
			$("#text16").val($("#text15").val());
			$("#text18").val($("#text17").val());
			$("#text20").val($("#text19").val());
			$("#text22").val($("#text21").val());
			$("#text24").val($("#text23").val());
		}   
		else{
		
			$("#text14").val(oldvalue7);
			$("#text16").val(oldvalue8);
			$("#text18").val(oldvalue9);
			$("#text20").val(oldvalue10);
			$("#text22").val(oldvalue11);
			$("#text24").val(oldvalue12);
		}

});

$("#checkbox3").on("change",function(){
	if (this.checked ) {
		
		oldvalue13 = $("#text26").val();
		oldvalue14 = $("#text28").val();
		oldvalue15 = $("#text30").val();
		oldvalue16 = $("#text32").val();
		oldvalue17 = $("#text34").val();
		oldvalue18 = $("#text36").val();
		
			$("#text26").val($("#text25").val());
			$("#text28").val($("#text27").val());
			$("#text30").val($("#text29").val());
			$("#text32").val($("#text31").val());
			$("#text34").val($("#text33").val());
			$("#text36").val($("#text35").val());
		}   
		else{
		
			$("#text26").val(oldvalue13);
			$("#text28").val(oldvalue14);
			$("#text30").val(oldvalue15);
			$("#text32").val(oldvalue16);
			$("#text34").val(oldvalue17);
			$("#text36").val(oldvalue18);
		}

});

function showBillingInfo(thiselement, ifpayingwithinvoce, divToShow) {
	if(thiselement.value==1){
	$("#"+divToShow).show();
	$('#'+ifpayingwithinvoce).prop('checked', true);
}
}


function addRoom(selectedRoom, addselectedroomtodiv) {
  var selectedRoomID =  $( "#"+selectedRoom ).val();
  var selectedRoomName =  $( "#"+selectedRoom+" option:selected" ).text();
 // console.log($("#selectedRooms p").text().match(selectedRoomName));
 if (!$("#"+addselectedroomtodiv+" p").text().match(selectedRoomName)&& selectedRoomName!="Vali ruum" && selectedRoomName!="Kõik ruumid"){
  $("#"+addselectedroomtodiv).append( '<p class="removeRoom  mt-1 mr-1 btn btn-success  text-light"  value="' + selectedRoomID + '">' + selectedRoomName + '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='+selectedRoomID+'></input></p>' );
}
if ( selectedRoomName=="Kõik ruumid"){
	$("#"+selectedRoom+" option").each(function(){
			if(!$("#"+addselectedroomtodiv+" p").text().match($(this).text() ) && $(this).text()!="Vali ruum" && $(this).text()!="Kõik ruumid"){
			$("#"+addselectedroomtodiv).append( '<p class="removeRoom  mt-1 mr-1 btn btn-success  text-light">' + $(this).text() + '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='+$(this).val()+'></input></p>' );
		}
	});
	}
	$( "#"+selectedRoom ).val('Vali ruum')
}


$("#approveNow, #approvePeriodNow").change(function() {
    if(this.checked) {
		$(this).val('1');
    }else{
		$(this).val('0');
	}
});
</script>
