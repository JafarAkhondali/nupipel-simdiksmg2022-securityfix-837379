<?= get_header(); ?>

<?= get_navigation(); ?>


<style>
    .parallax {
        /* The image used */
        background-image: url("<?= theme_asset() ?>img/front/19366.jpg");

        /* Set a specific height */
        /* min-height: 500px; */

        /* Create the parallax scrolling effect */
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }


    .transparent {
        background-color: rgba(255, 255, 255, 0.8);
    }

    .transparent-dark {
        background-color: rgba(1, 1, 1, 0.8);
        color: #fff;
    }

    #map {
        height: 600px;
    }
</style>


<nav class="navbar navbar-dark bg-primary">
    <marquee direction="right">
        <div class="container-fluid">
            <span class="navbar-text">
                Selamat Datang di Website Data Pokok Pendidikan Dinas Pendidikan Kota Semarang.
            </span>
        </div>
    </marquee>
</nav>

<div class="parallax">
    <div class="row transparent">
        <div class="col-md-8">
            <div id="map"></div>
        </div>
        <div class="col-md-4 ">
            <div class="box box-warning">
                <div class="box-body text-center">
                    <h2 class="mt-3">Satuan Pendidikan Tiap Kecamatan</h2>
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="parallax">
    <div class="row transparent-dark p-5">
        <div class="col-md-4">
            <h4 class="text-center">Satuan Pendidikan</h4>
            <canvas id="donat_satpen"></canvas>
        </div>
        <div class="col-md-4">
            <h4 class="text-center">Pendidik & Tenaga Kependidikan</h4>
            <canvas id="donat_ptk"></canvas>
        </div>
        <div class="col-md-4">
            <h4 class="text-center">Peserta Didik</h4>
            <canvas id="donat_pd"></canvas>
        </div>
    </div>
</div>


<script>
    const addressPoints = <?= $lat; ?>;

    const corner1 = L.latLng(-7.285336, 110.102841),
        corner2 = L.latLng(-6.775622, 110.662372),
        bounds = L.latLngBounds(corner1, corner2);

    let tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 15,
            minZoom: 11,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ'
        }),
        latlng = L.latLng(-7.010411, 110.399771);

    let map = L.map('map', {
        center: latlng,
        maxBounds: bounds,
        zoom: 12,
        layers: [tiles]
    });

    $.getJSON("<?= BASE_ASSET; ?>/geojson/Wilayah-Administrasi-Kecamatan-Kota_Semarang.geojson", function(data) {
        L.geoJSON(data, {
            style: function(feature) {
                return {
                    opacity: 0.3,
                    color: 'red',
                    fillOpacity: 0.1
                }
            }
        }).addTo(map);
    });


    let markers = L.markerClusterGroup();

    for (let i = 0; i < addressPoints.length; i++) {
        let a = addressPoints[i];
        let title = a[2];
        let marker = L.marker(new L.LatLng(a[0], a[1]), {
            title: title
        });
        marker.bindPopup(title);
        markers.addLayer(marker);
    }

    map.addLayer(markers);
</script>

<!-- Page script -->
<script>
    function loadChartSatpen() {
        const ctx = document.getElementById('donat_satpen');
        const labels = ['Red', 'Orange', 'Yellow', 'Green', 'Blue'];
        const values = [10, 11, 15, 9, 18];

        const data = {
            labels: labels,
            datasets: [{
                label: 'Dataset 1',
                data: values,
                backgroundColor: [
                    '#FF5765',
                    '#15B5B0',
                    '#FFDB15',
                    '#F9BDC0',
                    '#8A6FDF',
                    '#FBE698',
                    '#A8E10C',
                    '#6DECE0'
                ],
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            // This more specific font property overrides the global property
                            font: {
                                size: 14,
                            },
                            color: "#fff"
                        }
                    },
                }
            },
        };

        const myChart = new Chart(ctx, config);
    }

    function loadChartPtk() {
        const ctx = document.getElementById('donat_ptk');


        const labels = ['Red', 'Orange', 'Yellow', 'Green', 'Blue'];
        const values = [10, 11, 15, 9, 18];

        const data = {
            labels: labels,
            datasets: [{
                label: 'Dataset 1',
                data: values,
                backgroundColor: [
                    '#FF5765',
                    '#15B5B0',
                    '#FFDB15',
                    '#F9BDC0',
                    '#8A6FDF',
                    '#FBE698',
                    '#A8E10C',
                    '#6DECE0'
                ],
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            // This more specific font property overrides the global property
                            font: {
                                size: 14,
                            },
                            color: "#fff"
                        }
                    },
                }
            },
        };

        const myChart = new Chart(ctx, config);
    }

    function loadChartPd() {
        const ctx = document.getElementById('donat_pd');


        const labels = ['Red', 'Orange', 'Yellow', 'Green', 'Blue'];
        const values = [10, 11, 15, 9, 18];

        const data = {
            labels: labels,
            datasets: [{
                label: 'Dataset 1',
                data: values,
                backgroundColor: [
                    '#FF5765',
                    '#15B5B0',
                    '#FFDB15',
                    '#F9BDC0',
                    '#8A6FDF',
                    '#FBE698',
                    '#A8E10C',
                    '#6DECE0'
                ],
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            // This more specific font property overrides the global property
                            font: {
                                size: 14,
                            },
                            color: "#fff"
                        }
                    },
                }
            },
        };

        const myChart = new Chart(ctx, config);
    }

    // READY FUNCTION 
    $(document).ready(function() {
        loadChartSatpen();
        loadChartPtk();
        loadChartPd();


        const ctx = document.getElementById('myChart');

        const labels = <?= $labels; ?>;
        const values = <?= $values; ?>;

        const data = {
            labels: labels,
            datasets: [{
                label: 'Jumlah Sekolah',
                data: values,
                backgroundColor: [
                    '#FF5765',
                    '#15B5B0',
                    '#FFDB15',
                    '#F9BDC0',
                    '#8A6FDF',
                    '#FBE698',
                    '#A8E10C',
                    '#6DECE0'
                ],
                hoverOffset: 4
            }]
        };

        const config = {
            type: 'polarArea',
            data: data,
            options: {
                responsive: true,
                scales: {
                    r: {
                        pointLabels: {
                            display: true,
                            centerPointLabels: true,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        // position: 'top',
                        display: false,
                    },
                }
            },
        };

        const myChart = new Chart(ctx, config);

    }); /*end doc ready*/
</script>

<?= get_footer(); ?>