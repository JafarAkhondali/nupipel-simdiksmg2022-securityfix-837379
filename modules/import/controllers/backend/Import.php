<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 *| --------------------------------------------------------------------------
 *| Dashboard Controller
 *| --------------------------------------------------------------------------
 *| For see your board
 *|
 */
class Import extends Admin
{

	public function __construct()
	{
		parent::__construct();
		// $this->load->model('ImportModel');
		$this->load->database();
		$this->load->model('model_datatable_staff');
		$this->load->model('model_datatable_pd');
		$this->load->model('model_datatable_pdkeluar');
		$this->load->model('model_datatable_pembelajaran');
		$this->load->library(array('excel', 'session'));
	}

	public function pd()
	{
		if (!$this->aauth->is_allowed('dashboard')) {
			redirect('/', 'refresh');
		}

		$this->load->model('Model_datatable_pd');

		$kodesekolah = get_user_data('npsn');

		$this->data['jumlah_siswa_aktif'] = $this->model_datatable_pd->count_siswa_by_id($kodesekolah, 'Aktif');
		$this->data['jumlah_siswa_nonaktif'] = $this->model_datatable_pd->count_siswa_by_id($kodesekolah, 'Non Aktif');
		$this->render('backend/standart/upload', $this->data);
	}

	public function staff()
	{
		if (!$this->aauth->is_allowed('dashboard')) {
			redirect('/', 'refresh');
		}

		$this->load->model('Model_datatable_staff');

		$kodesekolah = get_user_data('npsn');

		$this->data['jumlah_guru_aktif'] = $this->model_datatable_staff->count_guru_by_id($kodesekolah, 'Aktif', 'Guru');
		$this->data['jumlah_guru_nonaktif'] = $this->model_datatable_staff->count_guru_by_id($kodesekolah, 'Non Aktif', 'Guru');


		$this->render('backend/standart/upload_staff', $this->data);
	}

	public function tendik()
	{
		if (!$this->aauth->is_allowed('dashboard')) {
			redirect('/', 'refresh');
		}

		$this->load->model('Model_datatable_staff');

		$kodesekolah = get_user_data('npsn');

		$this->data['jumlah_tendik_aktif'] = $this->model_datatable_staff->count_guru_by_id($kodesekolah, 'Aktif', 'Tendik');
		$this->data['jumlah_tendik_nonaktif'] = $this->model_datatable_staff->count_guru_by_id($kodesekolah, 'Non Aktif', 'Tendik');

		$this->render('backend/standart/upload_tendik', $this->data);
	}


	public function pd_keluar()
	{
		if (!$this->aauth->is_allowed('dashboard')) {
			redirect('/', 'refresh');
		}
		$this->render('backend/standart/upload_pd_keluar');
	}

	public function pembelajaran()
	{
		if (!$this->aauth->is_allowed('dashboard')) {
			redirect('/', 'refresh');
		}
		$this->render('backend/standart/upload_pembelajaran');
	}


	public function updatepd($id = null, $status = null)
	{
		$status = $this->input->get('status');

		$data = array(
			'status' => $status
		);
		$this->db->where('nisn', $id);
		$tolak = $this->db->update('pd_peserta_didik', $data);

		if ($tolak) {
			set_message('Ubah Status ' . $status . ' Berhasil', 'success');
		} else {
			set_message('Gagal update status', 'error');
		}

		redirect_back();
	}

	public function updatestaff($id = null, $status = null, $npsn = null)
	{
		$vnpsn = get_user_data('npsn');
		$npsn = $this->input->get('npsn');
		if ($vnpsn == $npsn) {
			$status = $this->input->get('status');
			$data = array(
				'status' => $status
			);
			$this->db->where('nik', $id);
			$update_status = $this->db->update('st_staff', $data);

			if ($update_status) {
				set_message('Ubah Status ' . $status . ' Berhasil', 'success');
			} else {
				set_message('Gagal update status', 'error');
			}

			redirect_back();
		} else {
			redirect_back();
		}
	}


	function dataTableStaff()
	{

		header('Content-Type: application/json');
		$list = $this->model_datatable_staff->get_datatables();
		$data = array();
		$no = $this->input->post('start');

		foreach ($list as $datas) {
			$no++;
			$row = array();

			$row[] =  '<strong>' . $no . '</strong>';
			$row[] = $datas->nama;
			$row[] = $datas->nuptk;
			$row[] = $datas->nip;
			$row[] = $datas->status_kepegawaian;
			if ($datas->status == 'Aktif') {

				$row[] = '<a href="updatestaff/' . $datas->nik . '?status=Non Aktif&npsn=' . $datas->created_by . '" id="update-data" class="btn btn-danger update-data" data-toggle="tooltip" data-placement="top" title="Non Aktifkan"><i class="fa fa-ban" aria-hidden="true"></i></a>';
			} else {
				$row[] = '<a href="updatestaff/' . $datas->nik . '?status=Aktif&npsn=' . $datas->created_by . '" id="update-data" class="btn btn-info update-data" data-toggle="tooltip" data-placement="top" title="Aktifkan"><i class="fa fa-check-circle-o" aria-hidden="true"></i></a>';
			}
			$data[] = $row;
		}
		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->model_datatable_staff->count_all(),
			"recordsFiltered" => $this->model_datatable_staff->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
		// $this->output->set_output(json_encode($output));
	}

	function dataTableMatpel()
	{

		header('Content-Type: application/json');
		$list = $this->model_datatable_pembelajaran->get_datatables();
		$data = array();
		$no = $this->input->post('start');

		foreach ($list as $datas) {
			$no++;
			$row = array();

			$row[] =  '<strong>' . $no . '</strong>';
			$row[] = $datas->nama_ptk;
			$row[] = $datas->nuptk;
			$row[] = $datas->kepegawaian;
			$row[] = $datas->nama_matpel;
			$row[] = $datas->nama_rombel;
			$data[] = $row;
		}
		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->model_datatable_pembelajaran->count_all(),
			"recordsFiltered" => $this->model_datatable_pembelajaran->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
		// $this->output->set_output(json_encode($output));
	}

	function dataTablePdKeluar()
	{

		header('Content-Type: application/json');
		$list = $this->model_datatable_pdkeluar->get_datatables();
		$data = array();
		$no = $this->input->post('start');

		foreach ($list as $datas) {
			$no++;
			$row = array();

			$row[] =  '<strong>' . $no . '</strong>';
			$row[] = $datas->nisn;
			$row[] = $datas->keluar_karena;
			$row[] = $datas->tanggal_keluar;
			$data[] = $row;
		}
		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->model_datatable_pdkeluar->count_all(),
			"recordsFiltered" => $this->model_datatable_pdkeluar->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
		// $this->output->set_output(json_encode($output));
	}

	function dataTablePd()
	{
		// echo json_encode('tes');
		// die;
		// $id = $this->input->post('id');
		header('Content-Type: application/json');
		$list = $this->model_datatable_pd->get_datatables();
		$data = array();
		$no = $this->input->post('start');
		$url = base_url();

		foreach ($list as $datas) {
			$no++;
			$row = array();

			$row[] =  '<strong>' . $no . '</strong>';
			$row[] = $datas->nisn;
			$row[] = $datas->nama;
			$row[] = $datas->nipd;
			$row[] = $datas->rombel;
			$row[] = $datas->jk;
			$row[] = $datas->tanggal_lahir;
			$row[] = $datas->tempat_lahir;
			$row[] = $datas->nik;
			$row[] = $datas->agama;
			$row[] = $datas->alamat;
			if ($datas->status == 'Aktif') {
				$row[] = '<a href="updatepd/' . $datas->nisn . '?status=Non Aktif" id="update-data" class="btn btn-danger update-data" data-toggle="tooltip" data-placement="top" title="Non Aktifkan"><i class="fa fa-ban" aria-hidden="true"></i></a>';
			} else {
				$row[] = '<a href="updatepd/' . $datas->nisn . '?status=Aktif" id="update-data" class="btn btn-info update-data" data-toggle="tooltip" data-placement="top" title="Aktifkan"><i class="fa fa-check-circle-o" aria-hidden="true"></i></a>';
			}

			$data[] = $row;
		}
		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->model_datatable_pd->count_all(),
			"recordsFiltered" => $this->model_datatable_pd->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
		// $this->output->set_output(json_encode($output));
	}

	function deleteDataStaff()
	{
		$this->model_datatable_staff->delete_by_id();
		return json_encode("del");
	}

	function deleteDataMatpel()
	{
		$this->model_datatable_pembelajaran->deleteMatpel_by_id();
		return json_encode("del");
	}

	function deleteDataPD()
	{
		$this->model_datatable_pd->deletePD_by_id();
		return  json_encode("del");
	}

	function deleteDataPdKeluar()
	{
		$this->model_datatable_pdkeluar->deletePdKeluar_by_id();
		return  json_encode("del");
	}

	function cekDatatableStaff()
	{
		$jumlahdata = $this->model_datatable_staff->count_all();
		echo json_encode($jumlahdata);
	}

	function cekDatatableMatpel()
	{
		$jumlahdata = $this->model_datatable_pembelajaran->count_all();
		echo json_encode($jumlahdata);
	}

	function cekDatatablePd()
	{
		$jumlahdata = $this->model_datatable_pd->count_all();
		echo json_encode($jumlahdata);
	}

	function cekDatatablePdKeluar()
	{
		$jumlahdata = $this->model_datatable_pdkeluar->count_all();
		echo json_encode($jumlahdata);
	}

	public function import_pd()
	{
		$config['allowed_types']    = 'xls|xlsx';
		$this->load->library('upload', $config);
		$temp = explode(".", $_FILES["file"]["name"]);
		// $extension = end($temp);
		$mimes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

		$tahun = $this->input->post('tahun_ajaran');

		if (isset($_FILES["fileExcel"]["name"]) && in_array($_FILES["fileExcel"]["type"], $mimes)) {

			$path = $_FILES["fileExcel"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach ($object->getWorksheetIterator() as $worksheet) {
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				$cell = $worksheet->getCellByColumnAndRow(1, 7)->getValue();

				for ($row = 7; $row <= $highestRow; $row++) {
					$nama = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$nipd = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$jk = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$nisn = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$tempat_lahir = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$tanggal_lahir = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$nik = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					$agama = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
					$alamat = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
					$rt = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
					$rw = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
					$dusun = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
					$kelurahan = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
					$kecamatan = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
					$kode_pos = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
					$jenis_tinggal = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
					$transportasi = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
					$telepon = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
					$hp = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
					$email = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
					$skhun = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
					$penerima_kps = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
					$no_kps = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
					$nama_ayah = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
					$tahun_lahir_ayah = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
					$pendidikan_ayah = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
					$pekerjaan_ayah = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
					$penghasilan_ayah = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
					$nik_ayah = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
					$nama_ibu = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
					$tahun_lahir_ibu = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
					$pendidikan_ibu = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
					$pekerjaan_ibu = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
					$penghasilan_ibu = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
					$nik_ibu = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
					$nama_wali = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
					$tahun_lahir_wali = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
					$pendidikan_wali = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
					$pekerjaan_wali = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
					$penghasilan_wali = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
					$nik_wali = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
					$rombel = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
					$no_peserta_ujian_nasional = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
					$no_seri_ijazah = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
					$penerima_kip = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
					$nomor_kip = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
					$nama_kip = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
					$nomor_kks = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
					$no_registrasi_akta_lahir = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
					$bank = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
					$nomor_rekening_bank = $worksheet->getCellByColumnAndRow(51, $row)->getValue();
					$rekening_atas_nama = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
					$layak_pip = $worksheet->getCellByColumnAndRow(53, $row)->getValue();
					$alasan_layak_pip = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
					$kebutuhan_khusus = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
					$sekolah_asal = $worksheet->getCellByColumnAndRow(56, $row)->getValue();
					$anak_ke = $worksheet->getCellByColumnAndRow(57, $row)->getValue();
					$lintang = $worksheet->getCellByColumnAndRow(58, $row)->getValue();
					$bujur = $worksheet->getCellByColumnAndRow(59, $row)->getValue();
					$no_kk = $worksheet->getCellByColumnAndRow(60, $row)->getValue();
					$berat_badan = $worksheet->getCellByColumnAndRow(61, $row)->getValue();
					$tinggi_badan = $worksheet->getCellByColumnAndRow(62, $row)->getValue();
					$lingkar_kepala = $worksheet->getCellByColumnAndRow(63, $row)->getValue();
					$jml_saudara_kandung = $worksheet->getCellByColumnAndRow(64, $row)->getValue();
					$jarak_rumah_ke_sekolah = $worksheet->getCellByColumnAndRow(65, $row)->getValue();

					$data_pesertadidik[] = array(
						'nisn'    => $nisn,
						'nama'    => $nama,
						'nipd'    => $nipd,
						'jk'    => $jk,
						'tempat_lahir'    => $tempat_lahir,
						'tanggal_lahir'    => $tanggal_lahir,
						'nik'    => $nik,
						'agama'    => $agama,
						'alamat'    => $alamat,
						'rt'    => $rt,
						'rw'    => $rw,
						'dusun'    => $dusun,
						'kelurahan'    => $kelurahan,
						'kecamatan'    => $kecamatan,
						'kode_pos'    => $kode_pos,
						'jenis_tinggal'    => $jenis_tinggal,
						'alat_transportasi'    => $transportasi,
						'telepon'    => $telepon,
						'hp'    => $hp,
						'email'    => $email,
						'skhun'    => $skhun,
						'penerima_kps'    => $penerima_kps,
						'no_kps'    => $no_kps,
						'id_rombel'    => $rombel,
						'created_by'    => get_user_data('npsn')
					);

					$data_pesertadidik_detail[] = array(
						// 'id_peserta_didik'    => $nipd,
						'nisn'    => $nisn,
						'no_peserta_ujian_nasional'    => $no_peserta_ujian_nasional,
						'no_seri_ijazah'    => $no_seri_ijazah,
						'penerima_kip'    => $penerima_kip,
						'nomor_kip'    => $nomor_kip,
						'nama_di_kip'    => $nama_kip,
						'nomor_kks'    => $nomor_kks,
						'no_registrasi_akta_lahir'    => $no_registrasi_akta_lahir,
						'bank'    => $bank,
						'nomor_rekening_bank'    => $nomor_rekening_bank,
						'rekening_atas_nama'    => $rekening_atas_nama,
						'layak_pip'    => $layak_pip,
						'alasan_layak_pip'    => $alasan_layak_pip,
						'kebutuhan_khusus'    => $kebutuhan_khusus,
						'anak_ke'    => $anak_ke,
						'lintang'    => $lintang,
						'bujur'    => $bujur,
						'no_kk'    => $no_kk,
						'berat_badan'    => $berat_badan,
						'tinggi_badan'    => $tinggi_badan,
						'lingkar_kepala'    => $lingkar_kepala,
						'jml_saudara_kandung'    => $jml_saudara_kandung,
						'jarak_rumah_ke_sekolah'    => $jarak_rumah_ke_sekolah,
						'created_by'    => get_user_data('npsn')
					);

					$data_orangtua[] = array(
						// 'id_peserta_didik'    => $nipd,
						'nisn'    => $nisn,
						'nama_ayah'    => $nama_ayah,
						'tahun_lahir_ayah'    => $tahun_lahir_ayah,
						'jenjang_pendidikan_ayah'    => $pendidikan_ayah,
						'pekerjaan_ayah'    => $pekerjaan_ayah,
						'penghasilan_ayah'    => $penghasilan_ayah,
						'nik_ayah'    => $nik_ayah,
						'nama_ibu'    => $nama_ibu,
						'tahun_lahir_ibu'    => $tahun_lahir_ibu,
						'jenjang_pendidikan_ibu'    => $pendidikan_ibu,
						'pekerjaan_ibu'    => $pekerjaan_ibu,
						'penghasilan_ibu'    => $penghasilan_ibu,
						'nik_ibu'    => $nik_ibu,
						'created_by'    => get_user_data('npsn')
					);

					$data_wali[] = array(
						// 'id_peserta_didik'    => $nipd,
						'nisn'    => $nisn,
						'nama'    => $nama_wali,
						'tahun_lahir'    => $tahun_lahir_wali,
						'jenjang_pendidikan'    => $pendidikan_wali,
						'pekerjaan'    => $pekerjaan_wali,
						'penghasilan'    => $penghasilan_wali,
						'nik'    => $nik_wali,
						'created_by'    => get_user_data('npsn')
					);

					$data_rombel[] = array(
						// 'id_peserta_didik'    => $nipd,
						'nisn'    => $nisn,
						'rombel'    => $rombel,
						'tahun' => $tahun,
						'created_by'    => get_user_data('npsn')
					);
				}
			}

			if ($highestColumn === 'BN' && $cell !== null) {
				$this->load->model('Model_datatable_pd');
				$insert_pesertadidik = $this->Model_datatable_pd->insert_PesertaDidik($data_pesertadidik);
				$insert_detail_pesertadidik = $this->Model_datatable_pd->insertDetail_PesertaDidik($data_pesertadidik_detail);
				$insert_data_orangtua = $this->Model_datatable_pd->insert_Data_Orangtua($data_orangtua);
				$insert_data_wali = $this->Model_datatable_pd->insert_Data_Wali($data_wali);
				$insert_data_rombel = $this->Model_datatable_pd->insert_Data_rombel($data_rombel);

				$rowtrim = 6;
				$this->session->set_flashdata('status', '<span class="glyphicon glyphicon-ok"> </span> Data Excel Berhasil di Import ke Database. Total : ' . ($highestRow - $rowtrim) . ' Record');
				redirect($_SERVER['HTTP_REFERER']);
			} else {
				$this->session->set_flashdata('error', '<span class="glyphicon glyphicon-remove"> </span> Terjadi Kesalahan, Dokumen tidak sesuai template');
				redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			$this->session->set_flashdata('error', '<span class="glyphicon glyphicon-remove"> </span> Terjadi kesalahan, file Tidak Valid (Format file harus .xls atau .xlsx)');
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	public function import_staff()
	{
		$config['allowed_types']    = 'xls|xlsx';
		$this->load->library('upload', $config);
		$temp = explode(".", $_FILES["file"]["name"]);
		// $extension = end($temp);
		$mimes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

		$jenisPTK = $this->input->post('jenis_ptk');

		if (isset($_FILES["fileExcel"]["name"])) {


			if (in_array($_FILES["fileExcel"]["type"], $mimes)) {

				$path = $_FILES["fileExcel"]["tmp_name"];
				$object = PHPExcel_IOFactory::load($path);
				foreach ($object->getWorksheetIterator() as $worksheet) {
					$highestRow = $worksheet->getHighestRow();
					$highestColumn = $worksheet->getHighestColumn();
					$cell = $worksheet->getCellByColumnAndRow(1, 6)->getValue();

					for ($row = 6; $row <= $highestRow; $row++) {
						$nama = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
						$nuptk = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
						$jk = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
						$tempat_lahir = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
						$tanggal_lahir = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
						$nip = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
						$status_pegawai = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
						$jenis_ptk = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
						$agama = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
						$alamat = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
						$rt = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
						$rw = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
						$dusun = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
						$kelurahan = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
						$kecamatan = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
						$kodepos = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
						$telepon = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
						$hp = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
						$email = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
						$tugas_tambahan = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
						$sk_cpns = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
						$tgl_cpns = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
						$sk_pengangkatan = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
						$tmt_pengangkatan = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
						$lembaga_pengangkatan = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
						$pangkat_golongan = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
						$sumber_gaji = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
						$ibu_kandung = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
						$status_kawin = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
						$nama_suami_istri = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
						$nip_suami_istri = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
						$pekerjaan_suami_istri = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
						$tmt_pns = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
						$lisensi_kepsek = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
						$diklat_pengawas = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
						$ahli_braille = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
						$ahli_bahasa_isyarat = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
						$npwp = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
						$nama_wp = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
						$kewarganegaraan = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
						$bank = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
						$nomor_rekening_bank = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
						$rekening_atas_nama = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
						$nik = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
						$no_kk = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
						$karpeg = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
						$karis_karsu = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
						$lintang = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
						$bujur = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
						$nuks = $worksheet->getCellByColumnAndRow(51, $row)->getValue();

						if ($jenisPTK == 'Guru') {
							$jenis = 'Guru';
						} else {
							$jenis = 'Tendik';
						}

						$data_staff[] = array(
							'nama'    => $nama,
							'nuptk'    => $nuptk,
							'jk'    => $jk,
							'tempat_lahir'    => $tempat_lahir,
							'tanggal_lahir'    => $tanggal_lahir,
							'nip'    => $nip,
							'status_kepegawaian'    => $status_pegawai,
							'jenis_ptk'    => $jenis_ptk,
							'agama'    => $agama,
							'alamat_jalan'    => $alamat,
							'rt'    => $rt,
							'rw'    => $rw,
							'nama_dusun'    => $dusun,
							'kelurahan'    => $kelurahan,
							'kecamatan'    => $kecamatan,
							'kode_pos'    => $kodepos,
							'telepon'    => $telepon,
							'hp'    => $hp,
							'email'    => $email,
							'tugas_tambahan'    => $tugas_tambahan,
							'sk_cpns'    => $sk_cpns,
							'tanggal_cpns'    => $tgl_cpns,
							'sk_pengangkatan'    => $sk_pengangkatan,
							'tmt_pengangkatan'    => $tmt_pengangkatan,
							'lembaga_pengangkatan'    => $lembaga_pengangkatan,
							'pangkat_golongan'    => $pangkat_golongan,
							'sumber_gaji'    => $sumber_gaji,
							'nama_ibu_kandung'    => $ibu_kandung,
							'status_perkawinan'    => $status_kawin,
							'nama_suami_istri'    => $nama_suami_istri,
							'nip_suami_istri'    => $nip_suami_istri,
							'pekerjaan_suami_istri'    => $pekerjaan_suami_istri,
							'tmt_pns'    => $tmt_pns,
							'sudah_lisensi_kepala_sekolah'    => $lisensi_kepsek,
							'pernah_diklat_kepegawaian'    => $diklat_pengawas,
							'keahlian_braille'    => $ahli_braille,
							'keahlian_bahasa_isyarat'    => $ahli_bahasa_isyarat,
							'npwp'    => $npwp,
							'nama_wajib_pajak'    => $nama_wp,
							'kewarganegaraan'    => $kewarganegaraan,
							'bank'    => $bank,
							'nomor_rekening_bank'    => $nomor_rekening_bank,
							'rekening_atas_nama'    => $rekening_atas_nama,
							'nik'    => $nik,
							'no_kk'    => $no_kk,
							'karpeg'    => $karpeg,
							'karis_karsu'    => $karis_karsu,
							'lintang'    => $lintang,
							'bujur'    => $bujur,
							'nuks'    => $nuks,
							'jenis'    => $jenis,
							'created_by'    => get_user_data('npsn')
						);
					}
				}

				if ($highestColumn === 'AZ' && $cell !== null) {
					$rowtrim = 5;
					$this->load->model('Model_datatable_staff');
					$insert_staff = $this->Model_datatable_staff->insert_staff($data_staff);

					$this->session->set_flashdata('status', '<span class="glyphicon glyphicon-ok"> </span> Data Berhasil di Import ke Database. Total : ' . ($highestRow - $rowtrim) . ' Record');
					redirect($_SERVER['HTTP_REFERER']);
				} else {
					$this->session->set_flashdata('error', '<span class="glyphicon glyphicon-remove"> </span> Terjadi Kesalahan, Dokumen tidak sesuai template');
					redirect($_SERVER['HTTP_REFERER']);
				}
			} else {
				$this->session->set_flashdata('error', '<span class="glyphicon glyphicon-remove"> </span> Terjadi kesalahan, file Tidak Valid (Format file harus .xls atau .xlsx)');
				redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	public function import_pdkeluar()
	{
		$config['allowed_types']    = 'xls|xlsx';
		$this->load->library('upload', $config);

		// $extension = end($temp);
		$mimes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

		// $jenisPTK = $this->input->post('jenis_ptk');
		if (isset($_FILES["fileExcel"]["name"])) {
			// $temp = explode(".", $_FILES["file"]["name"]);
			if (in_array($_FILES["fileExcel"]["type"], $mimes)) {


				$path = $_FILES["fileExcel"]["tmp_name"];
				$object = PHPExcel_IOFactory::load($path);
				foreach ($object->getWorksheetIterator() as $worksheet) {
					$highestRow = $worksheet->getHighestRow();
					$highestColumn = $worksheet->getHighestColumn();
					$cell = $worksheet->getCellByColumnAndRow(3, 9)->getValue();
					// echo json_encode($cell);

					for ($row = 9; $row <= $highestRow; $row++) {
						$nisn = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
						$keluar = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
						$tanggal = $worksheet->getCellByColumnAndRow(42, $row)->getValue();

						$data_pdkeluar[] = array(
							'nisn'    => $nisn,
							'keluar_karena'    => $keluar,
							'tanggal_keluar'    => $tanggal,
							'created_by'    => get_user_data('npsn')
						);
					}
				}

				if ($highestColumn === 'AQ' && $cell !== null) {
					$rowtrim = 8;
					$this->load->model('Model_datatable_pdkeluar');
					$insert_pdkeluar = $this->Model_datatable_pdkeluar->insert_PesertaDidikKeluar($data_pdkeluar);

					$this->session->set_flashdata('status', '<span class="glyphicon glyphicon-ok"> </span> Data Berhasil di Import ke Database. Total : ' . ($highestRow - $rowtrim) . ' Record');
					redirect($_SERVER['HTTP_REFERER']);
				} else {
					$this->session->set_flashdata('error', '<span class="glyphicon glyphicon-remove"> </span> Terjadi Kesalahan, Dokumen tidak sesuai template');
					redirect($_SERVER['HTTP_REFERER']);
				}
			} else {
				$this->session->set_flashdata('error', '<span class="glyphicon glyphicon-remove"> </span> Terjadi kesalahan, file Tidak Valid (Format file harus .xls atau .xlsx)');
				redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			redirect($_SERVER['HTTP_REFERER']);
		}
	}


	public function import_pembelajaran()
	{
		$config['allowed_types']    = 'xls|xlsx';
		$this->load->library('upload', $config);
		$temp = explode(".", $_FILES["file"]["name"]);
		// $extension = end($temp);
		$mimes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

		// $jenisPTK = $this->input->post('jenis_ptk');
		if (isset($_FILES["fileExcel"]["name"])) {
			if (in_array($_FILES["fileExcel"]["type"], $mimes)) {

				$path = $_FILES["fileExcel"]["tmp_name"];
				$object = PHPExcel_IOFactory::load($path);
				foreach ($object->getWorksheetIterator() as $worksheet) {
					$highestRow = $worksheet->getHighestRow();
					$highestColumn = $worksheet->getHighestColumn();
					$cell = $worksheet->getCellByColumnAndRow(1, 9)->getValue();


					for ($row = 9; $row <= $highestRow; $row++) {
						$jenisRombel = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
						$tingkat = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
						$namaRombel = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
						$kurikulum = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
						$program = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
						$nama_ptk = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
						$nuptk = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
						$ptk_induk = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
						$kepegawaian = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
						$nama_matpel = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
						$kode_matpel = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
						$jjm = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
						$jml_siswa = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
						$tgl_sk_mengajar = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
						$sk_mengajar = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
						$status_kurikulum = $worksheet->getCellByColumnAndRow(16, $row)->getValue();

						$data_pembelajaran[] = array(
							'jenis_rombel'    => $jenisRombel,
							'tingkat'    => $tingkat,
							'nama_rombel'    => $namaRombel,
							'kurikulum'    => $kurikulum,
							'kompetensi_keahlian'    => $program,
							'nama_ptk'    => $nama_ptk,
							'nuptk'    => $nuptk,
							'ptk_induk'    => $ptk_induk,
							'kepegawaian'    => $kepegawaian,
							'nama_matpel'    => $nama_matpel,
							'kode_matpel'    => $kode_matpel,
							'jjm'    => $jjm,
							'jml_siswa'    => $jml_siswa,
							'tgl_sk_mengajar'    => $tgl_sk_mengajar,
							'sk_mengajar'    => $sk_mengajar,
							'status_di_kurikulum'    => $status_kurikulum,
							'created_by'    => get_user_data('npsn')
						);
					}
				}

				if ($highestColumn === 'Q' && $cell !== null) {
					$rowtrim = 8;
					$this->load->model('Model_datatable_pembelajaran');
					$insert_pembelajaran = $this->model_datatable_pembelajaran->insert_Matpel($data_pembelajaran);

					$this->session->set_flashdata('status', '<span class="glyphicon glyphicon-ok"> </span> Data Berhasil di Import ke Database. Total : ' . ($highestRow - $rowtrim) . ' Record');
					redirect($_SERVER['HTTP_REFERER']);
				} else {
					$this->session->set_flashdata('error', '<span class="glyphicon glyphicon-remove"> </span> Terjadi Kesalahan, Dokumen tidak sesuai template');
					redirect($_SERVER['HTTP_REFERER']);
				}
			} else {
				$this->session->set_flashdata('error', '<span class="glyphicon glyphicon-remove"> </span> Terjadi kesalahan, file Tidak Valid (Format file harus .xls atau .xlsx)');
				redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			redirect($_SERVER['HTTP_REFERER']);
		}
	}
}

/* End of file Dashboard.php */
/* Location: ./application/controllers/administrator/Dashboard.php */