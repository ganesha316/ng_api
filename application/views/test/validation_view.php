	private function validation_rules()
	{
		$config = [];
		$config = [
			<?php foreach ($inputs as $field => $value) { ?>
			<space/>
				<?php if($value['type'] != 'file'){ ?>
				<space/>
			[
				'field' => '<?= $field ?>',
				'label' => '<?= $value['label'] ?>',
				'rules' => 'trim|required',
			],
				<?php } ?>
				<space/>
			<?php } ?>
			<space/>
		];

		return $config;
	}