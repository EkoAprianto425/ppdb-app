<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Administrasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->cek_login();
        $this->load->model('Master_Model', 'mm');
        date_default_timezone_set('Asia/Jakarta');
    }
    
    public function create_va(){
        $nama_adm = $_GET['nama'];
        $join = "ppdb_jenjang ON ppdb_adm.adm_jenjang=ppdb_jenjang.jenjang_id";
        $get_data = $this->mm->detail_join('*', 'ppdb_adm', $join, 'adm_no_urut='.$nama_adm.' AND jenjang_nama="'.$this->session->userdata('tujuan').'"');
        
        // echo json_encode($get_data);exit;
        $total = $get_data['adm_harga'];
        // if ($nama_adm != "Formulir") {   
        $kalimat = $nama_adm;
        $arr_kalimat = explode (";",$kalimat);
        $bayar_total = count($arr_kalimat) - 1;
        if ($bayar_total > 1) {
            $sampai = " sampai Tahap ".$bayar_total;
        } else {
            $sampai = "";
        }
        $mao_bayar = $get_data['adm_no_urut']; //nama adm

        // $id_transaksi = $_GET['id'];
        $join = " ppdb_jenjang ON ppdb_user.user_tujuan=ppdb_jenjang.jenjang_nama
                  JOIN ppdb_pendaftaran ON ppdb_user.user_id=ppdb_pendaftaran.daftar_tamu";
        $get_id = $this->mm->detail_join('*','ppdb_user', $join, 'user_id='.$this->session->userdata('id'));

        $ref = $get_id['daftar_id'] . date('ymd');
        // echo $ref;exit;
        $jml_ref = 10-strlen($ref);
        //create no_ref
        $nol_ref = "";
        for ($j=1; $j <= $jml_ref; $j++)
        {
            $nol_ref.="0";
        }
        $no_ref = $nol_ref.$ref;

        //create va 9=previx_va, 4842=kode institusi, 4 digit id pendaftaran, 4 digit id administrasi, 4 digit id jenjang
        $va = "90316"; //previx dan kode institusi production
        // $va = "94842"; //Development
        $tgl = date('ymd'); // 6 digit
        $daftar= 4-strlen($get_id['daftar_id']); // 4 digit
        // $adm_id = $get_id['adm_id'];
        if ($mao_bayar == 1) {
            $kode_adm = "01";
            $flag = "F";
        } else {
            $kode_adm = "0".$get_data['adm_no_urut'];
            $flag = "P";
        }

        $nol_daftar = "";
        for ($d=1; $d <= $daftar; $d++) { 
            $nol_daftar .= "0";
        }
        $va_daftar = $nol_daftar.$get_id['daftar_id'];

        $no_va = $va.$tgl.$va_daftar.$kode_adm;
        // echo $no_va;exit;
        //create no_id  id_transaksi+id_adm+transaksi_calsis
        $noid = $get_id['jenjang_id'].$get_id['user_id']; //7 digit max
        $noId = 7-strlen($noid);
        $nol_id = "";
        for ($ni=1; $ni <= $noId ; $ni++) { 
            $nol_id .= "0";
        }
        $no_id = $nol_id.$noid.$kode_adm;
        // echo "no_id=".$no_id;

        //Harga
        if ($get_id['user_asal_sekolah'] == "SMP AL HASRA" && $mao_bayar == 1) {
            $tagihan = 200000;
        } else {
            $tagihan = $total;
        }

        $data = array (
            'id_calsis'     => $get_id['user_id'],
            'ref'           => $no_ref,
            'va'            => $no_va,
            'nama_siswa'    => $get_id['daftar_nama_siswa'],
            'jenis_bayar'   => $get_data['adm_nama'],
            'no_urut'       => $get_data['adm_no_urut'],
            'no_id'         => $no_id,
            'tagihan'       => $tagihan,
            'flag'          => $flag
        );

        // echo json_encode($data);exit;
        $result = $this->api_btn->create_va($data);
        
    }

}
