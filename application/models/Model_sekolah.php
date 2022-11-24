<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_sekolah extends CI_Model
{

    public function DetailSekolah()
    {
        $kodesekolah = get_user_data('npsn');

        $this->db->select('npsn, nama_satuan_pendidikan, bentuk_pendidikan, alamat');
        $this->db->from('master_sekolah');
        $this->db->where('npsn', $kodesekolah);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result_array();
    }
}

/* End of file Model_sekolah.php */
