<div class="container">
    <div class="table-container mt-3">
       <div class="mb-2 pb-5">
		<?php if( $this->session->userdata('roleID')==='1' || $this->session->userdata('roleID')==='2'):?>
			<a class="btn btn-custom text-white text-center py-2 px-sm-2 px-lg-5 px-md-4 float-right pluss cursor-pointer" onclick="location.href='<?php echo base_url(); ?>users/addRightsToUser';">
                <p class="m-0 txt-lg txt-strong text-center cursor-pointer">Lisa õigused või asutuse kasutaja</p>
            </a>
		<?php endif; ?>
        </div>
		<?php if( $this->session->userdata('roleID')==='1'){?>
			<h4	>	Asutuste kasutajad</h4>
        <table class="table-borderless table-responsive-md table-users mt-3">
            <thead class="bg-grey border-bottom ">
                <tr>
				<th class="pl-3 py-2 txt-strong text-darkblue" scope="col">Email</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Nimi</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Telefon</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Asutus</th>
					<th class="py-2 txt-strong text-darkblue" scope="col">Roll</th>
					<th class="py-2 txt-strong text-darkblue" scope="col">Viimane sisse logimine</th>
                    <!-- <th class="py-2 txt-strong text-darkblue" scope="col">Staatus</th> -->
                    <th class="py-2 txt-strong text-darkblue" scope="col"></th>
					<th class="py-2 txt-strong text-darkblue" scope="col"></th>
                </tr>
            </thead>
            <tbody class="">
            <?php foreach($manageUsers as $singleUser) : ?>
                <tr>
				<td class="pl-3 p-1 text-darkblue border-bottom-light"><?php echo $singleUser['email']; ?></td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php echo $singleUser['userName']; ?></td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php echo $singleUser['userPhone']; ?></td>

                    <td class="p-1 text-darkblue border-bottom-light"><?php if (!is_array($singleUser['buildingName'])){
						echo $singleUser['buildingName'].'<br>';
					} else {
						foreach ($singleUser['buildingName'] as $role){
							echo $role.'<br>'; }
					} 
					 ?></td>

					<td class="p-1 text-darkblue border-bottom-light"><?php if (!is_array($singleUser['roleName'])){
						echo $singleUser['roleName'].'<br>';
					} else {
						foreach ($singleUser['roleName'] as $role){
							echo $role.'<br>'; }
					} 
					 ?> </td>
					
				
					<td class="p-1 text-darkblue border-bottom-light"><?php echo $singleUser['last_login']; ?> </td>
					<td class="p-1 text-darkblue border-bottom-light">
					<?php if (empty($singleUser['additionalBuilding'])){  ?>
						<form class="cat-delete" action="users/edit/<?php echo $singleUser['userID'].'/'. $singleUser['buildingID'] ?>" method="POST">
						<button type="submit" class="btn btn-second btn-width-mg text-white text-center py-1 px-2 mb-1 txt-strong ">Halda õigusi</button>
					</form>

						<?php 	} else {
						foreach ($singleUser['additionalBuilding'] as $buildingID){ ?>
							<form class="cat-delete" action="users/edit/<?php echo $singleUser['userID'].'/'. $buildingID ?>" method="POST">
							<button type="submit" class="btn btn-second btn-width-mg text-white text-center py-1 px-2 mb-1 txt-strong ">Halda õigusi</button>
						</form>
						<?php } ?>
					<?php } ?>
					</td>
                    <!-- <td class="p-1 text-darkblue border-bottom-light"><?php if( $singleUser['status']==1){ echo "Aktiivne";} else {echo "Mitteakviivne";} ?></td> -->
					<td class="p-1 text-darkblue border-bottom-light">
					

                      
                        <?php if($this->session->userdata('roleID')==='1'):?>

							<?php if (empty($singleUser['additionalBuilding'])){  ?>
								<form class="cat-delete pl-1" action="users/delete" method="POST">
							<input type="hidden" name="userID" value="<?php echo $singleUser['userID']; ?>" />
                            <input type="submit" value="Kustuta" class="btn btn-delete btn-width text-white text-center py-1 px-2 mb-1 txt-strong " onclick="return confirm('Oled kindel kustutada kasutaja <?php echo $singleUser['userName']; ?>?')" />
                        </form>

						<?php 	} else {
						foreach ($singleUser['additionalBuilding'] as $buildingID){ ?>
									<form class="cat-delete pl-1" action="users/delete" method="POST">
							<input type="hidden" name="userID" value="<?php echo $singleUser['userID']; ?>" />
                            <input type="submit" value="Kustuta" class="btn btn-delete btn-width text-white text-center py-1 px-2 mb-1 txt-strong " onclick="return confirm('Oled kindel kustutada kasutaja <?php echo $singleUser['userName']; ?>?')" />
                        </form>
						<?php } ?>
					<?php } ?>

				


                        <?php endif;?>
                    </td>
                </tr>                
            <?php endforeach; ?>
		</tbody>

		</table>
		
		<?php ;} ?>

		<?php if( $this->session->userdata('roleID')==='2'|| $this->session->userdata('roleID')==='3'){?>
			<h4	>	Asutuse kasutajad</h4>
			<table class="table-borderless table-responsive-md table-users mt-3">
            <thead class="bg-grey border-bottom ">
                <tr>
                    <th class="pl-3 py-2 txt-strong text-darkblue" scope="col">Email</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Nimi</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Telefon</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Asutus</th>
                    <th class="py-2 txt-strong text-darkblue" scope="col">Roll</th>
                    <!-- <th class="py-2 txt-strong text-darkblue" scope="col">Staatus</th> -->
                    <th class="py-2 txt-strong text-darkblue" scope="col"></th>
                </tr>
            </thead>
            <tbody class="">
			<?php foreach($manageUsers as $singleUser) : 
				if($singleUser['buildingID']==$this->session->userdata('building')){?>
                <tr>
                    <td class="pl-3 p-1 text-darkblue border-bottom-light"><?php echo $singleUser['email']; ?></td>
					<td class="p-1 text-darkblue border-bottom-light"><?php if($singleUser['requestFromBuilding']=='1'){echo '/Nimi ja telefon kuvatakse kui kasutaja on teie kutset aktsepteerinud/';}else {echo $singleUser['userName'];} ?></td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php if($singleUser['requestFromBuilding']=='1'){echo '';}else {echo $singleUser['userPhone'];} ?></td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php echo $singleUser['name']; ?></td>
                    <td class="p-1 text-darkblue border-bottom-light"><?php echo $singleUser['role']; ?></td>
                    <!-- <td class="p-1 text-darkblue border-bottom-light"><?php if( $singleUser['status']==1){ echo "Aktiivne";} else {echo "Mitteakviivne";} ?></td> -->
                    <td class="d-flex justify-content-end p-1 pr-3">
					<?php if($this->session->userdata('roleID')==='2'):?>
                       	<form class="cat-delete" action="users/edit/<?php echo $singleUser['userID'].'/'. $singleUser['buildingID'] ?>" method="POST">
                            <button type="submit" class="btn btn-second btn-width-mg text-white text-center py-1 px-2 txt-strong ">Halda õigusi</button>
						</form>
						<?php endif;?>
                     
                    </td>
                </tr>                
            <?php ;}  endforeach; ?>
		</tbody>

		</table>
		
		<?php ;} ?>
<?php if($this->session->userdata('roleID')==='1'):?>
	</div></div>
<?php endif;?>


<?php if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'):?>


 <head>  
  
 <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>          
 
 </head>  
 



 


 <div style="max-width: 800px;  margin: auto;">
	  <br>  <br>

<h4	>Broneeringute kontaktid:</h4>
	 


	
         
    
	
                <br/>  
                <table id="user_data" class="table table-striped">  
				 
                     <thead>  
                          <tr>  
							<th class="py-2 txt-strong text-darkblue" scope="col">Klubi</th>
							<th class="py-2 txt-strong text-darkblue" scope="col">Kontaktisik</th>
							<th class="py-2 txt-strong text-darkblue" scope="col">Telefon</th>
							<th class="py-2 txt-strong text-darkblue" scope="col">e-mail</th>
			  
                          </tr>  
                     </thead>  
                </table>  
           </div>  
      </div>  
 



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
    }
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
		
		//    "scrollX": true,
           responsive: true,
		   fixedHeader: true
      });  



 });  
 </script>  
  <?php endif; ?>
