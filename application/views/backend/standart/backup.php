<style type="text/css">
    .widget-user-header {
        padding-left: 20px !important;
    }
</style>

<section class="content-header">
    <?= isset($breadcrumb) ? $breadcrumb : '' ?>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- AREA CHART -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>Data Sekolah Sudah Upload</b></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
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
                                toastr["success"]("File Berhasil diupload")
                            </script>
                            <div class="alert alert-info" role="alert"> <?= $this->session->flashdata('status'); ?></div>
                    <?php }
                    } ?>
                    <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" id="idsekolah" name="idsekolah" value="<?= get_user_data('npsn') ?>" readonly>

                    <div class="box">
                        <div class="box-body">
                            <table id="dataTableInfo" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NPSN</th>
                                        <th>Nama Sekolah</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>alamat</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- AREA CHART -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>Backup Data Seluruh Sekolah</b></h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
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
                                toastr["success"]("File Berhasil diupload")
                            </script>
                            <div class="alert alert-info" role="alert"> <?= $this->session->flashdata('status'); ?></div>
                    <?php }
                    } ?>
                    <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" id="idsekolah" name="idsekolah" value="<?= get_user_data('npsn') ?>" readonly>
                    <br>
                    <center>
                        <div class="input-group margin">

                            <div class="input-group">
                                <b>Backup data tahun ajaran | </b><br><br>
                                <select style="width:130px" class="form-control select2 required" id='tahun' name='tahun' required>
                                    <option value=''>Pilih Tahun</option>
                                    <option value='2022'>2022</option>
                                    <option value='2023'>2023</option>
                                    <option value='2024'>2024</option>
                                    <option value='2025'>2025</option>
                                    <option value='2026'>2026</option>
                                </select>&nbsp;
                                <a href="javascript:void(0);" id='backup' class="btn btn-info backup-data" data-toggle="tooltip" data-placement="top" title="<?= 'Proses ini akan membackup seluruh data dari data yg telah teruplod ke database backup.' ?>"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Backup Data</a>
                                <br><br><span class="loading-backup loading-hide">
                                    <img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg">
                                    <i><?= 'Harap Tunggu sedang proses backup data'; ?></i>
                                </span>
                            </div>
                        </div>
                    </center>
                    <hr>

                    <div class="box">
                        <div class="box-body">
                            <table id="dataTableBackup" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Tanggal Backup</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($detail_backup as $data) : ?>

                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $data->kode ?></td>
                                            <td><?= $data->tahun ?></td>
                                            <td><?= $data->created_at ?></td>
                                            <td><a href="<?= base_url('administrator/import/deletebackup?id=') . $data->id . '&tahun=' . $data->tahun ?>" id="delete-backup" class="btn btn-danger delete-backup" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-close" aria-hidden="true"></i></a></td>
                                        </tr>
                                    <?php endforeach; ?>
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
    // $('#dataTableInfo').DataTable({
    //     searchable: true
    // });

    $('#dataTableBackup').DataTable({
        searchable: true
    });

    $('#dataTableInfo').DataTable({
        processing: true,
        serverSide: true,
        // searchable: true,
        // lengthChange: false,

        ajax: {
            url: '<?= site_url() ?>administrator/import/datatableinfo',
            type: 'POST',
            data: {
                [csrfName]: csrfHash
            },
        },
    });

    $('.backup-data').click(function() {
        const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
            csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
        var tahun = $("#tahun").val();

        if (tahun == '') {
            swal({
                title: "Terjadi Kesalahan",
                text: "Tahun wajib dipilih",
                type: "error"
            });
            return false;
        } else {
            swal({
                    title: "<?= cclang('are_you_sure'); ?>",
                    text: "Pastikan pilih tahun ajaran sesuai dengan data yang telah terupload sebelumnya, proses ini akan memindahkan seluruh data existing ke database backup",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#00E06D",
                    confirmButtonText: "Ya, Backup Data",
                    cancelButtonText: "Batal",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            dataType: 'json',
                            type: 'POST',
                            url: '<?= base_url('administrator/import/backupData') ?>',
                            data: {
                                tahun: tahun,
                                [csrfName]: csrfHash,
                            },
                            beforeSend: function() {
                                $('.loading-backup').show();
                            },
                            complete: function() {
                                $('.loading-backup').hide();
                                location.reload();
                            }
                        });
                    }
                });

            return false;
        }
    });

    $('.delete-backup').click(function() {
        const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
            csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

        var url = $(this).attr('href');


        swal({
                title: "<?= cclang('are_you_sure'); ?>",
                text: "Pastikan anda telang menghapus seluruh data exixting sebelum menghapus data backup",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#FF0000",
                confirmButtonText: "Hapus Backup",
                cancelButtonText: "Batal",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    document.location.href = url;
                }
            });

        return false;
    });
</script>