<?php

function get_json_post($response_in_array = TRUE)
{
    return json_decode(file_get_contents("php://input"), $response_in_array);
}

function admin_url($append='')
{
	$ci = & get_instance();
    return $ci->config->item('admin_url').$append;
}

function user_url($append='')
{
    $ci = & get_instance();
    return $ci->config->item('user_url').$append;
}

if(!function_exists('pre'))
{
    /**
     * prints the array in readable format
     * @param array $data 
     * @param bool $die whether to stop execution of script after array print
     * @return type
     */
    function pre($data,$die = true)
    {
    	echo '<pre>';print_r($data);echo '</pre>';
    	if($die){ die(); }
    }
}

function curl_request($curl_array)
{
    $curl = curl_init();

    curl_setopt_array($curl, $curl_array);

    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);

    if ($error) {
        return ['type' => 'error', 'data' => $error];
    }
    return ['type' => 'success', 'data' => $response];
}




if(!function_exists('make_upload_directory')) {
    /**
     * make new directory in upload folder if not exists
     * @param string $directory 
     * @return void
     */
    function make_upload_directory($directory = '',$recursive = FALSE)
    {
        if(empty($directory))
        {
            return false;
        }

        $directory = rtrim($directory, '/');

        if(!is_dir($directory))
        {
            $oldmask = umask(0);
            mkdir($directory,0777,$recursive);
            umask($oldmask);
        }
     }
}

function upload_file($field_name,$config) {
    $ci = & get_instance();
    $ci->load->library('upload');
    $ci->upload->initialize($config);
    if (!$ci->upload->do_upload($field_name))
    {
        return $ci->upload->display_errors();
    }
    return $ci->upload->data();
}     

function validate_file($field_name,$config) {
    $ci = & get_instance();
    $ci->load->library('upload');
    $ci->upload->initialize($config);
    if (!$ci->upload->do_validate($field_name))
    {
        return $ci->upload->display_errors();
    }
    return TRUE;
}

function send_mail($data,$config=[])
{
    $ci = & get_instance();
    $ci->load->library('email');        
    if(!empty($config)){
        $ci->email->initialize($config);
    }

    foreach ($data as $function => $value) {
        
        if (is_array($value)) {
            call_user_func_array([$ci->email,$function], $value);
        }

        if (!is_array($value)) {
            $ci->email->$function($value);
        }
    }

    $send = $ci->email->send();
    if (!$send) {
        return $ci->email->print_debugger();
    }
    return true;
}

function encrypt_array($data)
{
    if(!is_array($data) || empty($data))
    {
        return $data;
    }

    $ci =& get_instance();
    $ci->load->library('encryption');
    $str = $ci->encryption->encrypt(json_encode($data));
    return str_replace(array('+', '/', '='), array('-', '_', '~'), $str);
}

function decrypt_uri($str='')
{
    if(empty($str))
    {
        return $str;
    }

    $ci =& get_instance();
    $ci->load->library('encryption');
    $str=str_replace(array('-', '_', '~'), array('+', '/', '='), $str);
    $decrypt = $ci->encryption->decrypt($str);
    
    if($decrypt)
    {
        return json_decode($decrypt,true);
    }
    return;
}

function encrypt_validation($param)
{
    $ci =& get_instance();
    if(empty($ci->input->get($param)))
    {
        return false;
    }

    $get = decrypt_uri($ci->input->get($param));
    if(empty($get))
    {
        return false;
    }
    return $get;
}

 if(!function_exists('is_logged_in'))
{
    /**
     * check if user or particular is logged in
     * @param string|array|null $role 
     * @return bool
     */
    function is_logged_in($role = NULL)
    {
        $CI = & get_instance();
        if (empty($role)) {
            return !empty($CI->session->userdata('is_logged_in'));
        }

        if(is_string($role))
        {
            return ($role == $CI->session->userdata('user_type'));
        }

        if(is_array($role))
        {
            return in_array($CI->session->userdata('user_type'), $role);
        }

        return false;
    }
}

function password_pattern()
{
    return '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';
}


function pagination_config(){
    $config['per_page'] = 10;
    $config["uri_segment"] = 4;
  
    $config['full_tag_open'] = "<ul class='pagination pull-right'>";
    $config['full_tag_close'] = '</ul>';
  
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
  
    $config['cur_tag_open'] = '<li class="active"><a href="#">';
    $config['cur_tag_close'] = '</a></li>';
  
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
  
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
  
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';

    $config['prev_link'] = '<i class="fa fa-angle-left"></i>Previous Page';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';

    $config['next_link'] = 'Next Page <i class="fa fa-angle-right"></i>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';

    $config['reuse_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';
    $config['use_page_numbers'] = TRUE;

    return $config;
}

function user_home_page($user_type = NULL)
{
    $CI =& get_instance();
    if ($user_type === NULL) {
        $user_type = $CI->session->userdata('user_type');
    }
    switch ($user_type) {
        case 'admin':
            return admin_url('category');
            break;
        
        default:
            return base_url();
            break;
    }
}

function set_menu($uri_segment='1',$haystack = '')
{
    $CI =& get_instance();
    if (is_array($haystack)) {

        $haystack = array_map('strtolower', $haystack);

        if (in_array($CI->uri->segment($uri_segment), $haystack)) {
            return 'active';
        }
        return '';
    }

    $haystack = strtolower($haystack);
    if ($CI->uri->segment($uri_segment) == $haystack) {
        return 'active';
    }

    return '';
}

function csrf_input()
{
    $CI =& get_instance();
    return "<input type='hidden' name='".$CI->security->get_csrf_token_name()."' value='".$CI->security->get_csrf_hash()."' />";
}

function get_validation_errors($data = [])
{
    $CI =& get_instance();
    if (empty($data)) {
        $data = $CI->input->post(NULL,TRUE);
    }

    $error = [];
    foreach ($_POST  as $key => $value) {
        $error[$key] = form_error($key,NULL,NULL);
    }
    return array_filter($error);
}

function resize_images($image_array,$extra_config = [],$replace = false)
{
    $ci = & get_instance();
    $ci->load->library('image_lib');
    
    $config = array();
    $config['source_image'] = $image_array['full_path'];
    $config['create_thumb'] = !$replace;
    $config['new_image'] = $image_array['file_path'];

    $config = array_merge($config,$extra_config);

    $ci->image_lib->initialize($config);
    $ci->image_lib->resize();
    $ci->image_lib->clear();
}

function get_page_offset($page,$per_page)
{
    return ($page - 1) * $per_page;
}

function create_sort_link($query_arr,$sort,$current_sort,$current_order,$url)
{
    if(isset($query_arr['page'])){
        unset($query_arr['page']);
    }
    $query_arr['sort'] = $sort;
    $query_arr['order'] = (($current_sort != $sort) || (strtolower($current_order) == 'desc')) ? 'asc' : 'desc';

    return [
        'class' => ($sort == $current_sort) ? strtolower($current_order) : '',
        'link' => $url.'?'.http_build_query($query_arr),
    ];
}

function set_admin_session($user_data)
{
    $ci =& get_instance();
    $ci->session->set_userdata([
        'user_id'      => $user_data['id'],
        'email'        => $user_data['email'],
        'is_logged_in' => TRUE,
        'user_type'    => 'admin',
    ]);
}

function is_valid_slug($slug)
{
    return (bool)preg_match('/^[a-zA-Z0-9-_]+$/', $slug);
}

function get_youtube_id_from_url($url)
{
    $url_string = parse_url($url, PHP_URL_QUERY);
    parse_str($url_string, $args);
    return isset($args['v']) ? $args['v'] : false;
}

function get_vimeo_id_from_url($url)
{
    // return substr(parse_url($url, PHP_URL_PATH), 1);
    $a = @file_get_contents('https://vimeo.com/api/oembed.json?url='.$url);
    if ($a) {
        return json_decode($a,true)['video_id'];
    }
    return false;
}

function rearrange_file_array($data)
{
    foreach($data as $key => $all) {
        foreach($all as $i => $val) {
            $new_array[$i][$key] = $val;    
        }    
    }
    return $new_array;
}

function category_menu()
{
    $CI =& get_instance();
    return $CI->common->select_data_array_options([
        'table'=>'category',
        'select'=>'name,slug',
        'where'=>['status'=>'active'],
        'order_by'=>['column'=>'sort_order','type'=>'asc']
    ]);
}