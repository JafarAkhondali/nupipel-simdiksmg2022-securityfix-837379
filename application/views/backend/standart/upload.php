<style type="text/css">
    .widget-user-header {
        padding-left: 20px !important;
    }

    .vl {
        border-left: 2px solid green;
        height: 200px;
    }
</style>

<section class="content-header">

</section>


<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- AREA CHART -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>Upload Data Siswa/ Peserta Didik</b></h3>

                </div>
                <div class="box-body chart-responsive">
                    <?php if (!empty($this->session->flashdata('status')) || !empty($this->session->flashdata('error'))) {

                        if ($this->session->flashdata('error')) { ?>
                            <script>
                                toastr["error"]("Error, Terjadi kesalahan upload file")
                            </script>
                            <div class="alert alert-danger" role="alert"> <?= $this->session->flashdata('error'); ?></div>
                        <?php } else { ?>
                            <script>
                                toastr["success"]("File Excel Berhasil diupload")
                            </script>
                            <div class="alert alert-success" role="alert"> <?= $this->session->flashdata('status'); ?></div>
                    <?php }
                    } ?>

                    <div class="col-lg-3">
                        <br>
                        <center><img src="<?= BASE_ASSET; ?>/img/logo_disdik2.png" alt="" width="50%" height="50%">
                            <h4><b>SDN Banyumanik 04</b></h4>
                        </center>
                    </div>
                    <div class="col-lg-3">
                        <form action="<?= base_url('administrator/Import/import_pd'); ?>" method="post" enctype="multipart/form-data" name="form_upload_pd" id="form_upload_pd">
                            <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" id="idsekolah" name="idsekolah" value="<?= get_user_data('npsn') ?>" readonly>

                            <div class="form-group">
                                <label>Tahun ajaran</label>
                                <select style="width:230px" class="form-control select2 required" id="tahun_ajaran" name="tahun_ajaran" required>
                                    <option value="">- Pilih Tahun -</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Pilih File Excel</label>
                                <input type="file" id='fileExcel' name="fileExcel" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                            </div>
                            <div>
                                <button id='import' name='import' class='btn btn-success' type="submit">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    Import
                                </button>&nbsp;&nbsp;
                                <a href="javascript:void(0);" id='hapus' class="btn btn-danger remove-data" data-toggle="tooltip" data-placement="top" title="<?= cclang('remove_button'); ?>"><i class="fa fa-close"></i> Hapus Data</a>

                                <span class="loading loading-hide">
                                    <img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg">
                                    <i><?= 'Harap Tunggu sedang proses'; ?></i>
                                </span>
                            </div>
                            <div class="box box-warning">
                                <div class="box-header with-border">

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body" style="margin: auto;">
                                    Download template excel data siswa <a class="btn btn-block btn-social btn-download" href="<?= BASE_ASSET; ?>template/template_data_siswa.xslx" download>
                                        <i class="fa fa-dropbox"></i> Download Template</a>
                                </div>
                            </div>
                        </form>
                    </div><br>

                    <div class="col-lg-3">

                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><?= $jumlah_siswa_aktif; ?> siswa</h3>
                                <p class="txSmall"><b>Aktif</b></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person"></i>
                            </div>
                            <a href="#" class="small-box-footer">Jumlah Siswa Aktif <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3">

                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3><?= $jumlah_siswa_nonaktif; ?> siswa</h3>
                                <p><b>Non Aktif</b></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="#" class="small-box-footer">Jumlah Siswa Non Aktif <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <h3 class="box-title">Informasi</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="box-body" style="">
                                *Apabila ada perbaikan/ perubahan data (misal : alamat, rombel, dll) silakan upload ulang file excel yang sudah diperbaiki & terlebih dahulu hapus data yg telah terupload.
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="box">
                    <div class="box-body">
                        <table id="dataTablePd" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>NIPD</th>
                                    <th>Rombel</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Tempat lahir</th>
                                    <th>NIK</th>
                                    <th>Agama</th>
                                    <th>Alamat</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>NIPD</th>
                                    <th>Rombel</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Tempat lahir</th>
                                    <th>NIK</th>
                                    <th>Agama</th>
                                    <th>Alamat</th>
                                    <th></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</section>


<script>
    const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var idsekolah = $("#idsekolah").val();
    // console.log(id);

    $('#dataTablePd').DataTable({
        processing: true,
        serverSide: true,
        searchable: true,
        lengthChange: false,

        ajax: {
            url: '<?= site_url() ?>administrator/import/dataTablePd',
            type: 'POST',
            data: {
                id: idsekolah,
                [csrfName]: csrfHash
            },
        },
    });

    $.ajax({
        url: "<?= site_url() ?>administrator/import/cekDatatablePd",
        type: "POST",
        data: {
            id: idsekolah,
            [csrfName]: csrfHash,
        }
    }).done(function(res) {
        var jumlahData = res;
        if (jumlahData == 0) {
            //alert();
            $('.remove-data').addClass('disabled');
        } else {
            $('.remove-data').removeClass('disabled');
            document.getElementById('import').disabled = true;
            document.getElementById('fileExcel').disabled = true;
            document.getElementById('tahun_ajaran').disabled = true;


        }
        // console.log(res);
    });
</script>

<script>
    function deletePD() {
        const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
            csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
        var idsekolah = $("#idsekolah").val();
        // console.log(idsekolah);
        swal({
                title: "<?= cclang('are_you_sure'); ?>",
                text: "Proses ini akan menghapus seluruh data yang telah anda import/ upload. Silakan upload ulang data anda apabila ada perbaikan",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#D30000",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '<?= base_url('administrator/import/deleteDataPD') ?>',
                        data: {
                            id: idsekolah,
                            [csrfName]: csrfHash,
                        },
                        beforeSend: function() {
                            $('.loading').show();
                        },
                    }).success(function(res) {
                        var data = res;
                        if (data = 'del') {
                            $('.loading').hide();
                            location.reload();
                        }
                        // console.log(data);
                        // alert();
                    });
                } else {
                    location.reload();
                }
            });
    }
</script>

<script>
    $('.remove-data').click(function() {
        const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
            csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
        var idsekolah = $("#idsekolah").val();
        var jenisptk = $("#jenis_ptk").val();

        swal({
                title: "<?= cclang('are_you_sure'); ?>",
                text: "Proses ini akan menghapus seluruh data yang telah anda import/ upload. Silakan upload ulang data anda apabila ada perbaikan",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '<?= base_url('administrator/import/deleteDataPD') ?>',
                        data: {
                            id: idsekolah,
                            [csrfName]: csrfHash,
                        },
                        beforeSend: function() {
                            $('.loading').show();
                        },
                        complete: function() {
                            $('.loading').show();
                            location.reload();
                        }
                    });
                }
            });

        return false;
    });
</script>

<script>
    $("#form_upload_pd").on("submit", function() {
        $('.loading').show();
    });
</script>