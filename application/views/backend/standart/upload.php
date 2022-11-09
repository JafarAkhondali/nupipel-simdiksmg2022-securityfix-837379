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
                    <h3 class="box-title">Upload Data Peserta Didik</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                    <?php if (!empty($this->session->flashdata('status')) || !empty($this->session->flashdata('error'))) {

                        if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-danger" role="alert"><?= $this->session->flashdata('error'); ?></div>
                        <?php } else { ?>
                            <div class="alert alert-info" role="alert"><?= $this->session->flashdata('status'); ?></div>
                    <?php }
                    } ?>
                    <form action="<?= base_url('Import/import_excel'); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <div class="form-group">
                            <label>Tahun ajaran</label>
                            <select class="form-control select2 required" id="tahun_ajaran" name="tahun_ajaran">
                                <option>- Pilih Tahun Ajaran -</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pilih File Excel</label>
                            <input type="file" name="fileExcel" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                        </div>
                        <div>
                            <button class='btn btn-success' type="submit">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</section>