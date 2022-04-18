<div class="container" style="width:98%;">
  <a id="allCalenderLink" class=" text-center py-2 px-sm-2 px-lg-5 px-md-4 float-right pluss" href="<?php echo base_url(); ?>/fullcalendar?roomId=<?php echo $this->session->userdata('room'); ?>">Tagasi töökalendrisse</a>
  <div id="spinner" class="sticky-top d-flex justify-content-center">

    <div class="spinner-grow text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>
  <br>
  <?php
  $json = file_get_contents(base_url() . 'allbookings/loadRooms/' . $this->session->userdata['building']);
  $array = json_encode(json_decode($json, true));
  //echo mb_substr('õõõõõõõ', 0, 3,"utf-8");
  ?>
  <!-- <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Tooltip on top">  Tooltip on top</button> -->
  <?php
  foreach ($rooms as $value) {
    echo  ' <span style="background-color:' . $value['roomColor'] . ' !important;-webkit-print-color-adjust: exact; "><input type="checkbox" id="addOrRemoveRoom' . $value['id'] . '" name="vehicle" value="' . $value['id'] . '" checked>
  <label  for="vehicle1"> ' . $value['roomName'] . '&nbsp; </label></span> ';
  } ?>

  <div id='calendar1'></div>
</div>


<!-- <link href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.4/fullcalendar.css" rel="stylesheet" type="text/css" />
<link href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.4/fullcalendar.print.css " rel="stylesheet" type="text/css" media="print" /> -->

<div id="createEventModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span> <span class="sr-only">close</span></button>
        <h4>Add an Event</h4>
      </div>
      <div id="modalBody" class="modal-body">
        <div class="form-group">
          <input class="form-control" type="text" placeholder="Event Name" id="eventName">
        </div>

        <div class="form-group form-inline">
          <div class="input-group date" data-provide="datepicker">
            <input type="text" id="eventDueDate" class="form-control" placeholder="Due Date mm/dd/yyyy">
            <div class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
            </div>
          </div>
        </div>

        <div class="form-group">
          <textarea class="form-control" type="text" rows="4" placeholder="Event Description" id="eventDescription"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button type="submit" class="btn btn-primary" id="submitButton">Save</button>
      </div>
    </div>
  </div>
</div>

<div id="calendarModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
        <h4 id="modalTitle" class="modal-title"></h4>
      </div>
      <div id="modalBody1" class="modal-body"> </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.min.js"></script>
<link href='<?php echo base_url(); ?>assets/css/fullcalendar.print.css' rel="stylesheet" type="text/css" media="print">`
<link href='<?php echo base_url(); ?>assets/packages/core/main.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>assets/packages/daygrid/main.css' rel='stylesheet' />
<!-- <link href='<?php echo base_url(); ?>assets/packages/list/main.css' rel='stylesheet' /> -->
<link href='<?php echo base_url(); ?>assets/packages/timegrid/main.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>assets/css/style.css' rel="stylesheet" />
<link href='<?php echo base_url(); ?>assets/css/calendar.css' rel="stylesheet" />
<script src='<?php echo base_url(); ?>assets/packages/core/main.js'></script>
<script src='<?php echo base_url(); ?>assets/packages/interaction/main.js'></script>
<script src='<?php echo base_url(); ?>assets/packages/daygrid/main.js'></script>
<!-- <script src='<?php echo base_url(); ?>assets/packages/list/main.js'></script> -->
<script src='<?php echo base_url(); ?>assets/packages/timegrid/main.js'></script>
<script src='<?php echo base_url(); ?>assets/packages-premium/resource-common/main.js'></script>
<script src='<?php echo base_url(); ?>assets/packages-premium/resource-daygrid/main.js'></script>
<!-- <script src='<?php echo base_url(); ?>assets/packages-premium/resource-timeline/main.js'></script> -->
<script src='<?php echo base_url(); ?>assets/packages-premium/resource-timegrid/main.js'></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {

    const urlParams = new URLSearchParams(window.location.search);
    var theUrlDate = urlParams.get('date');
    if (!theUrlDate) {
      theUrlDate = moment(new Date()).format("DD.MM.YYYY");
    }
    //console.log(theUrlDate);
    var dateConvert = new Date(theUrlDate.replace(/(\d{2}).(\d{2}).(\d{4})/, "$2/$1/$3"))

    var defaultView = 'resourceTimeGridWeek';
    if (window.innerWidth < 800) {
      defaultView = 'resourceTimeGridDay';
    } else if (window.innerWidth < 1400) {
      defaultView = 'resourceTimeGridFourDay';
    }


    var calendar1El = document.getElementById('calendar1');

    var calendar1 = new FullCalendar.Calendar(calendar1El, {
        //schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        resourceRender: function(info) {
          //console.log(info.resource._resource.extendedProps.description);
          info.el.style.backgroundColor = info.resource.eventBackgroundColor;
          var roomTitle = document.createElement('span');
          //    roomTitle.setAttribute('class', 'btn btn-secondary');
          info.el.textContent = "";
          roomTitle.innerText = info.resource.title;
          //roomTitle.innerText ='\n';
          //	<button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Tooltip on top">  Tooltip on top</button>
          info.el.appendChild(roomTitle);
          info.el.title = info.resource._resource.extendedProps.description;
          // var tooltip = new Tooltip(roomTitle, {
          //   title: info.resource.title + '!!!',
          //   placement: 'top',
          //   trigger: 'hover',
          //   container: 'body'
          // });


          // var roomTitle = document.createElement('strong');
          //   roomTitle.innerText = ' (?) ';

          //   info.el.querySelector('.fc-cell-text')
          //     .appendChild(roomTitle);

          // var tooltip = new Tooltip(roomTitle, {
          //   title: info.resource.title + '!!!',
          //   placement: 'top',
          //   trigger: 'hover',
          //   container: 'body'
          // });


        },
        locale: 'est',
        eventRender: function(info) {
          //	console.log(info.el.innerText);
          var eventTooltip = document.createElement('span');
          eventTooltip.setAttribute("data-toggle", "tooltip");
          eventTooltip.setAttribute("data-placement", "top");
          eventTooltip.setAttribute("title", (info.el.innerText).substring(0, 13) + " " + (info.el.innerText).substring(13));
          eventTooltip.innerText = (info.el.innerText).substring(0, 13) + " " + (info.el.innerText).substring(13);
          info.el.textContent = ""
          info.el.prepend(eventTooltip);
          info.el.title = info.el.innerText;
          // var tooltip = new Tooltip(info.el, {
          //   title: info.event.extendedProps.description,
          //   placement: 'top',
          //   trigger: 'hover',
          //   container: 'body'
          // });
        },
        plugins: ['interaction', 'resourceDayGrid', 'resourceTimeGrid', 'momentPlugin', 'listPlugin'],
        defaultDate: dateConvert,
        defaultView: defaultView,
        datesAboveResources: true,
        firstDay: 1,
        allDaySlot: false,
        aspectRatio: 2.5,

      //  editable: true,
      //  selectable: true,
        selectHelper: true,

        minTime: '08:00:00',
        maxTime: '22:00:00',

        snapDuration: '00:05:00',
        slotDuration: '00:30:00',

        eventTimeFormat: { // like '14:30:00'
          hour: '2-digit',
          minute: '2-digit',

          meridiem: false
        },
        slotLabelFormat: [{
            month: 'long',
            year: 'numeric'
          }, // top level of text
          {
            weekday: 'short'
          }, // lower level of text
          {
            hour: 'numeric',
            minute: '2-digit',

            meridiem: false
          }
        ],
        //   titleFormat: { // will produce something like "Tuesday, September 18, 2018"
        //     month: 'long',
        //     year: 'numeric',
        //     day: 'numeric',
        //     weekday: 'long'
        //   },

        header: {
          left: 'today',
          center: 'prev,title,next',
          right: 'resourceTimeGridDay,resourceTimeGridThreeDay,resourceTimeGridFourDay,resourceTimeGridWeek,timeGridWeek,dayGridMonth,list'
        },

        views: {
          resourceTimeGridWeek: {
            type: 'resourceTimeGrid',

            buttonText: 'Nädal',

          },
          resourceTimeGridFourDay: {
            type: 'resourceTimeGrid',
            duration: {
              days: 4
            },
            buttonText: '4 päeva'
          },
          resourceTimeGridThreeDay: {
            type: 'resourceTimeGrid',
            duration: {
              days: 3
            },
            buttonText: '3 päeva',
          },
          resourceTimeGridDay: {
            type: 'resourceTimeGridDay',
            buttonText: 'Päev',
          },
          timeGridWeek: {
            buttonText: 'Nädal 2',
          },
          dayGridMonth: {
            buttonText: 'Kuu',
          },
          list: {
            buttonText: 'list',
          }

        },


        //allDaySlot: false,

        resources: {
          url: "<?php echo base_url(); ?>allbookings/loadRooms/<?php echo $this->session->userdata['building']; ?>" // use the `url` property
        },
        eventSources: [{
          url: "<?php echo base_url(); ?>allbookings/load/<?php echo $this->session->userdata['building']; ?>" // use the `url` property
        }],
        //When u select some space in the calendar do the following:
        select: function(start, end, allDay) {
          //do something when space selected
          //Show 'add event' modal
          $('#createEventModal').modal('show');
        },
        dateClick: function(arg) {
          // console.log(
          //   'dateClick',
          //   arg.date,
          //   arg.resource ? arg.resource.id : '(no resource)'
          // );
        },
        //When u drop an event in the calendar do the following:
        eventDrop: function(event, delta, revertFunc) {
          //do something when event is dropped at a new location
        },

        //When u resize an event in the calendar do the following:
        eventResize: function(event, delta, revertFunc) {
          //do something when event is resized
        },

        //Activating modal for 'when an event is clicked'
        eventClick: function(info, element) {
          $('#modalTitle').html(info.el.title);
          $('#modalBody1').html(info.event.extendedProps.eventdescription);
          $('#eventUrl').attr('href', event.url);
          $('#calendarModal').modal();
        },
        loading: function(bool) {
          // alert('events are being rendered'); // Add your script to show loading
          if (!bool) {
            $(".spinner-grow").hide();
          }
        },

      }

    );


    $('#submitButton').on('click', function(e) {
      // We don't want this to act as a link so cancel the link action
      e.preventDefault();

      doSubmit();
    });

    function doSubmit() {
      $("#createEventModal").modal('hide');
      $("#calendar").fullCalendar('renderEvent', {
          title: $('#eventName').val(),
          start: new Date($('#eventDueDate').val()),

        },
        true);
    };

    calendar1.setOption('height', 951);
    calendar1.render();


    var removedResources = [];
    var removedResources2 = [];
    var removedAll = true;
    var phpRoomInfo = '<?php echo $array; ?>';
    // console.log(phpRoomInfo);

    $('input[type="checkbox"]').change(function() {
      var json = JSON.parse(phpRoomInfo);
      //	console.log(json[0].id);

      if ($(this).is(':checked')) {

        var found = false;
        for (var i = 0; i < json.length; i++) {
          if (json[i].id == $(this).val()) {
            found = i;
            break;
          }
        }
        //	console.log(found);
        calendar1.addResource(json[found]);
        index = removedResources.indexOf($(this).val());
        removedResources = [];
        removedAll = false;

      } else {
        var resourceA = calendar1.getResourceById($(this).val());
        resourceA.remove();
      }

    });

    $('#allCalenderLink').click(function(e) {
      e.preventDefault();

      window.location.href = "<?php echo base_url(); ?>fullcalendar?roomId=<?php echo $this->session->userdata('room'); ?>&date=" + moment(calendar1.getDate()).format("DD.MM.YYYY");
    });



  });
</script>