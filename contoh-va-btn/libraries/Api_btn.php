<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_btn
{

    // SET SUPER GLOBAL
    var $CI = NULL;
    public function __construct()
    {
        $this->CI = &get_instance();
        // $this->load->model('Master_Model', 'mm');
    }

    public function create_va($data){
        $id = "PPDBALHASRA";

        //dev
        // $key = "cwn5GKenoBIc43a43wsuQLAW8rrGzwMi"; 
        // $secrete = "AEkvExg92F";

        //prodcution
        $key = "8ZTWgm228wT0CtPg3KK0b0C0EXKR9TER"; 
        $secrete = "WvGO2KovcH";

        $signature = hash_hmac('SHA256', 
                                $id.':{"ref":"'.$data['ref'].'","va":"'.$data['va'].'","nama":"'.$data['nama_siswa'].'","layanan":"PPDB","kodelayanan":"","jenisbayar":"'.$data['jenis_bayar'].'","kodejenisbyr":"","nogiro":"","noid":"'.$data['no_id'].'","tagihan":"'.$data['tagihan'].'","flag":"'. $data['flag'].'","expired":"","reserve":"","angkatan":"","description":""}:'.$key, 
                                $secrete);
        
        //dev
        // $url = 'https://vabtn-dev.btn.co.id/v1/ppdbalhasra/createVA';
        
        //production
        $url = 'https://vabtn.btn.co.id/v1/ppdbalhasra/createVA';
                
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        $req_va = [
                    'ref'           => $data['ref'], //kodependaftaran+kodeadm+kodeuser
                    'va'            => $data['va'], // 9=previx_va, 4842=kode institusi, 4 digit id pendaftaran, 4 digit id administrasi, 4 digit id jenjang
                    'nama'          => $data['nama_siswa'],
                    'layanan'       => "PPDB",
                    'kodelayanan'   => '',
                    'jenisbayar'    => $data['jenis_bayar'],
                    'kodejenisbyr'  => '',
                    'nogiro'        => '',
                    'noid'          => $data['no_id'],
                    'tagihan'       => $data['tagihan'],
                    'flag'          => $data['flag'],
                    'expired'       => '',
                    'reserve'       => '',
                    'angkatan'      => '',
                    'description'   => ''
                ];
        // echo json_encode($req_va);exit;
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($req_va));
        
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'id: '.$id,
            'key: '.$key,
            'signature: '.$signature
            ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        // echo 
        // echo json_encode($req_va);exit;
        $rsp = json_decode($result, true);
        if ($rsp['rsp'] == "000") {
            
            $this->CI->db->query('INSERT INTO ppdb_transaksi (transaksi_calsis, transaksi_adm, transaksi_harga, transaksi_va) VALUES ('.$data['id_calsis'].', "'.$data['no_urut'].'", '.$data['tagihan'].', '.$rsp['va'].')');
            redirect(base_url('student/administrasi'));
        }else {
            echo "Gagal";
            echo "<br>";
            echo $rsp['rsp'];
            echo "<br>";
            echo $rsp['rspdesc'];
        }
    }

}