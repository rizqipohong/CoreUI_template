<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lib_template
{
    protected $_ci;
    protected $additional_css;
    protected $additional_js;

    function __construct()
    {
        $this->_ci = &get_instance();
        $this->additional_css = array();
        $this->additional_js = array();

        $this->_ci->load->model('Main/main_model');
    }

    public function tes_var($n){
        echo "<pre>"; print_r($n); echo "</pre>";
    }

    public function load_raw($_views, $data)
    {
        $data['_url_plugin'] = site_url('vendor/almasaeed2010/adminlte/plugins/');
        $data['_url_js'] = site_url('vendor/almasaeed2010/adminlte/dist/js/');
        $data['_url_css'] = site_url('vendor/almasaeed2010/adminlte/dist/css/');
        $data['_url_asset'] = site_url('assets/');

        $data['_url_base'] = base_url();
        $data['_url_dashboard'] = site_url('Dashboard');
        $data['_url_profile'] = site_url('Main/Main_profile');
        $data['_url_darkmode'] = site_url('Main/Main_user/darkmode');

        $theme = $this->theme();
        $data['_menu_sidebar'] = $this->main_sidebar();
        $data['_user_data'] = $this->user_data();

        if (!empty(@$theme) and @$theme == 'Yes') {
            //Dark Mode
            $data['_theme_body'] = 'accent-orange dark-mode';
            $data['_theme_navbar'] = 'navbar-orange';
            $data['_theme_sidebar'] = 'sidebar-dark-orange';

            //card style
            $data['_card_style'] = 'card-orange';

            //backgroud style
            $data['_bg_style'] = 'bg-orange';

            //table style
            $data['_th_style'] = 'dark-mode-th';

            //color
            $data['_color'] = '#fd7e14';
            $data['_sec_color'] = '#eed3bd';

            //theme identifier
            $data['_theme'] = 'dark';
        } else {
            //Light Mode
            $data['_theme_body'] = '';
            $data['_theme_navbar'] = 'navbar-navy';
            $data['_theme_sidebar'] = 'sidebar-light-navy';

            //card style
            $data['_card_style'] = 'card-navy';

            //backgroud style
            $data['_bg_style'] = 'bg-navy';

            //table style
            $data['_th_style'] = 'light-mode-th';

            //color
            $data['_color'] = '#001f3f';
            $data['_sec_color'] = '#eee';

            //theme identifier
            $data['_theme'] = 'light';
        }

        $data['_headers'] = $this->_ci->load->view('partial-raw/main-header', $data, true);
        $data['_sidebars'] = $this->_ci->load->view('partial-raw/main-sidebar', $data, true);
        $data['_contents'] = $this->_ci->load->view($_views, $data, true);
        $data['_footers'] = $this->_ci->load->view('partial-raw/main-footer', $data, true);
        $this->_ci->load->view('main-template-raw', $data);
    }

    public function load($_views, $data, $additionalCss = array(), $additionalJs = array())
    {
        $cssArr = [
            'vendor/almasaeed2010/adminlte/plugins/fontawesome-free/css/all.min.css',
            'vendor/almasaeed2010/adminlte/plugins/toastr/toastr.min.css',
            'vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css',
            'vendor/almasaeed2010/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
            'vendor/almasaeed2010/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css',
            'vendor/almasaeed2010/adminlte/plugins/select2/css/select2.min.css',
            'assets/custom_template/css/custom.css',
        ];
        $jsArr = [
            'vendor/almasaeed2010/adminlte/plugins/jquery/jquery.min.js',
            'vendor/almasaeed2010/adminlte/dist/js/adminlte.js',
            'vendor/almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js',
            'vendor/almasaeed2010/adminlte/plugins/toastr/toastr.min.js',
            'vendor/almasaeed2010/adminlte/plugins/jquery-validation/jquery.validate.min.js',
            'vendor/almasaeed2010/adminlte/plugins/sweetalert2/sweetalert2.min.js',
            'vendor/almasaeed2010/adminlte/plugins/select2/js/select2.min.js',
        ];

        if (!empty($additionalCss)) {
            $cssArr = array_merge($cssArr, $additionalCss);
        }

        if (!empty($additionalJs)) {
            $jsArr = array_merge($jsArr, $additionalJs);
        }

        $data['cssArr'] = $cssArr;
        $data['jsArr'] = $jsArr;

        //        $data['_url_plugin'] = site_url('vendor/almasaeed2010/adminlte/plugins/');
        //        $data['_url_js'] = site_url('vendor/almasaeed2010/adminlte/dist/js/');
        //        $data['_url_css'] = site_url('vendor/almasaeed2010/adminlte/dist/css/');
        //        $data['_url_asset'] = site_url('assets/');

        //DEFAULT BASE UNTUK TAMPILAN
        $data['_url_base'] = base_url();
        $data['_url_dashboard'] = site_url('Dashboard');
        $data['_url_profile'] = site_url('Main/Main_profile');
        $data['_url_darkmode'] = site_url('Main/Main_user/darkmode');

        $theme = $this->theme();
        $data['_menu_sidebar'] = $this->main_sidebar();
        $data['_user_data'] = $this->user_data();

        if (!empty(@$theme) and @$theme == 'Yes') {
            //Dark Mode
            $data['_theme_body'] = 'accent-orange dark-mode';
            $data['_theme_navbar'] = 'navbar-orange';
            $data['_theme_sidebar'] = 'sidebar-dark-orange';

            //card style
            $data['_card_style'] = 'card-orange';

            //backgroud style
            $data['_bg_style'] = 'bg-orange';

            //table style
            $data['_th_style'] = 'dark-mode-th';

            //color
            $data['_color'] = '#fd7e14';
            $data['_sec_color'] = '#eed3bd';

            //theme identifier
            $data['_theme'] = 'dark';
        } else {
            //Light Mode
            $data['_theme_body'] = '';
            $data['_theme_navbar'] = 'navbar-navy';
            $data['_theme_sidebar'] = 'sidebar-light-navy';

            //card style
            $data['_card_style'] = 'card-navy';

            //backgroud style
            $data['_bg_style'] = 'bg-navy';

            //table style
            $data['_th_style'] = 'light-mode-th';

            //color
            $data['_color'] = '#001f3f';
            $data['_sec_color'] = '#eee';

            //theme identifier
            $data['_theme'] = 'light';
        }
        $data['_headers'] = $this->_ci->load->view('partial/main-header', $data, true);
        $data['_sidebars'] = $this->_ci->load->view('partial/main-sidebar', $data, true);

        $data['_contents'] = $this->_ci->load->view($_views, $data, true);

        $data['_footers'] = $this->_ci->load->view('partial/main-footer', $data, true);
        $this->_ci->load->view('main-template', $data);
    }

    public function load_detail($_views, $data, $return = TRUE)
    {
        $theme = $this->theme();
        if (!empty(@$theme) and @$theme == 'Yes') {
            //Dark Mode

            //card style
            $data['_card_style'] = 'card-orange';

            //backgroud style
            $data['_bg_style'] = 'bg-orange';

            //table style
            $data['_th_style'] = 'dark-mode-th';

            //color
            $data['_color'] = '#fd7e14';
            $data['_sec_color'] = '#eed3bd';

            //theme identifier
            $data['_theme'] = 'dark';
        } else {
            //Light Mode

            //card style
            $data['_card_style'] = 'card-navy';

            //backgroud style
            $data['_bg_style'] = 'bg-navy';

            //table style
            $data['_th_style'] = 'light-mode-th';

            //color
            $data['_color'] = '#001f3f';
            $data['_sec_color'] = '#eee';

            //theme identifier
            $data['_theme'] = 'light';
        }

        return $this->_ci->load->view($_views, $data, $return);
    }

    public function theme()
    {
        // main setting
        $user_id = isset($_SESSION['sgn_user_id']) ? $_SESSION['sgn_user_id'] : null;
        if (!empty($user_id)) {
            $where = ['user_id' => $user_id];
            $main_user_setting = $this->_ci->db->get_where('main_user_setting', $where)->result();
            $main_user_setting = json_decode($main_user_setting[0]->setting_data);
            $theme = $main_user_setting->dark_mode;
        } else {
            $theme = 'No';
        }
        return $theme;
    }

    public function list_menu()
    {
        $theme = $this->theme();
        if (!empty(@$theme) and @$theme == 'Yes') {
            //Dark Mode
            $_theme = 'color:white;';
        } else {
            //Light Mode
            $_theme = 'color:black;';
        }

        $menu_data = $this->_ci->main_model->get_list_main_menu_all()->result();
        $html = "";
        $html2 = "";
        foreach ($menu_data as $key => $val) {
            $is_base_menu = $val->is_base_menu == 'Yes' ? " display:none;" : "";
            $menu_type = $val->menu_type == 'Header' ? "<strong>" . $val->menu_type . "</strong>" : $val->menu_type;
            $menu_name = $val->menu_type == 'Header' ? "<strong>" . strtoupper($val->menu_name) . "</strong>" : $val->menu_name;
            $access_status = $val->access_status == 'Activated' ? "success" : "default";

            $html .= "
                <li style='' class='' id='menuitem_" . $val->menu_id . "'>
                    <div>
                        <span class='d-flex justify-content-between'>
                            <a href='#' class='btn btn-xs pl-0' style='" . $_theme . "'><b>" . $menu_name . "</b></a>
                            <span class='d-flex align-items-center'>
                                <span class='btn btn-xs' style='" . $_theme . "'>" . $menu_type . "</span>
                                <span>
                                    <button type='button' class='btn btn-xs btn-action-icon btn-" . $access_status . "' onclick='_switch(" . $val->menu_id . ")'>
                                        <i class='fas fa-power-off'></i>
                                    </button>
                                </span>
                                <span>
                                    <button type='button' class='btn btn-xs btn-action-icon btn-warning' onclick='_update(" . $val->menu_id . ")'>
                                        <i class='fas fa-pencil-alt'></i>
                                    </button>
                                </span>
                                <span style='" . $is_base_menu . "'>
                                    <button type='button' class='btn btn-xs btn-action-icon btn-danger' onclick='_delete(" . $val->menu_id . ")'>
                                        <i class='fas fa-trash-alt'></i>
                                    </button>
                                </span>
                            </span>
                        </span>
                    </div>
            ";

            $submenu_data = $this->_ci->main_model->get_list_sub_menu_all($val->menu_id)->result();
            if (!empty($submenu_data)) {
                $html .= "<ol>";
                foreach ($submenu_data as $key2 => $val2) {
                    $is_base_menu2 = $val2->is_base_menu == 'Yes' ? " display:none;" : "";
                    $menu_type2 = $val2->menu_type == 'Header' ? "<strong>" . $val2->menu_type . "</strong>" : $val2->menu_type;
                    $menu_name2 = $val2->menu_type == 'Header' ? "<strong>" . strtoupper($val2->menu_name) . "</strong>" : $val2->menu_name;
                    $access_status2 = $val2->access_status == 'Activated' ? "success" : "default";

                    $html .= "
                        <li style='' class='' id='menuitem_" . $val2->menu_id . "'>
                            <div>
                                <span class='d-flex justify-content-between'>
                                    <a href='#' class='btn btn-xs pl-0' style='" . $_theme . "'><b>" . $menu_name2 . "</b></a>
                                    <span class='d-flex align-items-center'>
                                        <span class='btn btn-xs' style='" . $_theme . "'>" . $menu_type2 . "</span>
                                        <span>
                                            <button type='button' class='btn btn-xs btn-action-icon btn-" . $access_status2 . "' onclick='_switch(" . $val2->menu_id . ")'>
                                                <i class='fas fa-power-off'></i>
                                            </button>
                                        </span>
                                        <span>
                                            <button type='button' class='btn btn-xs btn-action-icon btn-warning' onclick='_update(" . $val2->menu_id . ")'>
                                                <i class='fas fa-pencil-alt'></i>
                                            </button>
                                        </span>
                                        <span style='" . $is_base_menu2 . "'>
                                            <button type='button' class='btn btn-xs btn-action-icon btn-danger' onclick='_delete(" . $val2->menu_id . ")'>
                                                <i class='fas fa-trash-alt'></i>
                                            </button>
                                        </span>
                                    </span>
                                </span>
                            </div>
                    ";

                    $submenu_data_2 = $this->_ci->main_model->get_list_sub_menu_all($val2->menu_id)->result();
                    if (!empty($submenu_data_2)) {
                        $html .= "<ol>";
                        foreach ($submenu_data_2 as $key3 => $val3) {
                            $is_base_menu3 = $val3->is_base_menu == 'Yes' ? " display:none;" : "";
                            $menu_type3 = $val3->menu_type == 'Header' ? "<strong>" . $val3->menu_type . "</strong>" : $val3->menu_type;
                            $menu_name3 = $val3->menu_type == 'Header' ? "<strong>" . strtoupper($val3->menu_name) . "</strong>" : $val3->menu_name;
                            $access_status3 = $val3->access_status == 'Activated' ? "success" : "default";

                            $html .= "
                                <li style='' class='' id='menuitem_" . $val3->menu_id . "'>
                                    <div>
                                        <span class='d-flex justify-content-between'>
                                            <a href='#' class='btn btn-xs pl-0' style='" . $_theme . "'><b>" . $menu_name3 . "</b></a>
                                            <span class='d-flex align-items-center'>
                                                <span class='btn btn-xs' style='" . $_theme . "'>" . $menu_type3 . "</span>
                                                <span>
                                                    <button type='button' class='btn btn-xs btn-action-icon btn-" . $access_status3 . "' onclick='_switch(" . $val3->menu_id . ")'>
                                                        <i class='fas fa-power-off'></i>
                                                    </button>
                                                </span>
                                                <span>
                                                    <button type='button' class='btn btn-xs btn-action-icon btn-warning' onclick='_update(" . $val3->menu_id . ")'>
                                                        <i class='fas fa-pencil-alt'></i>
                                                    </button>
                                                </span>
                                                <span style='" . $is_base_menu3 . "'>
                                                    <button type='button' class='btn btn-xs btn-action-icon btn-danger' onclick='_delete(" . $val3->menu_id . ")'>
                                                        <i class='fas fa-trash-alt'></i>
                                                    </button>
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                            ";
                        }
                        $html .= "</ol>";
                    }
                    $html .= "</li>";
                }
                $html .= "</ol></li>";
            } else {
                $html .= "</li>";
            }
        }

        return $html;
    }

    public function main_sidebar()
    {
        $active_menu = $this->menu_active();
        $menu_data = $this->_ci->main_model->get_sidebar_main_menu_active()->result();
        $html = "";
        $test = [];
        foreach ($menu_data as $key => $val) {
            $valid_menu = true;
            $valid_menu = can($val->url);
            if ($valid_menu == true) {
                if ($val->menu_type == "Header") {
                    $submenu_data_1 = $this->_ci->main_model->get_sidebar_sub_menu_active($val->menu_id)->result();
                    if (!empty($submenu_data_1)) {
                        $menu_open = $active_menu['menu_id_parent'] == $val->menu_id ? 'menu-open' : '';
                        $active = $active_menu['menu_id_parent'] == $val->menu_id ? 'active' : '';
                        $html .= '
                            <li class="nav-item ' . $menu_open . '">
                                <a href="#" class="nav-link ' . $active . '">
                                    <i class="' . $val->nav_icon . ' nav-icon"></i>
                                    <p class="text-bold">
                                        ' . strtoupper($val->menu_name) . '
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                        ';

                        foreach ($submenu_data_1 as $key_1 => $val_1) {
                            $valid_menu_1 = $this->valid_menu_sidebar($val_1->menu_id);
                            $valid_menu_1 = can($val_1->url);
                            $submenu_data_2 = $this->_ci->main_model->get_sidebar_sub_menu_active($val_1->menu_id)->result();
                            if ($valid_menu_1 == true) {
                                if (!empty($submenu_data_2)) {
                                    $menu_open = $active_menu['menu_id_parent_sub'] == $val_1->menu_id ? 'menu-open' : '';
                                    $active = $active_menu['menu_id_parent_sub'] == $val_1->menu_id ? 'active' : '';
                                    $html .= '
                                        <li class="nav-item ' . $menu_open . '">
                                            <a href="#" class="nav-link ' . $active . '">
                                                <i class="' . $val_1->nav_icon . ' nav-icon"></i>
                                                <p class="text-bold">
                                                    ' . $val_1->menu_name . '
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                    ';

                                    foreach ($submenu_data_2 as $key_2 => $val_2) {
                                        $valid_menu_2 = $this->valid_menu_sidebar($val_2->menu_id);
                                        $valid_menu_2 = can($val_2->url);
                                        if ($valid_menu_2 == true) {
                                            // $link = $this->sidebar_link($val_2->modules_name, $val_2->controller_name, $val_2->function_name);
                                            $link = $val_2->url;
                                            $active = $active_menu['menu_id'] == $val_2->menu_id ? 'active' : '';
                                            $html .= '
                                                <li class="nav-item">
                                                    <a href="' . site_url($link) . '" class="nav-link ' . $active . '">
                                                        <i class="' . $val_2->nav_icon . ' nav-icon"></i>
                                                        <p>' . $val_2->menu_name . '</p>
                                                    </a>
                                                </li>
                                            ';
                                        }
                                    }

                                    $html .= '
                                            </ul>
                                        </li>
                                    ';
                                } else {
                                    // $link = $this->sidebar_link($val_1->modules_name, $val_1->controller_name, $val_1->function_name);
                                    $link = $val_1->url;
                                    $active = $active_menu['menu_id'] == $val_1->menu_id ? 'active' : '';
                                    $html .= '
                                        <li class="nav-item">
                                            <a href="' . site_url($link) . '" class="nav-link ' . $active . '">
                                                <i class="' . $val_1->nav_icon . ' nav-icon"></i>
                                                <p>' . $val_1->menu_name . '</p>
                                            </a>
                                        </li>
                                    ';
                                }
                            }
                        }

                        $html .= '
                                </ul>
                            </li>
                        ';
                    } else {
                        $html .= '
                            <li class="nav-header text-bold">' . strtoupper($val->menu_name) . '</li>
                        ';
                    }
                } else if ($val->menu_type == "Main Menu") {
                    $submenu_data_1 = $this->_ci->main_model->get_sidebar_sub_menu_active($val->menu_id)->result();
                    $test[$key][] = $val->url;
                    if (!empty($submenu_data_1)) {
                        $menu_open = $active_menu['menu_id_parent'] == $val->menu_id ? 'menu-open' : '';
                        $active = $active_menu['menu_id_parent'] == $val->menu_id ? 'active' : '';
                        $html .= '
                            <li class="nav-item ' . $menu_open . '">
                                <a href="#" class="nav-link ' . $active . '">
                                    <i class="' . $val->nav_icon . ' nav-icon"></i>
                                    <p class="text-bold">
                                        ' . strtoupper($val->menu_name) . '
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                        ';

                        foreach ($submenu_data_1 as $key_1 => $val_1) {
                            $valid_menu_1 = $this->valid_menu_sidebar($val_1->menu_id);
                            if ($valid_menu_1 == true) {
                                $submenu_data_2 = $this->_ci->main_model->get_sidebar_sub_menu_active($val_1->menu_id)->result();
                                if (!empty($submenu_data_2)) {
                                    $menu_open = $active_menu['menu_id_parent_sub'] == $val_1->menu_id ? 'menu-open' : '';
                                    $active = $active_menu['menu_id_parent_sub'] == $val_1->menu_id ? 'active' : '';
                                    $html .= '
                                        <li class="nav-item ' . $menu_open . '">
                                            <a href="#" class="nav-link ' . $active . '">
                                                <i class="' . $val_1->nav_icon . ' nav-icon"></i>
                                                <p class="text-bold">
                                                    ' . $val_1->menu_name . '
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                    ';

                                    foreach ($submenu_data_2 as $key_2 => $val_2) {
                                        $valid_menu_2 = $this->valid_menu_sidebar($val_2->menu_id);
                                        if ($valid_menu_2 == true) {
                                            // $link = $this->sidebar_link($val_2->modules_name, $val_2->controller_name, $val_2->function_name);
                                            $link = $val_2->url;
                                            $active = $active_menu['menu_id'] == $val_2->menu_id ? 'active' : '';
                                            $html .= '
                                                <li class="nav-item">
                                                    <a href="' . site_url($link) . '" class="nav-link ' . $active . '">
                                                        <i class="' . $val_2->nav_icon . ' nav-icon"></i>
                                                        <p>' . $val_2->menu_name . '</p>
                                                    </a>
                                                </li>
                                            ';
                                        }
                                    }

                                    $html .= '
                                            </ul>
                                        </li>
                                    ';
                                } else {
                                    // $link = $this->sidebar_link($val_1->modules_name, $val_1->controller_name, $val_1->function_name);
                                    $link = $val_1->url;
                                    $active = $active_menu['menu_id'] == $val_1->menu_id ? 'active' : '';
                                    $html .= '
                                        <li class="nav-item">
                                            <a href="' . site_url($link) . '" class="nav-link ' . $active . '">
                                                <i class="' . $val_1->nav_icon . ' nav-icon"></i>
                                                <p>' . $val_1->menu_name . '</p>
                                            </a>
                                        </li>
                                    ';
                                }
                            }
                        }

                        $html .= '
                                </ul>
                            </li>
                        ';
                    } else {
                        // $link = $this->sidebar_link($val->modules_name, $val->controller_name, $val->function_name);
                        $link = $val->url;
                        $active = $active_menu['menu_id'] == $val->menu_id ? 'active' : '';
                        $html .= '
                            <li class="nav-item">
                                <a href="' . site_url($link) . '" class="nav-link ' . $active . '">
                                    <i class="' . $val->nav_icon . ' nav-icon"></i>
                                    <p>' . $val->menu_name . '</p>
                                </a>
                            </li>
                        ';
                    }
                }
            }
        }
        // dd($test);

        return $html;
    }

    public function main_sidebar_raw()
    {
        $active_menu = $this->menu_active();
        $menu_data = $this->_ci->main_model->get_sidebar_main_menu_active()->result();
        $html = "";
        foreach ($menu_data as $key => $val) {
            $valid_menu = $this->valid_menu_sidebar($val->menu_id);
            if ($valid_menu == true) {
                if ($val->menu_type == "Header") {
                    $submenu_data_1 = $this->_ci->main_model->get_sidebar_sub_menu_active($val->menu_id)->result();
                    if (!empty($submenu_data_1)) {
                        $menu_open = $active_menu['menu_id_parent'] == $val->menu_id ? 'menu-open' : '';
                        $active = $active_menu['menu_id_parent'] == $val->menu_id ? 'active' : '';
                        $html .= '
                            <li class="nav-item ' . $menu_open . '">
                                <a href="#" class="nav-link ' . $active . '">
                                    <i class="' . $val->nav_icon . ' nav-icon"></i>
                                    <p class="text-bold">
                                        ' . strtoupper($val->menu_name) . '
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                        ';

                        foreach ($submenu_data_1 as $key_1 => $val_1) {
                            $valid_menu_1 = $this->valid_menu_sidebar($val_1->menu_id);
                            $submenu_data_2 = $this->_ci->main_model->get_sidebar_sub_menu_active($val_1->menu_id)->result();
                            if ($valid_menu_1 == true) {
                                if (!empty($submenu_data_2)) {
                                    $menu_open = $active_menu['menu_id_parent_sub'] == $val_1->menu_id ? 'menu-open' : '';
                                    $active = $active_menu['menu_id_parent_sub'] == $val_1->menu_id ? 'active' : '';
                                    $html .= '
                                        <li class="nav-item ' . $menu_open . '">
                                            <a href="#" class="nav-link ' . $active . '">
                                                <i class="' . $val_1->nav_icon . ' nav-icon"></i>
                                                <p class="text-bold">
                                                    ' . $val_1->menu_name . '
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                    ';

                                    foreach ($submenu_data_2 as $key_2 => $val_2) {
                                        $valid_menu_2 = $this->valid_menu_sidebar($val_2->menu_id);
                                        if ($valid_menu_2 == true) {
                                            // $link = $this->sidebar_link($val_2->modules_name, $val_2->controller_name, $val_2->function_name);
                                            $link = $val_2->url;
                                            $active = $active_menu['menu_id'] == $val_2->menu_id ? 'active' : '';
                                            $html .= '
                                                <li class="nav-item">
                                                    <a href="' . site_url($link) . '" class="nav-link ' . $active . '">
                                                        <i class="' . $val_2->nav_icon . ' nav-icon"></i>
                                                        <p>' . $val_2->menu_name . '</p>
                                                    </a>
                                                </li>
                                            ';
                                        }
                                    }

                                    $html .= '
                                            </ul>
                                        </li>
                                    ';
                                } else {
                                    // $link = $this->sidebar_link($val_1->modules_name, $val_1->controller_name, $val_1->function_name);
                                    $link = $val_1->url;
                                    $active = $active_menu['menu_id'] == $val_1->menu_id ? 'active' : '';
                                    $html .= '
                                        <li class="nav-item">
                                            <a href="' . site_url($link) . '" class="nav-link ' . $active . '">
                                                <i class="' . $val_1->nav_icon . ' nav-icon"></i>
                                                <p>' . $val_1->menu_name . '</p>
                                            </a>
                                        </li>
                                    ';
                                }
                            }
                        }

                        $html .= '
                                </ul>
                            </li>
                        ';
                    } else {
                        $html .= '
                            <li class="nav-header text-bold">' . strtoupper($val->menu_name) . '</li>
                        ';
                    }
                } else if ($val->menu_type == "Main Menu") {
                    $submenu_data_1 = $this->_ci->main_model->get_sidebar_sub_menu_active($val->menu_id)->result();
                    if (!empty($submenu_data_1)) {
                        $menu_open = $active_menu['menu_id_parent'] == $val->menu_id ? 'menu-open' : '';
                        $active = $active_menu['menu_id_parent'] == $val->menu_id ? 'active' : '';
                        $html .= '
                            <li class="nav-item ' . $menu_open . '">
                                <a href="#" class="nav-link ' . $active . '">
                                    <i class="' . $val->nav_icon . ' nav-icon"></i>
                                    <p class="text-bold">
                                        ' . strtoupper($val->menu_name) . '
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                        ';

                        foreach ($submenu_data_1 as $key_1 => $val_1) {
                            $valid_menu_1 = $this->valid_menu_sidebar($val_1->menu_id);
                            if ($valid_menu_1 == true) {
                                $submenu_data_2 = $this->_ci->main_model->get_sidebar_sub_menu_active($val_1->menu_id)->result();
                                if (!empty($submenu_data_2)) {
                                    $menu_open = $active_menu['menu_id_parent_sub'] == $val_1->menu_id ? 'menu-open' : '';
                                    $active = $active_menu['menu_id_parent_sub'] == $val_1->menu_id ? 'active' : '';
                                    $html .= '
                                        <li class="nav-item ' . $menu_open . '">
                                            <a href="#" class="nav-link ' . $active . '">
                                                <i class="' . $val_1->nav_icon . ' nav-icon"></i>
                                                <p class="text-bold">
                                                    ' . $val_1->menu_name . '
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                    ';

                                    foreach ($submenu_data_2 as $key_2 => $val_2) {
                                        $valid_menu_2 = $this->valid_menu_sidebar($val_2->menu_id);
                                        if ($valid_menu_2 == true) {
                                            // $link = $this->sidebar_link($val_2->modules_name, $val_2->controller_name, $val_2->function_name);
                                            $link = $val_2->url;
                                            $active = $active_menu['menu_id'] == $val_2->menu_id ? 'active' : '';
                                            $html .= '
                                                <li class="nav-item">
                                                    <a href="' . site_url($link) . '" class="nav-link ' . $active . '">
                                                        <i class="' . $val_2->nav_icon . ' nav-icon"></i>
                                                        <p>' . $val_2->menu_name . '</p>
                                                    </a>
                                                </li>
                                            ';
                                        }
                                    }

                                    $html .= '
                                            </ul>
                                        </li>
                                    ';
                                } else {
                                    // $link = $this->sidebar_link($val_1->modules_name, $val_1->controller_name, $val_1->function_name);
                                    $link = $val_1->url;
                                    $active = $active_menu['menu_id'] == $val_1->menu_id ? 'active' : '';
                                    $html .= '
                                        <li class="nav-item">
                                            <a href="' . site_url($link) . '" class="nav-link ' . $active . '">
                                                <i class="' . $val_1->nav_icon . ' nav-icon"></i>
                                                <p>' . $val_1->menu_name . '</p>
                                            </a>
                                        </li>
                                    ';
                                }
                            }
                        }

                        $html .= '
                                </ul>
                            </li>
                        ';
                    } else {
                        // $link = $this->sidebar_link($val->modules_name, $val->controller_name, $val->function_name);
                        $link = $val->url;
                        $active = $active_menu['menu_id'] == $val->menu_id ? 'active' : '';
                        $html .= '
                            <li class="nav-item">
                                <a href="' . site_url($link) . '" class="nav-link ' . $active . '">
                                    <i class="' . $val->nav_icon . ' nav-icon"></i>
                                    <p>' . $val->menu_name . '</p>
                                </a>
                            </li>
                        ';
                    }
                }
            }
        }

        return $html;
    }

    public function menu_active()
    {
        $_modules = $this->_ci->uri->segment(1);
        $_controller = $this->_ci->uri->segment(2);
        $_function = !empty($this->_ci->uri->segment(3)) ? $this->_ci->uri->segment(3) : 'index';
        $_function = 'index';
        $menu = $this->_ci->main_model->get_menu_access($_modules, $_controller, $_function)->result();

        $active_menu = isset($_SESSION['_main_id']) ? $_SESSION['_main_id'] : null;

        if (@$menu[0]->menu_id) {
            $active_menu = $menu[0]->menu_id; //PR
        }

        $where = ['menu_id' => $active_menu, 'access_status' => 'Activated', 'is_delete' => 'No',];
        $check_menu = $this->_ci->db->get_where('main_menu', $where)->num_rows();

        if ($check_menu != 0) {
            $menu_data = $this->_ci->db->get_where('main_menu', $where)->result();

            $where_2 = ['menu_id' => $menu_data[0]->menu_id_parent,];
            $check_menu_2 = $this->_ci->db->get_where('main_menu', $where_2)->num_rows();
            if ($check_menu_2 != 0) {
                $menu_data_2 = $this->_ci->db->get_where('main_menu', $where_2)->result();

                $result = [
                    'menu_id' => $menu_data[0]->menu_id,
                    'menu_id_parent' => !empty($menu_data_2[0]->menu_id_parent) ? $menu_data_2[0]->menu_id_parent : $menu_data[0]->menu_id_parent,
                    'menu_id_parent_sub' => $menu_data[0]->menu_id_parent,
                ];
            } else {
                $result = [
                    'menu_id' => $menu_data[0]->menu_id,
                    'menu_id_parent' => $menu_data[0]->menu_id_parent,
                    'menu_id_parent_sub' => '',
                ];
            }
        } else {
            $result = [
                'menu_id' => '',
                'menu_id_parent' => '',
                'menu_id_parent_sub' => '',
            ];
        }
        return $result;
    }

    public function role_landing_page()
    {
        $menu_id = isset($_SESSION['sgn_landing_page']) ? $_SESSION['sgn_landing_page'] : null;
        $menu = $this->_ci->db->get_where('main_menu', ["menu_id" => $menu_id])->result();

        if (!empty($menu[0]->function_name) && $menu[0]->function_name != '-' && $menu[0]->function_name != 'index') {
            $link = $menu[0]->modules_name . '/' . $menu[0]->controller_name . '/' . $menu[0]->function_name;
        } else if (!empty($menu[0]->controller_name) && $menu[0]->controller_name != '-' && $menu[0]->controller_name != $menu[0]->modules_name) {
            $link = $menu[0]->modules_name . '/' . $menu[0]->controller_name;
        } else {
            $link = $menu[0]->modules_name;
        }

        return $link;
    }

    public function sidebar_link($_modules = null, $_controller = null, $_function = null)
    {
        if (!empty($_function) && $_function != '-' && $_function != 'index') {
            $link = $_modules . '/' . $_controller . '/' . $_function;
        } else if (!empty($_controller) && $_controller != '-' && $_controller != $_modules) {
            $link = $_modules . '/' . $_controller;
        } else {
            $link = $_modules;
        }

        return $link;
    }

    public function valid_menu()
    {
        $_modules = $this->_ci->uri->segment(1);
        $_controller = $this->_ci->uri->segment(2);
        $_function = !empty($this->_ci->uri->segment(3)) ? $this->_ci->uri->segment(3) : 'index';

        $role_id = isset($_SESSION['sgn_role_id']) ? $_SESSION['sgn_role_id'] : null;

        $menu = $this->_ci->main_model->get_menu_access($_modules, $_controller, $_function)->result();
        $array_role_id = json_decode($menu[0]->role_id);
        if (in_array($role_id, $array_role_id)) {
            $menu_name = $menu[0]->menu_name;
            $this->_ci->session->set_userdata('_main_id', $menu[0]->menu_id);
            return $menu_name;
        } else {
            $this->_ci->session->set_flashdata('alert_error', 'Anda Tidak Memiliki Akses Halaman.');
            $landing_page = $this->role_landing_page();
            redirect(site_url($landing_page));
            die();
        }
    }

    public function valid_menu_sidebar($menu_id = null)
    {
        $role_id = isset($_SESSION['sgn_role_id']) ? $_SESSION['sgn_role_id'] : null;

        $menu = $this->_ci->main_model->get_menu_sidebar_access($menu_id)->result();
        $array_role_id = json_decode($menu[0]->role_id);
        if (in_array($role_id, $array_role_id)) {
            return true;
        } else {
            return false;
        }
    }

    public function user_data()
    {
        // $user_id = isset($_SESSION['sgn_user_id']) ? $_SESSION['sgn_user_id'] : null;
        // $user = $this->_ci->db->get_where('main_users', ['user_id' => $user_id])->result();
        // return $user;
    }
}
