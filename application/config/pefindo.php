<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05/08/2022
 * Time: 10:37
 */

//Untuk set environment codeigniter
//Tambahkan script ini di .htaccess
//<IfModule mod_env.c>
//    SetEnv CI_ENV development
//</IfModule>
if (ENVIRONMENT == 'production') {
    $pefindo['url'] = 'https://fo.pefindobirokredit.com/WsReport/v5.82/service.svc';
    $pefindo['auth'] = 'Authorization: Basic ZGVtb19pbmZyYToxVDFuZnI0c2FuZGVycw==';//masih dummy, belum yg production
} else {
    $pefindo['url'] = 'https://cbs5bodemo2.pefindobirokredit.com/WsReport/v5.82/service.svc';
    $pefindo['auth'] = 'Authorization: Basic ZGVtb19pbmZyYToxVDFuZnI0c2FuZGVycw==';
    $pefindo['nik'] = '3114050501850036';
    $pefindo['fullname'] = 'Andy firmansah';
    $pefindo['tanggalLahir'] = '1985-05-01';

}
$config['pefindo'] = $pefindo;
