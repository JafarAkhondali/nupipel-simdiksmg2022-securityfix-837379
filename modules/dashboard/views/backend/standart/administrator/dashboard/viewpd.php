<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.0.1/chart.js" integrity="sha512-zulXrCFpnkALZBNUHe4E6rTUt7IhNLDUuLTLqTyKb93z7CrEVzFdL8KfPV6VPplL8+b4MZGOdh00+d2nzGiveg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= cclang('user'); ?>
        <small><?= cclang('list_all'); ?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= cclang('home'); ?></a></li>
        <li class="active"><?= cclang('user'); ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">

        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-body text-center">
                    <h1>dashboard</h1>

                    <div class="row">
                        <div class="col-md-4">
                            <canvas id="myChart"></canvas>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
                <!--/box body -->
            </div>
            <!--/box -->

        </div>
    </div>
</section>
<!-- /.content -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<!-- Page script -->
<script>
    $(document).ready(function() {
        const ctx = document.getElementById('myChart');

        const data = {
            labels: [
                'Red',
                'Blue',
                'Yellow'
            ],
            datasets: [{
                label: 'My First Dataset',
                data: [300, 50, 100],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                hoverOffset: 4
            }]
        };

        const config = {
            type: 'pie',
            data: data,
        };

        const myChart = new Chart(ctx, config);

    }); /*end doc ready*/
</script>