
<a href="<?php echo base_url(); ?>/allbookings/">Nimekiri </a>
<a href="<?php echo base_url(); ?>/allbookings/weekview">Kalender</a>

<br>
<?php
$json = file_get_contents(base_url().'allbookings/loadRooms/'.$this->session->userdata['building']);
$array = json_encode(json_decode($json, true));
?>

<?php 
foreach($rooms as $value){
	echo  '<input type="checkbox" id="addOrRemoveRoom'.$value['id'].'" name="vehicle" value="'.$value['id'].'" checked>
  <label for="vehicle1"> '.$value['roomName'].'</label>'
	;}?>

<div id='calendar1'></div>
		

<!-- <link href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.4/fullcalendar.css" rel="stylesheet" type="text/css" />
<link href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.4/fullcalendar.print.css " rel="stylesheet" type="text/css" media="print" /> -->
<link href='<?php echo base_url(); ?>assets/css/fullcalendar.print.css' rel="stylesheet" type="text/css"  media="print">`
<link href='<?php echo base_url(); ?>assets/packages/core/main.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>assets/packages/daygrid/main.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>assets/packages/timegrid/main.css' rel='stylesheet' />
<script src='<?php echo base_url(); ?>assets/packages/core/main.js'></script>
<script src='<?php echo base_url(); ?>assets/packages/interaction/main.js'></script>
<script src='<?php echo base_url(); ?>assets/packages/daygrid/main.js'></script>
<script src='<?php echo base_url(); ?>assets/packages/timegrid/main.js'></script>
<script src='<?php echo base_url(); ?>assets/packages-premium/resource-common/main.js'></script>
<script src='<?php echo base_url(); ?>assets/packages-premium/resource-daygrid/main.js'></script>
<script src='<?php echo base_url(); ?>assets/packages-premium/resource-timegrid/main.js'></script>

<script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendar1El = document.getElementById('calendar1');

    var calendar1 = new FullCalendar.Calendar(calendar1El, {
		//schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
      plugins: [ 'interaction', 'resourceDayGrid', 'resourceTimeGrid' ],
		
      defaultView: 'resourceTimeGridWeek',
	   datesAboveResources: true,
   	firstDay: 1,
		 allDaySlot: false,
	  aspectRatio: 2,
	 minTime: '08:00:00',
			maxTime: '22:00:00',

      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'resourceTimeGridDay,resourceTimeGridThreeDay,resourceTimeGridFourDay,resourceTimeGridWeek,timeGridWeek,dayGridMonth'
      },
      views: {
        resourceTimeGridWeek: {
          type: 'resourceTimeGrid',
          slotDuration: '00:30:00',
          buttonText: 'Nädal',
					
        }, resourceTimeGridFourDay: {
          type: 'resourceTimeGrid',
          duration: { days: 4 },
          buttonText: '4 päeva'
        }, resourceTimeGridThreeDay: {
          type: 'resourceTimeGrid',
          duration: { days: 3 },
          buttonText: '3 päeva'
        },
        resourceTimeGridDay: {
          type: 'resourceTimeGridDay',
          slotDuration: '00:30:00',
          buttonText: 'Päev',
					
        }
      },


      //allDaySlot: false,

      resources: {	
					url: "<?php echo base_url(); ?>allbookings/loadRooms/<?php echo $this->session->userdata['building']; ?>" // use the `url` property
				 },
     	eventSources: [
    {
    url: "<?php echo base_url(); ?>allbookings/load/<?php echo $this->session->userdata['building']; ?>" // use the `url` property
    }
    ],
      select: function(arg) {
        console.log(
          'select',
          arg.startStr,
          arg.endStr,
          arg.resource ? arg.resource.id : '(no resource)'
        );
      },
      dateClick: function(arg) {
        console.log(
          'dateClick',
          arg.date,
          arg.resource ? arg.resource.id : '(no resource)'
        );
      }
    });

    calendar1.render();
 var removedResources=[];
 var removedResources2=[];
 var removedAll=true;
 var phpRoomInfo='<?php echo $array;?>';

    $('input[type="checkbox"]').change(function()
      {
				var json = JSON.parse(phpRoomInfo);
			console.log(json[0].id);
		
			if($(this).is(':checked')){

				var found = false;
				for(var i = 0; i < json.length; i++) {
						if (json[i].id == $(this).val()) {
								found = i;
								break;
						}
				}
			console.log(found);
			calendar1.addResource( json[found]);
					index = removedResources.indexOf($(this).val());
				removedResources=[];
				removedAll=false;
		
				}
				else{
					var resourceA = calendar1.getResourceById($(this).val());
							resourceA.remove();
				}

      });

		

  });

</script>









