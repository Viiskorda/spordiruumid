<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php $data=$this->session->flashdata('key');if($data):// print_r($data); 

// foreach ($data['weekday'] as $each) { 
//    echo $each;
//  };
 endif; ?>
<?php $stack = array(); foreach ($allBookingInfo as $each) { 
    array_push($stack, $each['c_name'] );
 };
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
                    <li class="nav-item"><a  class="nav-link link txt-lg <?php if(!isset($data['type'])){ echo 'active';$data['type']=1;}else if($data['type']==1){echo 'active';}; ?>" href="#mitmekordne" data-toggle="tab">Ühekordne broneering</a></li>
                    <li class="nav-item"><a  class="nav-link link txt-lg <?php if($data['type']==2){echo 'active';}; ?>" href="#hooajaline" data-toggle="tab">Hooajaline broneering</a></li>
                    <?php if($this->session->userdata('roleID')==='2'||$this->session->userdata('roleID')==='3'):?>
                    <li class="nav-item"><a  class="nav-link link txt-lg <?php if($data['type']==4){echo 'active';}; ?>" href="#suletud" data-toggle="tab">Suletud broneering</a></li>
                    <?php endif;?>
                </ul>
            </div>


			
            <div class="tab-content ">
                <div id="mitmekordne" class="tab-pane center  <?php if(!isset($data['type'])){ echo 'active';}else if($data['type']==1){echo 'active';}; ?>">
                    <?php echo form_open('booking/createOnce', array('id' => 'myOnceForm')); ?>

                        <h4 class="pt-2 txt-xl px-md-5 mx-md-5 ml-3">Kontakt</h4>
                        <div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
							
								<label class=" col-7 col-sm-7 col-md-12  p-0" for="contact">Klubi nimi (avalik info)<?php if($bookingformdata['clubname_admin']==1){echo "*";} ?> <b data-tooltip="Klubi puudumisel kirjuta kontaktisiku nime initsiaalid"><img id="tool" class="mr-5" src="<?php echo base_url(); ?>assets/img/icon-info.svg" width="7%"></b> <?php if($this->session->flashdata('validationErrorMessageForClubname')){  echo $this->session->flashdata('validationErrorMessageForClubname');} ?></label>
								<input class="form-control <?php if($this->session->flashdata('validationErrorMessageForClubname')){ echo 'is-invalid';}?>" id="clubnameForSingle" type="text" name="clubname" required value="<?php if(isset($data['clubname'])): echo $data['clubname'];endif; ?>">
							</div>
                            <input class="d-none" type="checkbox" id="typeOnce" name="type" value="1" checked>
                            <input class="d-none" type="checkbox" id="allowFormToSubmitAndNeverMindConflicts1" name="allowSave" value="0" checked>
                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label>Kontaktisik<?php if($bookingformdata['contactname_admin']==1){echo "*";} ?>	<?php if($this->session->flashdata('validationErrorMessageContactPerson')){  echo $this->session->flashdata('validationErrorMessageContactPerson');} ?> </label>
                                <input class="form-control" id="contactForSingle" name="contactPerson" value="<?php if(isset($data['contactPerson'])){ echo $data['contactPerson'];} else if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('userName');}; ?>">
                            </div>
                        </div>
                        <div class="row d-flex mt-2 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label>Telefon<?php if($bookingformdata['phone_admin']==1){echo "*";} ?> <?php if($this->session->flashdata('phoneIsNotCorrect')){  echo $this->session->flashdata('phoneIsNotCorrect');} ?><?php if($this->session->flashdata('validationErrorMessageForPhone')){  echo $this->session->flashdata('validationErrorMessageForPhone');} ?></label>
                                <input class="form-control" id="phoneForSingle" name="phone" value="<?php if(isset($data['phone'])){ echo $data['phone'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('phone');}; ?>">
                            </div>

                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label>Email<?php if($bookingformdata['email_admin']==1){echo "*";} ?> <?php if($this->session->flashdata('emailIsNotCorrect')){  echo $this->session->flashdata('emailIsNotCorrect');} ?><?php if($this->session->flashdata('email_flash')){  echo $this->session->flashdata('email_flash');} ?></label>
                                <input class="form-control" id="emailForSingle" name="email" value="<?php if(isset($data['email'])){ echo $data['email'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('email');}; ?>">
                            </div>
                        </div>

                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Asukoht ja sündmus / treeningu tüüp</h4>
                        <div class="row d-flex mt-4 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label for="sport_facilityOnce">Asutus</label>
                                <input id="sport_facilityOnce" class="form-control" list="asutus" id="building" value="<?php $test = $this->session->userdata('building'); foreach ($buildings as $each) { $id = $each->id; $name = $each->name; if ($id == $test) {echo $each->name;}};?>" disabled>
                            </div>
                           <?php echo $this->input->get('roomId');?>
                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label for="roomOnce">Ruum*</label><?php if($this->session->flashdata('sportroomMissing')){  echo $this->session->flashdata('sportroomMissing');} ?>
								<select id="roomOnce"  onchange="addRoomInOnce()" list="saal" class="form-control arrow" >
								
								<option >Vali ruum</option>
                                    <?php foreach ($rooms as $each) {
										echo $each->id;
									
											echo '<option value="' . $each->id . '">' . $each->roomName . '</option>';
									}
										   ?>
										   	<option >Kõik ruumid</option>
                                </select>
							<div id="selectedRooms">	 Valitud ruumid:
								<?php 

								if(isset($data['sportrooms'])){ 
									foreach($data['sportrooms'] as $value){
										foreach($rooms as $room){
											if($value== $room->id){
												echo '<p class="removeRoom mt-1 mr-1 btn btn-success" value="' . $room->id . '">' . $room->roomName . '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='.$room->id.'></input></p>';
											}
										}
									}
								}
	
								else {
									foreach($rooms as $room){
									
										if( $this->uri->segment(3)== $room->id){
											echo '<p class="removeRoom mt-1 mr-1 btn btn-success" value="' . $room->id . '">' . $room->roomName . '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='.$room->id.'></input></p>';
										}
									} 
									 } ?>
										</div>

                            </div>
                        </div>
                        <div class="row d-flex mt-2 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label>Sündmus / Treeningu tüüp (avalik info)<?php if($bookingformdata['type_admin']==1){echo "*";} ?>  <?php if($this->session->flashdata('type_flash')){  echo $this->session->flashdata('type_flash');} ?></label>
                                <input class="form-control" id="typePeriod" name="workoutType" placeholder="nt iluvõimlemine" value="<?php if(isset($data['workoutType'])){ echo $data['workoutType'];}?>">
                            </div>
                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5"></div>
                        </div>

                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Kuupäev ja kellaaeg</h4>
                        <div class="mt-4 bg-grey py-2">
                            <div class="form-label-group px-md-5 mx-md-5" id="timestamp">
							
                                <div id="InputsWrapper" class="mb-3 p-md-0">
                                    <div class="row d-flex align-items-center justify-content-between">
										<div class="col-sm-4 col-9 m-0 p-md-0">
											<label class="col-md-5 m-0 p-md-0" for="datetime">Kuupäev</label>
										</div>
                                        <a class="removeclass mt-3 col-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class=""></span></a>
                                        <div class="col-4 col-sm-2">
											<div>	
												<label class="col-2 m-0 p-md-0" for="from1">Alates</label>
											</div>
										</div>
                                        <div class="col-4 col-sm-2">
										<div>	
											<label class="col-2 m-0 p-md-0" for="until1">Kuni</label>
										</div>
										</div>
										<div class="col-4 col-sm-3">
										<div>	
										<label class="col-2 m-0 p-md-0" for="color">Värv </label>
										</div>
										</div>
									</div>


									<div class="row d-flex align-items-center mb-3 justify-content-between">
										<div class="col-sm-4 col-9 m-0 p-md-0">
											<input class="datePicker col-11 form-control" id="datefield_1" data-toggle="datepicker" name="workoutDate[]" value="<?php  if(isset($data['workoutDate'])){ echo $data['workoutDate'][0];} else if(isset($_GET["startDate"])){echo $_GET["startDate"];}?>">
										</div>
                                        <a class="removeclass mt-3 col-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a>

                                        <div class="col-4 col-sm-2">
											<div>	
												<input type="input" class="clock pl-2 form-control" name="timesStart[]" data-minimum="06:00" data-maximum="22:00" id="timestartfield_1" value="<?php if(isset($data['timesStart'][0])){ echo $data['timesStart'][0];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>">
											</div>
										</div>
                                        <div class="col-4 col-sm-2">
										<div>	
											<input type="input" class="clock pl-2 form-control" name="timeTo[]" data-minimum="06:00" data-maximum="22:00" id="timeendfield_1" value="<?php if(isset($data['timeTo'][0])){ echo $data['timeTo'][0];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>">
										</div>
										</div>
										<div class="col-4 col-sm-3">
										<div>	
											<input type="color" class="form-control" name="color[]" value="<?php if(isset($data['color'][0])){ echo $data['color'][0];}else{  echo "#ffffff";}?>" list="presetColors">
											<datalist id="presetColors">
											<?php if(isset($bookingformdata['color1'])){
												echo "<option>".$bookingformdata['color1']."</option>";
												echo "<option>".$bookingformdata['color2']."</option>";
												echo "<option>".$bookingformdata['color3']."</option>";
												echo "<option>".$bookingformdata['color4']."</option>";
												echo "<option>".$bookingformdata['color5']."</option>";
												echo "<option>".$bookingformdata['color6']."</option>";
												echo "<option>".$bookingformdata['color7']."</option>";
												echo "<option>".$bookingformdata['color8']."</option>";
											} else {
											echo "<option>#ffffff</option>";
											echo "<option>#ddffee</option>";
											echo "<option>#cceeff</option>";
											echo "<option>#ffccee</option>";
											echo "<option>#ffffcc</option>";
											echo "<option>#aaffaa</option>";
											echo "<option>#eeffff</option>";
											echo "<option>#f6e5ff</option>";
											}
												?>	
											</datalist>
										</div>
										</div>
                                    </div>
                                    
                                    <?php if(isset($data['workoutDate'])){ for ($i = 1; $i<count($data['workoutDate']); $i++) { ?>
									    <div class="row d-flex align-items-center mb-3 justify-content-between">
                                        <div class="col-sm-4 col-9 m-0 p-md-0">
										<input class="datePicker col-11 form-control" id="datefield_<?php echo $i;?>" data-toggle="datepicker" name="workoutDate[]" value="<?php echo $data['workoutDate'][$i];?>">
										</div>


										<a class="removeclass col-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a>
										<div class="col-4 col-sm-2">
											<input type="text" class="clock pl-2 form-control" name="timesStart[]" data-minimum="06:00" data-maximum="22:00" id="timestartfield_<?php echo $i;?>" value="<?php if(isset($data['timesStart'][$i])){ echo $data['timesStart'][$i];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>">
										</div>
										<div class="col-4 col-sm-2">
											<input type="text" class="clock pl-2 form-control" name="timeTo[]" data-minimum="06:00" data-maximum="22:00" id="timeendfield_<?php echo $i;?>" value="<?php if(isset($data['timeTo'][$i])){ echo $data['timeTo'][$i];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>">
										</div>
										<div class="col-4 col-sm-3">
                                            <input type="color" class="form-control" name="color[]" value="<?php if(isset($data['color'][$i])){ echo $data['color'][$i];}else{  echo "#ffffff";}?>" list="presetColors">
											<datalist id="presetColors">
											<?php if(isset($bookingformdata['color1'])){
												echo "<option>".$bookingformdata['color1']."</option>";
												echo "<option>".$bookingformdata['color2']."</option>";
												echo "<option>".$bookingformdata['color3']."</option>";
												echo "<option>".$bookingformdata['color4']."</option>";
												echo "<option>".$bookingformdata['color5']."</option>";
												echo "<option>".$bookingformdata['color6']."</option>";
												echo "<option>".$bookingformdata['color7']."</option>";
												echo "<option>".$bookingformdata['color8']."</option>";
											} else {
											echo "<option>#ffffff</option>";
											echo "<option>#ddffee</option>";
											echo "<option>#cceeff</option>";
											echo "<option>#ffccee</option>";
											echo "<option>#ffffcc</option>";
											echo "<option>#aaffaa</option>";
											echo "<option>#eeffff</option>";
											echo "<option>#f6e5ff</option>";
											}
												?>	
											</datalist>
											
										</div>
								    	</div>
									<?php ;}}; ?>
								

                                </div>
                                <div id="AddMoreFileId" class="row d-flex p-md-0">
                                    <a id="AddMoreFileBox" class="btn btn-custom text-white text-center py-2 px-4 pluss ml-3 ml-md-0"><p class="m-0 px-0 txt-lg txt-strong text-center align-items-center">Lisa veel üks kuupäev</p></a>
                                </div>
                            </div>
                        </div>

                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Lisainfo (valikuline) </h4>
						<div class="mt-4 px-md-5 mx-md-5">
                            <div class="form-label-group pb-2 px-md-0 px-2">
                                <label>Lisainfo</label>
								<textarea class="form-control" id="additional" name="comment2" rows="3" placeholder="nt palun võrkpalli trenni jaoks eelnevalt üles seada võrk"><?php if(isset($data['comment2'])): echo $data['comment2'];endif; ?></textarea>
								
							</div>
							<label class="px-2"><input type="checkbox" <?php if(isset($data['approveNow'])){ if($data['approveNow']==1) echo  'checked'; }  else if($bookingformdata['approved_admin']==1){echo 'checked';} ?> name="approveNow" id="approveNow" value="<?php if(isset($data['approveNow'])){ echo $data['approveNow']; } else if(isset($bookingformdata['approved_admin'])){echo $bookingformdata['approved_admin'];} else{echo 1;}?>"><span></span></label> Kinnita kohe
						</div>
					
                        <div class="row d-flex justify-content-end mt-5 px-md-5 mx-md-5">
							
							  <a class="txt-xl link-deco align-self-center py-md-0 pr-md-5 mr-2" href="<?php echo base_url()?>fullcalendar?roomId=<?php foreach ($rooms as $each) {
									
									if(!empty($data['sportrooms'])){ 
										if($data['sportrooms'][0]== $each->id){ 
										echo $each->id;
									
									}
								}
									   else   if( $this->uri->segment(3)== $each->id){
												echo $each->id;
											}
										else if ( empty($this->uri->segment(3)) && $this->session->userdata('room')== $each->id){
											 echo $this->session->userdata('room');
											}
										} ?>&date=<?php if(isset($data['workoutDate'])){ echo $data['workoutDate'][0];} else if(isset($_GET["startDate"])){echo $_GET["startDate"];}else{ echo date("d.m.yy");}?>" >Katkesta</a>
							
							
							<input class="btn btn-custom col-12 col-sm-3 text-white txt-xl" type="submit"  id="checkForOnceConflicts" value="Broneeri">
						
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
			
                        <h4 class="pt-2 txt-xl px-md-5 mx-md-5 ml-3">Kontakt</h4>	<?php echo form_error('clubname'); ?>
                        <div class="row d-flex px-md-5 mx-md-5 mt-4">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
							<?php echo form_error('clubname'); ?>
							<label class=" col-7 col-sm-7 col-md-12  p-0" for="contact">Klubi nimi (avalik info)<?php if($bookingformdata['clubname_admin']==1){echo "*";} ?> <b data-tooltip="Klubi puudumisel kirjuta kontaktisiku nime initsiaalid"><img id="tool" class="mr-5" src="<?php echo base_url(); ?>assets/img/icon-info.svg" width="7%"></b> <?php if($this->session->flashdata('validationErrorMessageForClubname')){  echo $this->session->flashdata('validationErrorMessageForClubname');} ?></label>
								<input class="form-control <?php if($this->session->flashdata('validationErrorMessageForClubname')){  echo 'is-invalid';} ?>" id="clubname" type="text" name="clubname" required value="<?php if(isset($data['clubname'])): echo $data['clubname'];endif; ?>">
								
                            </div>

                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label>Kontaktisik<?php if($bookingformdata['contactname_admin']==1){echo "*";} ?> <?php if($this->session->flashdata('validationErrorMessageContactPerson')){  echo $this->session->flashdata('validationErrorMessageContactPerson');} ?></label>
                                <input class="form-control" id="contact" name="contactPerson" value="<?php if(isset($data['contactPerson'])){ echo $data['contactPerson'];} else if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('userName');}; ?>" required>
                            </div>
                        </div>
                        <div class="row d-flex mt-2 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label>Telefon<?php if($bookingformdata['phone_admin']==1){echo "*";} ?> <?php if($this->session->flashdata('phoneIsNotCorrect')){  echo $this->session->flashdata('phoneIsNotCorrect');} ?><?php if($this->session->flashdata('validationErrorMessageForPhone')){  echo $this->session->flashdata('validationErrorMessageForPhone');} ?></label>
                                <input class="form-control" id="phone" name="phone" value="<?php if(isset($data['phone'])){ echo $data['phone'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('phone');}; ?>">
                            </div>

                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label>Email<?php if($bookingformdata['email_admin']==1){echo "*";} ?> <?php if($this->session->flashdata('emailIsNotCorrect')){  echo $this->session->flashdata('emailIsNotCorrect');} ?><?php if($this->session->flashdata('email_flash')){  echo $this->session->flashdata('email_flash');} ?></label>
                                <input class="form-control" id="email" name="email" value="<?php if(isset($data['email'])){ echo $data['email'];} else  if($this->session->userdata('roleID')!='2' && $this->session->userdata('roleID')!='3'){echo $this->session->userdata('email');}; ?>">
                            </div>
                        </div>


                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Asukoht ja sündmus / treeningu tüüp</h4>
                        <div class="row d-flex mt-4 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label for="sport_facility">Asutus</label>
                                <input id="sport_facility" class="form-control" list="asutus" id="building" value="<?php $test = $this->session->userdata('building'); foreach ($buildings as $each) { $id = $each->id; $name = $each->name; if ($id == $test) {echo $each->name;}};?>" disabled>
                            </div>

                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <label for="roomPeriod">Ruum*</label><?php if($this->session->flashdata('sportroomMissing')){  echo $this->session->flashdata('sportroomMissing');} ?>
								<select id="roomPeriod"  onchange="addRoomInPeriod()" list="saal" class="form-control arrow" >
								<option >Vali ruum</option>
								<?php 
								foreach ($rooms as $each) {
										echo $each->id;
										
											    echo '<option value="' . $each->id . '">' . $each->roomName . '</option>';
									
											} ?>
									<option >Kõik ruumid</option>
								</select>
								<div id="selectedPeriodRooms">	Valitud ruumid:
								<?php 

								if(isset($data['sportrooms'])){ 
									foreach($data['sportrooms'] as $value){
										foreach($rooms as $room){
											if($value== $room->id){
												echo '<p class="removeRoom mt-1 mr-1 btn btn-success" value="' . $room->id . '">' . $room->roomName . '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='.$room->id.'></input></p>';
											}
										}
									}
								}
	
								else {
									foreach($rooms as $room){
									
										if( $this->uri->segment(3)== $room->id){
											echo '<p class="removeRoom mt-1 mr-1 btn btn-success" value="' . $room->id . '">' . $room->roomName . '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='.$room->id.'></input></p>';
										}
									} 
									 } ?>
										</div>

                            </div>
                        </div>

                        <div class="row d-flex mt-2 px-md-5 mx-md-5">
                            <div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
                                <label for="typeClosed">Sündmus / Treeningu tüüp (avalik info)<?php if($bookingformdata['type_admin']==1){echo "*";} ?> <?php if($this->session->flashdata('type_flash')){  echo $this->session->flashdata('type_flash');} ?></label>
                                <input class="form-control" id="typeClosed" name="workoutType" placeholder="nt iluvõimlemine"  value="<?php if(isset($data['workoutType'])): echo $data['workoutType'];endif; ?>">
                            </div>
                            <div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
                                <input class="d-none" type="checkbox" name="type" value="2" checked>
                                <input class="d-none" type="checkbox" id="allowFormToSubmitAndNeverMindConflicts2" name="allowSave" value="0" checked>
                            </div>
                        </div>                        
                           
                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Nädalapäev ja kellaaeg</h4>
                        <div class="mt-4 bg-grey py-2">
                            <div class="form-label-group px-md-5 mx-md-5"  id="InputsWrapper1">
                              
                                <div id="dateContainer">
								<div class="row d-flex align-items-center justify-content-between">
										<div class="col-sm-4 col-9 m-0 p-md-0">
										<label class="col-md-5 m-0 p-md-0" for="periodicWeekDay">Nädalapäev <?php if($this->session->flashdata('weekDayMissing')){  echo $this->session->flashdata('weekDayMissing');} ?></label>
										</div>
                                        <a class="removeclass1 mt-3 col-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class=""></span></a>
                                        <div class="col-4 col-sm-2">
											<div>	
												<label class="col-2 m-0 p-md-0" for="from1">Alates</label>
											</div>
										</div>
                                        <div class="col-4 col-sm-2">
										<div>	
											<label class="col-2 m-0 p-md-0" for="until1">Kuni</label>
										</div>
										</div>
										<div class="col-4 col-sm-3">
										<div>	
										<label class="col-2 m-0 p-md-0" for="color">Värv </label>
										</div>
										</div>
									</div>
									<div class="row d-flex align-items-center mb-3 justify-content-between">
										<div class="col-sm-4 col-9 m-0 p-md-0">
											
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
                                        <a class="removeclass1 col-3 mt-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a>
										
                                        <div class="col-4 col-sm-2">
											
                                            <input type="text" class="clock pl-2 form-control" name="timesStart[]" data-minimum="06:00" data-maximum="22:00" id="from1" value="<?php if(isset($data['timesStart'][0])){ echo $data['timesStart'][0];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>">
                                        </div>
										
                                        <div class="col-4 col-sm-2">
											
                                            <input type="text" class="clock pl-2 form-control" name="timeTo[]" data-minimum="06:00" data-maximum="22:00" id="until1" value="<?php if(isset($data['timeTo'][0])){ echo $data['timeTo'][0];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>">
										</div>   
										<div class="col-4 col-sm-3">
											
										<input type="color" class="form-control" name="color[]" value="<?php if(isset($data['color'][0])){ echo $data['color'][0];}else{  echo "#ffffff";}?>" list="presetColors">
											<datalist id="presetColors">
											<?php if(isset($bookingformdata['color1'])){
												echo "<option>".$bookingformdata['color1']."</option>";
												echo "<option>".$bookingformdata['color2']."</option>";
												echo "<option>".$bookingformdata['color3']."</option>";
												echo "<option>".$bookingformdata['color4']."</option>";
												echo "<option>".$bookingformdata['color5']."</option>";
												echo "<option>".$bookingformdata['color6']."</option>";
												echo "<option>".$bookingformdata['color7']."</option>";
												echo "<option>".$bookingformdata['color8']."</option>";
											} else {
											echo "<option>#ffffff</option>";
											echo "<option>#ddffee</option>";
											echo "<option>#cceeff</option>";
											echo "<option>#ffccee</option>";
											echo "<option>#ffffcc</option>";
											echo "<option>#aaffaa</option>";
											echo "<option>#eeffff</option>";
											echo "<option>#f6e5ff</option>";
											}
												?>	
											</datalist>
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
										<a class="removeclass1 col-3 mt-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a>
										<div class="col-4 col-sm-2">
											<input type="text" class="clock pl-2 form-control" name="timesStart[]" data-minimum="06:00" data-maximum="22:00" id="from<?php echo $i;?>" value="<?php if(isset($data['timesStart'][$i])){ echo $data['timesStart'][$i];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>">
										</div>
										<div class="col-4 col-sm-2">
											<input type="text" class="clock pl-2 form-control" name="timeTo[]" data-minimum="06:00" data-maximum="22:00" id="until<?php echo $i;?>" value="<?php if(isset($data['timeTo'][$i])){ echo $data['timeTo'][$i];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>">
										</div>
										<div class="col-4 col-sm-3">
										<input type="color" class="form-control" name="color[]" value="<?php if(isset($data['color'][$i])){ echo $data['color'][$i];}else{  echo "#ffffff";}?>" list="presetColors">
												<datalist id="presetColors">
												<?php if(isset($bookingformdata['color1'])){
													echo "<option>".$bookingformdata['color1']."</option>";
													echo "<option>".$bookingformdata['color2']."</option>";
													echo "<option>".$bookingformdata['color3']."</option>";
													echo "<option>".$bookingformdata['color4']."</option>";
													echo "<option>".$bookingformdata['color5']."</option>";
													echo "<option>".$bookingformdata['color6']."</option>";
													echo "<option>".$bookingformdata['color7']."</option>";
													echo "<option>".$bookingformdata['color8']."</option>";
												} else {
												echo "<option>#ffffff</option>";
												echo "<option>#ddffee</option>";
												echo "<option>#cceeff</option>";
												echo "<option>#ffccee</option>";
												echo "<option>#ffffcc</option>";
												echo "<option>#aaffaa</option>";
												echo "<option>#eeffff</option>";
												echo "<option>#f6e5ff</option>";
												}
													?>	
												</datalist>
											</div>
									</div>
									
											<?php 	} };?>
								</div>
                                <div id="AddMoreFileId1" class="flex ">
                                    <a id="AddMoreFileBoxPeriod" class="btn btn-custom text-white text-center py-2 px-4 pluss ml-3 ml-md-0"><p class="m-0 px-0 txt-lg txt-strong text-center align-items-center">Lisa nädalapäev</p></a>
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
					
                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Lisainfo (valikuline) </h4>
                        <div class="mt-4 px-md-5 mx-md-5">
                            <div class="form-label-group pb-2 px-md-0 px-2">
                                <label>Lisainfo</label>
                                <textarea class="form-control" id="comment2ForSingle" name="comment2" rows="3" placeholder="nt palun võrkpalli trenni jaoks eelnevalt üles seada võrk"><?php if(isset($data['comment2'])): echo $data['comment2'];endif; ?></textarea>
							</div>
							<label class="px-2"><input type="checkbox" <?php if($bookingformdata['approved_admin']==1){echo 'checked';} ?> name="approveNow" id="approvePeriodNow" value="<?php if(isset($bookingformdata['approved_admin'])){echo $bookingformdata['approved_admin'];} else{echo 1;}?>"><span></span></label> Kinnita kohe
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
										else if (empty($this->uri->segment(3)) && $this->session->userdata('room')== $each->id){ echo $this->session->userdata('room');}
										} ?>&date=<?php if(isset($data['workoutDate'])){ echo $data['workoutDate'][0];} else if(isset($_GET["startDate"])){echo $_GET["startDate"];}else{ echo date("d.m.yy");}?>" >Katkesta</a>
                            <input class="btn btn-custom col-12 col-sm-3 text-white txt-xl" type="button" id="checkForConflicts" value="Broneeri">
							
							<button id="loadingTemporarlyButton" class="d-none btn btn-custom text-white txt-xl" type="button" disabled>
							<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
							Kontrollin kattuvusi...
							</button>

                        </div>
						<input type="hidden" name="current_url" value="<?php echo current_url(); ?>" />
                    </form>
                </div>

                <div id="suletud" class="tab-pane center <?php if($data['type']==4){echo 'active';}; ?>">
				<?php echo form_open('booking/createClosed', array('id' => 'myClosedForm')); ?>

                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Ruum ja aeg</h4>
                        <div class="row d-flex px-md-5 mx-md-5">
						<input type="hidden" name="workoutType" value="">
                            <div class="form-label-group col-5 p-md-0 pl-6">
                                <label for="roomClosed">Ruum*</label><?php if($this->session->flashdata('sportroomMissing')){  echo $this->session->flashdata('sportroomMissing');} ?>
                                <select id="roomClosed"  onchange="addRoomInClosed()" name="sportrooms"  class="form-control arrow" >
								<option >Vali ruum</option>
								<?php 
								foreach ($rooms as $each) {
										echo $each->id;
										
											    echo '<option value="' . $each->id . '">' . $each->roomName . '</option>';
									
											} ?>
									<option >Kõik ruumid</option>
								</select>  
								<div id="selectedClosedRooms">	Valitud ruumid:
								<?php 

								if(isset($data['sportrooms'])){ 
									foreach($data['sportrooms'] as $value){
										foreach($rooms as $room){
											if($value== $room->id){
												echo '<p class="removeRoom mt-1 mr-1 btn btn-success" value="' . $room->id . '">' . $room->roomName . '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='.$room->id.'></input></p>';
											}
										}
									}
								}
	
								else {
									foreach($rooms as $room){
										if( $this->uri->segment(3)== $room->id){
											echo '<p class="removeRoom mt-1 mr-1 btn btn-success" value="' . $room->id . '">' . $room->roomName . '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='.$room->id.'></input></p>';
										}
									} 
									 } ?>
										</div>                      
                            </div>

                            <div class="row d-flex mt-2 px-md-5 mx-md-5">
                                <input class="d-none" type="checkbox" name="type" value="4" checked> <!-- Suletud (See tuleb ära peita ehk panna hidden)<br> -->
                                <input class="d-none" type="checkbox" id="allowFormToSubmitAndNeverMindConflicts3" name="allowSave" value="0" checked>
                                <input class="d-none" type="checkbox" name="clubname" value="Suletud" checked> <!-- Suletud Title (See tuleb ära peita ehk panna hidden)<br> -->
                                <input class="d-none" type="checkbox" name="approveNow" value="1" checked>
                            </div>
                        </div>
						<h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Nädalapäev ja kellaaeg</h4>
                        <div class="mt-4 bg-grey py-2">
                            <div class="form-label-group px-md-5 mx-md-5"  id="InputsWrapper2">
                      
							<div id="closeContainer">
								<div class="row d-flex align-items-center justify-content-between">
									<div class="col-sm-5 col-9 m-0 p-md-0">
									<label class="col-5 m-0 p-md-0" for="closedWeekDay">Nädalapäev <?php if($this->session->flashdata('weekDayMissing')){  echo $this->session->flashdata('weekDayMissing');} ?></label>
										</div>
										<a class="removeclass2 col-sm-1 col-3 pl-1 pr-xl-5"><span class=""></span></a>
										<div class="col-6 col-sm-3">
												<label class="col-2 m-0 pl-3" for="from2">Alates</label>
											</div>
											<div class="col-6 col-sm-3">
												<label class="col-2 m-0 p-md-0" for="until2">Kuni</label>
                                    	</div>
										<div class="col-4 col-sm-3">
										</div>
									</div>
								<div class="row d-flex align-items-center mb-3 justify-content-between">
										<div class="col-sm-5 col-9 m-0 p-md-0">
								
									<input class="form-control col-11 arrow <?php if($this->session->flashdata('weekDayMissing')){ echo 'is-invalid'; }?>" id="closedWeekDay" list="weekdays" name="weekday[]" required  value="<?php if(isset($data['weekday'])){ echo $data['weekday'][0];}else if (null!==($this->input->get('startDate'))){echo $weekdays[date('N', strtotime($this->input->get('startDate')))];} else {echo $weekdays[date('N', strtotime(date("d.m.Y")))]; ;}?>">
									<datalist id="weekdays">
										<option data-value="1"<?php if(isset($data['weekday'][0])&&$data['weekday'][0]=="Esmaspäev"): echo 'selected';endif; ?> value="Esmaspäev"></option>
                                                <option data-value="2"<?php if(isset($data['weekday'][0])&&$data['weekday'][0]=="Teisipäev"): echo 'selected';endif; ?> value="Teisipäev"></option>
                                                <option data-value="3"<?php if(isset($data['weekday'][0])&&$data['weekday'][0]=="Kolmapäev"): echo 'selected';endif; ?> value="Kolmapäev"></option>
                                                <option data-value="4"<?php if(isset($data['weekday'][0])&&$data['weekday'][0]=="Neljapäev"): echo 'selected';endif; ?> value="Neljapäev"></option>
                                                <option data-value="5"<?php if(isset($data['weekday'][0])&&$data['weekday'][0]=="Reede"): echo 'selected';endif; ?> value="Reede"></option>
                                                <option data-value="6"<?php if(isset($data['weekday'][0])&&$data['weekday'][0]=="Laupäev"): echo 'selected';endif; ?> value="Laupäev"></option>
                                                <option data-value="7"<?php if(isset($data['weekday'][0])&&$data['weekday'][0]=="Pühapäev"): echo 'selected';endif; ?> value="Pühapäev"></option>       
                                            </datalist>
										</div>
											<a class="removeclass2 col-sm-1 col-3 pl-1 pr-xl-5"><span class="icon-cancel"></span></a>
											
											<div class="col-6 col-sm-3">
											
												<input type="text" class="clock pl-2 form-control" data-minimum="06:00" data-maximum="22:00" name="timesStart[]" id="from2" value="<?php if(isset($data['timesStart'][0])){ echo $data['timesStart'][0];}else{ echo "08:00"; };?>">
											</div>
											
											<div class="col-6 col-sm-3">
											
                                            <input type="text" class="clock pl-2 form-control" data-minimum="06:00" data-maximum="22:00" name="timeTo[]" id="until2" value="<?php if(isset($data['timeTo'][0])){ echo $data['timeTo'][0];}else{ echo "22:00"; };?>">
										</div>
										<input type="color" class="d-none form-control" name="color[]" value="#ffffff"  list="presetColors">
											<datalist id="presetColors">
											<?php if(isset($bookingformdata['color1'])){
												echo "<option>".$bookingformdata['color1']."</option>";
												echo "<option>".$bookingformdata['color2']."</option>";
												echo "<option>".$bookingformdata['color3']."</option>";
												echo "<option>".$bookingformdata['color4']."</option>";
												echo "<option>".$bookingformdata['color5']."</option>";
												echo "<option>".$bookingformdata['color6']."</option>";
												echo "<option>".$bookingformdata['color7']."</option>";
												echo "<option>".$bookingformdata['color8']."</option>";
											} else {
											echo "<option>#ffffff</option>";
											echo "<option>#ddffee</option>";
											echo "<option>#cceeff</option>";
											echo "<option>#ffccee</option>";
											echo "<option>#ffffcc</option>";
											echo "<option>#aaffaa</option>";
											echo "<option>#eeffff</option>";
											echo "<option>#f6e5ff</option>";
											}
												?>	
											</datalist>
									
									
									</div>	

										<?php if(isset($data['weekday'])){ for ($i = 1; $i<count($data['weekday']); $i++) { ?>
                                    
									<div class="row d-flex align-items-center mb-3 justify-content-between">
                                        <div class="col-sm-5 col-9 m-0 p-md-0">
                                        <input class="form-control 	<?php if($this->session->flashdata('weekDayMissing')){ echo 'is-invalid';}?>  col-11 arrow" id="closedWeekDay<?php echo $i;?>" list="weekdays<?php echo $i;?>" name="weekday[]" value="<?php if($data['weekday'][$i]): echo $data['weekday'][$i];endif; ?>">
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
                                    <a class="removeclass2 col-sm-1 col-3 pl-1 pr-xl-5"><span class="icon-cancel"></span></a>
                                    <div class="col-6 col-sm-3">
                                        <input type="text" class="clock pl-2 form-control" name="timesStart[]" data-minimum="06:00" data-maximum="22:00" id="from<?php echo $i;?>" value="<?php if(isset($data['timesStart'][$i])){ echo $data['timesStart'][$i];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>">
                                        </div>
                                    <div class="col-6 col-sm-3">
                                        <input type="text" class="clock pl-2 form-control" name="timeTo[]" data-minimum="06:00" data-maximum="22:00" id="until<?php echo $i;?>" value="<?php if(isset($data['timeTo'][$i])){ echo $data['timeTo'][$i];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>">
									</div>
									<input type="color" class="d-none form-control" name="color[]" value="#ffffff" list="presetColors">
											<datalist id="presetColors">
											<?php if(isset($bookingformdata['color1'])){
												echo "<option>".$bookingformdata['color1']."</option>";
												echo "<option>".$bookingformdata['color2']."</option>";
												echo "<option>".$bookingformdata['color3']."</option>";
												echo "<option>".$bookingformdata['color4']."</option>";
												echo "<option>".$bookingformdata['color5']."</option>";
												echo "<option>".$bookingformdata['color6']."</option>";
												echo "<option>".$bookingformdata['color7']."</option>";
												echo "<option>".$bookingformdata['color8']."</option>";
											} else {
											echo "<option>#ffffff</option>";
											echo "<option>#ddffee</option>";
											echo "<option>#cceeff</option>";
											echo "<option>#ffccee</option>";
											echo "<option>#ffffcc</option>";
											echo "<option>#aaffaa</option>";
											echo "<option>#eeffff</option>";
											echo "<option>#f6e5ff</option>";
											}
												?>	
											</datalist>
                                </div>
                                
										<?php 	} };?>

					                </div>

                                <div id="AddMoreFileId2" class="flex">
                                    <a id="AddMoreFileBoxClosed" class="btn btn-custom text-white text-center py-2 px-4 pluss ml-3 ml-md-0"><p class="m-0 px-0 txt-lg txt-strong text-center align-items-center">Lisa nädalapäev</p></a>
                                </div>
                            </div>
                        </div>


                        <div class="row d-flex px-md-5 mx-md-5 mt-4">                        
                            <div class="form-label-group m-0 pl-md-0 pr-3 col-md-3 col-6">
								<label>Periood</label>
								<input class="datePicker form-control <?php if($this->session->flashdata('validationErrorMessageforPeriod')){ echo 'is-invalid';} ?>" id="periodStartClosed" data-toggle="datepicker" name="startingFrom"  value="<?php if(isset($data['startingFrom'])){echo $data['startingFrom'];} else if (null!==$this->input->get('startDate')){echo$this->input->get('startDate');} else {echo '';}?>">
                            </div>
                            <div class="form-label-group m-0 pl-md-0 col-md-3 col-6">  
                                <label class="invisible">Periood</label> 
                                <input class="datepickerClosedUntil form-control <?php if($this->session->flashdata('validationErrorMessageforPeriod')){ echo 'is-invalid';} ?>" id="periodEndClosed" data-toggle="datePicker" name="Ending">
                            </div>
						</div>
						
						<?php if($this->session->flashdata('validationErrorMessageforPeriod')){  
							echo  '<div class="row d-flex ml-3 px-md-5 mx-md-5">'.	$this->session->flashdata('validationErrorMessageforPeriod').'	</div>';} ?>
							
                        <h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Lisainfo (valikuline) </h4>
                        <div class="mt-4 px-md-5 mx-2 mx-md-5">
                            <div class="form-label-group pb-2 px-0">
                                <label>Lisainfo</label>
                                <textarea class="form-control" id="comment2" name="comment2" rows="3"><?php if(isset($data['comment2'])): echo $data['comment2'];endif; ?></textarea>
                            </div>
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
										else if (empty($this->uri->segment(3)) && $this->session->userdata('room')== $each->id){ echo $this->session->userdata('room');}
										} ?>&date=<?php if(isset($data['workoutDate'])){ echo $data['workoutDate'][0];} else if(isset($_GET["startDate"])){echo $_GET["startDate"];}else{ echo date("d.m.yy");}?>" >Katkesta</a>
                            <input class="btn btn-custom col-12 col-sm-3 text-white txt-xl" type="submit" value="Broneeri">
                        </div>
						<input type="hidden" name="current_url" value="<?php echo current_url();?>" />
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
                $('#InputsWrapper').append('<div class="row d-flex align-items-center mb-3 justify-content-between"><div class="col-sm-4 col-9 m-0 p-md-0"><input class="datePicker col-11 form-control" id="datefield_' + FieldCount + '" data-toggle="datepicker" name="workoutDate[]"></div><a class="removeclass mt-3 col-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a><div class="col-4 col-sm-2"><input type="text" class="clock pl-2 form-control" name="timesStart[]" data-minimum="06:00" data-maximum="22:00" id="timestartfield_' + FieldCount + '" value="<?php if(isset($data['timesStart'][0])){ echo $data['timesStart'][0];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>"></div><div class="col-4 col-sm-2"><input type="text" class="clock pl-2 form-control" name="timeTo[]" data-minimum="06:00" data-maximum="22:00" id="timeendfield_' + FieldCount + '" value="<?php if(isset($data['timeTo'][0])){ echo $data['timeTo'][0];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>"></div>	<div class="col-4 col-sm-3"><input type="color" class="form-control" name="color[]" value="#ffffff" list="presetColors"><datalist id="presetColors"><option>#ffffff</option><option>#ffffff</option><option>#ddffee</option><option>#cceeff</option><option>#ffccee</option><option>#ffffcc</option><option>#aaffaa</option><option>#eeffff</option><option>#f6e5ff</option>	</datalist></div></div>');

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

        $("#timestamp").on("click", ".removeclass", function(e) { //user click on remove text
     
            if (x > 2) {
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
                $('#dateContainer').append('<div class="row d-flex align-items-center mb-3 justify-content-between"><div class="col-sm-4 col-9 m-0 p-md-0"><input class="form-control col-11 arrow" id="periodicWeekDay' + FieldCount + '" list="weekdays" name="weekday[]"><datalist id="weekdays"><option data-value="1" value="Esmaspäev"></option><option data-value="2" value="Teisipäev"></option><option data-value="3" value="Kolmapäev"></option><option data-value="4" value="Neljapäev"></option><option data-value="5" value="Reede"></option><option data-value="6" value="Laupäev"></option><option data-value="7" value="Pühapäev"></option></datalist></div><a class="removeclass1 col-3 col-sm-1 pl-md-1 pr-xl-5 pr-md-0"><span class="icon-cancel"></span></a><div class="col-4 col-sm-2"><input type="text" class="clock pl-2 form-control" name="timesStart[]" data-minimum="06:00" data-maximum="22:00" id="from' + FieldCount + '" value="<?php if(isset($data['timesStart'][0])){ echo $data['timesStart'][0];}else{ echo $this->input->get('start') ? $this->input->get('start') : date('H:i'); };?>"></div><div class="col-4 col-sm-2"><input type="text" class="clock pl-2 form-control" name="timeTo[]" data-minimum="06:00" data-maximum="22:00" id="until' + FieldCount + '" value="<?php if(isset($data['timeTo'][0])){ echo $data['timeTo'][0];}else{  echo $this->input->get('end') ? $this->input->get('end') :  date("H:i", strtotime('+90 minutes')); }?>"></div>	<div class="col-4 col-sm-3"><input type="color" class="form-control" name="color[]" value="#ffffff"  list="presetColors"><datalist id="presetColors"><option>#ddffee</option><option>#cceeff</option><option>#ffccee</option><option>#ffffcc</option><option>#aaffaa</option><option>#eeffff</option><option>#f6e5ff</option>	</div></div>');

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

        $("#dateContainer").on("click", ".removeclass1", function(e) { //user click on remove text
            if (y > 2) {
                $(this).parent('div').remove(); //remove text box
                y--; 
                $("#AddMoreFileId1").show();

               }
            return false;
        });


        var maxClosed = 100;
        var InputsWrapper2 = $("#InputsWrapper2 #closeContainer"); //Input boxes wrapper ID
        var AddButton2 = $("#AddMoreFileBoxClosed"); //Add button ID

        var z = InputsWrapper2.children().length; //initlal text box count


        $("#AddMoreFileBoxClosed").click(function(e) {
            //max input box allowed

            if (z <= maxClosed) {
                FieldCount++; //text box added ncrement
                //add input box
				$('#closeContainer').append('<div class="row d-flex align-items-center mb-3 justify-content-between"><div class="col-sm-5 col-9 m-0 p-md-0"><input class="form-control col-11 arrow" id="closedWeekDay" list="weekdays" name="weekday[]"><datalist id="weekdays"><option data-value="1" value="Esmaspäev"></option><option data-value="2" value="Teisipäev"></option><option data-value="3" value="Kolmapäev"></option><option data-value="4" value="Neljapäev"></option><option data-value="5" value="Reede"></option><option data-value="6" value="Laupäev"></option><option data-value="7" value="Pühapäev"></option></datalist></div><a class="removeclass2 col-sm-1 col-3 pl-1 pr-xl-5"><span class="icon-cancel"></span></a><div class="col-6 col-sm-3"><input type="text" class="clock pl-2 form-control" name="timesStart[]" id="from' + FieldCount + '" value="08:00"></div><div class="col-6 col-sm-3"><input type="text" class="clock pl-2 form-control" name="timeTo[]" data-minimum="06:00" data-maximum="22:00" id="until' + FieldCount + '" value="22:00"></div></div>');
           
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

        $('#closeContainer').on('click', '.removeclass2', function(e) { //user click on remove text
            if (z > 2) {
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
			$("#suletud").removeClass("active");
			$("#hooajaline").removeClass("active");
			$("#mitmekordne").addClass("active");
			
		}
		if($(this).prop('hash')=='#hooajaline')
		{
			$("#suletud").removeClass("active");
			$("#mitmekordne").removeClass("active");
			$("#hooajaline").addClass("active");
			
		}
		if($(this).prop('hash')=='#suletud')
		{
			$("#hooajaline").removeClass("active");
			$("#mitmekordne").removeClass("active");
			$("#suletud").addClass("active");
		
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


function addRoomInOnce() {
  var selectedRoomID =  $( "#roomOnce" ).val();
  var selectedRoomName =  $( "#roomOnce option:selected" ).text();
 // console.log($("#selectedRooms p").text().match(selectedRoomName));
 if (!$("#selectedRooms p").text().match(selectedRoomName)&& selectedRoomName!="Vali ruum" && selectedRoomName!="Kõik ruumid"){
  $("#selectedRooms").append( '<p class="removeRoom mt-1 mr-1 btn btn-success"  value="' + selectedRoomID + '">' + selectedRoomName + '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='+selectedRoomID+'></input></p>' );
}
if ( selectedRoomName=="Kõik ruumid"){
	$("#roomOnce option").each(function(){
			if(!$("#selectedRooms p").text().match($(this).text() ) && $(this).text()!="Vali ruum" && $(this).text()!="Kõik ruumid"){
			$("#selectedRooms").append( '<p class="removeRoom mt-1 mr-1 btn btn-success">' + $(this).text() + '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='+$(this).val()+'></input></p>' );
		}
	});
}
$( "#roomOnce" ).val('Vali ruum')
}
 
function addRoomInPeriod () {
  var selectedRoomID =  $( "#roomPeriod" ).val();
  var selectedRoomName =  $( "#roomPeriod option:selected" ).text();

 if (!$("#selectedPeriodRooms p").text().match(selectedRoomName)&& selectedRoomName!="Vali ruum"  && selectedRoomName!="Kõik ruumid"){
  $("#selectedPeriodRooms").append( '<p class="removeRoom mt-1 mr-1 btn btn-success"  value="' + selectedRoomID + '">' + selectedRoomName + '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='+selectedRoomID+'></input></p>' );
}
if ( selectedRoomName=="Kõik ruumid"){
	$("#roomPeriod option").each(function(){
		if(!$("#selectedPeriodRooms p").text().match($(this).text() ) && $(this).text()!="Vali ruum" && $(this).text()!="Kõik ruumid"){
			$("#selectedPeriodRooms").append( '<p class="removeRoom mt-1 mr-1 btn btn-success">' + $(this).text() + '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='+$(this).val()+'></input></p>' );
		}
	});
}
$( "#roomPeriod" ).val('Vali ruum')
}
function addRoomInClosed () {
  var selectedRoomID =  $( "#roomClosed" ).val();
  var selectedRoomName =  $( "#roomClosed option:selected" ).text();

 if (!$("#selectedClosedRooms p").text().match(selectedRoomName)&& selectedRoomName!="Vali ruum"  && selectedRoomName!="Kõik ruumid"){
  $("#selectedClosedRooms").append( '<p class="removeRoom mt-1 mr-1 btn btn-success"  value="' + selectedRoomID + '">' + selectedRoomName + '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='+selectedRoomID+'></input></p>' );
}
if ( selectedRoomName=="Kõik ruumid"){
	$("#roomClosed option").each(function(){
		if(!$("#selectedClosedRooms p").text().match($(this).text() ) && $(this).text()!="Vali ruum" && $(this).text()!="Kõik ruumid"){
			$("#selectedClosedRooms").append( '<p class="removeRoom mt-1 mr-1 btn btn-success">' + $(this).text() + '<span aria-hidden="true"> &times; </span><input hidden name="sportrooms[]" value='+$(this).val()+'></input></p>' );
		}
	});
}
$( "#roomClosed" ).val('Vali ruum')
}


$("#approveNow, #approvePeriodNow").change(function() {
    if(this.checked) {
		$(this).val('1');
    }else{
		$(this).val('0');
	}
});
</script>
