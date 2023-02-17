<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Pagination Config
|--------------------------------------------------------------------------
*/
$config['_pageConfig']['page_query_string'] = TRUE;
$config['_pageConfig']['num_links'] = 2;

$config['_pageConfig']['first_link']       = '<i class="fas fa-fast-backward"></i>';
$config['_pageConfig']['last_link']        = '<i class="fas fa-fast-forward"></i>';
$config['_pageConfig']['next_link']        = '<i class="fas fa-forward"></i>';
$config['_pageConfig']['prev_link']        = '<i class="fas fa-backward"></i>';
$config['_pageConfig']['full_tag_open']    = '<ul class="pagination pagination-sm m-0 float-right">';
$config['_pageConfig']['full_tag_close']   = '</ul>';
$config['_pageConfig']['num_tag_open']     = '<li class="page-item"><span class="page-link">';
$config['_pageConfig']['num_tag_close']    = '</span></li>';
$config['_pageConfig']['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
$config['_pageConfig']['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
$config['_pageConfig']['next_tag_open']    = '<li class="page-item"><span class="page-link">';
$config['_pageConfig']['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
$config['_pageConfig']['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
$config['_pageConfig']['prev_tagl_close']  = '</span>Next</li>';
$config['_pageConfig']['first_tag_open']   = '<li class="page-item"><span class="page-link">';
$config['_pageConfig']['first_tagl_close'] = '</span></li>';
$config['_pageConfig']['last_tag_open']    = '<li class="page-item"><span class="page-link">';
$config['_pageConfig']['last_tagl_close']  = '</span></li>';
