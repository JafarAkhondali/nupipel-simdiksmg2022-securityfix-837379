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
                    <h3 class="box-title"><b>Upload Data Tenaga Pendidik (Non Guru)</b></h3>

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
                    <form action="<?= base_url('administrator/Import/import_staff'); ?>" method="post" enctype="multipart/form-data" name="form_upload_staff" id="form_upload_staff">
                        <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" id="idsekolah" name="idsekolah" value="<?= $this->session->userdata('username') ?>" readonly>
                        <input type="hidden" id="jenis_ptk" name="jenis_ptk" value="Tendik" readonly>

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
                            <input type="file" name="fileExcel" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                        </div>
                        <div>
                            <button id='import' name='import' class='btn btn-success' type="submit">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                Import
                            </button>&nbsp;&nbsp;
                            <button id='hapus' name='hapus' class='btn btn-danger' onclick="deleteStaff()">
                                <span class="glyphicon glyphicon-alert" aria-hidden="true"></span>&nbsp;
                                Hapus Data
                            </button>
                            &nbsp;&nbsp;
                            <span class="loading loading-hide">
                                <img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg">
                                <i><?= 'Harap Tunggu sedang proses'; ?></i>
                            </span>
                        </div>
                    </form>
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
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>No</td>
                                    <td>Nama</td>
                                    <td>NUPTK</td>
                                    <td>NIP</td>
                                    <td>Status</td>
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
    console.log(jenisptk);

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
        console.log(jumlahData);
        if (jumlahData == 0) {
            //alert();
            document.getElementById('hapus').disabled = true;
        } else {
            document.getElementById('hapus').disabled = false;
        }
        // console.log(res);
    });
</script>

<script>
    function deleteStaff() {
        const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
            csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
        var idsekolah = $("#idsekolah").val();
        var jenisptk = $("#jenis_ptk").val();
        console.log(jenisptk);
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
</script>


<script>
    $("#form_upload_staff").on("submit", function() {
        $('.loading').show();
    });
</script>