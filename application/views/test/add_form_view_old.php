<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	|?php $this->load->view('admin/common/module_header'); ?>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<!-- left column -->
			<div class="col-md-12">
				<div class="box box-primary">
					<!-- form start -->
					<form role="form" id="add_<?= $module ?>_form">
						<input type="hidden" name="form_type" value="|?= $form_type ?>">
						<div class="box-body">
							<?php foreach ($inputs as $field => $value){  //main foreach?>
							<?php if($value['type'] == 'text'){ ?>
							<space/>

							<div class="form-group">
								<label for="<?= $field ?>_input"><?= $value['label'] ?></label>
								<input type="text" class="form-control" name="<?= $field ?>" id="<?= $field ?>_input" placeholder="Enter <?= $value['label'] ?>">
								<span class="form_error" id="err_<?= $field ?>"></span>
							</div>
							<?php } ?>

							<?php if($value['type'] == 'textarea'){ ?>
							<space/>

							<div class="form-group">
								<label for="<?= $field ?>_text"><?= $value['label'] ?></label>
								<textarea name="<?= $field ?>" id="<?= $field ?>_text" class="form-control" rows="3" placeholder="Enter <?= $value['label'] ?>"></textarea>
								<span class="form_error" id="err_<?= $field ?>"></span>
							</div>
							<?php } ?>

							<?php if($value['type'] == 'file'){ ?>
							<space/>

							<div class="form-group">
								<label for="<?= $field ?>_file_input"><?= $value['label'] ?></label>
								<input type="file" name="<?= $field ?>" id="<?= $field ?>_file_input">
								<p class="help-block">Only JPG, PNG allowed</p>
								<span class="form_error" id="err_<?= $field ?>"></span>
							</div>
							<?php } ?>

							<?php if($value['type'] == 'select'){ ?>
							<space/>

							<div class="form-group">
								<label for="<?= $field ?>_select"><?= $value['label'] ?></label>
								<select class="form-control" name="<?= $field ?>" id="<?= $field ?>_select">
									<option value="">Select <?= $value['label'] ?></option>
									<?php if(isset($value['option'])){ ?>
										<?php foreach ($value['option'] as $option_val => $option_label){ ?>
									<space/>
									<option value="<?= $option_val ?>"><?= $option_label ?></option>
										<?php } ?>
									<?php } else { ?>
									<space/>
									|?php foreach ([] as $value){ ?>
									<option value="|?= $value['value'] ?>">|?= $value['name'] ?></option>
									|?php } ?>

									<?php } ?>
								<space/>
								</select>
								<span class="form_error" id="err_<?= $field ?>"></span>
							</div>
							<?php } ?>

							<?php if($value['type'] == 'radio'){ ?>
							<space/>

							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<label><?= $value['label'] ?></label>
									</div>
									<div class="col-md-12">
										<?php foreach ($value['option'] as $option_val => $option_label){ ?>	
										<div class="radio-inline">
											<label for="option_<?= $option_val ?>">
												<input type="radio" name="<?= $field ?>" id="option_<?= $option_val ?>" value="<?= $option_val ?>">
												<?= $option_label ?>
												<space/>
											</label>
										</div>
										<?php } ?>
										<space/>
									</div>
								</div>
								<span class="form_error" id="err_top_menu"></span>
							</div>
							<?php } ?>

							<?php } //main foreach ?>
							<space/>
						</div>
						<!-- /.box-body -->

						<div class="box-footer">
							<button type="submit" class="btn btn-primary" id="add_<?= $module ?>_btn">Submit</button>
						</div>
					</form>
				</div>
				<!-- /.box -->
			</div>
			<!--/.col (left) -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->