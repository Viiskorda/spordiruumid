
<?php if(!isset($rooms)){
			$this->session->set_flashdata('access_deniedToUrl', 'Sellist ruumi ei eksisteeri. Teid suunati avalehele');
			redirect('');
		}

		?>
<div class="container">
	<div class="row pt-2" id="widthToggle">
		
		<form class="row  d-flex flex-row col-sm-12 col-lg-7 col-xl-8 p-0" action="fullcalendar" method="get">
		<div class="form-label-group col-sm-5 col-md-3 col-lg-4 col-xl-2 p-0 mr-2">
						<label for="activity">Tegevus</label>
						<input id="activity" list="activities" class="form-control arrow" type="text" autocomplete="off">
						<datalist id="activities">
								<option  data-value="0" value="---"></option>
							<?php foreach ($activities as $row) {
								echo '<option  data-value="' . $row->activityID . '" value="' . $row->activityName . '"></option>';
							}
							?>
						</datalist>
					</div>
			<?php if ($this->session->userdata('roleID') != '2' && $this->session->userdata('roleID') != '3') : ?>
				<div class="form-label-group col-sm-6 col-md-4 col-lg-6 col-xl-2 p-0 mr-2">

					<label for="region">Piirkond</label>
					<input id="region" list="regions" class="form-control arrow" type="text" value="<?php echo $rooms['regionName'];?>"  autocomplete="off">
					<datalist id="regions">
						<option  data-value="0" value="---"></option>
						<?php foreach ($regions as $row) {
							echo '<option  data-value="' . $row->regionID . '" value="' . $row->regionName . '"></option>';
						}
						?>
					</datalist>
					<input type="hidden" id="roomId" name="roomId" value="roomId" />
				</div>

				<div class="form-label-group col-sm-5 col-md-4 col-lg-5 col-xl-3 p-0 mr-2">
					<label for="sport_facility">Asutus</label>
					<input id="sport_facility" list="asutus" class="form-control arrow" value="<?php echo $rooms['name']; ?>"  autocomplete="off">
					<datalist id="asutus">
					<option  data-value="0" value="---"></option>
						<?php foreach ($sportPlaces as $each) {
							if ($rooms['regionID'] == $each->regionID) {
								echo '<option data-value="' . $each->id . '" value="' . $each->name . '"></option>';
							}
						}
						?>
					</datalist>
					<input type="hidden" id="roomId" name="roomId" value="roomId" />
				</div>
			<?php endif; ?>


			<div class="form-label-group col-sm-6 col-md-3 col-lg-3 col-xl-2 p-0 mr-2">
				<label for="room">Ruum</label>
				<input id="room" list="saal" class="form-control arrow" value="<?php echo $rooms['roomName'];?>"  autocomplete="off">
				<datalist id="saal">
					<?php foreach ($sportPlacesToChoose as $each) {
						if ($this->session->userdata('roleID') == '2' or $this->session->userdata('roleID') == '3') {
							if ($this->session->userdata('building') == $each->buildingID) {
								if ($each->roomActive==0){
									echo '<option data-value="' . $each->id . '" value="' . $each->roomName . ' (peidetud)"></option>';
								}else{
								echo '<option data-value="' . $each->id . '" value="' . $each->roomName . '"></option>';
							}
							}
						} elseif ($rooms['id'] == $each->buildingID) {
							if ($each->roomActive==0){
								echo '<option data-value="' . $each->id . '" value="' . $each->roomName . ' (peidetud)"></option>';
							}else{
							echo '<option data-value="' . $each->id . '" value="' . $each->roomName . '"></option>';
						}
						}
					}
					?>
				</datalist>
				<input type="hidden" id="roomId" name="roomId" value="roomId" />
			</div>

			<div class="form-label-group  col-md-3 col-lg-3 col-xl-2 p-0  mr-2">
				<label for="datepicker">Kuupäev</label>
				<input id="datepicker" class="datePicker form-control" data-toggle="datepicker" name="date" autocomplete="off" />
			</div>
		</form>


		<?php if ($this->session->userdata('roleID') === '2' || $this->session->userdata('roleID') === '3') { ?>
			<div class="ml-md-auto col-sm-8 col-lg-4 col-xl-4 mr-auto p-0">
				<div class="col-1  col-sm-12 col-xl-2">	</div>
				<a id="allCalenderLink" class="text-center py-2 px-sm-2 px-lg-4 px-md-4 float-right pluss" href="<?php echo base_url(); ?>/allbookings/weekView/?date=">Kõik ruumid</a>
				<a class="col-12 col-lg-7 col-xl-6 col-sm-8 btn btn-custom text-white text-center py-2 px-sm-2 px-lg-4 px-md-4 float-right pluss" href="<?php echo base_url(); ?>booking/create/">
					<p class="m-0 txt-lg txt-strong text-center">Uus broneering</p>
				</a>
			<?php  } elseif ($this->session->userdata('session_id') === TRUE) { ?>
				<!-- <div class="col-2 mr-auto p-0">
					<a class="btn btn-custom text-white text-center py-2 px-sm-2 px-lg-5 px-md-4 float-right pluss" href="<?php echo base_url(); ?>booking/create/<?php echo ($this->input->get('roomId')); ?>">
						<p class="m-0 txt-lg txt-strong text-center">Esita päring</p>
					</a> -->

					
					<div class="ml-auto col-lg-3 p-0 col-md-12 bg-blue info-label text-white px-3 py-2">
						<p class="txt-strong">Broneerimiseks kirjuta või helista:</p>
						<p><?php if($rooms['contact_email']){echo $rooms['contact_email'].', ';} echo $rooms['phone']; ?></p>
						<a  class="text-light" href="<?php echo $rooms['price_url']; ?>"  target="_blank"><?php echo $rooms['price_url']; ?></a>
				<?php }; ?>

				<?php if (!$this->session->userdata('session_id')) { ?>
		
					<div class="ml-auto col-lg-3 p-0 col-md-12 bg-blue info-label text-white px-3 py-2">
						<p class="txt-strong">Broneerimiseks kirjuta või helista:</p>
						<p><?php if($rooms['contact_email']){echo $rooms['contact_email'].', ';} echo $rooms['phone']; ?></p>
						<a  class="text-light" href="<?php echo $rooms['price_url']; ?>"  target="_blank"><?php echo $rooms['price_url']; ?></a>
					<?php }; ?>
					</div>


				</div>
				<!-- KALENDER CONTAINERIS START -->
				<div id="calendar-container">
					<div id='calendar'></div>
				</div>
				<br/>

				<!-- KALENDER CONTAINERIS END -->
			</div>
			<!-- ****************** MODAL START ****************** -->

			<?php if ($this->session->userdata('roleID') === '2' || $this->session->userdata('roleID') === '3') : ?>
				<!-- Modal -->
				<div class="modal left" id="lefty" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">

							<div id="modal-header" class="modal-header d-flex justify-content-between pl-4 py-0 m-0 bg-grey border-bottom">
								<div>
									<h4 class="modal-title text-darkblue txt-xl m-0 py-3" id="myModalLabel">
										<input type="text" class="d-none" name="c_name" value="" id="c_name">
									</h4>
								</div>
								<div>
									<p class="modal-time text-darkblue txt-xl m-0 py-3"></p>
								</div>
								<div>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								</div>
							</div>



							<div class="modal-body m-0 p-0">
								<?php echo form_open(site_url("calendar/edit_event"), array("class" => "form-horizontal")) ?>
								<div class="d-flex pl-4 py-2 bg-grey border-bottom">
									<p class="pr-2 m-0 text-darkblue">Päring:</p>
									<p class="m-0 text-darkblue" id="time"></p>
									<input type="text" class="form-control d-none m-0" name="created_at" id="created_at">
								</div>
								<div class="accordion px-4">
									<div class="accordion-item">
										<a class="txt-xl text-darkblue active py-2">Broneeringu info</a>
										<div id="contact" class="content active p-0 m-0">
											<div class="remove">
												<p class="pt-2 txt-regular">Kontakt</p>
												<div class="d-flex justify-content-between p-0 m-0  remove">
													<div class="col-6 p-0 m-0">
														<p>Klubi nimi</p>
													</div>
													<div class="col-6 p-0 m-0">
														<p id="clubname"></p>
														<input type="text" class="d-none" name="clubname" id="clubname">
													</div>
												</div>
												<div class="d-flex justify-content-between p-0 m-0 remove">
													<div class="col-6 p-0 m-0">
														<p>Kontaktisik</p>
													</div>
													<div class="col-6 p-0 m-0">
														<p id="c_name"></p>
														<input type="text" class="d-none" name="c_name" id="c_name">
													</div>
												</div>
												<div class="d-flex justify-content-between p-0 m-0 remove">
													<div class="col-6 p-0 m-0">
														<p>Telefoni number</p>
													</div>
													<div class="col-6 p-0 m-0">
														<p id="phone"></p>
														<input type="text" class="d-none" name="phone" id="phone">
													</div>
												</div>
												<div class="d-flex justify-content-between remove">
													<div class="col-6 p-0 m-0">
														<p>Email</p>
													</div>
													<div class="col-6 p-0 m-0">
														<p id="email"></p>
														<input type="text" class="d-none" name="email" id="email">
													</div>
												</div>
											</div>

											<p id="saal" class="pt-3 txt-regular">Asukoht ja sündmus / treeningu tüüp</p>
											<div class="remove">
												<div class="d-flex justify-content-between remove2">
													<div class="col-6 p-0 m-0">
														<p>Asutus</p>
													</div>
													<div class="col-6 p-0 m-0">
														<p id="building"></p>
														<input type="text" class="d-none" name="building" id="building">
													</div>
												</div>
											</div>
											<div class="d-flex justify-content-between">
												<div class="col-6 p-0 m-0">
													<p>Ruum</p>
												</div>
												<div class="col-6 p-0 m-0">
													<p id="selectedroom"></p>
													<input type="text" class="d-none" name="selectedroom" id="selectedroom">
												</div>
											</div>
											<div class="remove">
												<div class="d-flex">
													<div class="col-6 p-0 m-0">
														<p>Sündmus / Treeningu tüüp</p>
													</div>
													<div class="col-6 p-0 m-0">
														<p id="workout"></p>
														<input type="text" class="d-none" name="workout" id="workout">
													</div>
												</div>
											</div>

											<div id="ajad">
												<p class="pt-3 txt-regular">Kuupäev ja kellaajad</p>
												<div class="d-flex justify-content-between">
													<div class="col-6 p-0 m-0">
														<p>Periood</p>
													</div>
													<div class="col-6 p-0 m-0 d-flex">
														<p id="event_in"></p>
														<input type="text" class="d-none" name="event_in" id="event_in">
														<p>–</p>
														<p id="event_out"></p>
														<input type="text" class="d-none" name="event_out" id="event_out">
													</div>
												</div>
											</div>

											<p id="info" class="pt-3 txt-regular">Lisainfo</p>
											<p id="comment" class="pb-4"></p>
											
											<div class="d-flex justify-content-between">
											<div class="form-group p-0 col-10">
												<label for="exampleInputPassword1">Kommentaar</label>
												<input type="text"  class="form-control timeComment" name="timeComment" id="timeComment">
											</div>
											<div class="form-check col-2">
										
											Näita	<label class="px-2"><input class="timeCommentShow" type="checkbox" name="timeCommentShow" id="" value=""><span></span></label> 
                 
											</div>
											</div>

										</div>
									</div>
								</div>

								<div class="accordion px-4">
									<div class="accordion-item">
										<a id="countNr" class="txt-xl text-darkblue active py-2">Kõik ajad</a>
										<div id="contact" class="content active p-0 m-0">
											<br>
											<label><input type="checkbox" name="selectAll" id="selectAll" value="1"><span></span></label> VALI KÕIK
											<hr>
											<table id="myTable">
												<tbody>
													<tr class="myTable"></tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<br>
								<div class="accordion px-4">
									<div class="accordion-item">
										<a id="versions" class="txt-xl text-darkblue  py-2">Eelnevad versioonid</a>
										<div id="version" class="content  p-0 m-0">
										
											<table id="versionTable">
												<tbody>
													<tr class="myTable"></tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>

								<?php echo form_close() ?>
								<input type="hidden" name="eventid" id="event_id" value="0" />
							</div>

							<div class="modal-footer d-block m-0  text-sm-center">

								<form id="approveCheck" class="m-0 row justify-content-sm-center">

									<input type="submit" class="btn btn-custom text-white txt-strong btn-width-lg example1 m-1 col-5" value="Kinnita">
								</form>

								<form id="change" method="post" action="<?php echo base_url(); ?>edit/index" class="row justify-content-sm-center m-0 ">
									<input type="hidden" id="dontShow" name="dontShow" value="1">
									<input type="hidden" id="BookingID" name="BookingID" value="">
									<input type="hidden" id="isPeriodic" name="isPeriodic" value="0">
									<input type="submit" id="changeTimes" class="btn btn-info text-white txt-strong btn-width-lg m-1 col-5" value="Muuda valitud">
									<div class="w-100 d-none d-block d-sm-none" ></div>
									<input type="submit" id="changePeriodTimes" class="btn btn-info text-white txt-strong btn-width-lg m-1 col-5" value="Muuda hooajaliselt">
								</form>

								<form id="delete" class="m-0 row justify-content-sm-center ">
									<input type="submit" class="btn btn-delete text-white txt-strong btn-width-lg  m-1 col-5" value="Kustuta" id="deleteChecked" name="deleteChecked">
								</form>

								<form id="takesPlaceCheck" class="m-0 row justify-content-sm-center">
									<input type="submit" class="btn btn-second text-white txt-strong btn-width-lg  m-1 col-5" value="Ei toimunud">
								</form>
							</div>






							<br> <br>
							<!-- </div> -->



						</div>
					</div>


				</div>
				
				
				<?php endif; ?>
			</div><!-- container -->
<!-- ****************** MODAL END ****************** -->

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/locale/et.js?v=1.1"></script>
<script>
	var counter = 0;
	$(document).ready(function() {

		function createDateTime(time, given_date) {
				var splitted = time.split(':');
				if (splitted.length != 2) return undefined;

				var date = new Date(given_date);
				date.setHours(parseInt(splitted[0], 10));
				date.setMinutes(parseInt(splitted[1], 10));
				date.setSeconds(0);
				return date;
			}

		var copyKey = false;
		$(document).keydown(function (e) {
			copyKey = e.shiftKey;
		}).keyup(function () {
			copyKey = false;
		});

		var days = ['P', 'E', 'T', 'K', 'N', 'R', 'L'];
		var displayOrNot = '<?php if($rooms['api_url']) {echo '4';} else echo $this->session->userdata('roleID') ?>';

		var monthNamesForModal = ['Jaanuar', 'Veebruar', 'Märts', 'Aprill', 'Mai', 'Juuni', 'Juuli', 'August', 'September', 'Oktoober', 'November', 'Detsember'];

		//lehe üleval pisike input kalender
		$(".datePicker").datepicker({
			language: "et-EE",
			autoHide: true,
			date: new Date(),
			autoPick: true
		});


		$("#selectAll").click(function() {
			var c = this.checked;
			$(':checkbox.abc').prop('checked', c);
			$('#countNr').text('Kõik ajad (' +$('.abc:checked').length + '/'+ $('#myTable tr:has(td)').length + ')');
		});



		var calendar = $('#calendar').fullCalendar({
		
			header: {
				left: '',
				center: 'prev, title, next',
				right: 'listWeek, month, agendaWeek, agendaDay, agendaList'
			},
			height: 'parent',
			// contentHeight: 600,
			// aspectRatio: 2.2,
			allDaySlot: false,
			firstDay: 1,
			dayNames: ['P', 'E', 'T', 'K', 'N', 'R', 'L'],
		//	dayNames: ['Pühapäev', 'Esmaspäev', 'Teisipäev', 'Kolmapäev', 'Neljapäev', 'Reede', 'Laupäev'],
		//	dayNamesShort: ['P', 'E', 'T', 'K', 'N', 'R', 'L'],
			windowResize: function(view) {
				if ($(window).width() < 768){
					dayNames: ['P', 'E', 'T', 'K', 'N', 'R', 'L'];
				}

			},
			dayNamesShort: ['P', 'E', 'T', 'K', 'N', 'R', 'L'],
			monthNames: ['jaanuar', 'veebruar', 'märts', 'aprill', 'mai', 'juuni', 'juuli', 'august', 'september', 'oktoober', 'november', 'detsember'],
		//	monthNamesShort: ['jaan', 'veebr', 'märts', 'apr', 'mai', 'juuni', 'juuli', 'aug', 'sept', 'okt', 'nov', 'dets'],
			monthNamesShort: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
			views: {
				week: { // name of view
					titleFormat: 'D. MMMM YYYY',
					columnFormat: "dddd, D.MMM"
					// other view-specific options here
				}

			},
			viewRender: (function () {
				return function (view) {
		
					$('.fc-slats tr').css('height', rowHeight + 'px');
					$(window).trigger('resize');
					$(".fc-body").trigger('reload');
				}
			})(),
			defaultDate: moment('<?php if($this->input->get("date")){ echo ($this->input->get("date")); } else{ echo date("dd.mm.YYYY");}?>', "DD.MM.YYYY"),
			defaultView: 'agendaWeek',
			weekNumbers: true,
			slotLabelFormat: 'H:mm',
			timeFormat: 'H:mm',
			snapDuration: '00:05:00',
			slotDuration: '00:30:00',
			minTime: '08:00:00',
			maxTime: '22:00:00',

			//contentHeight:"auto",

			rendering: 'background',
			eventSources: [

				// your event source
				{
					url: "<?php if($rooms['api_url']) {echo $rooms['api_url'];} else { echo base_url(); ?>fullcalendar/load/<?php echo ($this->input->get('roomId'));} ?>" // use the `url` property

					// rendering : 'background'
				},
			

			],
			selectable:  (displayOrNot == 2 || displayOrNot == 3) ? true:false,
			selectHelper: true,
		
			loading: function( isLoading, view ) {
				if(isLoading) {// isLoading gives boolean value
					//show your loader here 
					$("body").css("cursor", "wait");	
				} else {
					//hide your loader here
					$("body").css("cursor", "default");	
				}
     	   },
			locale: 'est',
			lang: 'est',
			eventRender: function(event, element) {
				
				var bookingDuration=diffMinutes($.fullCalendar.formatDate(event.start, 'YYYY-MM-DD HH:m:s'), $.fullCalendar.formatDate(event.end, 'YYYY-MM-DD HH:m:s'));
				var spaceOrnextLine=' ';
				if (!event.isMirror) {
				var tooltipTitle=(event.title) ? '\n'+ event.title : '';
				var tooltipDescription= (event.description) ?  '\n'+ event.description : '';
				var timeComment="";

				//järgmisel real element[0].title tähistab popupi
				element[0].title = $.fullCalendar.formatDate(event.start, "HH:mm") + ' - '+ $.fullCalendar.formatDate(event.end, "HH:mm") +tooltipTitle+ tooltipDescription;
		//	 $(element).tooltip({title: $.fullCalendar.formatDate(event.start, "HH:mm") + ' - '+ $.fullCalendar.formatDate(event.end, "HH:mm") +tooltipTitle+ tooltipDescription , container: "body",  trigger: 'hover'});  
				}
				if (event.description) {
					if(bookingDuration>60){
						spaceOrnextLine='</br>'; // Päringu kirje broneeringu lahtris
					}
					element.find('.fc-time').append(spaceOrnextLine+'<span style="padding-top:4px;padding-right:1px;font-weight:450;font-size:12px">' + event.description + ' </span>');

				} 
				if ((displayOrNot == 2 || displayOrNot == 3) && (event.typeID == 1 || event.typeID == 2)) {
					if(bookingDuration<40){
						$(element).find('.fc-title').html('');
						$(element).find('.fc-time').append(' '+event.title);
					}
					if(event.timeComment && event.showComment){
						timeComment='<span class="d-inline"> &#128172; </span>';
						}
					if(event.takesPlace != 1){
						element.find('.fc-time').before(timeComment+"<del><div class='timequery'>Päring: " + moment(event.created_at).format("DD.MM.YYYY HH:mm") + " </div></del>"); // Päringu kirje broneeringu lahtris
					}
					else{
					element.find('.fc-time').before(timeComment+"<div class='timequery'>Päring: " + moment(event.created_at).format("DD.MM.YYYY HH:mm") + " </div>"); // Päringu kirje broneeringu lahtris
				}
				

				}
				else{
					//see lisab paddingut juurde, kui slot on suurem kui 45 minutit - tavavaates
					if(bookingDuration>45){
						element.find('.fc-time').before("<div class='timequery'></div>"); 
					}
				
				}
				element.css('border-top', '1px solid #DDD');
				element.css('border-right', '1px solid #DDD');
				element.css('border-bottom', '1px solid #DDD');
				if (event.approved == true) {
					element.css('border', '1px solid #DDD');
					element.css('border-left', '7px solid #1A7AB7');

				} else {
					if(  event.id){

					element.find('.fc-content').after('<span class="notice notice-error">Kinnitamata</span>');
					}
					element.css({
						'background': 'repeating-linear-gradient(-45deg, rgba(255, 255, 255, 1), rgba(255, 255, 255, 1) 10px, rgba(245, 245, 245, 1) 10px, rgba(245, 245, 245, 1) 20px)'
					});
					if (isOverlapping(event)) {
						element.css('border-left', '7px solid lightsalmon');
						//  element.find('.fc-content').after('<span class="notice notice-error">Konflikt</span>')

					}

				}
				
				if (event.takesPlace != 1 && event.id) {
					
				//	console.log((event.start).isAfter());
					if((event.start).isAfter()){
						element.find('.fc-content').after('<span class="notice notice-error">Ei toimu</span>');
					}
					else{element.find('.fc-content').after('<span class="notice notice-error">Ei toimunud</span>');}
				}
	

				//kui on suletud
				if (event.typeID == 4) {
					element.find('.fc-time').addClass('d-none');
					element.find('.fc-title').text('Suletud');
					element.css({
						'background': 'repeating-linear-gradient(45deg, rgba(0, 0, 0, 0.05), rgba(0, 0, 0, 0.05) 55px, rgba(0, 0, 0, 0.07) 55px, rgba(0, 0, 0, 0.07) 110px)',
						'border-left': 'none'
					});
					element.find('.fc-content').css({
						'text-align': 'center'
					});
				}
				else if(event.hasChanged==1){
					element.find('.fc-time').before('<span class="timequery"> &#9432; </span>');
				}

			},

			select: function(start, end, allDay) {
				if ( (displayOrNot == 2 || displayOrNot == 3) ){
				var startDate = $.fullCalendar.formatDate(start, "DD.MM.YYYY");
				var start = $.fullCalendar.formatDate(start, "HH:mm");
				var end = $.fullCalendar.formatDate(end, "HH:mm");

				window.location.href = '<?php echo base_url(); ?>booking/create/<?php echo ($this->input->get('roomId')) . "?startDate="; ?>' + startDate + '&start=' + start + '&end=' + end;
			}
			

			},
			editable: (displayOrNot == 2 || displayOrNot == 3) ? true:false,
			
		
			eventResize: function(event) {
			//	console.log(event.end._i);
			//	console.log( $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss"));

				if(!(moment($.fullCalendar.formatDate(event.start, "Y-MM-DD")).isSame($.fullCalendar.formatDate(event.end, "Y-MM-DD")))){
					swal({
						icon: 'info',
						title: "Trenni lõpuaeg peab jääma samale päevale!",
						
					})
					calendar.fullCalendar('refetchEvents');
					event.preventDefault();
					}

					//if ajax is still working then do not go further
					if($.active){
						setTimeout(function(){calendar.fullCalendar('refetchEvents'); }, 200);
					return
					}
				
				swal({
					title:  "Kas salvestada? ",
					buttons: {
					cancel: "Ära salvesta",
					Salvesta: true,
				},
					}).then(function(value){
						if(value){
							swal({
					title: "Muudatus on salvestatud",
					text: "Soovi korral kirjuta põhjendus",
					content: "input",
					buttons: "Salvesta"
					}).then(function(value2) {
			
				$.ajax({
						url:"<?php echo base_url(); ?>fullcalendar/updateEvent",
						type:"POST",
						data: {
							start: $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss"),
							end: $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss"),
							timeID: event.timeID,
							selectedRoomID: event.roomID,
							versionStart: event.start._i,
							versionEnd: event.end._i,
							versionNameWhoChanged: '<?php echo $this->session->userdata('userName');?>',
							reason: value2,

						},
						success:function()
						{
							
							setTimeout(function(){calendar.fullCalendar('refetchEvents'); }, 200);
						
						}
					});
				})
						}
						else{	setTimeout(function(){calendar.fullCalendar('refetchEvents'); }, 200);}
			
				})
				

			},
			
			eventDrop: function(event) {
			
			var checkIfIsAfter8 = moment(event.start).subtract(3,'hour').toDate();
			var checkIfIsBefore22 = moment(event.end).subtract(3,'hour').toDate();
			var startDate = createDateTime("6:00", moment(event.start).toDate() );
			var endDate = createDateTime("23:00", moment(event.start).toDate() );	
			var isBetween = startDate <= checkIfIsAfter8 && checkIfIsAfter8 <= endDate;
			var isBetween2 = startDate <= checkIfIsBefore22 && checkIfIsBefore22 <= endDate;
			
			if(!(isBetween&&isBetween2)){
					swal({
						icon: 'info',
						title: "Trenn peab jääma vahemikku 06:00-22:00!",
						buttons: "sulge"
						
					})
					calendar.fullCalendar('refetchEvents');
					return;
					
			}
			
			//if ajax is still working then do not go further
			if($.active){
					setTimeout(function(){calendar.fullCalendar('refetchEvents'); }, 200);
					return
				}
			
			var eClone = {
					start: $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss"),
					end: $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss"),
					roomID: event.roomID,
					bookingID: event.bookingID,
					color: event.color,
					takesPlace: event.takesPlace,
					approved: event.approved,
					typeID: event.typeID,
					selectedRoomID: event.roomID,
				};
				if (copyKey) {
				
				swal({

					title: "Kas salvestada lisaaega?",
					buttons: [
						'Ei',
						'Jah'
					],
					}).then(function(isConfirm) {
					if (isConfirm) {
						$.ajax({
						url:"<?php echo base_url(); ?>fullcalendar/insert",
						type:"POST",
						data:eClone,
						success:function()
						{
							//console.log( eClone);
							setTimeout(function(){calendar.fullCalendar('refetchEvents'); }, 200);
							$.ajax({
									url: "<?php echo base_url(); ?>fullcalendar/getUnapprovedBookings",
									success: function(res) {
									$('.badge.badge-danger').text(res);
								}
								});
						
						}
					})
					} else {
						calendar.fullCalendar('refetchEvents');
						return;
					}
					});
			//	event.preventDefault();
		
			
			}else{
		
				swal({
					title:  "Kas salvestada? ",
					text: "*Lisaaja salvestamiseks hoia lohistamise ajal SHIFT klahvi all",
					buttons: {
					cancel: "Ära salvesta",
					Salvesta: true,
				},
					}).then(function(value) {
					if(value){
						swal({
						title: "Muudatus on salvestatud",
						text: "Soovi korral kirjuta põhjendus",
						content: "input",
						buttons: "Salvesta"
					}).then(function(value2) {
			
						$.ajax({
								url:"<?php echo base_url(); ?>fullcalendar/updateEvent",
								type:"POST",
								data: {
									start: $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss"),
									end: $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss"),
									timeID: event.timeID,
									selectedRoomID: event.roomID,
									versionStart: event.start._i,
									versionEnd: event.end._i,
									versionNameWhoChanged: '<?php echo $this->session->userdata('userName');?>',
									reason: value2,

								},
								success:function()
								{
									
									setTimeout(function(){calendar.fullCalendar('refetchEvents'); return;}, 200);
									$.ajax({
									url: "<?php echo base_url(); ?>fullcalendar/getUnapprovedBookings",
									success: function(res) {
									$('.badge.badge-danger').text(res);
								}
								});
								}
							});
						})
								}
						else{	
							calendar.fullCalendar('refetchEvents');
							return;}
							
				})




					
				}
				
			},
		

			eventClick: function(event) {
					var callback = function () {
					if ($.active !== 0) {
						setTimeout(callback, '500');
						return;
					}
				if (displayOrNot == 2 || displayOrNot == 3){
				counter = 0;
				var hasVersions=false;
				$.ajax({
					url: "<?php echo base_url(); ?>fullcalendar/fetch_versions",
					method: "POST",
					data: {
						timeID: event.timeID
					},
					success: function(data) {
						var toJSjson=JSON.parse(data);
					//	console.log(toJSjson.length);
						if(toJSjson.length>0){
							hasVersions=true;
						}
									
						$('#versionTable tbody').empty();
						if(!hasVersions){
							$('#versionTable > tbody:last-child').append('<tr id=""><th colspan="3">Muudatusi pole </th></tr>');
						}
						else{
						$('#versionTable > tbody:last-child').append('<tr id=""><th colspan="3"> Endine aeg </th><th> &nbsp; Kes? </th><th>&nbsp;  Põhjus </th><th>&nbsp;  Muudatus </th></tr>');
						jQuery.each(toJSjson, function(i, val) {
						$("#" + i).append(document.createTextNode(" - " + val));
						
						if(toJSjson[i].startTime==null){
							$('#versionTable > tbody:last-child').append(' <tr> <td> &nbsp;&nbsp;&nbsp;  </td> <td> </td><td>   </td><td> &nbsp;' +  toJSjson[i].nameWhoChanged + '  </td><td> &nbsp;' +  toJSjson[i].reason + '  </td> <td> &nbsp;' +   moment(toJSjson[i].whenChanged).format('DD.MM.YYYY HH:mm') + '  </td> </tr>');
					
						}else{

						$('#versionTable > tbody:last-child').append(' <tr> <td> '+days[moment(toJSjson[i].startTime).day()] +',&nbsp;'+  moment(toJSjson[i].startTime).format('DD.MM.YYYY') + '&nbsp;&nbsp;  </td> <td> ' +   moment(toJSjson[i].startTime).format('HH:mm') + '-</td><td> ' +  moment(toJSjson[i].endTime).format('HH:mm') + '  </td><td> &nbsp;' +  toJSjson[i].nameWhoChanged + '  </td><td> &nbsp;' +  toJSjson[i].reason + '  </td> <td> &nbsp;' +   moment(toJSjson[i].whenChanged).format('DD.MM.YYYY HH:mm') + '  </td> </tr>');
						}
					
						});
					}
									
					}
				});

				$(':checkbox[name=selectAll]').prop('checked', false);

				// $("#successModal").modal("show");
				// $("#successModal .modal-body p").text(event.title);
				$('#myTable tbody').empty();
				$('#lefty').modal('show');
				$("#lefty .modal-header h4").text(event.title);
				$("#lefty .modal-header p").text(moment(event.start).format('DD.MM.YYYY'));
				$("#lefty #time").text(moment(event.created_at).format('DD.MM.YYYY HH:mm:ss'));
				//  $('.modal-dialog').draggable();
				$('#c_name').val(event.title);
				if (event.typeID == 4) {
					$('p#saal').text("Asukoht");
					$('.remove').each(function() {
						$(this).hide();
					});
				} else {
					$('.remove').each(function() {
						$(this).show();
					});
				}

				$('#clubname').val(event.title);
				$("#clubname").text(event.title);
				$("#c_name").text(event.clubname);
				$('#event_in').val(event.event_in);
				if (!$('#event_in').val()) {
					$("#ajad").addClass("d-none");
				} else {
					$("#ajad").removeClass("d-none");
					$("#contact #event_in").text(moment(event.event_in).format('DD.MM.YYYY'));
				}
				$('#event_out').val(event.event_out);
				if (!$('#event_out').val()) {
					$("#ajad").addClass("d-none");
				} else {
					$("#ajad").removeClass("d-none");
					$("#contact #event_out").text(moment(event.event_out).format('DD.MM.YYYY'));
				}
				// if ($('#event_out').is(':empty')){
				// $('#event_out').val('Pole hooajaline broneering');}
				// else{
				//     $('#event_out').val(moment(event.event_out).format('DD/MM/YYYY HH:mm'));
				// }
			
				var id = event.bookingID;
			
					

				var events = $('#calendar').fullCalendar('clientEvents');
				$('tbody').attr('id', id);

				var eventToCheck=[];
				var startDateTime = [];
				var endDateTime = [];
				var arrayOfIDs = [];
				var arrayOfTitles = [];
				var monthCheckbox = '';
					
				for (var i = 0; i < events.length; i++) {
					var Bid = events[i].bookingID;
			
					if (events[i].start != null && events[i].end != null) {
						startDateTime.push(events[i].start._i.substring(0, 16));
						arrayOfIDs.push(events[i].timeID);
						endDateTime.push(events[i].end._i.substring(0, 16));
						arrayOfTitles.push(events[i].title);
						
					}
					if (event.bookingID == Bid) {
						eventToCheck.push(events[i]);

					}
					
				}
			
				
				for ( i = 0; i < eventToCheck.length; i++) {
					
				var approved = eventToCheck[i].approved;
					if (approved == 1) {
						approved = "Kinnitatud";
					} else {
						approved = "Kinnitamata";
					}

				var takesPlace = eventToCheck[i].takesPlace;
					if (takesPlace == 1) {
						takesPlace = "";
					} else {
						takesPlace = "XXX";
					}

				var nameOfWeek=days[moment(eventToCheck[i].start._i).day()];
				var start_date = new Date(eventToCheck[i].start._i);
				var st_monthIndex = moment(eventToCheck[i].start._i).toDate().getUTCMonth() + 1;

				if (monthCheckbox != st_monthIndex) {
				//	console.log(st_monthIndex);
				$('#myTable > tbody:last-child').append('<tr id="monthRow' + start_date.getUTCMonth() + '"><th colspan="2"><label><input type="checkbox"  id="selectMonth[' + start_date.getUTCMonth() + ']" value="1"></label> ' + monthNamesForModal[moment(eventToCheck[i].start._i).toDate().getUTCMonth()] + ' </th></tr>');
				}
				monthCheckbox = st_monthIndex;
				var isSelected=false;
				if (event.timeID == eventToCheck[i].timeID) {
					isSelected='checked';
				
				}
				else {
					isSelected='';	
				}

				$('#myTable > tbody:last-child').append(' <tr class="red' + i + '">  <td><label><input type="checkbox" class="abc brdr" name="choices" id="' + eventToCheck[i].timeID + '"'+isSelected+' ><span></span></label> ' +  nameOfWeek + ',</td> <td>' + moment(eventToCheck[i].start._i).format('DD.MM.YYYY') + ' <br>  </td> <td>&nbsp;&nbsp; ' + moment(eventToCheck[i].start._i).format('HH:mm') +  '-' + moment(eventToCheck[i].end._i).format('HH:mm') + '</td>   <td>&nbsp;&nbsp;&nbsp;' + approved + ' </td> <td>&nbsp;' + takesPlace + ' </td>   </tr>');
				
				var checkingConflicts=isOverlapping2(eventToCheck[i], events);
			
				if	(checkingConflicts.title){
					$(".red" + i).css("color", "red");
						if ($("table").find(".red" + i + ":first td").length < 6) {
							checkingConflicts.title.length > 10 ? checkingConflicts.title = checkingConflicts.title.substring(0, 10) + "...": checkingConflicts.title = checkingConflicts.title;
						
							$(".red" + i).append('<td> &nbsp;' + checkingConflicts.title + '</td>');
						}
					
					}

				}
				
				

				$('#phone').val(event.phone);
				$("#contact #phone").text(event.phone);
				$('#selectedroom').val(event.selectedroom);
				$('#email').val(event.email);
				$("#contact #email").text(event.email);
				$('#created_at').val(event.created_at);
				$('#workout').val(event.workout);
				$("#contact #workout").text(event.workout);

				$('#start').val(event.start);
				$('#building').val(event.building);
				$("#contact #building").text(event.building);
				$('#selectedroom').val(event.roomName);
				$("#contact #selectedroom").text(event.roomName);
				$("#contact #comment").text(event.comment);
				$('.timeComment').val(event.timeComment);
				$('.timeComment').attr("id",event.timeID);
				$('.timeCommentShow').val(event.showComment);
				$('.timeCommentShow').attr("id",event.timeID);
				if(event.showComment==1){
					$(".timeCommentShow").prop("checked", true);
				} else{
					$(".timeCommentShow").prop("checked", false);
				}
				
				
				
				$('#editModal').modal();



				$("input[id*='selectMonth']").each(function(i, el) {
					$(this).parent().parent().css("text-decoration", "underline");

				});

			}
			//whatever you need to do here
					//...
				};
				callback();
			}




		});

		$("#delete").submit(function(event) {
			var selectedRoomID = $('#saal  option').filter(function() {
					return $('#room').val();
					}).data('value');
			var countSelected;
			$.post("<?php echo base_url(); ?>fullcalendar/countBookindTimes/", { bookingID: $('input:checkbox:checked').parents("tbody").attr('id') } )
			.done(function( data ) {
			if ($('.abc:checked').length == $('.abc').length && $('.abc').length == data) {
				swal({
					title:  "Oled kindel kustutada kogu broneeringu?",
					buttons: {
					cancel: "Ei",
					Jah: true,
				},
					}).then(function(value){
						if(value){
							var id = $('input:checkbox:checked').parents("tbody").attr('id');
			
				$.ajax({
					url: "<?php echo base_url(); ?>fullcalendar/deleteAllConnectedBookings",
					type: "POST",
					data: {
						bookingID: id,
						selectedRoomID:selectedRoomID
					},
					success: function() {
					

						jQuery('input:checkbox:checked').parents("tr").remove();
						$("#lefty").modal("hide");
						$('#calendar-container').css({
							'margin-left': '0'
						});
						$('#widthToggle').css({
							'margin-left': '0'
						});
					
					},
					error: function(returnval) {
						$(".message").text(returnval + " failure");
						$(".message").fadeIn("slow");
						$(".message").delay(2000).fadeOut(1000);
					}
				});
				setTimeout(function(){calendar.fullCalendar('refetchEvents'); }, 200);
						$.ajax({
							url: "<?php echo base_url(); ?>fullcalendar/getUnapprovedBookings",
							success: function(res) {
							$('.badge.badge-danger').text(res);
						}
						});
						}	})

						event.preventDefault();


			} else if ($('.abc:checked').length <= $('.abc').length && $('.abc:checked').length > 0) {
				
				swal({
					title:  "Oled kindel, et soovid kustutada valitud ajad?",
					buttons: {
					cancel: "Ei",
					Jah: true,
				},
					}).then(function(value){
						if(value){
							var countTimes=$('#myTable tr:has(td)').length-$('.abc:checked').length;
				$('#countNr').text('Kõik ajad (' +countTimes + ')');

				$("input:checkbox").each(function() {
					var $this = $(this);
			
				//	console.log(selectedRoomID);
				
					if ($this.is(":checked")) {
						var id = $this.attr("id");
						$.ajax({
							url: "<?php echo base_url(); ?>fullcalendar/delete",
							type: "POST",
							data: {
								timeID: id,
								selectedRoomID:selectedRoomID
							},
							success: function() {
							
								jQuery('input:checkbox:checked').parents("tr").remove();
							
							},
							error: function(returnval) {
								$(".message").text(returnval + " failure");
								$(".message").fadeIn("slow");
								$(".message").delay(2000).fadeOut(1000);
							}
						});
					}
				});
				setTimeout(function(){calendar.fullCalendar('refetchEvents'); }, 200);
								$.ajax({
									url: "<?php echo base_url(); ?>fullcalendar/getUnapprovedBookings",
									success: function(res) {
									$('.badge.badge-danger').text(res);
								}
								});

						}	})
						event.preventDefault();

		
			} else {
			
				swal({
						icon: 'info',
						title: "Sa ei valinud midagi mida kustutada!",
						buttons: "Sain aru"
						
					})
				event.preventDefault();
			}
		});
			event.preventDefault();


		});

		var IAmTheLastChecked
		$("#approveCheck").click(function(event) {
			var checkedInputLenght=$('.abc:checked').length;
		
				
			if (checkedInputLenght <= $('.abc').length && checkedInputLenght > 0) {
				var text='';
				if (checkedInputLenght> 300){
					text="Nii paljude broneeringuaegade kinnitamine võtab aega üle 20 sekundi";
						};

				swal({
					title:  "Muudan kinnitamise olekut?",
					text: text,
					closeOnConfirm: false,
					showLoaderOnConfirm: true,
					buttons: {
					cancel: "Ei",
					Jah: true,
				},
					}).then(function(value){
						setTimeout(function () {
							var counter=0;
							IAmTheLastChecked=$(".abc:checkbox:checked").last();
							
							
							$(".abc:checkbox:checked").each(function() {
								var $this = $(this);
								counter++;
							//	console.log(counter);

								if ($this.is(":checked")) {
									var id = $this.attr("id");
								
								
									var approvedOrNot = $this.parents("tr").children("td:nth-child(4)");
								
									var approvedOrNotToDB;
									if ($.trim(approvedOrNot.text()) == "Kinnitatud") {
										approvedOrNotToDB = 0;
									} else {
										approvedOrNotToDB = 1;
									}
									var selectedRoomID = $('#saal  option').filter(function() {
									return $('#room').val();
									}).data('value');


									$.ajax({
										url: "<?php echo base_url(); ?>fullcalendar/approveEvents",
										type: "POST",
										data: {
											timeID: id,
											approved: approvedOrNotToDB,
											selectedRoomID:selectedRoomID

										},
										success: function() {
											
											//siia tule teha panna kinnitatud olekuks modalis  
											if (approvedOrNotToDB == 1 ) {
												$this.parents("tr").children("td:nth-child(4)").html("&nbsp;&nbsp;&nbsp;Kinnitatud");
											} else {
												$this.parents("tr").children("td:nth-child(4)").html("&nbsp;&nbsp;&nbsp;Kinnitamata");
											}
										
										},
										error: function(returnval) {
											$(".message").text(returnval + " failure");
											$(".message").fadeIn("slow");
											$(".message").delay(2000).fadeOut(1000);
										},
										complete: function (data) {
											if($this[0] ===IAmTheLastChecked[0] ) {
												$("body").css("cursor", "default");
											}
											
										}
									});
								}
						});
						$.ajax({
										url: "<?php echo base_url(); ?>fullcalendar/getUnapprovedBookings",
										success: function(res) {
										$('.badge.badge-danger').text(res);
									}
						});
						setTimeout(function(){calendar.fullCalendar('refetchEvents'); }, 200);
					}, 200);
						if(value){
						//	swal.close();
							$("body").css("cursor", "wait");
							

						}	
					
					})

					event.preventDefault();
		
			} else {
			
				swal({
						icon: 'info',
						title: "Sa ei valinud midagi mida kinnitada!",
						buttons: "Sain aru"
						
					})
				event.preventDefault();
			}



		});

		$("#takesPlaceCheck").submit(function(event) {
			if ($('.abc:checked').length <= $('.abc').length && $('.abc:checked').length > 0) {
			

				swal({
					title:  "Muudan trennide toimumise staatust?",
					buttons: {
					cancel: "Katkesta",
					Jah: true,
				},
					}).then(function(value){
						if(value){
							$("body").css("cursor", "wait");

						setTimeout(function () {
							IAmTheLastChecked=$(".abc:checkbox:checked").last()
							$(".abc:checkbox:checked").each(function() {
								var $this = $(this);

								var approvedOrNot = $this.parents("tr").children("td:nth-child(5)");
						
								var approvedOrNotToDB;
								if ($.trim(approvedOrNot.text()) == "XXX") {
									approvedOrNotToDB = 1;
								} else {
									approvedOrNotToDB = 0;
								}
								var selectedRoomID = $('#saal  option').filter(function() {
								return $('#room').val();
								}).data('value');

								if ($this.is(":checked")) {
									var id = $this.attr("id");
									$.ajax({
										url: "<?php echo base_url(); ?>fullcalendar/takesPlace",
										type: "POST",
										data: {
											timeID: id,
											takesPlace: approvedOrNotToDB,
											selectedRoomID:selectedRoomID

										},
									
										success: function() {
											
											if (approvedOrNotToDB == 0) {
												$this.parents("tr").children("td:nth-child(5)").html("&nbsp;XXX");
											} else {
												$this.parents("tr").children("td:nth-child(5)").html("&nbsp;&nbsp;&nbsp;");
											}
											//  $('.abc:checkbox:checked').parents("tr").children("td:contains('Ei toimunud')").html("");
											//alert('Ei toimunud');
										},
										error: function(returnval) {
											alert('Midagi läks valesti ja tulemust ei salvestatud');
											$(".message").text(returnval + " failure");
											$(".message").fadeIn("slow");
											$(".message").delay(2000).fadeOut(1000);
										},
										complete: function (data) {
											if($this[0] ===IAmTheLastChecked[0] ) {
												$("body").css("cursor", "default");
											}	}
									});
								}
							});
							setTimeout(function(){calendar.fullCalendar('refetchEvents'); }, 200);
									}, 200);

									
						}
							})
						event.preventDefault();

	
			} else {
				swal({
						icon: 'info',
						title: "Sa ei märgistanud ühtegi ruutu!",
						buttons: "Sain aru"
						
					})
				event.preventDefault();
			}



		});


		$("#changeTimes").on('click', function(event) {
			if ($('.abc:checked').length <= $('.abc').length && $('.abc:checked').length > 0) {
				var timesIdArray = [];
				var id = '';

				$("input:checkbox").each(function() {
					var $this = $(this);

					if ($this.is(":checked")) {
						id = $('input:checkbox:checked').parents("tbody").attr('id');
						var timesId = $this.attr("id");
						timesIdArray.push(timesId);
					}

				});
				var myForm = document.getElementById('change');
				timesIdArray.forEach(function(value) {
					if (!isNaN(value)) {
						var hiddenInput = document.createElement('input');
						hiddenInput.type = 'hidden';
						hiddenInput.name = 'timesIdArray[]';
						hiddenInput.value = value;

						myForm.appendChild(hiddenInput);
					}

				});
				document.getElementById("BookingID").value = id;
			
				$('#change').submit();


			} else {
				swal({
						icon: 'info',
						title: "Sa ei märgistanud ühtegi ruutu!",
						buttons: "Sain aru"
						
					})
				event.preventDefault();
			}
		});
	
	

		$("#changePeriodTimes").on('click', function(event) {
			if ($('.abc:checked').length <1) {
				swal({
						icon: 'info',
						title: "Sa ei märgistanud ühtegi ruutu!",
						buttons: "Sain aru"
						
					})
				event.preventDefault();
			}
			else if ($('.abc:checked').length <2) {
				var timesIdArray = [];
				var id = '';

				$("input:checkbox").each(function() {
					var $this = $(this);

					if ($this.is(":checked")) {
						id = $('input:checkbox:checked').parents("tbody").attr('id');
						var timesId = $this.attr("id");
						timesIdArray.push(timesId);
					}

				});
				var myForm = document.getElementById('change');
				timesIdArray.forEach(function(value) {
					if (!isNaN(value)) {
						var hiddenInput = document.createElement('input');
						hiddenInput.type = 'hidden';
						hiddenInput.name = 'timesIdArray[]';
						hiddenInput.value = value;

						myForm.appendChild(hiddenInput);
					}

				});
				document.getElementById("isPeriodic").value = 1;
				document.getElementById("BookingID").value = id;
				$('#change').submit();


			} else {
				swal({
						icon: 'info',
						title: "Palun vali ainult üks nädalapäev",
						buttons: "Sain aru"
						
					})
			
				event.preventDefault();
			}
		});



		var calHeight = $(".fc-body").height();
		var calRows = $(".fc-slats tr").length;
		var rowHeightRaw = calHeight / calRows;
		var rowHeight = rowHeightRaw.toString().match(/^-?\d+(?:\.\d{0,1})?/)[0];
		$('.fc-slats tr').css('height', rowHeight + 'px');
		$(window).trigger('resize');
		$(".fc-body").trigger('reload');


		$('input[id=activity]').focusin(function() {
            $('input[id=activity]').val('');
        });

        $('input[id=region]').focusin(function() {
            $('input[id=region]').val('');           
        });

        $('input[id=sport_facility]').focusin(function() {
            $('input[id=sport_facility]').val('');
        });

        $('input[id=room]').focusin(function() {
            $('input[id=room]').val('');
        });


			//tegevus
			$("#activity").on('change keydown input paste', function(e) {
            var $input = $(this),
                val = $input.val();
            list = $input.attr('list'),
                match = $('#' + list + ' option').filter(function() {
                    return ($(this).val() === val);
                });
            if (match.length > 0) {

				$('#region').val('');
                $('#sport_facility').val('');
                $('#room').val('');

                var value = $('#activity').val();
                var activity_id = $('#activities [value="' + value + '"]').data('value');
			
				$.ajax({
                    url: "<?php echo base_url(); ?>home/fetch_region",
                    method: "POST",
                    data: {
                        activity_id: activity_id
                    },
                    success: function(data) {
                        $('#regions').html(data);
					    $("#room").empty();
					 
                    }
                });
                $.ajax({
                    url: "<?php echo base_url(); ?>home/fetch_building_from_activity_or_region",
                    method: "POST",
                    data: {
                        activity_id: activity_id
                    },
                    success: function(data) {
                       // console.log("data on " + data);
                    
                        $('#asutus').html(data).appendTo("#asutus");
                    }
                });
			
				$.ajax({
                    url: "<?php echo base_url(); ?>home/fetch_rooms_from_region_activity_building",
                    method: "POST",
                    data: {
                        activity_id: activity_id
                    },
                    success: function(data) {
                        $('#saal').html(data);
                     
                    }
                });
            } else {
            //    console.log("dismatch");
				$('#region').val('');
                $('#sport_facility').val('');
                $('#room').val('');

            }
        });


		$("#region").on('change keydown input paste', function(e) {

			var $input = $(this),
				val = $input.val();
			list = $input.attr('list'),
				match = $('#' + list + ' option').filter(function() {
					return ($(this).val() === val);
				});
			if (match.length > 0) {
		
				var value = $('#region').val();
				var country_id = $('#regions [value="' + value + '"]').data('value');

				var activity = $('#activity').val();
				var activity_id = $('#activities [value="' + activity + '"]').data('value');

				var building = $('#sport_facility').val();
                var buildingID = $('#asutus [value="' + building + '"]').data('value');

				$.ajax({
					url: "<?php echo base_url(); ?>home/fetch_building_from_activity_or_region",
					method: "POST",
					data: {
						country_id: country_id,
						activity_id: activity_id,
					},
					success: function(data) {
				
						$("#asutus").empty();
						$("#room").empty();
						$('#asutus').html(data).appendTo("#asutus");
					}
				});
				$.ajax({
                    url: "<?php echo base_url(); ?>home/fetch_rooms_from_region_activity_building",
                    method: "POST",
                    data: {
                        country_id: country_id, 
						buildingID: buildingID, 
						activity_id: activity_id,
                    },
                    success: function(data) {
						$('#saal').html(data).appendTo("#saal");
                     
                    }
                });
			} else {
			
				$('#room').val('');
				$('#sport_facility').val('');

			}
		});
		$("#sport_facility").on('load change keydown input paste', function(e) {
			var $input = $(this),
				val = $input.val();
			list = $input.attr('list'),
				match = $('#' + list + ' option').filter(function() {
					return ($(this).val() === val);
				});
			if (match.length > 0) {
			
				var activity = $('#activity').val();
				var activity_id = $('#activities [value="' + activity + '"]').data('value');

				var country = $('#region').val();
				var country_id = $('#regions [value="' + country + '"]').data('value');

				var building = $('#sport_facility').val();
                var buildingID = $('#asutus [value="' + building + '"]').data('value');
			
				$.ajax({
					url: "<?php echo base_url(); ?>home/fetch_rooms_from_region_activity_building",
					method: "POST",
					data: {
						activity_id: activity_id,
						country_id: country_id, 
						buildingID: buildingID, 
					},
					success: function(data) {
					
						$('#room').val('');
                       	$("#room").empty();
                        $("#saal").empty();
                        $('#saal').html(data).appendTo("#saal");
					}
				});
			} else {
				
				$('#room').val('');
			}
		});
	
		$("#room").on('change keydown input paste', function(e) {
			var $input = $(this),
				val = $input.val();
			list = $input.attr('list'),
				match = $('#' + list + ' option').filter(function() {
					return ($(this).val() === val);
				});
		

			if (match.length > 0 && val.length > 0) {
				val = $('#room').val();
				var xyz = $('#saal   option').filter(function() {
					return this.value == val;
				}).data('value');

				window.location.href = '<?php echo base_url(); ?>fullcalendar?roomId=' + xyz + '&date=' + $('#calendar').fullCalendar('getDate').format('DD.MM.YYYY');
			} else {
		
			}
		});

		
		$(".timeCommentShow").click(function() {
		
			var $this = $(this);
			var id = $this.attr("id");
			var showComment=$this.val();
			if(showComment==1){
				$this.val(0)
			}
			if(showComment==0){
				$this.val(1)
			}
			console.log(showComment);
			
			$.ajax({
				url: "<?php echo base_url(); ?>fullcalendar/showComment",
				type: "POST",
				data: {
					timeID: id,
					showComment: $this.val()
				},
				success: function() {
				
				},
				error: function(returnval) {
					$(".message").text(returnval + " failure");
					$(".message").fadeIn("slow");
					$(".message").delay(2000).fadeOut(1000);
				},
				complete: function (data) {
					
					
				}
			});
			setTimeout(function(){calendar.fullCalendar('refetchEvents'); }, 200);
			
		
		});
		

		$('.timeComment').on('change', function(){
			var $this = $(this);
			var id = $this.attr("id");
			var commentText=$this.val();
			console.log(commentText);
			
			$.ajax({
				url: "<?php echo base_url(); ?>fullcalendar/updateComment",
				type: "POST",
				data: {
					timeID: id,
					comment: commentText
				},
				success: function() {
				
				},
				error: function(returnval) {
					$(".message").text(returnval + " failure");
					$(".message").fadeIn("slow");
					$(".message").delay(2000).fadeOut(1000);
				},
				complete: function (data) {
					
				}
			});
			setTimeout(function(){calendar.fullCalendar('refetchEvents'); }, 200);
	
		});

		$('#calendar').fullCalendar({
			dayClick: function(date, jsEvent, view) {
				$(".fc-state-highlight").removeClass("fc-state-highlight");
				$("td[data-date=" + date.format('YYYY-MM-DD') + "]").addClass("fc-state-highlight");
			}
		});


		$("#datepicker").val('<?php echo ($this->input->get("date")); ?>');
		if ('<?php echo ($this->input->get("date")); ?>') {
		
			date = moment('<?php echo ($this->input->get("date")); ?>', "DD.MM.YYYY");
			$("#calendar").fullCalendar('gotoDate', date);
			$('.fc-slats tr').css('height', rowHeight + 'px');
			$(window).trigger('resize');
			$(".fc-body").trigger('reload');
		}


		$('#datepicker').datepicker()
			.on("input change", function(e) {
				date = moment(e.target.value, "DD.MM.YYYY");
				$("#calendar").fullCalendar('gotoDate', date);
			
				// $('#calendar').fullCalendar( 'gotoDate', e.target.value )
				//  window.location.href = '<?php echo site_url(''); ?>fullcalendar?roomId=<?php echo ($this->input->get('roomId')); ?>&date='+ e.target.value;
				window.history.replaceState("", "", "fullcalendar?roomId=<?php echo ($this->input->get('roomId')); ?>&date=" + e.target.value);
				$('.fc-slats tr').css('height', rowHeight + 'px');
				$(window).trigger('resize');
				$(".fc-body").trigger('reload');
			});


		$('body').on('click', 'button.fc-prev-button', function() {
			window.history.replaceState("", "", "fullcalendar?roomId=<?php echo ($this->input->get('roomId')); ?>&date=" + $('#calendar').fullCalendar('getDate').format('DD.MM.YYYY'));
			//kas alumist on vaja? see muudab input datepickeri väärtust vastavalt kuupäevadele
			//    $('#datepicker').val($('#calendar').fullCalendar('getDate').format('DD.MM.YYYY'));

			$('.fc-slats tr').css('height', rowHeight + 'px');
			$(window).trigger('resize');
		});

		$('body').on('click', 'button.fc-next-button', function() {
			window.history.replaceState("", "", "fullcalendar?roomId=<?php echo ($this->input->get('roomId')); ?>&date=" + $('#calendar').fullCalendar('getDate').format('DD.MM.YYYY'));

			$('.fc-slats tr').css('height', rowHeight + 'px');
			$(window).trigger('resize');
			//$('#datepicker').val($('#calendar').fullCalendar('getDate').format('DD.MM.YYYY'));

		});

		$(window).on('load', function() {
			$('.fc-slats tr').css('height', rowHeight + 'px');
			$(window).trigger('resize');
		});

		date = moment('<?php echo ($this->input->get("date")); ?>', "DD-MM-YYYY");

		if ('<?php echo ($this->input->get("date")); ?>') {
			$("#calendar").fullCalendar('gotoDate', date);
		}



	function toggleAccordion() {
		this.classList.toggle('active');
		this.nextElementSibling.classList.toggle('active');
	}



	$('.accordion a').bind("click", toggleAccordion);
	

	$('#calendar').click(function() {
		$('#countNr').text('Kõik ajad (' +$('.abc:checked').length + '/'+ $('#myTable tr:has(td)').length + ')');
	});

	$(document).on('click', '.abc', function() { 
		$('#countNr').text('Kõik ajad (' +$('.abc:checked').length + '/'+ $('#myTable tr:has(td)').length + ')');

	});

	//Kui modalit lahti tehakse, siis kalender läheb väiksemaks. Ja vastupidi
	$(window).on('click', function(event) {

		if ($('body').hasClass('modal-open')) {
			$('#calendar-container').css({
				'margin-left': '350px'
			});
			$('#widthToggle').css({
				'margin-left': '350px'
			});
		} else {
			$('#calendar-container').css({
				'margin-left': '0'
			});
			$('#widthToggle').css({
				'margin-left': '0'
			});
		}
	});

	function isOverlapping(event) {
		var array = $('#calendar').fullCalendar('clientEvents');
		for (var i in array) {
			if (array[i].bookingID != event.bookingID && array[i].start != null && array[i].end != null && event.start != null && event.end != null) {
				if (array[i].start.isBefore(event.end) && array[i].end.isAfter(event.start)) {
					return true;
				}
			}
		}
		return false;
	}

	function isOverlapping2(event, events) {
	//	var events = $('#calendar').fullCalendar('clientEvents');
		
		for (var i in events) {
		
			if (events[i].timeID != event.timeID && events[i].start != null && events[i].end != null && event.start != null && event.end != null) {
			
				if (events[i].start.isBefore(event.end) && events[i].end.isAfter(event.start) && events[i].end> moment().subtract(7, "days")) {
			
					return events[i];
				}
			}
		}
		return false;
	}
		
	function diffMinutes(start, end){
	
		var diff =(new Date(end).getTime() - new Date(start).getTime()) / 1000;
		diff /= 60;
		return Math.abs(Math.round(diff));

	}


	$('#allCalenderLink').click( function(e) { 
		e.preventDefault();
	
		window.location.href = '<?php echo base_url(); ?>allbookings/weekView/?date='+	moment($('#calendar').fullCalendar('getDate')).format("DD.MM.YYYY");
		} );

	
	});


</script>
