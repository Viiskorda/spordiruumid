<div class="container">
    <div class="table-container mt-3">
        <div class="mb-2 pb-5">
            <a class="btn btn-custom text-white text-center py-2 px-sm-2 px-lg-5 px-md-4 float-right pluss cursor-pointer" onclick="location.href='<?php echo base_url(); ?>createUser';">
                <p class="m-0 txt-lg txt-strong text-center cursor-pointer">Lisa uus</p>
            </a>
        </div>
	<h4	>	Asutuse kasutajad</h4>
		<?php if( $this->session->userdata('roleID')==='1'){?>
		
        <table class="table-borderless table-users mt-3">
            <thead class="bg-grey border-bottom ">
                <tr>
                    <th class="pl-3 py-2 txt-strong text-darkblue" scope="col">Nimi</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Email</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Telefon</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Asutus</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Roll</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Staatus</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col"></th>
                </tr>
            </thead>
            <tbody class="">
            <?php foreach($manageUsers as $singleUser) : ?>
                <tr>
                    <td class="pl-3 p-1 text-darkblue border-bottom-light"><?php echo $singleUser['userName']; ?></td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php echo $singleUser['email']; ?></td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php echo $singleUser['userPhone']; ?></td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php echo $singleUser['name']; ?></td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php echo $singleUser['role']; ?> &nbsp; &nbsp;</td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php if( $singleUser['status']==1){ echo "Aktiivne";} else {echo "Mitteakviivne";} ?></td>
                    <td class="d-flex justify-content-end p-1 pr-3">
                        <form class="cat-delete" action="users/edit/<?php echo $singleUser['userID']; ?>" method="POST">
                            <button type="submit" class="btn btn-second btn-width text-white text-center py-1 px-2 txt-strong ">Muuda</button>
                        </form>
                        <?php if($this->session->userdata('roleID')==='1'):?>
                        <form class="cat-delete pl-1" action="users/delete/<?php echo $singleUser['userID']; ?>" method="POST">
                            <button type="submit" class="btn btn-delete btn-width text-white text-center py-1 px-2 txt-strong ">Kustuta</button>
                        </form>
                        <?php endif;?>
                    </td>
                </tr>                
            <?php endforeach; ?>
		</tbody>

		</table>
		
		<?php ;} ?>

		<?php if( $this->session->userdata('roleID')==='2'){?>
        <table class="table-borderless table-users mt-3">
            <thead class="bg-grey border-bottom ">
                <tr>
                    <th class="pl-3 py-2 txt-strong text-darkblue" scope="col">Nimi</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Email</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Telefon</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Asutus</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Roll</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Staatus</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col"></th>
                </tr>
            </thead>
            <tbody class="">
			<?php foreach($manageUsers as $singleUser) : 
				if($singleUser['buildingID']==$this->session->userdata('building')){?>
                <tr>
                    <td class="pl-3 p-1 text-darkblue border-bottom-light"><?php echo $singleUser['userName']; ?></td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php echo $singleUser['email']; ?></td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php echo $singleUser['userPhone']; ?></td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php echo $singleUser['name']; ?></td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php echo $singleUser['role']; ?> &nbsp; &nbsp;</td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php if( $singleUser['status']==1){ echo "Aktiivne";} else {echo "Mitteakviivne";} ?></td>
                    <td class="d-flex justify-content-end p-1 pr-3">
                        <form class="cat-delete" action="users/edit/<?php echo $singleUser['userID']; ?>" method="POST">
                            <button type="submit" class="btn btn-second btn-width text-white text-center py-1 px-2 txt-strong ">Muuda</button>
                        </form>
                        <?php if($this->session->userdata('roleID')==='1'):?>
                        <form class="cat-delete pl-1" action="users/delete/<?php echo $singleUser['userID']; ?>" method="POST">
                            <button type="submit" class="btn btn-delete btn-width text-white text-center py-1 px-2 txt-strong ">Kustuta</button>
                        </form>
                        <?php endif;?>
                    </td>
                </tr>                
            <?php ;}  endforeach; ?>
		</tbody>

		</table>
		
		<?php ;} ?>




 <head>  
  
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
     
      <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
      <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>            
      <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />  
   <style>  
         
      </style>  
 </head>  
 <body> 
 
      <div class="container box">  
	  <br>  <br>

<h4	>Broneeringute kontaktid</h4>
	 
<br>

	 <div class="form-row">
	
    <div class="col-md-2">
   
    <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo date('Y-m-01'); ?>" />
    </div>
    <div class="col-md-2">
   
    <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo date('Y-m-t'); ?>" />
    </div>
    <div>
  <div class="col-md-2">
	  
      <input type="button" name="search" id="search" value="Filtreeri" class="btn btn-info" />
	</div>	</div>
	</div>
         
    
	
                <br />  
                <table id="user_data" class="table table-striped">  
				 
                     <thead>  
                          <tr>  

					 <th >Salvestamise aeg</th>  
                         
                    <th class="py-2 txt-strong text-darkblue" scope="col">Klubi</th>
          
					<th class="py-2 txt-strong text-darkblue" scope="col">Kontaktisik</th>
					<th class="py-2 txt-strong text-darkblue" scope="col">Telefon</th>
					<th class="py-2 txt-strong text-darkblue" scope="col">e-mail</th>
			
				              
                          </tr>  
                     </thead>  
                </table>  
           </div>  
      </div>  
 </body>  



 </div>
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
        "previous":   "Eelmine",
    },
	"infoFiltered":   ""
  },
           "processing":true,  
           "serverSide":true,  
		
           "order":[],  
           "ajax":{  
                url:"<?php echo base_url() . 'users/fetch_allbookingsInfo'; ?>",  
                type:"POST",  data:{
				orderBy:"orderBy"
			},
			
           },  
		
          
          
      });  

	

function fetch_data(is_date_search, start_date='', end_date='')
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
	"infoFiltered":   ""
  },
   "serverSide" : true,
   "order" : [],
   "ajax" : {
	url:"<?php echo base_url() . 'users/fetch_allbookingsInfo'; ?>",  
    type:"POST",
    data:{
     is_date_search:is_date_search, start_date:start_date, end_date:end_date
    },
    
   },
 
  });
 }



$('#search').click(function(){
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  if(start_date != '' && end_date !='')
  {
   $('#user_data').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
  }
  else
  {
   alert("Both Date is Required");
  }
 }); 

 
 });  
 </script>  
