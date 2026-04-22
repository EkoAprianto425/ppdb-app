<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callback extends CI_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->model('Master_Model', 'mm');
    }

    function index(){

        $arr = json_decode(file_get_contents('php://input'), true);

        if (!$arr) {
            echo '{"rsp":"001","rspdesc":"Transaction Failed"}';
        }
        else {
            $ref = $arr['ref'];
            $va = $arr['va'];
            $nama = $arr['nama'];
            $teller = $arr['teller'];
            $transcode = $arr['transcode'];
            $seq = $arr['seq'];
            $tgl = $arr['tgl'];
            $jam = $arr['jam'];
            $amount = $arr['amount'];
            $revflag = $arr['revflag'];
            $revseq = $arr['revseq'];
            $revjam = $arr['revjam'];
            $tagihan = $arr['tagihan'];
            $terbayar = $arr['terbayar'];
            
            // $cek = $this->mm->detail('*', 'ppdb_transaksi', ' where transaksi_va='.$va); // Cek apakah ada data VAnya
            
            $this->db->select('*');
            $this->db->from('ppdb_transaksi');
            $this->db->where('transaksi_va', $va);
            $query = $this->db->get();
            $cek = $query->row_array();
            if ($cek['transaksi_va'] == $va) {
                echo '{"rsp":"000","rspdesc":"Transaction Success"}';
                // $total_bayar = $cek['transaksi_bayar'] + $amount;
                $data = array(
                    'transaksi_bayar'           => $terbayar,
                    'transaksi_tgl_bukti'   => date('Y-m-d')
                );
                $this->mm->edit('ppdb_transaksi', 'transaksi_id='.$cek['transaksi_id'], $data); // Proses update
            }
        }
    }
}