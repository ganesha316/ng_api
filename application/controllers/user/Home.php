<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		$data = [];
		$data['view'] = 'user/home_view';
		$data['home_cms'] = $this->common->select_data_array_options([
			'table' => 'home_cms',
			'limit' => 1
		]);

		$banner_url = '';
		if (!empty($data['home_cms'])) {
			if ($data['home_cms']['video_type'] == 'vimeo') {
				$banner_url = 'https://vimeo.com/' . $data['home_cms']['video_id'];
				$data['video_type'] = "video/mp4";
			}

			if ($data['home_cms']['video_type'] == 'youtube') {
				$banner_url = 'https://www.youtube.com/watch?v='. $data['home_cms']['video_id'] .'?modestbranding=1';
				$data['video_type'] = "video/youtube";
			}
		}

		$data['banner_url'] = $banner_url;
		$data['about_us'] = (!empty($data['home_cms'])) ? $data['home_cms']['about_us'] : '';

		$categories = [];

		$category_data = $this->common->select_data_array_options([
			'table' => 'category',
			'select' => [
				'category.id as cat_id',
				'category.name as cat_name',
				'home_gallery.image as gallery_image',
				'slug'
			],
			'where'=>['category.status'=>'active'],
			'join'=>[
				['table'=>'home_gallery','condition'=>'category.id=home_gallery.cat_id','type'=>'left']
			],
			'order_by' => ['column'=>'sort_order','type'=>'asc'],
		]);

		foreach ($category_data as $value) {
			$categories[$value['cat_name']]['cat_id'] = $value['cat_id'];
			$categories[$value['cat_name']]['cat_name'] = $value['cat_name'];
			$categories[$value['cat_name']]['slug'] = $value['slug'];
			$categories[$value['cat_name']]['gallery'][] = $value['gallery_image'];
		}

		$data['categories'] = $categories;

		unset($categories,$category_data);
		$this->load->view('template/user', $data);
	}

}

/* End of file Home.php */
/* Location: ./application/controllers/user/Home.php */