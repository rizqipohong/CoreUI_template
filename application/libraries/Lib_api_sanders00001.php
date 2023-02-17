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
        var_dump($endpoint);
        try {

            $client = new \GuzzleHttp\Client();
            $res = $client->{$method}($endpoint . $url, $array_data);
            $return_api = json_decode($res->getBody());

            

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            

            $response = $e->getResponse();
            $jsonBody = (string)$response->getBody();
            $return_api = json_decode($jsonBody);

            var_dump($return_api);

            print_r("Nahh");

        }


        return $return_api;
    }

    public function params($data = '', $token = '')
    {
        if ($token == '') {
            $token = $this->_ci->db->from('tb_token_key')->get()->row()->token;
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
        $exist_token = $this->_ci->db->from('tb_token_key')->get()->row();
        $url = 'Auth/getToken';

        $username = $this->_ci->config->item('api_sanders_username');
        $password = $this->_ci->config->item('api_sanders_password');

        $array_data = [
            'headers' => [
                'Authorization' => ['Basic ' . base64_encode($username . ':' . $password)],
            ],
            'form_params' => [
                "grant_type" => "client_credentials"
            ]
        ];
        if (empty($exist_token)) {
            $data_api = $this->connection('post', $url, $array_data);
            if (isset($data_api->status) && $data_api->status == 200) {
                $this->_ci->db->insert('tb_token_key', ['token' => $data_api->token]);
                $token = $data_api->token;
                
                print_r($token);
                
                return true;
            } else {
                echo 'Terjadi kendala koneksi api di server.';
                // $this->_ci->session->set_flashdata('alert_error', 'Terjadi kendala koneksi api di server.');
                // redirect($_SERVER['HTTP_REFERER']);
                die;
            }
        } else {
            $check_token = $this->check_token($exist_token->token);
            if ($check_token) {
                return true;
            } else {
                $data_api = $this->connection('post', $url, $array_data);
                if (isset($data_api->status) && $data_api->status == 200) {
                    $this->_ci->db->empty_table('tb_token_key');
                    $this->_ci->db->insert('tb_token_key', ['token' => $data_api->token]);
                    $token = $data_api->token;

                    return true;
                } else {
                    echo 'Terjadi kendala koneksi api di server.';

                    // $this->_ci->session->set_flashdata('alert_error', 'Terjadi kendala koneksi api di server.');
                    // redirect($_SERVER['HTTP_REFERER']);
                    die;
                }
            }
        }
    }
}
