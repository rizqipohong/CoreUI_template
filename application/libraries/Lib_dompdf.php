<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter DomPDF Library
 *
 * Generate PDF's from HTML in CodeIgniter
 *
 * @packge        CodeIgniter
 * @subpackage        Libraries
 * @category        Libraries
 * @author        Ardianta Pargo
 * @license        MIT License
 * @link        https://github.com/ardianta/codeigniter-dompdf
 */

use Dompdf\Dompdf;
class Lib_dompdf
{
    public function __construct(){   
        // require_once autoloader 
        // $folder_name = 'sanders-backend-v2-rm';
        // require_once($_SERVER['DOCUMENT_ROOT']. $folder_name .'/vendor/dompdf/autoload.inc.php');
        require_once dirname(__FILE__).'/dompdf/autoload.inc.php';
        $pdf = new DOMPDF();
        $CI =& get_instance();
        $CI->dompdf = $pdf;

    }
}
?>
