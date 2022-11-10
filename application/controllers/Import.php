<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Import extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('excel', 'session'));
    }

    public function index()
    {
        $this->load->model('ImportModel');
        $data = array(
            'list_data'    => $this->ImportModel->getData()
        );
        $this->load->view('import_excel.php', $data);
    }

    public function import_excel()
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
                        'id_rombel'    => $rombel
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
                        'jarak_rumah_ke_sekolah'    => $jarak_rumah_ke_sekolah
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
                        'nik_ibu'    => $nik_ibu
                    );

                    $data_wali[] = array(
                        // 'id_peserta_didik'    => $nipd,
                        'nisn'    => $nisn,
                        'nama'    => $nama_wali,
                        'tahun_lahir'    => $tahun_lahir_wali,
                        'jenjang_pendidikan'    => $pendidikan_wali,
                        'pekerjaan'    => $pekerjaan_wali,
                        'penghasilan'    => $penghasilan_wali,
                        'nik'    => $nik_wali
                    );

                    $data_rombel[] = array(
                        // 'id_peserta_didik'    => $nipd,
                        'nisn'    => $nisn,
                        'rombel'    => $rombel,
                        'tahun' => $tahun
                    );
                }
            }

            if ($highestColumn == 'BN') {
                $this->load->model('ImportModel');
                $insert_pesertadidik = $this->ImportModel->insert_PesertaDidik($data_pesertadidik);
                $insert_detail_pesertadidik = $this->ImportModel->insertDetail_PesertaDidik($data_pesertadidik_detail);
                $insert_data_orangtua = $this->ImportModel->insert_Data_Orangtua($data_orangtua);
                $insert_data_wali = $this->ImportModel->insert_Data_Wali($data_wali);
                $insert_data_rombel = $this->ImportModel->insert_Data_rombel($data_rombel);

                $this->session->set_flashdata('status', '<span class="glyphicon glyphicon-ok"> </span> Data Berhasil di Import ke Database');
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
}
