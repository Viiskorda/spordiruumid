<?php  $conflictDates=$this->session->flashdata('conflictDates');  ?>
<div class="modal" id="myModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Muudatus on salvestatud</h5>
 
      </div>
      <div class="modal-body">
		<h6>Broneering kattub allolevate broneeringutega.</h6>
	
		<table id="myModalTable" class="table">
		<thead>	<tr><th>Nädalapäev</th><th>Kuupäev</th><th>Kellaaeg</th><th>Treening</th><th>Klubi</th></tr>	
		</thead>
			<tbody>
		
			</tbody>
		</table>
      </div>
      <div class="modal-footer">
        <button type="button" id="submitWithConflicts" class="btn btn-secondary">Vaatan kalendrist</button>
        <button type="button" class="btn btn-custom text-white " data-dismiss="modal">Muudan broneeringut</button>
      </div>
    </div>
  </div>
</div>  
<div class="container">

	<div id="forms" class="container-md pb-5">
		<div id="nav-tabs" class="mt-5 pb-5 form-bg">
			<div class="row d-flex mb-5">
				<ul class="nav nav-tabs nav-justified col-12 bg-grey">

				<?php if ($_POST['isPeriodic'] == 0):?>	
				<li class="nav-item"><a class="nav-link link txt-lg single-tab active" href="#mitmekordne" data-toggle="tab">Broneeringu muudatus</a></li>
			
				<?php endif;?>
				<?php if ($_POST['isPeriodic'] == 1):?>
				
					<li class="nav-item"><a class="nav-link link txt-lg  active" href="#hooajaline" data-toggle="tab">Hooajaline broneeringu muutmine</a></li>
					<?php endif;?>
					<li class="nav-item"></li>
				</ul>
			</div>
				<div class="tab-content ">
		
				<div id="mitmekordne" class="tab-pane center <?php if ($_POST['isPeriodic'] == 0) echo "active";?>">
					<?php echo form_open('edit/update', array('id' => 'change')); ?>
					<h4 class="pt-2 txt-xl px-md-5 mx-md-5 ml-3">Kontakt</h4>
					<div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
					<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								<label for="contact">Klubi nimi (avalik info)<?php if($bookingformdata['clubname_admin']==1){echo "*";} ?> <?php if($this->session->flashdata('validationErrorMessageForClubname')){  echo $this->session->flashdata('validationErrorMessageForClubname');} ?></label>
								<input type="text" class="form-control" name="publicInfo" value="<?php if(!empty($allPostData['publicInfo'])){echo $allPostData['publicInfo'];}else {echo $bookingData['public_info'];}?>" id="publicInfo">
							</div>
							<input class="d-none" type="checkbox" id="type" name="type" value="1" checked>
							<input class="d-none" type="checkbox" id="type" name="closed" value="<?php if($bookingData['typeID']=='4'){echo '4';}else { echo '1';}?>" checked>
							<input class="d-none" type="checkbox" id="allowFormToSubmitAndNeverMindConflicts1" name="allowSave" value="0" checked>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label>Kontaktisik <?php if($this->session->flashdata('validationErrorMessageContactPerson')){  echo $this->session->flashdata('validationErrorMessageContactPerson');} ?> </label>
								<input type="text" class="form-control" name="contactPerson" id="contactPerson" value="<?php if(!empty($allPostData['contactPerson'])){echo $allPostData['contactPerson'];}else {echo $bookingData['c_name'];}?>">
							</div>
						</div>
						<div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								<label>Telefon<?php if($bookingformdata['phone_admin']==1){echo "*";} ?>   <?php if($this->session->flashdata('phoneIsNotCorrect')){  echo $this->session->flashdata('phoneIsNotCorrect');} ?><?php if($this->session->flashdata('validationErrorMessageForPhone')){  echo $this->session->flashdata('validationErrorMessageForPhone');} ?></label>
								<input type="text" class="form-control" name="phone" id="phone" value="<?php if(!empty($allPostData['phone'])){echo $allPostData['phone'];}else {echo $bookingData['c_phone'];}?>">
							</div>

							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label>Email<?php if($bookingformdata['email_admin']==1){echo "*";} ?> <?php if($this->session->flashdata('emailIsNotCorrect')){  echo $this->session->flashdata('emailIsNotCorrect');} ?></label>
								<input type="text" class="form-control" name="email" id="email" value="<?php if(!empty($allPostData['email'])){echo $allPostData['email'];}else {echo $bookingData['c_email'];}?>">
							</div>
						</div>

						<h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Sündmus / Asukoht ja treeningu tüüp</h4>
						 <div class="row d-flex mt-4 px-md-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								<label for="sport_facility">Asutus</label>
								<input id="sport_facility" class="form-control" list="asutus" disabled value="">
								<!-- <datalist id="asutus">
                            <?php //foreach ($buildings as $each) {
							// echo '<option data-value="' . $each->id . '" value="' . $each->name . '"></option>';
							//}
							?>
                            </datalist> -->
							</div>

							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label for="room">Ruum</label>
								<select id="roomID" name="roomID"  class="form-control arrow" >
								
								<option >Vali ruum</option>
								
									<?php 
								
									foreach ($rooms as $each) {
										if( $each==$selectedRoom[0]){
											echo '<option selected  value="' . $each['id'] . '">' . $each['roomName'] . '</option>';
										}	
										else{								
											echo '<option value="' . $each['id'] . '">' . $each['roomName'] . '</option>';
										}
									}
										   ?>
										 
                                </select>
								
							</div>
						</div>

						<div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								<label>Sündmus / Treeningu tüüp (avalik info)<?php if($bookingformdata['type_admin']==1){echo "*";} ?> <?php if($this->session->flashdata('type_flash')){  echo $this->session->flashdata('type_flash');} ?></label>
								<input type="text" class="form-control" name="workoutType" id="workoutType" value="<?php if(!empty($allPostData['workoutType'])){echo $allPostData['workoutType'];}else {echo $bookingData['workout'];}?>">
							</div>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5"></div>
						</div>

						<h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Kuupäev ja kellaaeg</h4>
						<div class="mt-3 px-md-5 ml-2 mx-md-5">
							<div class="form-label-group pb-2 px-0">
								<table style=" min-width: 500px;" id="myTable" class="table table-borderless">
									<thead>
										<tr>
											<th class="txt-regular txt-lg p-0" colspan="2">Broneeritud aeg</th>
											<th class="txt-regular txt-lg p-0">Uus aeg</th>
											<th class="p-0"></th>
											<th class="p-0"></th>
											<th class="p-0"></th>
										</tr>
									</thead>
									<tbody>
										<tr class="bg-blue mb-5">
											<td id="month" class="text-white txt-regular td-width-l p-1">Kuupäev</td>
											<td id="blank" class="text-white txt-regular td-width-m p-1">Vahemik</td>
											<td id="kp" class="text-white txt-regular td-width-s p-1 pl-3">Kuupäev</td>
											<td id="alates" class="text-white txt-regular td-width-s p-1 pl-3">Alates</td>
											<td id="kuni" class="text-white txt-regular td-width-s p-1 pl-3">Kuni</td>
											<td id="color" class="text-white txt-regular td-width-s p-1 pl-3">Värv</td>
										</tr> <br/>
										<!-- Genereerib automaatselt -->

										
									</tbody>
								</table>
								<?php if(!empty($allPostData['additionalBookingDate'])){ ?>
								Uued lisatud ajad:
								<table>
								<tbody>
								<tr id="x"> <td class="td-width-l"><b>K</b>,&nbsp;<?php echo $allPostData['additionalBookingDate'][0]; ?></td><td class="td-width-m">&nbsp;&nbsp;<?php echo $allPostData['additionalBookingtimeStart'][0]; ?>‑<?php echo $allPostData['additionalBookingtimeEnd'][0]; ?></td><td class="td-width-s pl-3"><input class="datePicker form-control p" id="time_x" data-toggle="datepicker" name="additionalBookingDate[0]" value="<?php echo $allPostData['additionalBookingDate'][0]; ?>" style="border: 1px solid rgb(158, 50, 83);"></td><td class="td-width-s pl-3"><input type="text" class="clock form-control" name="additionalBookingtimeStart[]" data-minimum="06:00" data-maximum="22:00" id="timestartfield28" value="<?php echo $allPostData['additionalBookingtimeStart'][0]; ?>" style="border: 1px solid rgb(158, 50, 83);"></td>  <td class="td-width-s pl-3"><input type="text" class="clock form-control" name="additionalBookingtimeEnd[]" data-minimum="06:00" data-maximum="22:00" id="timeendfield_28" value="<?php echo $allPostData['additionalBookingtimeEnd'][0]; ?>" style="border: 1px solid rgb(158, 50, 83);"></td><td class="pl-3"><input name="addcolor[]" type="color" class="form-control" value="<?php echo $allPostData['addcolor'][0]; ?>" list="presetColors"><datalist id="presetColors">	<option>#ffffff</option><option>#fbebff</option><option>#ffecd6</option><option>#d6ffc7</option><option>#ccffe2</option><option>#dbfbff</option><option>#d9d1ff</option><option>#fffedb</option>	</datalist></td></tr>
						</tbody>
							</table>
								<?php print_r($allPostData); ?>
								<?php } ?>

								<!-- <div class="row d-flex col-5 p-0">
                            <a id="addTimes" class="btn btn-custom text-white text-center py-2 px-4 pluss"><p class="m-0 px-0 txt-lg text-center align-items-center">Lisa veel üks kuupäev</p></a>
                        </div> -->

							</div>
						</div>

						

						<h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Lisainfo (valikuline) </h4>
					    <div class="mt-3 px-md-5 ml-2 mx-md-5">
                            <div class="form-label-group pb-2 px-0">
								<label>Lisainfo</label>
								<textarea class="form-control" id="additional" name="additionalComment" rows="3"><?php if(!empty($allPostData['additionalComment'])){echo $allPostData['additionalComment'];}else {echo $bookingData['comment'];}?></textarea>
								<br/>
								<label>Muutmise põhjus</label>
								<input class="form-control" id="reason" name="reason" rows="3">
							</div>
						</div>
						<input class="d-none" type="hidden" name="BookingID" id="BookingID" value="<?php echo $_POST['BookingID']; ?>">
						<input class="d-none" type="hidden" name="isPeriodic" id="isPeriodic" value="<?php echo $_POST['isPeriodic']; ?>">
						<!-- <input class="d-none" type="hidden" name="roomID" id="roomID" value="<?php echo $bookingData['roomID'];?>">  -->

						<div class="row d-flex justify-content-end mt-5 px-md-5 mx-md-5">
							<a class="txt-xl link-deco align-self-center py-0 pr-5 mr-2" href="<?php echo base_url(); ?>fullcalendar?roomId=<?php echo $bookingData['roomID'];?>">Katkesta</a>
							<input type="submit" id="changeTimes" class="btn btn-custom col-12 col-sm-4 text-white txt-xl" value="Salvesta muudatused">
							
							<button id="loadingTemporarlyButtonOnce" class="d-none btn btn-custom text-white txt-xl" type="button" disabled>
							<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
							Kontrollin kattuvusi...
							</button>
						</div>


					</form>
				</div>

				 <div id="hooajaline" class="tab-pane center  <?php if ($_POST['isPeriodic'] == 1) echo "active";?>">
					<?php echo form_open('edit/updatePeriod', array('id' => 'changePeriod')); ?>
					<h4 class="pt-2 txt-xl px-md-5 mx-md-5 ml-3">Kontakt</h4>
					<div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
					<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								<label for="contact">Klubi nimi (avalik info)<?php if($bookingformdata['clubname_admin']==1){echo "*";} ?> <?php if($this->session->flashdata('validationErrorMessageForClubname')){  echo $this->session->flashdata('validationErrorMessageForClubname');} ?></label>	
								<input type="text" class="form-control" name="publicInfoPeriod" value="<?php if(!empty($allPostData['publicInfoPeriod'])){echo $allPostData['publicInfoPeriod'];}else { echo $bookingData['public_info'];}?>" id="publicInfoPeriod" >
							</div>
							<input class="d-none" type="checkbox" id="type" name="type" value="1" checked>
							<input class="d-none" type="checkbox" id="type" name="closed" value="<?php if($bookingData['typeID']=='4'){echo '4';}else { echo '1';}?>" checked>
							<input class="d-none" type="checkbox" id="allowFormToSubmitAndNeverMindConflicts2" name="allowSave" value="0" checked>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label>Kontaktisik <?php if($this->session->flashdata('validationErrorMessageContactPerson')){  echo $this->session->flashdata('validationErrorMessageContactPerson');} ?> </label>
								<input type="text" class="form-control" name="contactPersonPeriod" id="contactPersonPeriod"  value="<?php if(!empty($allPostData['contactPersonPeriod'])){echo $allPostData['contactPersonPeriod'];}else {echo $bookingData['c_name'];}?>">
							</div>
						</div>
						<div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								<label>Telefon<?php if($bookingformdata['phone_admin']==1){echo "*";} ?>  <?php if($this->session->flashdata('phoneIsNotCorrect')){  echo $this->session->flashdata('phoneIsNotCorrect');} ?></label>
								<input type="text" class="form-control" name="phonePeriod" id="phonePeriod" value="<?php if(!empty($allPostData['phonePeriod'])){echo $allPostData['phonePeriod'];}else {echo $bookingData['c_phone'];}?>">
							</div>

							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label>Email<?php if($bookingformdata['email_admin']==1){echo "*";} ?>  <?php if($this->session->flashdata('emailIsNotCorrect')){  echo $this->session->flashdata('emailIsNotCorrect');} ?></label>
								<input type="text" class="form-control" name="emailPeriod" id="emailPeriod" value="<?php if(!empty($allPostData['emailPeriod'])){echo $allPostData['emailPeriod'];}else {echo $bookingData['c_email'];}?>">
							</div>
						</div>

						<h4 class="pt-2 txt-xl px-md-5 mx-md-5 ml-3">Sündmus / Asukoht ja treeningu tüüp</h4>
						 <div class="row d-flex mt-4 px-md-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								<label for="sport_facilityPeriod">Asutus</label>
								<input id="sport_facilityPeriod" class="form-control" list="asutus" disabled value="">
								<!-- <datalist id="asutus">
                            <?php //foreach ($buildings as $each) {
							// echo '<option data-value="' . $each->id . '" value="' . $each->name . '"></option>';
							//}
							?>
                            </datalist> -->
							</div>

							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5">
								<label for="room">Ruum</label>
								<select id="roomID" name="roomID"  class="form-control arrow" >
								
								<option >Vali ruum</option>
								
									<?php 
								
									foreach ($rooms as $each) {
										if( $each==$selectedRoom[0]){
											echo '<option selected  value="' . $each['id'] . '">' . $each['roomName'] . '</option>';
										}	
										else{								
											echo '<option value="' . $each['id'] . '">' . $each['roomName'] . '</option>';
										}
									}
										   ?>
										 
                                </select>
								<!-- <input type="text" class="form-control" name="roomID" id="selectedroomPeriod" value="" disabled> -->
							</div>
						</div>

						<div class="row d-flex p-md-0 mt-4 px-md-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-md-0 pl-md-0 pr-md-5">
								<label>Sündmus / Treeningu tüüp (avalik info)<?php if($bookingformdata['type_admin']==1){echo "*";} ?> <?php if($this->session->flashdata('type_flash')){  echo $this->session->flashdata('type_flash');} ?></label>
								<input type="text" class="form-control" name="workoutTypePeriod" id="workoutTypePeriod" value="<?php if(!empty($allPostData['workoutTypePeriod'])){echo $allPostData['workoutTypePeriod'];}else {echo $bookingData['workout'];}?>">
							</div>
							<div class="form-label-group col-12 col-md-6 p-md-0 pl-md-5"></div>
						</div>

						<h4 class="pt-2 txt-xl px-md-5 mx-md-5 ml-3">Nädalapäev ja kellaaeg</h4>
						<div class="mt-4 bg-grey py-2">
                            <div class="form-label-group px-md-5 mx-md-5"  id="InputsWrapper1">
                                <div class="row d-flex justify-content-between m-0 px-0 pt-0 pb-1">
									<label class="d-hidden col-1 mr-1 p-0"></label>
                                </div>
                                <div id="dateContainer">
									<div class="row d-flex align-items-center mb-3 justify-content-between">
									<div class="col-md-5 col-9 m-0 p-md-0">
										<label class="col-5 m-0 p-0" for="sport_facility2">Nädalapäev</label>
										<input class="form-control col-11 arrow" id="sport_facility2" list="weekdays" name="weekday[]">

										<datalist id="weekdays">
											<option data-value="1" value="Esmaspäev"></option>
											<option data-value="2" value="Teisipäev"></option>
											<option data-value="3" value="Kolmapäev"></option>
											<option data-value="4" value="Neljapäev"></option>
											<option data-value="5" value="Reede"></option>
											<option data-value="6" value="Laupäev"></option>
											<option data-value="7" value="Pühapäev"></option>       
                                            </datalist>
											
											</div>
											
											<div class="col-4 col-md-2">
											<label class="col-2 m-0 p-md-0" for="from1">Alates</label>
												<input type="text" class="clock form-control" name="timesStart" data-minimum="06:00" data-maximum="22:00" id="from1" value="">
											</div>
											
											<div class="col-4 col-md-2">
											<label class="col-2 m-0 p-0" for="until1">Kuni</label>
											<input type="text" class="clock form-control" name="timeTo" data-minimum="06:00" data-maximum="22:00" id="until1" value="">
										</div> 
										<div class="col-4 col-md-2">
											<label class="col-2 m-0 p-0" for="color">Värv</label>
                                            <input type="color" id="periodWorkoutColor" class="form-control" name="color" value="#ffffff"  list="presetColors">
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
											} 
												?>	
											</datalist>
										</div>                                  
                                    </div>
                                </div>

                             
                            </div>
                        </div>

					
						<div class="mt-3 px-md-5 ml-2 mx-md-5">
							<div class="form-label-group pb-2 px-0 mr-2">
								<table  id="myTablePeriod" class="table table-borderless">
									<thead>
										<tr>
											<th colspan="4" class="txt-regular txt-lg p-0">Korraga muudetakse kõiki alljärgnevaid aegu ja värve</th>
										
								
											<th class="p-0"></th>
											<th class="p-0"></th>
										</tr>
									</thead>
									<tbody>
										<tr class="bg-blue mb-5">
											<td id="month" class="text-white txt-regular td-width-l p-1">Kuupäev</td>
											<td id="blank" class="text-white txt-regular td-width-m p-1">Aeg</td>
											<td id="blank" class="text-white txt-regular td-width-m p-1">Värv</td>
											<td id="blank" colspan=3 class="text-white txt-regular td-width-m p-1"></td>
										
										</tr> <br/>
										<!-- Genereerib automaatselt -->
									</tbody>
								</table>
								<!-- <div class="row d-flex col-5 p-0">
                            <a id="addTimes" class="btn btn-custom text-white text-center py-2 px-4 pluss"><p class="m-0 px-0 txt-lg text-center align-items-center">Lisa veel üks kuupäev</p></a>
                        </div> -->

							</div>
						</div>


						<h4 class="mt-5 txt-xl px-md-5 mx-md-5 ml-3">Lisainfo (valikuline) </h4>
					    <div class="mt-3 px-md-5 ml-2 mx-md-5">
                            <div class="form-label-group pb-2 px-0">
								<label>Lisainfo</label>
								<textarea class="form-control" id="additionalPeriod" name="additionalCommentPeriod" rows="3"><?php if(!empty($allPostData['additionalCommentPeriod'])){echo $allPostData['additionalCommentPeriod'];}else {echo $bookingData['comment'];}?></textarea>
								<br/>
								<label>Muutmise põhjus</label>
								<input class="form-control" id="reason" name="reason" rows="3">
							</div>
						</div>
						
						<input class="d-none" type="hidden" name="weekendNumber" id="weekendNumber" value="">
						<input class="d-none" type="hidden" name="BookingID" id="BookingID" value="<?php echo $_POST['BookingID']; ?>">
						<input class="d-none" type="hidden" name="isPeriodic" id="isPeriodic" value="<?php echo $_POST['isPeriodic']; ?>">
						<!-- <input class="d-none" type="hidden" name="roomID" id="roomIDPeriodic" value="<?php echo $bookingData['roomID'];?>"> -->

						<div class="row d-flex justify-content-end mt-5 px-md-5 mx-md-5">
							<a class="txt-xl link-deco align-self-center py-0 pr-5 mr-2" href="<?php echo base_url(); ?>fullcalendar?roomId=<?php echo $bookingData['roomID'];?>">Katkesta</a>
							<input type="submit" id="changePeriodTimes" class="btn btn-custom col-12 col-sm-4 text-white txt-xl" value="Salvesta muudatused">
							<button id="loadingTemporarlyButton" class="d-none btn btn-custom text-white txt-xl" type="button" disabled>
							<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
							Kontrollin kattuvusi...
							</button>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<?php $arr2 = array();
foreach ($_POST['timesIdArray'] as $key => $value) {
	$arr2[] = $value;
}; ?>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datepicker.js"></script>
<script>
	$(document).ready(function() {
		var URLdate="";
		var isPeriodic = '<?= $_POST['isPeriodic']; ?>';
		$('input[id=sport_facility2]').focusin(function() {
            $('input[id=sport_facility2]').val('');           
        });

		var FieldCount = $('#myTable tbody tr').length;
		FieldCount++;
		//  console.log(FieldCount);

		$('#addTimes').click(function(e) {
			//max input box allowed
			FieldCount++;
			//add input box
			$('#myTable > tbody').append('<tr> <td class="td-width-l"></td><td class="td-width-m pl-3"><span class="removeclass col-1 pl-1 pr-5"><span class="icon-cancel"></span></span></td><td class="td-width-s pl-3"><input class="addTimes datePicker form-control p" id="date_' + FieldCount + '" data-toggle="datepicker" name="additionalBookingDate[]"  value="<?php echo date("d.m.Y");?>"></td><td class="td-width-s pl-3"><input type="text" class="addTimes clock form-control" name="additionalBookingtimeStart[]" data-minimum="06:00" data-maximum="22:00" id="timestartfield_' + FieldCount + '"value="<?php echo date("H:i", round( time() / (15 * 60)) * (15 * 60)); ?>"></td>  <td class="td-width-s pl-3"><input type="text" class="addTimes clock form-control" name="additionalBookingtimeEnd[]" data-minimum="06:00" data-maximum="22:00" id="timeendfield_' + FieldCount + '"value="<?php echo date("H:i", 5400+ round( time() / (15 * 60)) * (15 * 60)); ?>"></td><td class="pl-3"><input type="color" id="periodWorkoutColor" class="form-control" name="addcolor[]" value="#ffffff"  list="presetColors"></td></tr>');
			var once = false;
			$('.datePicker, .clock').focusin(function() {
				if (once == false) {
					$(".addTimes.datePicker").datepicker({
						language: "et-EE",
						autoHide: true,
						date: new Date(),
						autoPick: true
					});

					$('.addTimes.clock').clockTimePicker({
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
				}
				once = true;
			});


		});
		$(document).on('click', '.removeclass', function(e) {


			$(this).parent().parent().remove(); //remove text box
		});




		var today = new Date();
		var endOfPeriond = new Date('05/31/' + new Date().getFullYear());

		var dateToShow = '';
		if (today < endOfPeriond) {
			dateToShow = endOfPeriond;

		} else {
			dateToShow = new Date(endOfPeriond.setFullYear(endOfPeriond.getFullYear() + 1));;
		};




		//   var eventToModificate = "<?php echo base_url(); ?>edit/load2/<?php echo $_POST['BookingID']; ?>";
		var resConflicts = [];
		var res2Conflicts = [];
		var ConflictID = [];
		var publicInfo = [];
		var counter = 0;

		// var urltoload =  "<?php echo base_url(); ?>fullcalendar/load/1";
		// console.log(urltoload+" konfliktid");
		var datafrom = ['<?= implode("', '", $arr2) ?>'];


		$.post("<?php echo base_url(); ?>edit/load2/<?php echo $allPostData['BookingID']; ?>",
			function(data) {
				var res = $.parseJSON(data);
			
			
				
				var days = ['P', 'E', 'T', 'K', 'N', 'R', 'L'];
				var weekDays= ['Pühapäev', 'Esmaspäev', 'Teisipäev', 'Kolmapäev', 'Neljapäev', 'Reede', 'Laupäev'];
				var conflicts = "";



				//   console.log(datafrom);
				for (var i = 0, l = res.length; i < l; i++) {
					var obj = res[i];
			
					
					$('#selectedroomPeriod').val(obj.roomName);
					$('#sport_facility').val(obj.building);
					$('#sport_facilityPeriod').val(obj.building);
				
					var BTimesid = obj.timeID;

					var start = obj.start;
					var end = obj.end;
					//   console.log(start+' '+end);

					var eventColor=obj.color;
				
					if(!eventColor){
						eventColor='#ffffff';
					}
					
					

					var n = datafrom.indexOf(BTimesid)!== -1;
				
					if(n && !URLdate){
						URLdate=start;
					}	

					//    console.log(BTimesid);
					if(isPeriodic==1){
						if(n){
							var weekDayToChange=new Date(obj.start).getDay();
							var startPeriodTime=moment(start).format("HH:mm");
							var endPeriodTime=moment(end).format("HH:mm");
							$('#sport_facility2').val(weekDays[weekDayToChange]);
							$('#weekendNumber').val(weekDayToChange);
							
							$('#from1').val(startPeriodTime);
							$('#until1').val(endPeriodTime);
							$('#periodWorkoutColor').val(eventColor);
							
					
						};
						if(weekDayToChange==new Date(obj.start).getDay()){
						
							//kui ajad klapivad, siis pane muutmisvaatesse
							if(startPeriodTime==moment(start).format("HH:mm")&&endPeriodTime==moment(end).format("HH:mm")){
						

							$('#myTablePeriod > tbody').append(' <tr id="' + BTimesid + '"> <td class="td-width-l"><b>' + days[new Date(start).getDay()] + '</b>,&nbsp;' + moment(start).format("DD.MM.YYYY") + '</td><td class="td-width-s pl-3">&nbsp;&nbsp;' + moment(start).format("HH:mm") + '&#8209;' + moment(end).format("HH:mm") + '</td>'+
							'<td class="td-width-m pl-3"> <input type="color" id="periodWorkoutColor"  name="color" value="' + eventColor + '" disabled ></td><td class="td-width-s pl-3"></td><td class="td-width-s pl-3"><input class="d-none" type="hidden" name="timesIdArray[]"  value="' +BTimesid +'"></td>  <td class="td-width-s pl-3" >	<input class="d-none" type="hidden" name="bookingtimesFrom[' + counter + ']"  value="' + moment(start).format("DD.MM.YYYY") +'"></td></tr>');
								resConflicts.push(start.replace('T', ' ').substring(0, 16));
								res2Conflicts.push(end.replace('T', ' ').substring(0, 16));
								ConflictID.push(obj.timeID);
								publicInfo.push(obj.title);
								counter++;
							

							}
							//siia tuleb panna seda, millised päevade mustrid ei kattu
							else{
								
						
							}



						}

					
					}
					else if (n) {
						//     console.log(i);
						$('#myTable > tbody').append(' <tr id="' + BTimesid + '"> <td class="td-width-l"><b>' + days[new Date(start).getDay()] + '</b>,&nbsp;' + moment(start).format("DD.MM.YYYY") + '</td><td class="td-width-m">&nbsp;&nbsp;' + moment(start).format("HH:mm") + '&#8209;' + moment(end).format("HH:mm") + '</td><td class="td-width-s pl-3"><input class="datePicker form-control p" id="time_' + BTimesid + '" data-toggle="datepicker" name="bookingtimesFrom[' + counter + ']"  value="' + moment(start).format("DD.MM.YYYY") + '"></td><td class="td-width-s pl-3"><input type="text" class="clock form-control" name="timeStart[]" data-minimum="06:00" data-maximum="22:00" id="timestartfield' + i + '" value="' + moment(start).format("HH:mm") + '"></td>  <td class="td-width-s pl-3"><input type="text" class="clock form-control" name="timeEnd[]" data-minimum="06:00" data-maximum="22:00" id="timeendfield_' + i + '" value="' + moment(end).format("HH:mm") + '"></td><td class="pl-3"><input name="color[]" type="color" class="form-control" value="'+eventColor+'"  list="presetColors"><datalist id="presetColors">	<?php if(isset($bookingformdata['color1'])){echo "<option>".$bookingformdata['color1']."</option>";echo "<option>".$bookingformdata['color2']."</option>";echo "<option>".$bookingformdata['color3']."</option>";echo "<option>".$bookingformdata['color4']."</option>";echo "<option>".$bookingformdata['color5']."</option>";echo "<option>".$bookingformdata['color6']."</option>";echo "<option>".$bookingformdata['color7']."</option>";echo "<option>".$bookingformdata['color8']."</option>";} else {echo "<option>#ffffff</option><option>#ddffee</option><option>#cceeff</option><option>#ffccee</option><option>#ffffcc</option><option>#aaffaa</option><option>#eeffff</option><option>#f6e5ff</option>";}	?>	</td></tr>'); 
						resConflicts.push(start.replace('T', ' ').substring(0, 16));
						res2Conflicts.push(end.replace('T', ' ').substring(0, 16));
						ConflictID.push(obj.timeID);
						publicInfo.push(obj.title);
						counter++;

					}
				

				}
				console.log(counter);

				var conflictTimes=<?php echo $conflictTimes; ?>;
				
				conflicts = conflictTimes;
				// console.log(conflictTimes);
				// console.log(res);
				for (var i = 0, l = conflicts.length; i < l; i++) {
					var conflicts2 = conflicts[i];
					// console.log(conflicts2.start+" - "+conflicts2.end + " "+ i);
					var startDateTime =  moment(conflicts2.start.substring(0, 16)).toDate(); //yyyy-mm-dd hh:tt
					var endDateTime =  moment(conflicts2.end.substring(0, 16)).toDate();
					var timeIDofConflict = conflicts2.timeID;
					var titleIDofConflict = conflicts2.title;
					
					
					
					//	console.log(roomID);
					//   console.log(timeIDofConflict); 
					
					// iga selle aja kohta tuleb kontrollida ajaxi aega"
					for (var t = 0; t < resConflicts.length; t++) {
						var checkDateTime = moment(resConflicts[t]).toDate(); //magic date
						var checkDateTime2 = moment(res2Conflicts[t]).toDate(); //magic date
						
					
						
						if (ConflictID[t] !== timeIDofConflict) {
						
							if (checkDateTime < endDateTime && checkDateTime2 > startDateTime) {
								//   console.log(checkDateTime +" - "+ checkDateTime2 + " nende vastu "+ startDateTime+ " " +endDateTime);// 
								//   console.log("tingumus on täidetud " + resConflicts[t] + " või "+res2Conflicts[t]);
								$('#myTable #' + ConflictID[t]).after("<tr class='m-0 p-0'><td colspan='5' class='conflict txt-regular'><img src='<?php echo base_url(); ?>assets/img/warning.svg'> Kattuv aeg: " + moment(conflicts2.start).format('HH:mm') /*conflicts2.start.substring(0, 16) */ + "–" + moment(conflicts2.end).format('HH:mm') + " " /*conflicts2.end.substring(0, 16)*/ + titleIDofConflict + "</td></tr>");
								//   console.log( ConflictID[t] +" ning " +timeIDofConflict);
								$('#myTablePeriod #' + ConflictID[t]).after("<tr class='m-0 p-0'><td colspan='5' class='conflict txt-regular'><img src='<?php echo base_url(); ?>assets/img/warning.svg'> Kattuv aeg: " + moment(conflicts[i].start).format('HH:mm') /*conflicts2.start.substring(0, 16) */ + "–" + moment(conflicts[i].end).format('HH:mm') + " " /*conflicts2.end.substring(0, 16)*/ + conflicts[i].title + "</td></tr>");
						
								$('#myTable #' + ConflictID[t]).find('.clock.form-control, .datePicker.form-control').css('border', '1px solid #9E3253')

							}
						}

					
					};


				}


				var once = false;
				$('.datePicker, .clock').focusin(function() {
					if (once == false) {
						$(".datePicker").datepicker({
							language: "et-EE",
							autoHide: true,
							date: new Date(),
							autoPick: false
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
					}
					once = true;

				});


				//alert(res.values());
				//  console.log(Object.prototype.toString.call(res));
			});



		$("#changeTimes").on('click', function(event) {

	
		$(this).hide();
	
	
		$("#loadingTemporarlyButtonOnce").removeClass('d-none');
		$("#loadingTemporarlyButtonOnce").html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Kontrollin kattuvusi...');

		var bookingID = '<?= $_POST['BookingID'] ?>';
		//	console.log(bookingID);
			var datafrom = ['<?= implode("', '", $arr2) ?>'];
			var myForm = document.getElementById('change');

			datafrom.forEach(function(value) {
				var hiddenInput = document.createElement('input');

				hiddenInput.type = 'hidden';
				hiddenInput.name = 'timesIdArray[]';
				hiddenInput.value = value;

				myForm.appendChild(hiddenInput);
			});

			var hiddenInput = document.createElement('input');
			hiddenInput.type = 'hidden';
			hiddenInput.name = 'BookingID';
			hiddenInput.value = bookingID;
			myForm.appendChild(hiddenInput);
			$("#changeTimes").on('click', function(event) {

				$('#change').submit();


			});
		//	event.preventDefault();
	

		});


		$("#changePeriodTimes").on('click', function(event) {
			$(this).hide();
	
			$("#loadingTemporarlyButton").removeClass('d-none');
			$("#loadingTemporarlyButton").html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Kontrollin kattuvusi...');

			var bookingID = '<?= $_POST['BookingID'] ?>';
		//	console.log(bookingID);
		
			var myForm = document.getElementById('changePeriod');
			var hiddenInput = document.createElement('input');
			hiddenInput.type = 'hidden';
			hiddenInput.name = 'BookingID';
			hiddenInput.value = bookingID;
			myForm.appendChild(hiddenInput);
		

		});

		$( "#submitWithConflicts" ).click(function() {
		
			var whichFormToSubmit='<?php echo $_POST['isPeriodic']; ?>';		
			var bookingID = '<?= $_POST['BookingID'] ?>';
		//	console.log(bookingID);
			var datafrom = ['<?= implode("', '", $arr2) ?>'];
			var myForm = document.getElementById('change');

			datafrom.forEach(function(value) {
				var hiddenInput = document.createElement('input');

				hiddenInput.type = 'hidden';
				hiddenInput.name = 'timesIdArray[]';
				hiddenInput.value = value;

				myForm.appendChild(hiddenInput);
			});

			var hiddenInput = document.createElement('input');
			hiddenInput.type = 'hidden';
			hiddenInput.name = 'BookingID';
			hiddenInput.value = bookingID;
			myForm.appendChild(hiddenInput);
			$("#changeTimes").on('click', function(event) {

				$('#change').submit();


			});
		//	event.preventDefault();
		// 	if (whichFormToSubmit==0){
		// 		$( "#allowFormToSubmitAndNeverMindConflicts1" ).val("1");
		// //		$('#change').submit();
		// 	//	
		// 	window.location.href = "<?php echo base_url(); ?>fullcalendar?roomId=<?php echo $bookingData['roomID'];?>"+'&date='+moment(URLdate).format("DD.MM.YYYY");
		// 	}
		// 	else if (whichFormToSubmit==1 ){
		// 		$( "#allowFormToSubmitAndNeverMindConflicts2" ).val("1");
		// 		console.log(moment(URLdate).format("DD.MM.YYYY"))
		// 	//	window.location.href = "<?php echo base_url(); ?>fullcalendar?roomId=<?php echo $bookingData['roomID'];?>"+'&date='+moment(URLdate).format("DD.MM.YYYY");
		// 	//	$( "#changePeriod" ).submit();

		// 	}
		
			window.location.href = "<?php echo base_url(); ?>fullcalendar?roomId=<?php echo $bookingData['roomID'];?>"+'&date='+moment(URLdate).format("DD.MM.YYYY");
			});


		
		var allConflictsFromBE=JSON.stringify(<?php echo json_encode($conflictDates);?>);

		if(hasJsonStructure(allConflictsFromBE)){
			//console.log(allConflictsFromBE);

			var conflict = JSON.parse(allConflictsFromBE);
			conflict.forEach(function(item) {
			// console.log(item.public_info+":  "+item.startTime+"-"+item.endTime);
			var days=['Pühapäev', 'Esmaspäev', 'Teisipäev', 'Kolmapäev', 'Neljapäev', 'Reede', 'Laupäev'];
				$('#myModalTable > tbody:last-child').append('<tr><td>'+days[new Date(item.startTime.substring(0, 10)).getDay()]+'</td><td>'+moment(item.startTime.substring(0, 10), "YYYY-MM-DD").format("DD.MM.YYYY")+'</td><td>'+ item.startTime.substring(11, 16)+"-"+item.endTime.substring(11, 16)+'</td><td>'+item.workout+'</td><td>'+item.public_info+'</td></tr>');
			
				});
				$('#myModal').modal('show')
		}




	});

	$(".nav a").on("click", function() { // TAB'i active klassi toggle
        $(".nav a").removeClass("active");
        $(this).addClass("active");
	});

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

	
</script>
