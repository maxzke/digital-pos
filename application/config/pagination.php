<?php 
$config['pagination']['page_query_string'] = TRUE;
$config['pagination']['uri_segment'] = 3;
                
$config['pagination']['per_page'] = RECORDS_PER_PAGE;

// $config['pagination']['prev_link'] = '‹';
// $config['pagination']['next_link'] = '›';
// $config['pagination']['full_tag_open'] = '<ul class="pagination">';
// $config['pagination']['next_tag_open'] = '<li class="page-item">';
// $config['pagination']['next_tag_close'] = '</li>';
// $config['pagination']['prev_tag_open'] = '<li>';
// $config['pagination']['prev_tag_close'] = '</li>';
// $config['pagination']['num_tag_open'] = '<li>';
// $config['pagination']['num_tag_close'] = '</li>';
// $config['pagination']['cur_tag_open'] = '<li class="active"><a href="">';
// $config['pagination']['cur_tag_close'] = '</a></li>';
// $config['pagination']['full_tag_close'] = '</ul>';
// $config['pagination']['first_link'] = '&laquo;';
// $config['pagination']['first_tag_open'] = '<li>';
// $config['pagination']['first_tag_close'] = '</li>';
// $config['pagination']['last_link'] = '&raquo;';
// $config['pagination']['last_tag_open'] = '<li>';
// $config['pagination']['last_tag_close'] = '</li>';
//--------------------------------------------------
$config['full_tag_open'] = '<ul class="pagination pagination-sm">';
$config['full_tag_close'] = '</ul>';
$config['attributes'] = ['class' => 'page-link'];
$config['first_link'] = false;
$config['last_link'] = false;
$config['first_tag_open'] = '<li class="page-item">';
$config['first_tag_close'] = '</li>';
$config['prev_link'] = '&laquo';
$config['prev_tag_open'] = '<li class="page-item">';
$config['prev_tag_close'] = '</li>';
$config['next_link'] = '&raquo';
$config['next_tag_open'] = '<li class="page-item">';
$config['next_tag_close'] = '</li>';
$config['last_tag_open'] = '<li class="page-item">';
$config['last_tag_close'] = '</li>';
$config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
$config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
$config['num_tag_open'] = '<li class="page-item">';
$config['num_tag_close'] = '</li>';
//---------------------------------------------
 // Bootstrap 4 Pagination fix
//  $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination">';
//  $config['full_tag_close']   = '</ul></nav></div>';
//  $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
//  $config['num_tag_close']    = '</span></li>';
//  $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
//  $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
//  $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
//  $config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
//  // $config['next_tag_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
//  $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
//  $config['prev_tag_close']  = '</span></li>';
//  $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
//  $config['first_tag_close'] = '</span></li>';
//  $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
//  $config['last_tag_close']  = '</span></li>';
?>