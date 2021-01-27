
 <head>  
  
      <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>   -->
      <!-- <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>   -->


      <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
      
      <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" />   -->

 </head>  


      <div class="container">  
	
	
	 	
		<br>
		<div class="row ">
			<div class="col-12 col-sm-3 col-xl-2">Alates  <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo date('Y-m-01'); ?>" /></div>  
			<div class="col-12 col-sm-3 col-xl-2"> Kuni <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo date('Y-m-t'); ?>" /></div>
			<div class="col-6 col-md-3 col-xl-2 mt-4">
				<input type="button" name="search" id="search" value="Filtreeri" class="btn btn-info text-white" />
			</div>
		</div>
	
		
		      <br/>  
                <table id="user_data" class="table  compact table-striped"> 
				 
                     <thead>  
                          <tr>  

					 <th >Päringu aeg</th>  
                               <th >Ruumi nimi</th>  
							   <th>Nädalapäev</th>  
							   <th  data-priority="3">Kuupäev</th>  
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
			 </table>  
		
		
			
 
 </div>
 <script type="text/javascript" language="javascript" >  
 $(document).ready(function(){  
      var dataTable = $('#user_data').DataTable({  
		"lengthMenu": [[ 25, 50, 100, 200, 500], [ 25, 50, 100, 200, 500]],
		"language": {
    "search": "Otsi:",
    "info":           "Kuvatakse _START_ kuni _END_ rida _TOTAL_ reast",
    "lengthMenu":     "Kuva  _MENU_  kirjet lehel",
    "paginate": {
        "first":      "Esimene",
        "last":       "Viimane",
        "next":       "Järgmine",
        "previous":   "Eelmine"
    },
  },
           "processing":true,  
           "serverSide":true,  
		 "compact":true,
           "order":[],  
           "ajax":{  
                url:"<?php echo base_url() . 'allbookings/fetch_allbookings'; ?>",  
                type:"POST",  data:{
				orderBy:"orderBy"
			},
			
           },  
		
           "columnDefs":[  
                {  
                     "targets":[2, 4, 5, 6, 15],  
                     "orderable":false,  
                },  
           ], 
		 responsive: true ,
		// "scrollX": true,
		 fixedHeader: true,
		 colReorder: true
      });  

	

function fetch_data(is_date_search, start_date, end_date)
 {
  var dataTable = $('#user_data').DataTable({
	"lengthMenu": [[25, 50, 100, 200, 500], [ 25, 50, 100, 200, 500]],
   "processing" : true,
   "language": {
    "search": "Otsi:",
    "info":           "Kuvatakse _START_ kuni _END_ rida _TOTAL_ reast",
    "lengthMenu":     "Kuva  _MENU_  kirjet lehel",
    "paginate": {
        "first":      "Esimene",
        "last":       "Viimane",
        "next":       "Järgmine",
        "previous":   "Eelmine"
    },
  },
   "serverSide" : true,
   "order" : [],
   "ajax" : {
	url:"<?php echo base_url() . 'allbookings/fetch_allbookings'; ?>",  
    type:"POST",
    data:{
     is_date_search:is_date_search, start_date:start_date, end_date:end_date
    },
    
   },
   "columnDefs":[  
                {  
                     "targets":[2, 4, 5, 6, 15],  
                     "orderable":false,  
                },  
           ],  
		 responsive: true,
		// "scrollX": true,
		 fixedHeader: true,
		 colReorder: true
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
 $('#user_data').DataTable();
  $('#user_data_wrapper').find('label').each(function () {
    $(this).parent().append($(this).children());
  });
  $('#user_data_wrapper .dataTables_filter').find('input').each(function () {
    const $this = $(this);
    $this.attr("placeholder", "");
    $this.removeClass('form-control-sm');
  });


 }



$('#search').click(function(){
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  if(start_date != '' && end_date !='')
  {
   $('#user_data').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
 //  console.log("help"+ dataTable.columns(':visible').count());
 //console.log(dataTable.columns(0).count());
  }
  else
  {
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
 $('#user_data').DataTable();
  $('#user_data_wrapper').find('label').each(function () {
    $(this).parent().append($(this).children());
  });
  $('#user_data_wrapper .dataTables_filter').find('input').each(function () {
    const $this = $(this);
    $this.attr("placeholder", "");
    $this.removeClass('form-control-sm');
  });





} ); </script>  
