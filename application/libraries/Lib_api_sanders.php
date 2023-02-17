<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lib_api_sanders
{
  protected $_ci;

  function __construct()
  {
    $this->_ci = &get_instance();
  }

  // FUNGSI UNTUK KONEKSI DENGAN API
  public function connection($method, $url, $array_data)
  {
    $endpoint = $this->_ci->config->item('api_sanders_url');
    try {
      $client = new \GuzzleHttp\Client();
      $res = $client->{$method}($endpoint . $url, $array_data);
      $return_api = json_decode($res->getBody());
    } catch (\GuzzleHttp\Exception\RequestException $e) {
      $response = $e->getResponse();
      $jsonBody = (string)$response->getBody();
      $return_api = json_decode($jsonBody);
    }
    return $return_api;
  }

  public function params($data = '', $token = '')
  {
    if ($token == '') {
      $username = $this->_ci->config->item('api_sanders_username');
      $password = $this->_ci->config->item('api_sanders_password');

      $token = $this->connection('get', 'Auth/check_existing_token', ['headers' => ['Authorization' => ['Basic ' . base64_encode($username . ':' . $password)]]]);
      $token = $token->data->token;
    }

    $array_data = [
      'headers' => [
        'Authorization' => [
          'Bearer ' . $token,
        ],
      ]
    ];

    if (is_array($data)) {
      foreach ($data as $key => $value) {
        $array_data[$key] = $value;
      }
    }

    return $array_data;
  }

  public function check_token($token)
  {
    $url = "Auth/check_token";
    $array_data = $this->params([], $token);
    $data_api = $this->connection('get', $url, $array_data);
    if ($data_api->data->is_valid) {
      return true;
    } else {
      return false;
    }
  }

  public function valid_token()
  {

    if (!isset($this->_ci->session->userdata['loginStatus'])) {
      redirect('Auth');
    }
    $username = $this->_ci->config->item('api_sanders_username');
    $password = $this->_ci->config->item('api_sanders_password');

    $url = 'Auth/getToken';
    $array_data = [
      'headers' => [
        'Authorization' => ['Basic ' . base64_encode($username . ':' . $password)],
      ],
      'form_params' => [
        "grant_type" => "client_credentials"
      ]
    ];


    $con_exist_token = $this->connection('get', 'Auth/check_existing_token', ['headers' => ['Authorization' => ['Basic ' . base64_encode($username . ':' . $password)]]]);
    $exist_token = $con_exist_token->data;

    if (empty($exist_token)) {
      $data_api = $this->connection('post', $url, $array_data);
      if (isset($data_api->status) && $data_api->status == 200) {
        return true;
      } else {
        echo 'Terjadi kendala koneksi api di server.';
        die;
      }
    } else {
      $check_token = $this->check_token($exist_token->token);
      if ($check_token) {
        return true;
      } else {
        $data_api = $this->connection('post', $url, $array_data);
        if (isset($data_api->status) && $data_api->status == 200) {
          return true;
        } else {
          echo 'Terjadi kendala koneksi api di server.';
          die;
        }
      }
    }
  }
}
