<!DOCTYPE html>
<html>

<head>
	<title>Import Data Peserta Didik</title>
</head>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<body>

	<section class="content-header">
		<h1>
			<?= cclang('Data Peserta Didik') ?><small> Upload Daftar Peserta Didik</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"> Upload Daftar Peserta Didik</li>
		</ol>
	</section>

	<div class="container">
		<div class="row" style="margin-top: 30px;">
			<div class="col-md-4 col-md-offset-3">
				<h3>Import Data</h3>
				<?php if (!empty($this->session->flashdata('status'))) { ?>
					<div class="alert alert-info" role="alert"><?= $this->session->flashdata('status'); ?></div>
				<?php } ?>
				<form action="<?= base_url('Import/import_excel'); ?>" method="post" enctype="multipart/form-data">
					<input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
					<div class="form-group">
						<label>Tahun ajaran</label>
						<select class="form-control select2 required" id="tahun_ajaran" name="tahun_ajaran" required>
							<option>- Pilih Tahun Ajaran -</option>
							<option value="2022">2022</option>
							<option value="2023">2023</option>
							<option value="2024">2024</option>
							<option value="2025">2025</option>
						</select>
					</div>
					<div class="form-group">
						<label>Pilih File Excel</label>
						<input type="file" name="fileExcel">
					</div>
					<div>
						<button class='btn btn-success' type="submit">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							Import
						</button>
					</div>
				</form>
			</div>
			<!-- <div class="col-md-6 col-md-offset-3" style="margin-top: 50px;">
				<h3>Daftar Data</h3>
				<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama</th>
								<th>Jurusan</th>
								<th>Angkatan</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							foreach ($list_data as $row) {
							?>
								<tr>
									<td><?= $no++; ?></td>
									<td><?= $row['nama'] ?></td>
									<td><?= $row['jurusan'] ?></td>
									<td><?= $row['angkatan'] ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div> -->
		</div>
	</div>
</body>

</html>