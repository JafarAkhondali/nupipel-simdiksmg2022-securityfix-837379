<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_datatable_pd extends CI_Model
{
    //set nama tabel yang akan kita tampilkan datanya
    var $table = 'pd_peserta_didik';
    //set kolom order, kolom pertama saya null untuk kolom edit dan hapus
    var $column_order = array(null, 'a.nisn', 'a.nama', 'a.nipd', 'a.jk', 'a.tanggal_lahir', 'a.tempat_lahir', 'a.nik', 'a.agama', 'a.alamat');

    var $column_search = array('a.nisn', 'a.nama', 'a.nipd', 'c.rombel');
    // default order 
    var $order = array('nama' => 'asc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        // $this->load->library('session');
    }

    public function updateDataSiswa()
    {
        $nisn = $this->input->get('nisn');
        $update_data = [
            'status' => 'Non Aktif'
        ];

        $this->db->where('nisn', $nisn);
        $this->db->update($this->table, $update_data);
        return $this;
    }

    private function _get_datatables_query()
    {
        $kodesekolah = $this->input->post('id');

        $this->db->from('pd_peserta_didik a');
        $this->db->join('pd_detail_siswa b', 'b.nisn=a.nisn', 'left');
        $this->db->join('pd_rombel c', 'c.nisn=a.nisn', 'left');
        $this->db->where('a.created_by', $kodesekolah);
        // $this->db->order_by('c.rombel', 'asc');
        // $this->db->order_by('a.status', 'desc');

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

    public function getBackup()
    {
        $this->db->from('backup');
        $query = $this->db->get();
        return $query->result();
    }

    public function count_siswa_by_id($kodesekolah, $status)
    {
        $this->db->from($this->table);
        $this->db->where(array('created_by' => $kodesekolah, 'status' => $status));

        return $this->db->count_all_results();
    }

    public function deletePD_by_id()
    {
        $kodesekolah = $this->input->post('id');
        // $this->db->from($this->table);
        $this->db->where('created_by', $kodesekolah);
        return $this->db->delete(array('pd_peserta_didik', 'pd_detail_siswa', 'pd_data_wali', 'pd_orang_tua', 'pd_rombel'));
    }

    public function deleteBackupTabel()
    {
        $tahun = $this->input->get('tahun');
        $id = $this->input->get('id');

        $this->db->where('id', $id);
        $res = $this->db->delete('backup');

        if ($res) {
            $delete_backup = $this->db->query('DROP TABLE IF EXISTS backup_peserta_didik_' . $tahun . ', backup_rombel_' . $tahun . ', backup_staff_' . $tahun . '');
            return $delete_backup;
        } else {
            return false;
        }
    }

    public function insert_PesertaDidik($data)
    {
        $npsn = get_user_data('npsn');
        $insert_PesertaDidik = $this->db->on_duplicate('pd_peserta_didik', $data);
        $insert_PesertaDidik = $this->db->where('npsn', $npsn);
        if ($insert_PesertaDidik) {
            return true;
        }
    }

    public function getBackupDataPd()
    {
        $kodesekolah = $this->input->post('id');

        $this->db->from($this->table);
        $this->db->where('created_by', $kodesekolah);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function Backup_by_tahun()
    {
        $tahun = $this->input->post('tahun');

        $tabel_pd_backup = $this->db->query('CREATE TABLE IF NOT EXISTS  backup_peserta_didik_' . $tahun . ' LIKE pd_peserta_didik');
        $tabel_staff_backup = $this->db->query('CREATE TABLE IF NOT EXISTS  backup_staff_' . $tahun . ' LIKE st_staff');
        $tabel_rombel_backup = $this->db->query('CREATE TABLE IF NOT EXISTS  backup_rombel_' . $tahun . ' LIKE pd_rombel');
        //$tabel_detailpd_backup = $this->db->query('CREATE TABLE IF NOT EXISTS  backup_detailpd_' . $tahun . ' LIKE pd_detail_siswa');

        if ($tabel_pd_backup && $tabel_staff_backup && $tabel_rombel_backup) {
            $this->db->query('INSERT INTO backup_peserta_didik_' . $tahun . ' SELECT * FROM pd_peserta_didik');
            $this->db->query('INSERT INTO backup_staff_' . $tahun . ' SELECT * FROM st_staff');
            $this->db->query('INSERT INTO backup_rombel_' . $tahun . ' SELECT * FROM pd_rombel');
            return true;
        } else {
            return false;
        }
    }

    public function backup_PesertaDidik($data)
    {
        $backup_PesertaDidik = $this->db->on_duplicate('backup_peserta_didik', $data);
        if ($backup_PesertaDidik) {
            return json_encode('berhasil');
        } else {
            return json_encode('gagal');
        }
    }

    public function insertDetail_PesertaDidik($data)
    {
        $npsn = get_user_data('npsn');
        $insertDetail_PesertaDidik = $this->db->on_duplicate('pd_detail_siswa', $data);
        $insertDetail_PesertaDidik = $this->db->where('npsn', $npsn);
        if ($insertDetail_PesertaDidik) {
            return true;
        }
    }

    public function insert_Data_Orangtua($data)
    {
        $npsn = get_user_data('npsn');
        $insert_Data_Orangtua = $this->db->on_duplicate('pd_orang_tua', $data);
        $insert_Data_Orangtua = $this->db->where('npsn', $npsn);
        if ($insert_Data_Orangtua) {
            return true;
        }
    }

    public function insert_Data_Wali($data)
    {
        $npsn = get_user_data('npsn');
        $insert_Data_Wali = $this->db->on_duplicate('pd_data_wali', $data);
        $insert_Data_Wali = $this->db->where('npsn', $npsn);
        if ($insert_Data_Wali) {
            return true;
        }
    }

    public function insert_Data_rombel($data)
    {
        $npsn = get_user_data('npsn');
        $insert_Data_rombel = $this->db->on_duplicate('pd_rombel', $data);
        $insert_Data_rombel = $this->db->where('npsn', $npsn);
        if ($insert_Data_rombel) {
            return true;
        }
    }

    public function insert_Data_backup($data)
    {
        $insert_Data_backup = $this->db->insert('backup', $data);
        if ($insert_Data_backup) {
            return true;
        }
    }
}
