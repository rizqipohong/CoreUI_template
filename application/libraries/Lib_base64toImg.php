<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Lib_base64toImg
{

  protected $_ci;

  function __construct()
  {
    $this->_ci = &get_instance();
  }

  function is_base64($s)
  {
    // Check if there are valid base64 characters
    // if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s)) return false;
    // Decode the string in strict mode and check the results
    // $decoded = base64_decode($s, true);
    // if (false === $decoded) return false;
    if(strlen($s)==0) return false;
    $img = '';

    if (!strlen($s) > 0) {
      print_r(json_encode(array('status' => false, 'message' => 'No Base64 image provided ' . strlen($s))));
      exit;
    }

    if (strpos($s, 'data:image/png;base64,') == 0) {
      $img = str_replace('data:image/png;base64,', '', $s);
    } else if (strpos($s, 'data:image/jpeg;base64,') == 0) {
      $img = str_replace('data:image/jpeg;base64,', '', $s);
    } else if (strpos($s, 'data:image/jpg;base64,') == 0) {
      $img = str_replace('data:image/jpg;base64,', '', $s);
    } else {
      print_r(json_encode(array('status' => false, 'message' => 'Base64 is not Image Encoded!')));
      exit;
    }

    // Encode the string again
    $dec = base64_decode($img);
    $enc = base64_encode($dec);
    if ($enc == $img) return true;
    print_r(json_encode(array('status' => false, 'message' => 'Base64 Image not valid!')));

    // if (base64_encode($decoded) != $s) return false;
    // return true;
  }

  public function file_uploads($path, $base64string, $providedName, $fileSize)
  {
    // if (
    //   $this->is_base64($base64string) == true
    // ) {
    //   $path = $path || './uploads/profile_picture/';
    //   $base64string = "data:image/png;base64," . $base64string;
    //   $this->check_size($base64string, $fileSize);
    //   $this->check_dir($path);
    //   $this->check_file_type($base64string);

    //   /*=================uploads=================*/
    //   list($type, $base64string) = explode(';', $base64string);
    //   list(, $extension)          = explode('/', $type);
    //   list(, $base64string)       = explode(',', $base64string);
    //   $fileName                  = $providedName . '.' . $extension;
    //   $base64string              = base64_decode($base64string);
    //   file_put_contents($path . $fileName, $base64string);
    //   return array('status' => true, 'message' => 'successfully upload !', 'file_name' => $fileName, 'with_path' => $path . $fileName);
    // } else {
    //   print_r(json_encode(array('status' => false, 'message' => 'This Base64 String not allowed !')));
    //   exit;
    // }


    // new code
    $this->is_base64($base64string);
    $this->check_size($base64string, $fileSize);
    $this->check_dir($path);
    $this->check_file_type($base64string);
    list($type, $base64string) = explode(';', $base64string);
    list(, $extension) = explode('/', $type);
    list(, $base64string) = explode(',', $base64string);
    $extension = $extension !== 'jpg' ? 'jpg' : $extension;
    $fileName = $providedName . '.' . $extension;
    $base64string = base64_decode($base64string);
    file_put_contents($path . $fileName, $base64string);
    return array('status' => true, 'message' => 'successfully upload !', 'file_name' => $fileName, 'with_path' => $path . $fileName);
  }

  public function check_size($base64string, $fileSize = 4096000)
  {
    // $size = @getimagesize($base64string);

    // if ($size['bits'] >= $fileSize) {
    //   print_r(json_encode(array('status' => false, 'message' => 'file size not allowed !')));
    //   exit;
    // }
    // return true;

    //new code for counting the image size
    $trimmed = rtrim($base64string, '=');
    $length = strlen($trimmed);
    $size = $length * (3 / 4);
    if ($size >= $fileSize) {
      print_r(json_encode(array('status' => false, 'message' => 'file size not allowed !' . $size . ' > ' . $fileSize)));
      exit;
    }
    return true;
  }

  public function check_dir($path)
  {
    if (!file_exists($path)) {
      mkdir($path, 0777, true);
      return true;
    }
    return true;
  }

  public function check_file_type($base64string)
  {
    $mime_type = @mime_content_type($base64string);
    $allowed_file_types = ['image/png', 'image/jpeg', 'image/webp'];
    if (!in_array($mime_type, $allowed_file_types)) {
      // File type is NOT allowed
      // print_r(json_encode(array('status' =>false,'message' => 'File type is NOT allowed !')));exit;
      print_r(json_encode(array('status' => false, 'message' => 'File type is NOT allowed !')));
      exit;
    }
    return true;
  }
}
