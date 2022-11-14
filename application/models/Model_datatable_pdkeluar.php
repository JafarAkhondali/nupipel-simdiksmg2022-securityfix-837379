<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_datatable_pdkeluar extends CI_Model
{
    //set nama tabel yang akan kita tampilkan datanya
    var $table = 'pd_peserta_didik_keluar';
    //set kolom order, kolom pertama saya null untuk kolom edit dan hapus
    var $column_order = array(null, 'nisn', 'keluar_karena', 'tanggal_keluar');

    var $column_search = array('nisn', 'keluar_karena', 'tanggal_keluar');
    // default order 
    var $order = array('tanggal_keluar' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        // $this->load->library('session');
    }


    private function _get_datatables_query()
    {
        $kodesekolah = $this->input->post('id');

        $this->db->from($this->table);
        $this->db->where('created_by', $kodesekolah);

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

    function getDataPd()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->result();
    }


    function get_datatables()
    {
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
        $kodesekolah = $this->input->post('id');

        $this->db->from($this->table);
        $this->db->where('created_by', $kodesekolah);

        return $this->db->count_all_results();
    }

    public function deleteSO_by_id()
    {
        $kodesekolah = $this->input->post('id');
        // $this->db->from($this->table);
        $this->db->where('created_by', $kodesekolah);
        return $this->db->delete('pd_peserta_didik_keluar');
    }

    public function insert_PesertaDidikKeluar($data)
    {
        $insert_PesertaDidik = $this->db->on_duplicate('pd_peserta_didik_keluar', $data);
        if ($insert_PesertaDidik) {
            return true;
        }
    }
}
