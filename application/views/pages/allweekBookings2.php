<head>
  <meta charset='utf-8' />
  <link href='<?php echo base_url(); ?>assets/fullcalendar-scheduler-5.11.0/lib/main.css' rel='stylesheet' />
  <script src='<?php echo base_url(); ?>assets/fullcalendar-scheduler-5.11.0/lib/main.js'></script>
  <!-- moment lib -->
  <script src='https://cdn.jsdelivr.net/npm/moment@2.27.0/min/moment.min.js'></script>


  <!-- the moment-to-fullcalendar connector. must go AFTER the moment lib -->
  <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/moment@5.5.0/main.global.min.js'></script>


  <div class="container">
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

    $containsSearch  = in_array($this->input->get("roomID"), array_column($rooms, 'id'));
   
    foreach ($rooms as $value) {
     
      if ($this->input->get("roomID") == $value['id']) {
        $checked='checked';
      } else if (!$containsSearch && $this->session->userdata('room')== $value['id']){
        $checked='checked';
      }
      else
      $checked='';
        echo  ' <span style="background-color:' . $value['roomColor'] . ' !important;-webkit-print-color-adjust: exact; "><input type="checkbox" id="' . $value['id'] . '" name="vehicle" value="' . $value['id'] .  '" '.$checked.'>
  <label  for="vehicle1"> ' . $value['roomName'] . '&nbsp; </label></span> ';
    } ?>
    <div id='calendar'></div>

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
          <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
        </div>
      </div>
    </div>
  </div>




  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  <script>
    //https://stackoverflow.com/questions/48427602/dynamically-toggle-resource-column-visibility
    var resourceData = '<?php echo $rooms_resource; ?>';
    resourceData =JSON.parse(resourceData);

    console.log(resourceData);
    var visibleResourceIds = [1];

    $("input:checkbox").each(function() {
      if ($(this).is(':checked')) {
        visibleResourceIds.push($(this).val())
      }

    });

    console.log(visibleResourceIds);

    // Your button/dropdown will trigger this function. Feed it resourceId.
    function toggleResource(resourceId) {
      var index = visibleResourceIds.indexOf(resourceId);
      if (index !== -1) {
        visibleResourceIds.splice(index, 1);
      } else {
        visibleResourceIds.push(resourceId);
      }
      $('#calendar').fullCalendar('refetchResources');
    }



    document.addEventListener('DOMContentLoaded', function() {
      var copyKey = false;
      $(document).keydown(function(e) {
        copyKey = e.shiftKey;
      }).keyup(function() {
        copyKey = false;
      });

      const urlParams = new URLSearchParams(window.location.search);
      var theUrlDate = urlParams.get('date');
      if (!theUrlDate) {
        theUrlDate = moment(new Date()).format("DD.MM.YYYY");
      }
      //console.log(theUrlDate);

      var defaultView = 'resourceTimeGridWeek';
      if (window.innerWidth < 800) {
        defaultView = 'resourceTimeGridDay';
      } else if (window.innerWidth < 1400) {
        defaultView = 'resourceTimeGridFourDay';
      }


      var calendarEl = document.getElementById('calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {

        resourceLabelDidMount: function(info) {
          info.el.style.backgroundColor = info.resource.eventBackgroundColor;
          info.el.title = info.resource._resource.extendedProps.description;

        },
        expandRows: true,
        stickyHeaderDates: true,
        stickyFooterScrollbar: true,
        eventMouseEnter: function(info) {
          // console.log(info);
          var roomTitle = document.createElement('span');
          roomTitle.innerText = info.event.title;
          var durationTime = moment(info.event.start).format('HH') + ":" + moment(info.event.start).format('mm') + " - " + moment(info.event.end).format('HH') + ":" + moment(info.event.end).format('mm')

          info.el.title = info.event.extendedProps.roomName + ' \n' + durationTime + ' \n' + info.event.extendedProps.eventdescription + ' \n' + info.event.title;
        },

        eventMouseLeave: function(info) {

        },


        eventContent: function(info) {
          // console.log(info);
          if (info.event.extendedProps.approved == true) {
            // arg.element.css('border', '1px solid #DDD');
            // arg.element.css('border-left', '7px solid #1A7AB7');
            info.backgroundColor = info.event.extendedProps.bookingTimeColor;

          }
        },



        initialView: defaultView,
        datesAboveResources: true,
        //  dayMinWidth: 40,
        locale: 'est',
        firstDay: 1,
        allDaySlot: false,

        snapDuration: '00:15:00',
        slotMinTime: '08:00:00',
        slotMaxTime: '22:00:00',

        eventTimeFormat: { // like '14:30:00'
          hour: '2-digit',
          minute: '2-digit',

          meridiem: false
        },
        editable: true,
        selectable: true,

        headerToolbar: {
          left: 'today,prev,next',
          center: 'title',
          right: 'resourceTimeGridDay,resourceTimeGridTwoDay,resourceTimeGridThreeDay,resourceTimeGridFourDay,resourceTimeGridWeek,timeGridWeek,dayGridMonth'
        },

        views: {
          resourceTimeGridDay: {
            type: 'resourceTimeGridDay',
            buttonText: 'Päev',
          },
          resourceTimeGridTwoDay: {
            type: 'resourceTimeGrid',
            duration: {
              days: 2
            },
            buttonText: '2 days',
          },
          resourceTimeGridThreeDay: {
            type: 'resourceTimeGrid',
            duration: {
              days: 3
            },
            buttonText: '3 päeva',
          },
          resourceTimeGridFourDay: {
            type: 'resourceTimeGrid',
            duration: {
              days: 4
            },
            buttonText: '4 päeva'
          },
          resourceTimeGridWeek: {
            type: 'resourceTimeGrid',

            buttonText: 'Nädal',

          },
          timeGridWeek: {
            buttonText: 'Nädal 2',
          },
          dayGridMonth: {
            buttonText: 'Kuu',
          },
          list: {
            buttonText: 'list',
          },


        },

        //// uncomment this line to hide the all-day slot
        //allDaySlot: false,

        resources: function(fetchInfo, successCallback, failureCallback) {
          // Filter resources by whether their id is in visibleResourceIds.
          var filteredResources = [];
          filteredResources = resourceData.filter(function(x) {
            return visibleResourceIds.indexOf(x.id) !== -1;
          });
          successCallback(filteredResources);
        },
        eventSources: [{
          url: "<?php echo base_url(); ?>allbookings/load/<?php echo $this->session->userdata['building']; ?>" // use the `url` property
        }],
        resourceOrder: 'title',

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
        },
        businessHours: {
          // days of week. an array of zero-based day of week integers (0=Sunday)


          startTime: '06:00', // a start time (10am in this example)
          endTime: '22:00', // an end time (6pm in this example)
        },



        eventResize: function(event) {
          //	console.log(event.end._i);
          //	console.log( $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss"));

          var checkIfIsAfter8 = moment(event.event.start).toDate();
          var checkIfIsBefore22 = moment(event.event.end).toDate();
          var startDate = createDateTime("06:00", moment(event.event.start).toDate());
          var endDate = createDateTime("23:00", moment(event.event.end).toDate());
          var isBetween = startDate <= checkIfIsAfter8 && checkIfIsAfter8 <= endDate;
          var isBetween2 = startDate <= checkIfIsBefore22 && checkIfIsBefore22 <= endDate;


          if (!(isBetween && isBetween2)) {
            swal({
              icon: 'info',
              title: "Trenn peab jääma vahemikku 06:00-22:00!",
              buttons: "sulge"

            })
            calendar.refetchEvents();
            // event.preventDefault();
            return;

          }

          //if ajax is still working then do not go further
          if ($.active) {
            setTimeout(function() {
              calendar.refetchEvents()
            }, 200);
            return
          }

          swal({
            title: "Kas salvestada? ",
            buttons: {
              cancel: "Ära salvesta",
              Salvesta: true,
            },
          }).then(function(value) {
            if (value) {
              swal({
                title: "Muudatus on salvestatud",
                text: "Soovi korral kirjuta põhjendus",
                content: "input",
                buttons: "Salvesta"
              }).then(function(value2) {

                $.ajax({
                  url: "<?php echo base_url(); ?>allbookings/updateEvent",
                  type: "POST",
                  data: {
                    start: moment(event.event.start).format("Y-MM-DD HH:mm:ss"),
                    end: moment(event.event.end).format("Y-MM-DD HH:mm:ss"),
                    timeID: event.event.extendedProps.timeID,
                    selectedRoomID: event.event._def.resourceIds[0],
                    versionStart: moment(event.oldEvent.start).format("Y-MM-DD HH:mm:ss"),
                    versionEnd: moment(event.oldEvent.end).format("Y-MM-DD HH:mm:ss"),
                    versionNameWhoChanged: '<?php echo $this->session->userdata('userName'); ?>',
                    reason: value2,

                  },
                  success: function() {

                    setTimeout(function() {
                      calendar.refetchEvents()
                    }, 200);

                  }
                });
              })
            } else {
              setTimeout(function() {
                calendar.refetchEvents()
              }, 200);
            }

          })


        },

        eventDrop: function(event) {

          var checkIfIsAfter8 = moment(event.event.start).toDate();
          var checkIfIsBefore22 = moment(event.event.end).toDate();
          var startDate = createDateTime("6:00", moment(event.event.start).toDate());
          var endDate = createDateTime("23:00", moment(event.event.end).toDate());
          var isBetween = startDate <= checkIfIsAfter8 && checkIfIsAfter8 <= endDate;
          var isBetween2 = startDate <= checkIfIsBefore22 && checkIfIsBefore22 <= endDate;

          if (!(isBetween && isBetween2)) {
            swal({
              icon: 'info',
              title: "Trenn peab jääma vahemikku 06:00-22:00!",
              buttons: "sulge"

            })
            calendar.refetchEvents()
            return;

          }

          //if ajax is still working then do not go further
          if ($.active) {
            setTimeout(function() {
              calendar.refetchEvents()
            }, 200);
            return
          }

          //   console.log(event);
          var eClone = {
            start: moment(event.event.start).format("Y-MM-DD HH:mm:ss"),
            end: moment(event.event.end).format("Y-MM-DD HH:mm:ss"),
            bookingID: event.event.extendedProps.bookingID,
            color: event.event.extendedProps.bookingTimeColor,
            takesPlace: event.event.extendedProps.takesPlace,
            approved: event.event.extendedProps.approved,
            typeID: event.event.extendedProps.typeID,
            selectedRoomID: event.event._def.resourceIds[0],
          };
          //  console.log(eClone);


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
                  url: "<?php echo base_url(); ?>fullcalendar/insert",
                  type: "POST",
                  data: eClone,
                  success: function() {
                    //console.log( eClone);
                    setTimeout(function() {
                      calendar.refetchEvents()
                    }, 200);
                    $.ajax({
                      url: "<?php echo base_url(); ?>fullcalendar/getUnapprovedBookings",
                      success: function(res) {
                        $('.badge.badge-danger').text(res);
                      }
                    });

                  }
                })
              } else {
                calendar.refetchEvents()
                return;
              }
            });
            //	event.preventDefault();


          } else {

            swal({
              title: "Kas salvestada? ",
              text: "*Lisaaja salvestamiseks hoia lohistamise ajal SHIFT klahvi all",
              buttons: {
                cancel: "Ära salvesta",
                Salvesta: true,
              },
            }).then(function(value) {
              if (value) {
                swal({
                  title: "Muudatus on salvestatud",
                  text: "Soovi korral kirjuta põhjendus",
                  content: "input",
                  buttons: "Salvesta"
                }).then(function(value2) {

                  var data = {
                    start: moment(event.event.start).format("Y-MM-DD HH:mm:ss"),
                    end: moment(event.event.end).format("Y-MM-DD HH:mm:ss"),
                    timeID: event.event.extendedProps.timeID,
                    selectedRoomID: event.event._def.resourceIds[0],
                    versionStart: moment(event.oldEvent.start).format("Y-MM-DD HH:mm:ss"),
                    versionEnd: moment(event.oldEvent.end).format("Y-MM-DD HH:mm:ss"),
                    versionNameWhoChanged: '<?php echo $this->session->userdata('userName'); ?>',
                    reason: value2,

                  };
                  //   console.log(data);

                  $.ajax({
                    url: "<?php echo base_url(); ?>allbookings/updateEvent",
                    type: "POST",
                    data: data,
                    success: function() {

                      setTimeout(function() {
                        calendar.refetchEvents()
                        return;
                      }, 200);
                      $.ajax({
                        url: "<?php echo base_url(); ?>fullcalendar/getUnapprovedBookings",
                        success: function(res) {
                          $('.badge.badge-danger').text(res);
                        }
                      });
                    }

                  });
                })
              } else {
                calendar.refetchEvents()
                return;
              }

            })





          }

        },


        eventClick: function(info) {
          // alert('Event: ' + info.event.title + ' '+ info.event.extendedProps.eventdescription);
          // alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
          // alert('View: ' + info.view.type);
          console.log(info.event.extendedProps.roomName);

          // change the border color just for fun
          //info.el.style.borderColor = 'red';
          var durationTime = moment(info.event.start).format('HH') + ":" + moment(info.event.start).format('mm') + " - " + moment(info.event.end).format('HH') + ":" + moment(info.event.end).format('mm')


          //$('#modalTitle').html();
          $('#modalBody1').html('<p>' + info.event.extendedProps.roomName + '</p><p>' + durationTime + ' </p><p>' + info.event.extendedProps.eventdescription + ' </p><p> ' + info.event.title + '</p>');
          $('#calendarModal').modal();
        },
        loading: function(bool) {
          // alert('events are being rendered'); // Add your script to show loading
          if (!bool) {
            $(".spinner-grow").hide();
          }
          if (bool) { // isLoading gives boolean value
            //show your loader here 
            $("body").css("cursor", "wait");
          } else {
            //hide your loader here
            $("body").css("cursor", "default");
          }

        },



      });

      calendar.setOption('aspectRatio', 2.5);
      calendar.setOption('height', 951);
      // calendar.setOption('contentHeight', 910);
      calendar.render();


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
          calendar.addResource(json[found]);
          index = removedResources.indexOf($(this).val());
          removedResources = [];
          removedAll = false;

        } else {
          var resourceA = calendar.getResourceById($(this).val());
          resourceA.remove();
        }

      });




      $('#allCalenderLink').click(function(e) {
        e.preventDefault();

        window.location.href = "<?php echo base_url(); ?>/fullcalendar?roomId=<?php echo $this->session->userdata('room'); ?>&date=" + moment(calendar.getDate()).format("DD.MM.YYYY");
      });


      function createDateTime(time, given_date) {
        var splitted = time.split(':');
        if (splitted.length != 2) return undefined;

        var date = new Date(given_date);
        date.setHours(parseInt(splitted[0], 10));
        date.setMinutes(parseInt(splitted[1], 10));
        date.setSeconds(0);
        return date;
      }

    });
  </script>

</head>

<body>



</body>

</html>