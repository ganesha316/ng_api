$(document).ready(function() {
	secRefData = secRefData();

	$('#add_<?= $module ?>_btn').click(function(event) {
		event.preventDefault();
		$('.form-group').removeClass('has-error');
		$('.form_error').html('');
		let elem = this;
		let formObj = document.getElementById('add_<?= $module ?>_form');
		let formData = new FormData(formObj);
		let formType = $('input[name=form_type]').val();
		let actionUrl = admin_url + ((formType == 'add') ? '<?= $controller ?>/save_<?= $module ?>' : '<?= $controller ?>/update_<?= $module ?>');

		$.ajax({
		 	url: actionUrl,
		 	type: 'POST',
		 	data: formData,
		 	contentType : false,
			processData:false,
		 	beforeSend: makeDisabled (elem),
		 	dataType: 'JSON',
		 	success:function (result) {
		 		if(typeof result != 'object'){
					alert('technical error occured');
					return false;
				}

				if (result.status == 'error') {
					display_form_errors(result.msg);
				}

				if (result.status == 'success') {
					window.location = result.msg;
				}

		 		makeEnable(elem);
		 	}
		});	  
	});

	$('.delete_<?= $module ?>').click(function(event) {
		cat_id = $(this).data('id');
		Swal.fire({
			title: 'Are you sure?',
			text: "You want to delete this <?= $module ?>?",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.value) {
				let data = {ids: cat_id};
				data[secRefData.key] = secRefData.val;
				delete_<?= $module ?>(data);
			}
		});
	});
});

function delete_<?= $module ?>(data){
	$.ajax({
		url: admin_url + '<?= $controller ?>/delete',
		type: 'POST',
		dataType: 'JSON',
		data: data,
		success:function(result) {
			if (result.status == 'success') {
				Swal.fire(
					'Success',
					result.msg,
					'success'
				).then((res) => {
					location.reload();
				});
			}
			else{
				Swal.fire(
					'Error',
					result.msg,
					'error'
				).then((res) => {
					location.reload();
				});
			}
		}
	});
}

function deleteSelectedRows() {

	Swal.fire({
		title: 'Are you sure?',
		text: "You want to delete these <?= $module ?>s?",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
		if (result.value) {
			let checkedIds = [];
			$('.rowCheckbox:checked').each(function(index, el) {
				checkedIds.push(parseFloat($(this).val()));
			});

			let data = {ids: checkedIds};
		    data[secRefData.key] = secRefData.val;

			delete_<?= $module ?>(data);
		}
	})
}