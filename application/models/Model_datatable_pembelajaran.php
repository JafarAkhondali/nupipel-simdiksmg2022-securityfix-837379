<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_datatable_pembelajaran extends CI_Model
{
    //set nama tabel yang akan kita tampilkan datanya
    var $table = 'rp_pembelajaran';
    //set kolom order, kolom pertama saya null untuk kolom edit dan hapus
    var $column_order = array(null, 'nama_ptk', 'nuptk', 'kepegawaian', 'kode_matpel', 'nama_matpel', 'tingkat', 'nama_rombel');

    var $column_search = array('nama_ptk', 'nuptk', 'kepegawaian', 'kode_matpel', 'nama_matpel', 'tingkat', 'nama_rombel');
    // default order 
    var $order = array('tingkat' => 'asc');

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

    function getDataMatpel()
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

    public function deleteMatpel_by_id()
    {
        $kodesekolah = $this->input->post('id');
        // $this->db->from($this->table);
        $this->db->where('created_by', $kodesekolah);
        return $this->db->delete('rp_pembelajaran');
    }

    public function insert_Matpel($data)
    {
        $insert_PesertaMapel = $this->db->on_duplicate('rp_pembelajaran', $data);
        if ($insert_PesertaMapel) {
            return true;
        }
    }
}
