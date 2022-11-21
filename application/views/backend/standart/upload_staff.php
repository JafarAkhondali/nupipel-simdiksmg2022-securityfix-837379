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
                    <h3 class="box-title"><b>Upload Data Guru Pengajar (PNS / Non PNS)</b></h3>

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
                    <div class="col-lg-6">
                        <form action="<?= base_url('administrator/Import/import_staff'); ?>" method="post" enctype="multipart/form-data" name="form_upload_staff" id="form_upload_staff">
                            <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" id="idsekolah" name="idsekolah" value="<?= get_user_data('npsn') ?>" readonly>
                            <input type="hidden" id="jenis_ptk" name="jenis_ptk" value="Guru" readonly>

                            <!-- <div class="form-group">
                            <label>Tahun ajaran</label>
                            <select class="form-control select2 required" id="tahun_ajaran" name="tahun_ajaran">
                                <option value="">- Pilih Tahun -</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                            </select>
                        </div> -->
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
                            </div><br>
                            <div class="box-body" style="margin: auto;">
                                Download template excel data guru <a class="btn btn-block btn-social btn-download" href="<?= BASE_ASSET; ?>template/template_data_guru.xslx" download>
                                    <i class="fa fa-dropbox"></i> Download Template</a>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-3">

                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><?= $jumlah_guru_aktif ?> Guru</h3>
                                <p class="txSmall"><b>Aktif</b></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person"></i>
                            </div>
                            <a href="#" class="small-box-footer">Jumlah Guru Aktif <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3">

                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3><?= $jumlah_guru_nonaktif ?> Guru</h3>
                                <p><b>Non Aktif</b></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="#" class="small-box-footer">Jumlah Guru Non Aktif <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="box">
                    <div class="box-body">
                        <table id="dataTableStaff" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NUPTK</th>
                                    <th>NIP</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>No</td>
                                    <td>Nama</td>
                                    <td>NUPTK</td>
                                    <td>NIP</td>
                                    <td>Status</td>
                                    <th></th>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var idsekolah = $("#idsekolah").val();
    var jenisptk = $("#jenis_ptk").val();
    // console.log(jenisptk);

    $('#dataTableStaff').DataTable({
        processing: true,
        serverSide: true,
        // searchable: true,
        ajax: {
            url: '<?= site_url() ?>administrator/import/dataTableStaff',
            type: 'POST',
            data: {
                id: idsekolah,
                ptk: jenisptk,
                [csrfName]: csrfHash,
            },
        }
    });

    $.ajax({
        url: "<?= site_url() ?>administrator/import/cekDatatableStaff",
        type: "POST",
        data: {
            id: idsekolah,
            ptk: jenisptk,
            [csrfName]: csrfHash,
        }
    }).done(function(res) {
        var jumlahData = res;
        // console.log(jumlahData);
        if (jumlahData == 0) {
            //alert();
            $('.remove-data').addClass('disabled');
            document.getElementById('import').disabled = false;
            document.getElementById('fileExcel').disabled = false;
        } else {
            $('.remove-data').removeClass('disabled');
            document.getElementById('import').disabled = true;
            document.getElementById('fileExcel').disabled = true;
        }
        // console.log(res);
    });
</script>

<!-- <script>
    function deleteStaff() {
        const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
            csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
        var idsekolah = $("#idsekolah").val();
        var jenisptk = $("#jenis_ptk").val();
        // console.log(jenisptk);
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
                        url: '<?= base_url('administrator/import/deleteDataStaff') ?>',
                        data: {
                            id: idsekolah,
                            ptk: jenisptk,
                            [csrfName]: csrfHash,
                        },
                        beforeSend: function() {
                            $('.loading').show();
                        },
                    }).done(function() {
                        $('.loading').hide();
                        location.reload();

                    });
                } else {
                    location.reload();
                }
            });
    }
</script> -->

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
                confirmButtonText: "<?= cclang('yes_delete_it'); ?>",
                cancelButtonText: "<?= cclang('no_cancel_plx'); ?>",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '<?= base_url('administrator/import/deleteDataStaff') ?>',
                        data: {
                            id: idsekolah,
                            ptk: jenisptk,
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
    $("#form_upload_staff").on("submit", function() {
        $('.loading').show();
    });
</script>