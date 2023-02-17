<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lib_thousand_format
{
    protected $_ci;

    function __construct()
    {
        $this->_ci = &get_instance();
    }

    public function thousand_format($number)
    {
        $number = (int) preg_replace('/[^0-9]/', '', $number);
        if ($number >= 1000) {
            $rn = round($number);
            $format_number = number_format($rn);
            $ar_nbr = explode(',', $format_number);
            $x_parts = array(' Ribu', ' Juta', ' M', ' T', 'Q');
            $x_count_parts = count($ar_nbr) - 1;
            $dn = $ar_nbr[0] . ((int) $ar_nbr[1][0] !== 0 ? '.' . $ar_nbr[1][0] : '');
            $dn .= $x_parts[$x_count_parts - 1];

            return $dn;
        }
        return $number;
    }
}
