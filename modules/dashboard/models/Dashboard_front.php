<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Dashboard_front extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function latLngSekolah()
	{
		$res = $this->db->select('nama_satuan_pendidikan, lintang, bujur')
			->from('master_sekolah')
			->get()->result();

		return $res;
	}

	function satpen_kecamatan()
	{
		$res = $this->db->select('count(npsn) as jml, kecamatan')
			->from('master_sekolah')
			->group_by('kecamatan')->order_by('count(npsn)', 'desc')->get()->result();

		return $res;
	}
}


/* End of file Model_user.php */
/* Location: ./application/models/Model_user.php */