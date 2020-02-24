<div class="content-wrapper" style="min-height: 394px;">
	<!-- Content Header (Page header) -->
	|?php $this->load->view('admin/common/module_header'); ?>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4">
									<form method="get" action="">
										<div class="input-group margin">
											<input type="text" name="search" value="|?= $this->input->get('search') ?>" class="form-control" placeholder="Search...">
											<span class="input-group-btn">
												<button type="submit" class="btn btn-info btn-flat">Search</button>
											</span>
										</div>
									</form>
								</div>
								<div class="col-md-8 text-right">
									<a href="|?= admin_url('<?= $controller ?>/add') ?>" class="btn btn-social-icon btn-linkedin mr-5" data-toggle="tooltip" title="Add <?= $module ?>"><i class="fa fa-plus"></i></a>

									<button id="deleteSelectedRows" class="btn btn-social-icon btn-google" data-toggle="tooltip" title="Delete Selected"><i class="fa fa-trash"></i></button>
								</div>
							</div>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body listView">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th width="5%"><label><input type="checkbox" id="selectAllCheckbox"></label></th>
									<th><a class="|?= $header_link['name']['class'] ?>" href="|?= $header_link['name']['link'] ?>"><?= $module_lable ?></a></th>
									<th><a class="|?= $header_link['sort_order']['class'] ?>" href="|?= $header_link['sort_order']['link'] ?>">Sort Order</a></th>
									<th width="10%">Action</th>
								</tr>
							</thead>
							<tbody>
								|?php foreach ($results as $key => $value){ ?>
								<tr>
									<td>
										<label>
											<input type="checkbox" value="|?= $value['id'] ?>" class="rowCheckbox"/>
										</label>
									</td>
									<td>|?= $value['name'] ?></td>
									<td>|?= $value['sort_order'] ?></td>
									<td>
										<a href="|?= admin_url('<?= $controller ?>/edit?<?= $module ?>_id='.$value['id']) ?>" class="btn btn-sm btn-social-icon btn-bitbucket" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>

										<a class="btn btn-sm btn-social-icon btn-google delete_<?= $module ?>" data-id="|?= $value['id'] ?>" data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></a>
									</td>
								</tr>
								|?php } ?>
								|?php if (empty($results)){ ?>
								<tr><td colspan="4" class="text-center">No Data Found</td></tr>
								|?php } ?>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
					<div class="box-footer clearfix">
						|?= $pagination; ?>
					</div>
				</div>
				<!-- /.box -->
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>