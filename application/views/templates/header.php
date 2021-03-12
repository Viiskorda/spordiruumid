<!-- Hello 
You can download this project on https://github.com/Viiskorda/spordiruumid
GPLv3
Under the GPL license, you may use Scheduler and this project without charge. You may even modify its source code and redistribute it under the same license. However, there is one big caveat. Any project that leverages Scheduler must be open source!
-->
<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <title>Pärnu Spordikeskuste Andmebaas</title>

    <!-- Styles -->
    
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/datepicker.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-clockpicker.min.css" type="text/css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">  
    <link href="<?php echo base_url(); ?>assets/css/calendar.css" rel="stylesheet">
		<link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
		<link href="https://cdn.datatables.net/colreorder/1.5.2/css/colReorder.dataTables.min.css" rel="stylesheet">
		
    
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css"/>
 	
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/fh-3.1.6/datatables.min.css"/>
 

 
  
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
		 <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script> -->

		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
		<script src="<?php echo base_url(); ?>assets/js/jquery-clock-timepicker.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>
		<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>    
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/fh-3.1.6/datatables.min.js"></script>
		<script src="	https://cdn.datatables.net/colreorder/1.5.2/js/dataTables.colReorder.min.js  "></script>  

	
</head>

<body>
<?php 
// echo '<pre>';
print_r($this->session->all_userdata());
// echo '</pre>';
 ?>
<!-- Navigation -->
    <header>
        <nav class="navbar navbar-expand-md p-0 nav-bg">
            <div class="container p-0">
   

                <div class="navbar-header pr-lg-5 pr-md-3 pr-sm-1 pl-0">
                <a class="navbar-brand mr-1 py-1" href="<?php echo base_url(); ?>"><img class="logo" src="<?php echo base_url(); ?>assets/img/plv_vapp_blue.svg" alt="logo"></a>
                    <a class="navbar-brand align-middle p-0 text-white" href="<?php echo base_url(); ?>">Pärnu Linnavalitsus</a>
                </div>
                <!-- <button >"Logi sisse"</button> -->
                
								<button class="navbar-toggler navbar-dark " type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
					</button>
		
			
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav mr-auto mt-lg-0 pl-lg-3 pl-md-2 pl-sm-1">
								
									<?php if(array_key_exists('building',$this->session->userdata())){?>
											<?php if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'):?>
											<?php	if($this->session->userdata('room')){?>
													<!-- <li class="nav-item"><a class="nav-link text-white py-0 pr-lg-5 pr-md-2 pr-sm-1 mr-lg-0 mr-md-0 mr-sm-0" href="#"><strong>Kõik ruumid</strong></a></li> -->
													<li class="nav-item"><a class="nav-link text-white py-0 pr-lg-5 pr-md-2 pr-sm-1 mr-lg-0 mr-md-0 mr-sm-0" href="<?php echo base_url(); ?>fullcalendar?roomId=<?php echo $this->session->userdata('room');?>"> 	<?php if($menu=='calendar'){echo ' <strong><u>Kalender</u></strong>';} else {echo 'Kalender';} ?>  </a></li>
													<li class="nav-item"><a class="nav-link text-white py-0 pr-lg-5 pr-md-2 pr-sm-1 mr-lg-0 mr-md-0 mr-sm-0" href="<?php echo base_url(); ?>allbookings/"> <?php if($menu=='allbookings'){echo ' <strong><u>Broneeringud</u></strong>';} else {echo 'Broneeringud';} ?>  	<?php if(isset($unapprovedBookings)){ if($unapprovedBookings!=0){echo '<span class="badge badge-danger">'.$unapprovedBookings.'</span>';}}; ?></a></li>
											<?php } ?>
													<?php endif; ?>
											<?php if($this->session->userdata('roleID')==='1'):?>
													<li class="nav-item"><a class="nav-link text-white py-0 pr-lg-5 pr-md-2 pr-sm-1 mr-lg-0 mr-md-0 mr-sm-0" href="<?php echo base_url(); ?>building/view/"><?php if($menu=='building'){echo ' <strong><u>Asutused</u></strong>';} else {echo 'Asutused';} ?></a></li>
													<li class="nav-item"><a class="nav-link text-white py-0 pr-lg-5 pr-md-2 pr-sm-1 mr-lg-0 mr-md-0 mr-sm-0" href="<?php echo base_url(); ?>region/view/"><?php if($menu=='region'){echo ' <strong><u>Piirkonnad</u></strong>';} else {echo 'Piirkonnad';} ?> </a></li>
											<?php endif; ?>
											<?php if( $this->session->userdata('roleID')==='1' || $this->session->userdata('roleID')==='2' ||$this->session->userdata('roleID')==='3'):?>
													<li class="nav-item"><a class="nav-link text-white py-0 pr-lg-5 pr-md-2 pr-sm-1 mr-lg-0 mr-md-0 mr-sm-0" href="<?php echo base_url(); ?>manageUsers"><?php if($menu=='users'){echo ' <strong><u>Kasutajad</u></strong>';} else {echo 'Kasutajad';} ?></a></li>
											<?php endif; ?>
											<?php if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'):?>
													<li class="nav-item"><a class="nav-link text-white py-0 pr-lg-5 pr-md-2 pr-sm-1 mr-lg-0 mr-md-0 mr-sm-0" href="<?php echo base_url(); ?>building/view/<?php  print_r($this->session->userdata['building']);  ?>"><?php if($menu=='building'){echo ' <strong><u>'.$this->session->userdata['buildingName'].' sätted</u></strong>';} else {echo $this->session->userdata['buildingName'].' sätted';} ?></a></li>
											<?php endif; ?>
										
									<?php ;}  if(!empty($this->session->userdata('userID'))){?>
												<li class="nav-item"><a class="nav-link text-white py-0" href="<?php echo base_url(); ?>profile/edit/<?php echo $this->session->userdata('userID');?>"> <?php if($menu=='profile'){echo ' <strong><u>'.$this->session->userdata('userName').' profiil</u></strong>';} else {echo ''.$this->session->userdata('userName').' profiil';} ?>  	<?php if(isset($requestFromBuilding)){if($requestFromBuilding['requestFromBuilding']=='1'){echo '<span class="badge badge-danger">1</span>';}} ?></a></li>
									<?php ;}?>


									</ul>
										<?php if($this->session->userdata('session_id') && count($this->session->userdata('my_building_ids')) >1){ ?>
										<div class="dropdown show mr-3">
											<a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Vali asutus
											</a>

											<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
											<?php foreach($this->session->userdata('my_building_ids') as $building){ ?>
												<a class="dropdown-item" href="<?php echo base_url(); ?>users/changeSessionData/<?php echo $building['buildingID']; ?>"><?php echo $building['name'] ?></a>

												<?php } ?>
											</div>
										</div>
									<?php } ?>										
                  <?php if($this->session->userdata('session_id')) : ?>
										<ul class="nav navbar-nav navbar-right p-0">
									
												<li class="nav-item"><a class="nav-link text-white p-0" href="<?php echo base_url(); ?>users/logout" ><u>Logi välja</u></a></li>
										</ul>
                  <?php endif; ?>
                <?php if(!$this->session->userdata('session_id')) : ?>
      
                <a class="nav-link text-white p-0" href="<?php echo base_url(); ?>login"><u>Logi sisse</u></a>
                <?php endif; ?>
                </div>

            </div>
        </nav>
	
	
    </header>


<!-- Navigation -->
<script>
 $(document).ready(function() {

                window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove(); 
                });
            }, 8000);});
 </script>


    <?php if($this->session->flashdata('user_registered')): ?>
            <?php echo '<p class="alert alert-success text-center">'.$this->session->flashdata('user_registered').'</p>'; ?>
        <?php endif; ?>


      <?php if($this->session->flashdata('user_loggedin')): ?>
        <?php echo '<p class="alert alert-success text-center">'.$this->session->flashdata('user_loggedin').'</p>'; ?>
      <?php endif; ?>

       <?php if($this->session->flashdata('user_loggedout')): ?>
        <?php echo '<p class="alert alert-success text-center">'.$this->session->flashdata('user_loggedout').'</p>'; ?>
      <?php endif; ?>

      <?php if($this->session->flashdata('success')): ?>
        <?php echo '<p class="alert alert-success text-center">'.$this->session->flashdata('success').'</p>'; ?>
      <?php endif; ?>

      <?php if($this->session->flashdata('post_created')): ?>
        <?php echo '<p class="alert alert-success text-center">'.$this->session->flashdata('post_created').'</p>'; ?>
      <?php endif; ?>

      <?php if($this->session->flashdata('post_updated')): ?>
        <?php echo '<p class="alert alert-success text-center">'.$this->session->flashdata('post_updated').'</p>'; ?>
      <?php endif; ?>

      <?php if($this->session->flashdata('category_created')): ?>
        <?php echo '<p class="alert alert-success text-center">'.$this->session->flashdata('category_created').'</p>'; ?>
      <?php endif; ?>

      <?php if($this->session->flashdata('post_deleted')): ?>
        <?php echo '<p class="alert alert-success text-center">'.$this->session->flashdata('post_deleted').'</p>'; ?>
      <?php endif; ?>

      <?php if($this->session->flashdata('access_deniedToUrl')): ?>
        <?php echo '<p class="alert alert-danger text-center">'.$this->session->flashdata('access_deniedToUrl').'</p>'; ?>
      <?php endif; ?>
      
      <?php if($this->session->flashdata('building_deleted')): ?>
        <?php echo '<p class="alert alert-success text-center">'.$this->session->flashdata('building_deleted').'</p>'; ?>
      <?php endif; ?>

			<?php if($this->session->flashdata('validationErrorMessage')): ?>
        <?php echo '<p class="alert alert-danger text-center">'.$this->session->flashdata('validationErrorMessage').'</p>'; ?>
      <?php endif; ?>
      
      <?php if($this->session->flashdata('login_failed')): ?>
        <?php echo '<p class="alert alert-danger text-center">'.$this->session->flashdata('login_failed').'</p>'; ?>
			<?php endif; ?>
			
			<?php if($this->session->flashdata('errors')): ?>
        <?php echo '<p class="alert alert-danger text-center">'.$this->session->flashdata('errors').'</p>'; ?>
      <?php endif; ?>
		
			<?php if($this->session->flashdata('data')): ?>
        <?php echo '<p class="alert alert-danger text-center">hello'.print_r($this->session->flashdata('data')).'hello</p>'; ?>
			<?php endif; ?>
					
			<?php if($this->session->flashdata('message')): ?>
        <?php echo '<p class="alert alert-danger text-center">'.$this->session->flashdata('message').'</p>'; ?>
      <?php endif; ?>
 
			<div id="textMessageToUser" ></div>


		<?php //print_r($this->session->userdata());?>
