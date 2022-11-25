<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_datatable_ceksekolah extends CI_Model
{
    //set nama tabel yang akan kita tampilkan datanya
    var $table = 'pd_peserta_didik';
    //set kolom order, kolom pertama saya null untuk kolom edit dan hapus
    var $column_order = array(null, 'a.npsn', 'b.nama_satuan_pendidikan', 'b.bentuk_pendidikan', 'b.status_sekolah', 'b.alamat');

    var $column_search = array('a.npsn', 'b.nama_satuan_pendidikan', 'b.bentuk_pendidikan', 'b.status_sekolah', 'b.alamat');
    // default order 
    var $order = array('b.bentuk_pendidikan' => 'asc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        // $this->load->library('session');
    }

    private function _get_datatables_query()
    {

        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('a.npsn, b.nama_satuan_pendidikan, b.bentuk_pendidikan, b.status_sekolah, b.alamat');
        $this->db->from('pd_peserta_didik a');
        $this->db->join('master_sekolah b', 'b.npsn=a.npsn', 'left');
        $this->db->group_by('a.npsn', 'b.nama_satuan_pendidikan', 'b.bentuk_pendidikan', 'b.status_sekolah', 'b.alamat');
        $this->db->order_by('b.bentuk_pendidikan', 'asc');

        // $this->db->from($this->table);
        // $this->db->where('created_by', $kodesekolah);

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }


    function get_datatables()
    {
        // var_dump('tes');
        // die;
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->select('a.npsn, b.nama_satuan_pendidikan, b.bentuk_pendidikan, b.status_sekolah, b.alamat');
        $this->db->from('pd_peserta_didik a');
        $this->db->join('master_sekolah b', 'b.npsn=a.npsn', 'left');
        $this->db->group_by('a.npsn', 'b.nama_satuan_pendidikan', 'b.bentuk_pendidikan', 'b.status_sekolah', 'b.alamat');

        return $this->db->count_all_results();
    }
}
