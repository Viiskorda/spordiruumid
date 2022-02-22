<?php if ($this->session->userdata('roleID') === '2' || $this->session->userdata('roleID') === '3' || $this->session->userdata('roleID') === '1') { ?>
	<div class="container">
		<div class="container-md mx-auto mt-5">
			<div class="bg-white form-bg">

				<div class="d-flex mb-5">
					<ul class="nav nav-tabs nav-justified col-12 bg-grey p-0">
						<li class="nav-item p-0"><a class="nav-link link txt-lg single-tab active pl-5" href="#asutus" data-toggle="tab"><?php echo $room_info['roomName']; ?> sätted</a></li>
						<li class="nav-item p-0"></li>
					</ul>
				</div>
			
				<div class="tab-content ">
					<div id="asutus" class="tab-pane center  <?php if (!isset($data['type'])) {
																	echo 'active';
																} else if ($data['type'] == 1) {
																	echo 'active';
																}; ?>">
															
						<?php echo form_open('building/editRoom/' . $room_info['id'], array('id' => 'change')); ?>
						<input class="d-none" type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">

						<h4 class="pt-2 txt-xl px-5 mx-5 mt-4 mb-4">Ruumis tehtavad tegevused</h4>

					

						<div class="form-label-group py-0 px-5 mx-5" id="activities">
						<?php echo validation_errors(); ?>
							<?php for ($i = 0; $i < count($room_activity_info); $i++) { ?>
								<!-- <?php print_r($room_activity_info[$i]['activityName']);  ?> -->
								<div class="row d-flex mb-3 p-0 justify-content-between">
									<input class="form-control col-6" type="text" name="room_activity[]"  required value="<?php echo set_value('room_activity[' . $i . ']', $room_activity_info[$i]['activityName']); ?>">
									<input data-id="<?php echo $room_activity_info[$i]['activity_id'] ?>" class="btn btn-delete btn-width-92 text-white text-center py-1 px-2 txt-strong" type="button" value="Kustuta">
								</div>
							<?php } ?>
						
							<?php if(!empty($additionalRoomActivity) ){ for ($i = 0; $i < count($additionalRoomActivity); $i++) { ?>
						
								<div class="row d-flex mb-3 p-0 justify-content-between">
									<input class="form-control col-6" type="text" name="additionalRoomActivity[]" required value="<?php echo $additionalRoomActivity[$i] ?>">
									<input data-id="<?php echo $additionalRoomActivity[$i] ?>" class="abc btn btn-delete btn-width-92 text-white text-center py-1 px-2 txt-strong" type="button" value="Kustuta">
								</div>
							<?php }} ?>
						</div>


						<div class="flex mx-5 px-5 mt-5">
							<a id="addActivity" class="btn btn-custom text-white text-center py-2 px-4 pluss">
								<p class="m-0 px-0 txt-lg txt-strong text-center align-items-center">Lisa tegevus</p>
							</a>
						</div>

						<div class="d-flex justify-content-end my-5 px-5 mx-5">
							<a class="txt-xl link-deco align-self-center py-0 pr-5 mr-2" href="<?php echo base_url(); ?>building/view/<?php print_r($this->session->userdata['building']); ?>">Katkesta</a>
							<button type="submit" class="btn btn-custom col-md-5 text-white txt-xl">Salvesta muudatused</button>
						</div>
						</form>

					</div>

				</div>
			</div>

		</div>
	</div>
	</div>
<?php } else {
	redirect('');
} ?>





<script>
	$(document).ready(function() {
		var counter = 1;
		$('#addActivity').on('click', function() {
			$('#activities').append('<div class="row d-flex mb-3 p-0 justify-content-between"><input class="form-control col-6" " type="text" name="additionalRoomActivity[]" required value="">	<input data-id=""  class="abc btn btn-delete btn-width-92 text-white text-center py-1 px-2 txt-strong"  type="button" value="Kustuta"></div>');
			counter++;
		});

		//delete just added with JS activity row
		$(document).on('click', '.abc', function() {
			$(this).parent().remove();
		});

		//delete activity row
		$(".btn-delete").on("click", function() {
			//	console.log($(this).data("id"));
			var elementToDelete = $(this);
			$.ajax({
				url: "<?php echo base_url(); ?>building/deleteRoomActivity",
				method: "POST", // use "GET" if server does not handle DELETE
				data: {
					"roomID": '<?php echo $room_info['id']; ?>',
					"activityID": $(this).data("id")
				},
				dataType: "html"
			}).done(function(msg) {

				if (msg == '""') {
					elementToDelete.parent().remove();
				} else {
					$("#textMessageToUser").append('<p class="alert alert-danger text-center">' + msg + '</p>');
					window.setTimeout(function() {
						$(".alert").fadeTo(500, 0).slideUp(500, function() {
							$(this).remove();
						});
					}, 4000);
				}

			}).fail(function(jqXHR, textStatus, errorThrown) {
				alert("Request failed: " + textStatus + ' ' + errorThrown);
			});
		});


	});
</script>
