<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Dashboard
 *
 * @property Lib_template lib_template
 * @property Lib_role lib_role
 * @property Lib_dashboard lib_dashboard
 */
class Dashboard extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        lorem_ipsum(); //helper example
        $this->lib_dashboard->lorem_ipsum(); //libraries example
        $this->authenticate->route_access();
    }

    public function index()
    {
        // page title / menu name / validasi akses menu
        //        $data['_title'] = $this->lib_template->valid_menu();
        $data['_title'] = 'Dashboard';
        $additionalJs = [
            'vendor/almasaeed2010/adminlte/plugins/jquery-mousewheel/jquery.mousewheel.js',
            'vendor/almasaeed2010/adminlte/plugins/raphael/raphael.min.js',
            'vendor/almasaeed2010/adminlte/plugins/jquery-mapael/jquery.mapael.min.js',
            'vendor/almasaeed2010/adminlte/plugins/jquery-mapael/maps/usa_states.min.js',
            'vendor/almasaeed2010/adminlte/plugins/chart.js/Chart.min.js',
            'vendor/almasaeed2010/adminlte/dist/js/pages/dashboard2.js',
            'modules/Dashboard/assets/dashboard.js' //assets di modules
        ];
        $this->lib_template->load('Dashboard/main-dashboard-raw', $data, null, $additionalJs);
    }
}
