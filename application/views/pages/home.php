<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- <a href="<?php echo site_url('login/logout');?>">Sign Out</a> -->

				

<div class="container-fluid">
    <div class="d-flex">

        <div class="d-none d-md-flex col-md-6 col-lg-8 p-0 img-container">
            <div class="content">
                <div class="blur"></div>                
            </div>
            <div class="text">
                <h1>Pärnu linna sportimiskohtade vabad ajad ühes kohas!</h1>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="d-flex align-items-center">
                <div class="col-7 mb-5"  id="form-container">
                    <form action="fullcalendar" method="get">
					<div class="form-label-group">
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

                    <?php if($this->session->userdata('roleID')!='2'&&$this->session->userdata('roleID')!='3'):?>
						<h5>
						<?php 	if(empty($buildings)){
										echo 'Ükski asutus pole süsteemiga veel liitunud';	
									}
                                ?>
						</h5>
                        <div class="form-label-group">
                            <label for="region">Piirkond</label>
                            <input id="region" list="regions" class="form-control arrow" type="text" autocomplete="off">
                            <datalist id="regions">
							<option  data-value="0" value="---"></option>
                                <?php foreach ($regions as $row) {
                                    echo '<option  data-value="' . $row->regionID . '" value="' . $row->regionName . '"></option>';
                                }
                                ?>
                            </datalist>
						</div>
					

					
                        <div class="form-label-group">
                            <label for="sport_facility">Asutus</label>
                            <input id="sport_facility" list="asutus" class="form-control arrow" autocomplete="off">
                            <datalist id="asutus">
								<?php 
								foreach ($buildings as $each) {
                                    echo '<option data-value="' . $each->id . '" value="' . $each->name . '"></option>';
                                }
                                ?>
                            </datalist>
                        </div>
						<?php endif;?>
						<h5 id="demo"></h5>
                        <div class="form-label-group">
                            <label for="room">Ruum</label>
                            <input id="room" list="saal" class="form-control arrow" required autocomplete="off"> 
                            <datalist id="saal">
							
                                <?php 	if(empty($rooms)){
										echo '<option data-value="' . $each->id . '"> Süsteemi pole veel ruume sisestatud </option>';	
									} foreach ($rooms as $each) {

                                if($this->session->userdata('roleID')=='2' or $this->session->userdata('roleID')=='3'){
                                    if($this->session->userdata('building')==$each->buildingID){
									
										if ($each->roomActive==0){
											echo '<option data-value="' . $each->id . '">' . $each->roomName . ' (peidetud)</option>';
										}else{
											echo '<option data-value="' . $each->id . '">' . $each->roomName . '</option>';
									}
                                    }  

                                }
                                    else{
										if ($each->roomActive==0){
											echo '<option data-value="' . $each->id . '">' . $each->roomName . ' (peidetud)</option>';
										}else{
											echo '<option data-value="' . $each->id . '">' . $each->roomName . '</option>';
									}
									} 
								

                                }
                                ?>
                            </datalist>
                            <input type="hidden" id="roomId" name="roomId" value="roomId" />
                        </div>

                        <div class="form-label-group">
                            <label for="datepicker">Kuupäev</label>     
                            <input id="datepicker" class="datePicker form-control" data-toggle="datepicker" name="date"/>
                        </div>

                        <input class="btn btn-custom col-12 text-white mt-3" type="submit" value="Kuva kalender">

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>


<!-- <script src='https://unpkg.com/v-calendar@next'></script> -->
<?php 
$display=false;
   if($this->session->userdata('roleID')=='2' or $this->session->userdata('roleID')=='3'){
	$display=true;
   }
?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datepicker.js"></script>
<script>

	var isIE = !!navigator.userAgent.match(/Trident/g) || !!navigator.userAgent.match(/MSIE/g);
   		let display='<?php echo $display;?>';
		//   console.log(display);
		if(isIE && display){
		//alert("Spordiruumi kalender ei toeta Internet Explorerit. Tänapäeval on palju paremaid veebilehitsejaid mida kasutada. Parima kogemuse saamiseks palume kasutada näiteks Chrome, Edge või Firefoxi."); 
       
		document.getElementById("demo").innerHTML = "Tähelepanu! INTERNET EXPLORER pole täielikult toetatud ning osad funktsionaalsused ei pruugi töötada. Parima kogemuse saamiseks palun vali teine veebilehitseja";
 
		}
	
	
			// if (window.document.documentMode) {
			// 	document.getElementById("demo").innerHTML = "INTERNET EXPLORER POLE TOETATUD! PALUN VALI TEINE BRAUSER";
			// 	alert('IEEdge ' + detectIEEdge());
			// 	};
            // if (navigator.appName == 'Microsoft Internet Explorer' ||  !!(navigator.userAgent.match(/Trident/) || navigator.userAgent.match(/rv:11/)) || (typeof $.browser !== "undefined" && $.browser.msie == 1))
            //     {
            //     alert("Please dont use IE.");
            //     };

    $(document).ready(function() {
	
	


        $(".datePicker").datepicker({
                language: "et-EE",
                autoHide: true,
                date: new Date(),
                autoPick: true,
            });


        $('#room[list]').on('input', function(e) {
            var $input = $(e.target),
                $options = $('#' + $input.attr('list') + ' option'),
                label = $input.val();

            for (var i = 0; i < $options.length; i++) {
                var $option = $options.eq(i);
                if ($option.text() === label) {
                    $("#roomId").val($option.attr('data-value'));
                    break;
                }
            }
        });

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


		//piirkond
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
				//console.log(buildingID);
				
                $.ajax({
                    url: "<?php echo base_url(); ?>home/fetch_building_from_activity_or_region",
                    method: "POST",
                    data: {
						activity_id: activity_id,
                        country_id: country_id
                    },
                    success: function(data) {
                      
						//val('') is for empty input and .empty is for empty datalist
					 	 $('#sport_facility').val('');
               			 $('#room').val('');
                        $("#asutus").empty();
                        $("#room").empty();
                        $('#asutus').html(data).appendTo("#asutus");
                    }
                });
				$.ajax({
                    url: "<?php echo base_url(); ?>home/fetch_rooms_from_region_activity_building",
                    method: "POST",
                    data: {
						activity_id: activity_id,
                        country_id: country_id, 
						buildingID: buildingID, 

                    },
                    success: function(data) {
                        $('#saal').html(data);
                     
                    }
                });
            } else {
				
                $('#sport_facility').val('');
                $('#room').val('');

            }
        });

		//asutus
        $("#sport_facility").on('change keydown input paste', function(e) {
            var $input = $(this),
                val = $input.val();
            list = $input.attr('list'),
                match = $('#' + list + ' option').filter(function() {
                    return ($(this).val() === val);
                });
            if (match.length > 0) {

				var activity = $('#activity').val();
				var activity_id = $('#activities [value="' + activity + '"]').data('value');
				
				var value = $('#region').val();
                var country_id = $('#regions [value="' + value + '"]').data('value');

                var building = $('#sport_facility').val();
                var buildingID = $('#asutus [value="' + building + '"]').data('value');
	
                $.ajax({
                    url: "<?php echo base_url(); ?>home/fetch_rooms_from_region_activity_building",
                    method: "POST",
                    data: {
						activity_id: activity_id,
                        country_id: country_id, 
						buildingID: buildingID
                    },
                    success: function(data) {
                  //      console.log("data on " + data);
					    $('#room').val('');
                       	$("#room").empty();
                        $("#saal").empty();
                        $('#saal').html(data).appendTo("#saal");
                    }
                });
            } else {
         //       console.log("dismatch");
			    $('#room').val('');
            }
        });
    });
</script>
