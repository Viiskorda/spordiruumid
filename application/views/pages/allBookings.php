<div class="container">



  <br>
  <div class="row ">
    <div class="col-12 col-sm-3 col-xl-2">Alates <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo date('Y-m-01'); ?>" /></div>
    <div class="col-12 col-sm-3 col-xl-2"> Kuni <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo date('Y-m-t'); ?>" /></div>
    <div class="col-6 col-md-3 col-xl-2 mt-4">
      <input type="button" id="activeOrPassiveFilter" data-id="0" class="btn btn-inactive text-white text-center py-1 px-2 txt-strong" value="Filter on väljas">
    </div>
  </div>
  <br />
  <table id="user_data" class="table  compact table-striped">

    <thead>
      <tr>

        <th>Päringu aeg</th>
        <th>Ruumi nimi</th>
        <th>Nädalapäev</th>
        <th data-priority="3">Kuupäev</th>
        <th>Alates</th>
        <th>Kuni</th>
        <th>Kestus</th>
        <th data-priority="1" class="py-2 txt-strong text-darkblue" scope="col">Kinnitatud</th>
        <th data-priority="2" class="py-2 txt-strong text-darkblue" scope="col">Klubi</th>
        <th class="py-2 txt-strong text-darkblue" scope="col">Trenn</th>
        <th class="py-2 txt-strong text-darkblue" scope="col">Kommentaar</th>
        <th class="py-2 txt-strong text-darkblue" scope="col">Kontaktisik</th>
        <th class="py-2 txt-strong text-darkblue" scope="col">Telefon</th>
        <th class="py-2 txt-strong text-darkblue" scope="col">e-mail</th>
        <th class="py-2 txt-strong text-darkblue" scope="col">Jäi ära</th>
        <th class="py-2 txt-strong text-darkblue" scope="col">Muuda või kustuta</th>



      </tr>
    </thead>

    <tfoot>

      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th colspan="2"></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tfoot>

  </table>




</div>
<script type="text/javascript" language="javascript">
  $(document).ready(function() {
    $("#user_data").append('<tfoot><th></th></tfoot>');
    $('#user_data thead tr')
      .clone(true)
      .addClass('filters')
      .appendTo('#user_data thead');

    var dataTable = create_dataTable();

    $('#start_date, #end_date').change(function() {
      dataTable.ajax.reload();
    });

    $('input[id^="activeOrPassive"]').on("click", function() {
      //  console.log($(this).data("id"));
      //  console.log($(this).val());
      var start_date = $('#start_date').val();
      var end_date = $('#end_date').val();

      if ($(this).val() == "Filter on sees") {
        $(this).val("Filter on väljas");
        $(this).removeClass("btn-custom");
        $(this).addClass("btn-inactive");
        $(this).data("id", 0);

      } else {
        $(this).val("Filter on sees");
        $(this).removeClass("btn-inactive");
        $(this).addClass("btn-custom");
        $(this).data("id", 1);
      }

      if (start_date != '' && end_date != '') {
        dataTable.ajax.reload();

        // console.log($('input[id^="activeOrPassive"]').data("id"));
        // console.log($('#start_date').val());
        // console.log($('#end_date').val());

      } else {
        alert("Both Date is Required");
      }




    });



    $('.dataTables_length').parent().removeClass('col-md-6');
    $('.dataTables_length').parent().removeClass('col-sm-12');
    $('.dataTables_filter').parent().removeClass('col-md-6');
    $('.dataTables_filter').parent().removeClass('col-sm-12');
    $('.dataTables_length').parent().addClass('col-5');
    $('.dataTables_length').parent().addClass('col-md-3');
    $('.dataTables_length').parent().addClass('col-sm-6');
    $('.dataTables_length').parent().addClass('col-xl-2');
    $('.dataTables_filter').addClass('col-12');
    $('.dataTables_filter').parent().addClass('col-12');
    $('.dataTables_filter').parent().addClass('col-md-4');
    $('.dataTables_filter').parent().addClass('col-sm-6');
    $('.dataTables_filter').parent().addClass('col-xl-2');
    dataTable;
    $('#user_data_wrapper').find('label').each(function() {
      $(this).parent().append($(this).children());
    });
    $('#user_data_wrapper .dataTables_filter').find('input').each(function() {
      const $this = $(this);
      $this.attr("placeholder", "");
      $this.removeClass('form-control-sm');
    });





  });

  function create_dataTable() {

    var dataTable = $('#user_data').DataTable({
      "lengthMenu": [
        [25, 50, 100, 200, 500],
        [25, 50, 100, 200, 500]
      ],
      "language": {
        "search": "Otsi:",
        "info": "Kuvatakse _START_ kuni _END_ rida _TOTAL_ reast",
        "lengthMenu": "Kuva  _MENU_  kirjet lehel",
        "paginate": {
          "first": "Esimene",
          "last": "Viimane",
          "next": "Järgmine",
          "previous": "Eelmine"
        },
      },
      "processing": true,
      "serverSide": true,
      "compact": true,
      "order": [],
      "ajax": {
        url: "<?php echo base_url() . 'allbookings/fetch_allbookings'; ?>",
        type: "POST",

        data: function(d) {
          d.orderBy = "orderBy",
            d.is_date_search = $('input[id^="activeOrPassive"]').data("id"),
            d.start_date = $('#start_date').val(),
            d.end_date = $('#end_date').val()
        },

      },

      "columnDefs": [{
        "targets": [2, 4, 5, 6, 15],
        "orderable": false,
      }, ],
      responsive: true,
      // "scrollX": true,
      fixedHeader: true,
      colReorder: true,
      orderCellsTop: true,




      "footerCallback": function(row, data, start, end, display) {
        var api = this.api();
        // console.log($('input[id^="activeOrPassive"]').data("id"));
        // Remove the formatting to get integer data for summation
        var intVal = function(i) {
          return typeof i === 'string' ?
            i.replace(/[\$,]/g, '') * 1 :
            typeof i === 'number' ?
            i : 0;
        };

        // Total over all pages
        total = api
          .column(6, {
            search: 'applied'
          })
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        // Total over this page
        pageTotal = api
          .column(6, {
            search: 'applied',
            page: 'current'
          })
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        // Update footer
        $(api.column(6).footer()).html(
          'Tunde lehel: ' + pageTotal /*+ ' (' + total + ' kokku)'*/
        );
        //  console.log(total);
      },


      "rowCallback": function(row, data, index) {
        var sum = 0;
        sum += data.miColumnToSum;

        //DO WHAT YOU WANT
      },

      "fnDrawCallback": function() {
        var api = this.api()
        var json = api.ajax.json();
        $(api.column(1).footer()).html(json.total);
        // console.log(json.totalSumOverPages);
        $(api.column(6).footer()).html(
          'Tunde lehel: ' + pageTotal + ' (' + json.totalSumOverPages + ' kokku)'
        );
      },


      initComplete: function() {

        var api = this.api();

        // For each column
        api
          .columns()
          .eq(0)
          .each(function(colIdx) {
            // Set the header cell to contain the input element
            var cell = $('.filters th').eq(
              $(api.column(colIdx).header()).index()
            );
            var title = $(cell).text();
            var input_type = 'text';
            // console.log(title);
            if ($(cell).text() == "Kuupäev") {
              title = '<?php echo date('Y-01-01'); ?>';
            }
            if ($(cell).text() == "Kinnitatud" || $(cell).text() == "Jäi ära") {
              title = '1 või 0';
            }
            if ($(cell).text() == "Alates" || $(cell).text() == "Kuni") {
              title = 'HH:MM';
            }
            if ($(cell).text() == "Kuni") {
              title = 'HH:MM';
            }
            if ($(cell).text() == "Nädalapäev") {
              title = 'number 0-6';
            }
            $(cell).html('<input type="' + input_type + '" placeholder="' + title + '" />');

            // On every keypress in this input
            $(
                'input',
                $('.filters th').eq($(api.column(colIdx).header()).index())
              )
              .off('keyup change')
              .on('keyup change', function(e) {
                e.stopPropagation();

                // Get the search value
                $(this).attr('title', $(this).val());
                var regexr = '{search}'; //$(this).parents('th').find('select').val();

                var cursorPosition = this.selectionStart;
                // Search the column for that value
                api
                  .column(colIdx)
                  .search(
                    this.value != '' ?
                    regexr.replace('{search}', this.value) :
                    '',
                    this.value != '',
                    this.value == ''
                  )
                  .draw();

                $(this)
                  .focus()[0]
                  .setSelectionRange(cursorPosition, cursorPosition);
              });
          });
      }


    });

    return dataTable;
  }
</script>