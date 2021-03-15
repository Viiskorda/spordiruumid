<?php echo validation_errors(); ?>

<div class="container text-darkblue mb-3">
	<div class="mt-5 container-md">
		<div class="form-bg">
			<div class="mx-auto">
				<div class="row d-flex mb-5">
					<ul class="nav nav-tabs nav-justified col-12 bg-grey">
						<li class="nav-item"><a class="nav-link link txt-lg single-tab active" data-toggle="tab">Muuda profiili</a></li>
						<li class="nav-item"></li>
						<li class="nav-item"></li>
					</ul>
				</div>
				<?php echo form_open('profile/updateProfile', array('id' => 'change')); ?>
				<?php foreach ($editProfile as $value) { ?>
					<h4 class="pt-2 txt-xl px-5 mx-md-5">Konto info</h4>
					
					<div class="row d-flex p-0 mt-4 px-5 mx-md-5">
						<div class="form-label-group col-12 col-md-6 py-0 pl-0 pr-5">
							<label>E-mail*</label>
							<input type="email" class="form-control" value="<?php echo $value['email']; ?>" disabled>
						</div>
						<?php if (isset($this->session->userdata['building'])) { ?>
							<div class="form-label-group col-12 col-md-6 p-0 pl-0 pl-md-5  pr-5 pr-md-0">
								<label>Sisse logimisel vaikimisi asutus/roll</label>
								<select class="form-control" name="buildingID">
									<option value="0">Tavakasutaja</option>
									<?php foreach ($allMyRoles as $singleRole) { ?>
										<option <?php if($value['buildingID']==$singleRole['buildingID']) {echo 'selected';} ?> value="<?php echo $singleRole['buildingID'] ?>"><?php echo $singleRole['name'] ? $singleRole['name'] : 'Linnavalitsuse administraator' ?></option>
									<?php 	} ?>
								</select>
							</div>
						<?php	}; ?>
					</div>

					<?php if ($value['roleID'] == '2' || $value['roleID'] == '3'  || $value['roleID'] == '1') : ?>
						<div class="row d-flex p-0 mt-4 px-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-0 pl-0 pr-5">
								<label>Roll*</label>
								<select id="roleID" name="roleID" class="form-control arrow" disabled>
									<option value="2" <?php if ($value['roleID'] == 1) echo ' selected' ?>>Linnavalitsuse administraator</option>
									<option value="3" <?php if ($value['roleID'] == 2) echo ' selected' ?>>Peaadministraator</option>
									<option value="4" <?php if ($value['roleID'] == 3) echo ' selected' ?>>Administraator</option>
								</select>



							</div>
							<!-- <div class="form-label-group col-12 col-md-6 p-0 pl-0 pl-md-5  pr-5 pr-md-0">
						
						
							
								<label>Staatus*</label>
								<select id="status" name="status" class="form-control arrow" disabled>
                                    <option value="1" <?php if ($value['status'] == 1) echo ' selected' ?>>Aktiivne</option>
                                    <option value="0" <?php if ($value['status'] == 0) echo ' selected' ?>>Mitteaktiivne</option>
                                   
                                    </select>
								</div> -->
						</div>
					<?php endif; ?>

					<h4 class="mt-5 txt-xl px-5 mx-md-5">Kasutaja info</h4>
					<div class="row d-flex p-0 mt-4 px-5 mx-md-5">
						<div class="form-label-group col-12 col-md-6 py-0 pl-0 pr-5">
							<label>Nimi* <?php if ($this->session->flashdata('validationErrorMessageForName')) {
												echo $this->session->flashdata('validationErrorMessageForName');
											} ?></label>
							<input type="text" class="form-control" name="name" value="<?php if (!empty($this->session->flashdata('key')['name'])) {
																							echo $this->session->flashdata('key')['name'];
																						} else {
																							echo $value['userName'];
																						} ?>">
						</div>
						<div class="form-label-group col-12 col-md-6 p-0 pl-0 pl-md-5  pr-5 pr-md-0">
							<label>Telefoni number* <?php if ($this->session->flashdata('phoneIsNotCorrect')) {
														echo $this->session->flashdata('phoneIsNotCorrect');
													} ?></label>
							<input type="text" class="form-control" name="phone" value="<?php if (!empty($this->session->flashdata('key')['phone'])) {
																							echo $this->session->flashdata('key')['phone'];
																						} else {
																							echo $value['userPhone'];
																						} ?>">
						</div>
					</div>
					<div class="tab-content mt-5 txt-xl px-5 mx-md-5">
						<button id="hidepasswordinputs" type="button" class="btn  txt-xl btn-info">Muuda parool</button>
					</div>
					<div id="hide">
						<div class="row d-flex p-0 mt-4 px-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-0 pl-0 pr-5">
								<label>Sisesta kehtiv parool <?php if ($this->session->flashdata('passwordnow')) {
																	echo '<small class="text-danger">' . $this->session->flashdata('passwordnow') . '</small>';
																} ?></label>
								<input id="passwordnow" type="password" class="form-control" name="passwordnow" placeholder="Salasõna" value="<?php if (!empty($this->session->flashdata('key')['passwordnow'])) {
																																					echo $this->session->flashdata('key')['passwordnow'];
																																				} ?>">
								<i id="login-icon" toggle="#passwordnow" class="far fa-eye-slash field-icon toggle-password1">&#128065;</i>
							</div>

						</div>
						<div class="row d-flex p-0 mt-4 px-5 mx-md-5">
							<div class="form-label-group col-12 col-md-6 py-0 pl-0 pr-5">
								<label>Uus parool <?php if ($this->session->flashdata('password')) {
														echo $this->session->flashdata('password');
													} ?></label>
								<input id="newpassword" type="password" class="form-control" name="password" placeholder="Salasõna">
								<i id="login-icon" toggle="#newpassword" class="far fa-eye-slash field-icon toggle-password2">&#128065;</i>
							</div>
							<div class="form-label-group col-12 col-md-6 p-0 pl-0 pl-md-5  pr-5 pr-md-0">
								<label>Uus parool uuesti <?php if ($this->session->flashdata('password2')) {
																echo $this->session->flashdata('password2');
															} ?></label>
								<input id="newpasswordagain" type="password" class="form-control" name="password2" placeholder="Korda salasõna">
								<i id="login-icon" toggle="#newpasswordagain" class="far fa-eye-slash field-icon toggle-password3">&#128065;</i>
							</div>
						</div>
					</div>

					<div class="row d-flex justify-content-end my-5 px-5 mx-md-5">
						<a class="txt-xl link-deco align-self-center py-0 pr-5 mr-2" href="<?php echo base_url(); ?>profile/view/<?php echo $this->session->userdata('userID'); ?>">Katkesta</a>
						<button type="submit" class="btn btn-custom col-md-5 text-white txt-xl">Salvesta muudatused</button>
					</div>
				<?php } ?>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$(".toggle-password1").click(function() {
		$(this).toggleClass("text-dark");
		var input = $($(this).attr("toggle"));
		if (input.attr("type") == "password") {
			input.attr("type", "text");
		} else {
			input.attr("type", "password");
		}
	});
	$(".toggle-password2").click(function() {
		$(this).toggleClass("text-dark");
		var input = $($(this).attr("toggle"));
		if (input.attr("type") == "password") {
			input.attr("type", "text");
		} else {
			input.attr("type", "password");
		}
	});
	$(".toggle-password3").click(function() {
		$(this).toggleClass("text-dark");
		var input = $($(this).attr("toggle"));
		if (input.attr("type") == "password") {
			input.attr("type", "text");
		} else {
			input.attr("type", "password");
		}
	});
	$("#hidepasswordinputs").click(function() {
		$('#hidepasswordinputs').toggleClass("text-dark");
		$('#hide').toggle();
		$('#passwordnow').val("");
		$('#newpassword').val("");
	});
	let show = '<?php if (!empty($this->session->flashdata('show'))) {
					echo true;
				} ?>';
	if (!show) {
		$('#hide').hide();
	}
</script>
