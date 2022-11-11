<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ImportModel extends CI_Model
{


    // public function insert($data)
    // {
    //     $insert = $this->db->insert_batch('tbl_data2', $data);
    //     if ($insert) {
    //         return true;
    //     }
    // }

    public function insert_PesertaDidik($data)
    {
        $insert_PesertaDidik = $this->db->on_duplicate('pd_peserta_didik', $data);
        if ($insert_PesertaDidik) {
            return true;
        }
    }

    public function insertDetail_PesertaDidik($data)
    {
        $insertDetail_PesertaDidik = $this->db->on_duplicate('pd_detail_siswa', $data);
        if ($insertDetail_PesertaDidik) {
            return true;
        }
    }

    public function insert_Data_Orangtua($data)
    {
        $insert_Data_Orangtua = $this->db->on_duplicate('pd_orang_tua', $data);
        if ($insert_Data_Orangtua) {
            return true;
        }
    }

    public function insert_Data_Wali($data)
    {
        $insert_Data_Wali = $this->db->on_duplicate('pd_data_wali', $data);
        if ($insert_Data_Wali) {
            return true;
        }
    }

    public function insert_Data_rombel($data)
    {
        $insert_Data_rombel = $this->db->on_duplicate('pd_rombel', $data);
        if ($insert_Data_rombel) {
            return true;
        }
    }

    public function insert_staff($data)
    {
        $insert_Data_staff = $this->db->on_duplicate('st_staff', $data);
        if ($insert_Data_staff) {
            return true;
        }
    }

    public function getData()
    {
        $this->db->select('*');
        return $this->db->get('pd_peserta_didik')->result_array();
    }

    public function getDataStaff()
    {
        $this->db->select('*');
        return $this->db->get('st_staff')->result_array();
    }
}
