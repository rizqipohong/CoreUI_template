<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lib_dashboard
{
    protected $_ci;

    function __construct()
    {
        $this->_ci = &get_instance();
    }

    function lorem_ipsum(){
        return 'lorem ipsum libraries';
    }
}
