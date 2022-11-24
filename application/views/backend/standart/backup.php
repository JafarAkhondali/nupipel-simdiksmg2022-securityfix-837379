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
                    <h3 class="box-title"><b>Backup data <?= $sekolah[0]['nama_satuan_pendidikan'] ?> </b></h3>

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
                                <select style="width:130px" class="form-control select2 required">
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
                                        <th>NPSN</th>
                                        <th>ID</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Tanggal Backup</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($detail_backup as $data) : ?>

                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $data->npsn ?></td>
                                            <td><?= $data->kode ?></td>
                                            <td><?= $data->tahun ?></td>
                                            <td><?= $data->created_at ?></td>
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
    $('#dataTableBackup').DataTable({
        searchable: true
    });

    $('.backup-data').click(function() {
        const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
            csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
        var idsekolah = $("#idsekolah").val();
        // var jenisptk = $("#jenis_ptk").val();

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
                            id: idsekolah,
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
    });
</script>