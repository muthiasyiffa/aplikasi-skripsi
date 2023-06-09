@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Sales Order') }}
                    {{ $tahun }}    
                </div>
                <div class="card m-4 map-container">
                    <div id="map" style="height: 500px;"></div>
                </div>
                <div class="row row-cols-1 row-cols-md-2 g-4 px-4 mt-1">
                    <div class="col">
                        <div class="card chart-cd shadow-sm">
                            <button type="button" id="info-icon1" class="btn btn-light" data-bs-toggle="popover" data-bs-placement="bottom">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <div class="chart-container">
                                <div id="doughnutHoleText"></div>
                                <canvas id="doughnutChart" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card chart-cd shadow-sm">
                            <button type="button" id="info-icon" class="btn btn-light" data-bs-toggle="popover" data-bs-placement="bottom">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <div class="chart-container">
                                <canvas id="coloChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3 g-4 px-4 mt-1">
                    <div class="col">
                        <div class="card chart-cd shadow-sm">
                            <div class="chart-container">
                                <canvas id="doughnutChart1" width="400" height="400"></canvas>
                                <div id="doughnutHoleText1"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card chart-cd shadow-sm">
                            <div class="chart-container">
                                <canvas id="doughnutChart2" width="400" height="400"></canvas>
                                <div id="doughnutHoleText2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card chart-cd shadow-sm">
                            <div class="chart-container">
                                <canvas id="doughnutChart3" width="400" height="400"></canvas>
                                <div id="doughnutHoleText3"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4 px-4 mt-1">
                    <div class="col-md-5">
                        <div class="card chart-cd shadow-sm">
                            <div class="chart-container">
                                <canvas id="doughnutChart4" width="400" height="400"></canvas>
                                <div id="doughnutHoleText4"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card chart-cd shadow-sm">
                            <div class="chart-container py-4">
                            <h4 class="progress-title">Progress Site RFI</h4>
                                <div class="progress-table-container pt-2">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="header-content">
                                                <th>Status Site</th>
                                                <th class="contain-header">Percentage</th>
                                                <th class="contain-header">Total</th>
                                                <th class="contain-header">Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($towerCountsByStatusRFI as $towerCount)
                                            <tr class="content">
                                                <td>
                                                    <div class="category">
                                                        <div class="progress-bar" style="--progress: {{ ($towerCount->total / $towerCountsByStatusRFI->sum('total')) * 100 }}%;">
                                                            <span>{{ $towerCount->status_xl }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="contain-header vermid">{{ intVal(($towerCount->total / $towerCountsByStatusRFI->sum('total')) * 100) }}%</td>
                                                <td class="contain-header vermid">{{ $towerCount->total }}</td>
                                                <td class="vermid">
                                                    @if($towerCount->status_xl == 'RFI-NY BAUF')
                                                        1. Site sudah RFI (status cons) <br> 2. Dokumen BAUF belum muncul di LMS (sistem XL) sehingga tidak bisa dilanjutkan proses create BAPS/BAK
                                                    @elseif($towerCount->status_xl == 'RFI-BAUF DONE')
                                                        1. Site sudah RFI (status cons) <br> 2. BAPS/BAK created, menunggu approval dari PMO XL dan LM XL.
                                                    @elseif($towerCount->status_xl == 'BAK Completed')
                                                        1. BAPS/BAK approved by PMO XL. <br> 2. BAPS/BAK approved by LM XL.
                                                    @elseif($towerCount->status_xl == 'Invoice Done')
                                                        1. Invoice done
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card m-4">
                    <div class="card-header">Aging Order RFI - BAK</div>
                    <div class="filters">
                        <label for="status-filter">Filter Status:</label>
                        <select id="status-filter">
                            <option value="">All</option>
                            @foreach($towerCountsByStatusXL as $towerCountByStatusXL)
                                <option value="{{ $towerCountByStatusXL->status_xl }}">{{ $towerCountByStatusXL->status_xl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="card m-4">
                        <table id="salesTable" class="table">
                            <thead>
                                <tr>
                                    <th class="contain-header">PID</th>
                                    <th class="contain-header">Site ID</th>
                                    <th class="contain-header">Site Name</th>
                                    <th class="contain-header">Status Site</th>
                                    <th class="contain-header">Aging</th>
                                    <th class="contain-header">Category Aging</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesOrders as $salesOrder)
                                    <tr data-status="{{ $salesOrder->status_xl }}">
                                        <td class="contain-header">{{ $salesOrder->pid }}</td>
                                        <td class="contain-header">{{ $salesOrder->site_id_tenant }}</td>
                                        <td class="contain-header">{{ $salesOrder->site_name }}</td>
                                        <td class="contain-header">{{ $salesOrder->status_xl }}</td>
                                        <td class="contain-header">{{ $salesOrder->aging_rfi_to_bak }} days</td>
                                        <td class="contain-header">
                                            @if($salesOrder->aging_rfi_to_bak <= 14)
                                                <button class="btn btn-success aging-button">Low Attention</button>
                                            @elseif($salesOrder->aging_rfi_to_bak >= 15 && $salesOrder->aging_rfi_to_bak <= 24)
                                                <button class="btn btn-warning aging-button">Attention</button>
                                            @elseif($salesOrder->aging_rfi_to_bak >= 25)
                                                <button class="btn btn-danger aging-button">Need More Attention</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css" />

<script>

    var statusFilter = document.getElementById('status-filter');
    var agingTable = document.getElementById('aging-table');
    var tableRows = agingTable.getElementsByTagName('tr');

    statusFilter.addEventListener('change', function() {
        var selectedStatus = this.value;

        for (var i = 1; i < tableRows.length; i++) {
        var row = tableRows[i];
        var rowStatus = row.getAttribute('data-status');

        if (selectedStatus === '' || rowStatus === selectedStatus) {
            row.style.display = '';
            if (i % 2 === 0) {
            row.classList.add('table-striped');
            } else {
            row.classList.remove('table-striped');
            }
        } else {
            row.style.display = 'none';
        }
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
//data maps sebaran tower
        var map = L.map('map', {
            minZoom: 5,
            maxZoom: 19,
            maxBounds: [
                [-11.5, 94.0], // Batas barat laut Indonesia
                [6.5, 141.0]   // Batas tenggara Indonesia
            ],
            zoomControl: false // Menonaktifkan default zoom control
        }).setView([-2.548926, 118.014863], 5);

        // Membuat custom zoom control
        var zoomControl = L.control.zoom({
            position: 'bottomleft' // Menentukan posisi di pojok kiri bawah
        }).addTo(map);

        // Mengubah posisi tombol zoom horizontal
        var zoomControlContainer = zoomControl.getContainer();
        zoomControlContainer.classList.add('custom-zoom-control');

        // Load GeoJSON data for each pulau
        Promise.all([
            @foreach ($towerCountsByPulau as $towerCount)
                fetch('/js/geojson/province/{{ strtolower(str_replace(' ', '', $towerCount->pulau)) }}.geojson')
                    .then(response => response.json())
                    .then(data => {
                        var layer = L.geoJSON(data, {
                            style: function (feature) {
                                return {
                                    fillColor: 'green',
                                    fillOpacity: 0.3,
                                    weight: 1
                                };
                            },
                            onEachFeature: function (feature, layer) {
                                layer.on({
                                    mouseover: function () {
                                        this.setStyle({
                                            fillColor: '#0275d8',
                                            fillOpacity: 0.9
                                        });
                                    },
                                    mouseout: function () {
                                        this.setStyle({
                                            fillColor: 'green',
                                            fillOpacity: 0.3
                                        });
                                    }
                                });

                                // Menambahkan tooltip dengan informasi jumlah tower per pulau dan pulau-sow2
                                var totalTowerPulau = {{ $towerCount->total }};
                                var totalTower = {{ $totalTowerCount }};

                                // Menambahkan persentase ke dalam tooltip
                                var percentage = (totalTowerPulau / totalTower * 100).toFixed(2);
                                var tooltipContent = '<span class="tooltip-title">{{ $towerCount->pulau }}</span> <br>';
                                tooltipContent += 'Percentage: ' + percentage + '% <br>';

                                tooltipContent += 'Total : {{ $towerCount->total }} site<br>';
                                @foreach ($towerCountsByPulauSow as $towerCountSow)
                                    if ('{{ $towerCount->pulau }}' === '{{ $towerCountSow->pulau }}') {
                                        tooltipContent += '{{ $towerCountSow->sow2 }}: {{ $towerCountSow->total }} site<br>';
                                    }
                                @endforeach

                                layer.bindTooltip(tooltipContent, { permanent: true, direction: 'top' }).openTooltip();
                            }
                        }).addTo(map);
                    }),
            @endforeach
        ]).then(() => {
            // Semua layer GeoJSON telah dimuat
        });

        var customStyles = `
            .custom-zoom-control {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                bottom: 20px;
            }
        `;
        var styleElement = document.createElement('style');
        styleElement.innerHTML = customStyles;
        document.head.appendChild(styleElement);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        map.fitBounds([
            [-11.5, 94.0], // Batas barat laut Indonesia
            [6.5, 141.0]   // Batas tenggara Indonesia
        ]);

        // Membuat elemen div di dalam peta
        var infoDiv = L.DomUtil.create('div', 'custom-info');
        infoDiv.innerHTML = 'Distribution of <br> {{ $totalTowerCount }} Site';

        // Menambahkan elemen div ke dalam peta dan memposisikannya di pojok kanan atas
        infoDiv.style.position = 'absolute';
        infoDiv.style.backgroundColor = 'white';
        infoDiv.style.padding = '10px';
        infoDiv.style.borderRadius = '5px';
        infoDiv.style.zIndex = '1000';

        map.getContainer().appendChild(infoDiv);
//chart SOW
        // Mengambil data untuk doughnut chart SOW
        var sowData = [];
        var sowLabels = [];

        @foreach ($towerCountsBySow as $towerCountSow)
            sowData.push({{ $towerCountSow->total }});
            sowLabels.push('{{ $towerCountSow->sow2 }}');
        @endforeach

        // Membuat doughnut chart SOW
        var doughnutChart = new Chart(document.getElementById('doughnutChart'), {
            type: 'doughnut',
            data: {
                labels: sowLabels,
                datasets: [{
                    data: sowData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',  // Warna untuk data pertama
                        'rgba(54, 162, 235, 0.7)', // Warna untuk data kedua
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'SOW Sales Order {{ $tahun }}',
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            generateLabels: function(chart) {
                                var data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map(function(label, i) {
                                        var meta = chart.getDatasetMeta(0);
                                        var dataItem = data.datasets[0].data[i];
                                        var total = meta.total;
                                        var percentage = ((dataItem / total) * 100).toFixed(0);
                                        return {
                                            text: label + ' (' + percentage + '%)',
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: isNaN(dataItem) || meta.data[i].hidden,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    }
                },
                animation: {
                    onComplete: function() {
                        var doughnutHoleText = document.getElementById('doughnutHoleText');
                        var chartArea = doughnutChart.chartArea;
                        var centerX = (chartArea.left + chartArea.right) / 1.95;
                        var centerY = (chartArea.top + chartArea.bottom) / 2.5;
                        var towerCount = {{ $totalTowerCount }};

                        doughnutHoleText.style.top = centerY + 'px';
                        doughnutHoleText.style.left = centerX + 'px';
                        doughnutHoleText.innerHTML = towerCount + '<br>Site';
                        doughnutHoleText.classList.add('doughnut-hole-text');
                    },
                    onProgress: function(animation) {
                        var chartInstance = animation.chart;
                        var ctx = chartInstance.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.font.size, 'bold', Chart.defaults.font.family);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';

                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.getDatasetMeta(i);
                            meta.data.forEach(function(element, index) {
                                var data = dataset.data[index];
                                var startAngle = element.startAngle;
                                var endAngle = element.endAngle;
                                var angle = startAngle + (endAngle - startAngle) / 2;

                                var radius = element.outerRadius * 0.75; // Sesuaikan jarak teks dari pusat

                                var x = element.x + Math.cos(angle) * radius;
                                var y = element.y + Math.sin(angle) * radius;

                                ctx.fillText(data, x, y);
                            });
                        });
                    }
                }
            }
        });

        const popover1 = new bootstrap.Popover(document.getElementById('info-icon1'), {
            container: 'body',
            html: true,
            content: function () {
                // Mengambil data tower berdasarkan area dan sow2
                var coloData = @json($towerCountsByAreaSow);
                var b2sData = @json($towerCountsByAreaB2S);

                var content = '';
                if (coloData.length > 0) {
                    content += '<b>COLO Based on Area</b> <br>'

                    // Menambahkan informasi area dan total ke konten popover
                    coloData.forEach(function (data) {
                        var area = data.area;
                        var total = data.total;

                        content += area ;
                        content += ' : ' + total + ' site <br>';
                    });
                }

                if (b2sData.length > 0) {
                    content += '<br>'
                    content += '<b>B2S Based on Area</b> <br>'

                    b2sData.forEach(function (data) {
                        var area = data.area;
                        var total = data.total;

                        content += area ;
                        content += ' : ' + total + ' site <br>';
                    });
                }

                return content;
            }
        });
//chart kat_tower
        // Mengambil data tower colo per area dari controller
        var coloDataByKatTower = {!! json_encode($coloDataByKatTower) !!};

        // Membuat array untuk label area dan data jumlah tower colo
        var labels = Object.keys(coloDataByKatTower);
        var coloCounts = Object.values(coloDataByKatTower);

        // Membuat bar chart
        var ctx = document.getElementById('coloChart').getContext('2d');
        var coloChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: coloCounts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Category Site Collocation',
                        padding: {
                            top: 10,
                            bottom: 30
                        },
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        beginAtZero: true,
                        precision: 0,
                        stepSize: 1
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    onProgress: function(animation) {
                        var chartInstance = animation.chart;
                        var ctx = chartInstance.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.font.size, 'bold', Chart.defaults.font.family);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.getDatasetMeta(i);
                            meta.data.forEach(function(bar, index) {
                                var data = dataset.data[index];
                                var xPos = bar.x;
                                var yPos = bar.y - 5;
                                ctx.fillText(data, xPos, yPos);

                                var total = coloCounts.reduce((total, count) => total + count, 0);

                                var percent = ((data / total) * 100).toFixed(0) + '%';
                                var percentXPos = bar.x;
                                var percentYPos = bar.y + 15;
                                ctx.fillText(percent, percentXPos, percentYPos);
                            });
                        });
                    }
                }
            }
        });

        const popover = new bootstrap.Popover(document.getElementById('info-icon'), {
            container: 'body',
            html: true,
            content: function () {
                // Mengambil data tower COLO
                var coloData = @json($coloDataByKatTower);

                // Menghitung total tower COLO
                var coloTotal = Object.values(coloData).reduce((total, count) => total + count, 0);

                // Mengkategorikan tower sebagai Tower Akuisisi atau Tower B2S Mitratel
                var akuisisiTowers = ['Titan', 'Edelweiss 1A', 'Edelweiss 1B', 'Edelweiss 2', 'Edelweiss 3', 'UNO', 'Akuisisi'];

                var akuisisiData = {};

                // Memisahkan data tower berdasarkan kategori
                Object.entries(coloData).forEach(([katTower, count]) => {
                    if (akuisisiTowers.includes(katTower)) {
                        akuisisiData[katTower] = count;
                    }
                });

                // Menghitung total tower akuisisi
                var akuisisiTotal = Object.values(akuisisiData).reduce((total, count) => total + count, 0);

                // Membuat konten popover
                var content = 'Dari total tower colo sebanyak <b>' + coloTotal + ' site</b>, terdapat kategori tower Bangun Mandiri atau biasa disebut B2S dan kategori tower Telkom Group. Kedua kategori tersebut <b>bukan</b> merupakan site akuisisi.';
                content += '<br><br>'
                content += '<b>Total Site Akuisisi : '+ akuisisiTotal + ' site</b> <br> <br>';

                //Notes Site akuisisi
                content += '<b>Titan :</b> Site akuisisi dari <b>ISAT</b><br> <b>Edelweiss 1A, 1B, 2, dan 3 :</b> Site akuisisi dari <b>TSEL</b><br>';
                content += '<b>UNO :</b> Site akuisisi dari <b>Telkom</b><br> <b>Akuisisi :</b> Site akuisisi dari <b>perusahaan kecil</b><br>';

                return content;
            }
        });
//chart tenant_existing
        // Mengambil data untuk doughnut chart tenant_existing
        var coloDataByTenantExisting = {!! json_encode($coloDataByTenantExisting) !!};

        // Membuat array untuk label tenant dan data jumlah tower colo
        var labelsTenant = Object.keys(coloDataByTenantExisting);
        var coloCountsbyTenant = Object.values(coloDataByTenantExisting);
        var totalColo = Object.values(coloDataByTenantExisting).reduce((total, count) => total + count, 0);

        // Membuat doughnut chart tenant_existing
        var doughnutChart1 = new Chart(document.getElementById('doughnutChart1'), {
            type: 'doughnut',
            data: {
                labels: labelsTenant,
                datasets: [{
                    data: coloCountsbyTenant,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 205, 86)',
                        'rgb(201, 203, 207)',
                        'rgb(54, 162, 235)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Collocation Based Site Condition',
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            generateLabels: function(chart) {
                                var data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map(function(label, i) {
                                        var meta = chart.getDatasetMeta(0);
                                        var dataItem = data.datasets[0].data[i];
                                        var total = meta.total;
                                        var percentage = ((dataItem / total) * 100).toFixed(0);
                                        return {
                                            text: label + ' (' + percentage + '%)',
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: isNaN(dataItem) || meta.data[i].hidden,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    }
                },
                animation: {
                    onComplete: function() {
                        var doughnutHoleText1 = document.getElementById('doughnutHoleText1');
                        var chartArea1 = doughnutChart1.chartArea;
                        var centerX1 = (chartArea1.left + chartArea1.right) / 1.95;
                        var centerY1 = (chartArea1.top + chartArea1.bottom) / 2.5;

                        doughnutHoleText1.style.top = centerY1 + 'px';
                        doughnutHoleText1.style.left = centerX1 + 'px';
                        doughnutHoleText1.innerHTML = totalColo + '<br>Site';
                        doughnutHoleText1.classList.add('doughnut-hole-text');
                    },
                    onProgress: function(animation) {
                        var chartInstance = animation.chart;
                        var ctx = chartInstance.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.font.size, 'bold', Chart.defaults.font.family);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';

                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.getDatasetMeta(i);
                            meta.data.forEach(function(element, index) {
                                var data = dataset.data[index];
                                var startAngle = element.startAngle;
                                var endAngle = element.endAngle;
                                var angle = startAngle + (endAngle - startAngle) / 2;

                                var radius = element.outerRadius * 0.75; // Sesuaikan jarak teks dari pusat

                                var x = element.x + Math.cos(angle) * radius;
                                var y = element.y + Math.sin(angle) * radius;

                                ctx.fillText(data, x, y);
                            });
                        });
                    }
                }
            }
        });
//chart area
        // Mengambil data untuk doughnut chart area
        var coloDataByArea = {!! json_encode($coloDataByArea) !!};

        // Membuat array untuk label tenant dan data jumlah tower colo
        var labelsArea = Object.keys(coloDataByArea);
        var coloCountsbyArea = Object.values(coloDataByArea);
        var totalColoArea = Object.values(coloDataByArea).reduce((total, count) => total + count, 0);

        // Membuat doughnut chart area
        var doughnutChart2 = new Chart(document.getElementById('doughnutChart2'), {
            type: 'doughnut',
            data: {
                labels: labelsArea,
                datasets: [{
                    data: coloCountsbyArea,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 205, 86)',
                        'rgb(54, 162, 235)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Collocation Based Site Acquisition',
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            generateLabels: function(chart) {
                                var data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map(function(label, i) {
                                        var meta = chart.getDatasetMeta(0);
                                        var dataItem = data.datasets[0].data[i];
                                        var total = meta.total;
                                        var percentage = ((dataItem / total) * 100).toFixed(0);
                                        return {
                                            text: label + ' (' + percentage + '%)',
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: isNaN(dataItem) || meta.data[i].hidden,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    }
                },
                animation: {
                    onComplete: function() {
                        var doughnutHoleText2 = document.getElementById('doughnutHoleText2');
                        var chartArea2 = doughnutChart2.chartArea;
                        var centerX2 = (chartArea2.left + chartArea2.right) / 1.95;
                        var centerY2 = (chartArea2.top + chartArea2.bottom) / 2.5;

                        doughnutHoleText2.style.top = centerY2 + 'px';
                        doughnutHoleText2.style.left = centerX2 + 'px';
                        doughnutHoleText2.innerHTML = totalColoArea + '<br>Site';
                        doughnutHoleText2.classList.add('doughnut-hole-text');
                    },
                    onProgress: function(animation) {
                        var chartInstance = animation.chart;
                        var ctx = chartInstance.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.font.size, 'bold', Chart.defaults.font.family);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';

                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.getDatasetMeta(i);
                            meta.data.forEach(function(element, index) {
                                var data = dataset.data[index];
                                var startAngle = element.startAngle;
                                var endAngle = element.endAngle;
                                var angle = startAngle + (endAngle - startAngle) / 2;

                                var radius = element.outerRadius * 0.75; // Sesuaikan jarak teks dari pusat

                                var x = element.x + Math.cos(angle) * radius;
                                var y = element.y + Math.sin(angle) * radius;

                                ctx.fillText(data, x, y);
                            });
                        });
                    }
                }
            }
        });
//chart demografi
        // Mengambil data untuk doughnut chart demografi
        var demographyData = [];
        var demographyLabels = [];

        @foreach ($towerCountsByDemography as $towerCountDemography)
            demographyData.push({{ $towerCountDemography->total }});
            demographyLabels.push('{{ $towerCountDemography->demografi }}');
        @endforeach

        // Membuat doughnut chart demografi
        var doughnutChart3 = new Chart(document.getElementById('doughnutChart3'), {
            type: 'doughnut',
            data: {
                labels: demographyLabels,
                datasets: [{
                    data: demographyData,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 205, 86)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Order Based on Demography',
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            generateLabels: function(chart) {
                                var data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map(function(label, i) {
                                        var meta = chart.getDatasetMeta(0);
                                        var dataItem = data.datasets[0].data[i];
                                        var total = meta.total;
                                        var percentage = ((dataItem / total) * 100).toFixed(0);
                                        return {
                                            text: label + ' (' + percentage + '%)',
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: isNaN(dataItem) || meta.data[i].hidden,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    }
                },
                animation: {
                    onComplete: function() {
                        var doughnutHoleText3 = document.getElementById('doughnutHoleText3');
                        var chartArea3 = doughnutChart3.chartArea;
                        var centerX3 = (chartArea3.left + chartArea3.right) / 1.95;
                        var centerY3 = (chartArea3.top + chartArea3.bottom) / 2.5;
                        var towerCount = {{ $totalTowerCount }};

                        doughnutHoleText3.style.top = centerY3 + 'px';
                        doughnutHoleText3.style.left = centerX3 + 'px';
                        doughnutHoleText3.innerHTML = towerCount + '<br>Site';
                        doughnutHoleText3.classList.add('doughnut-hole-text');
                    },
                    onProgress: function(animation) {
                        var chartInstance = animation.chart;
                        var ctx = chartInstance.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.font.size, 'bold', Chart.defaults.font.family);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';

                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.getDatasetMeta(i);
                            meta.data.forEach(function(element, index) {
                                var data = dataset.data[index];
                                var startAngle = element.startAngle;
                                var endAngle = element.endAngle;
                                var angle = startAngle + (endAngle - startAngle) / 2;

                                var radius = element.outerRadius * 0.75; // Sesuaikan jarak teks dari pusat

                                var x = element.x + Math.cos(angle) * radius;
                                var y = element.y + Math.sin(angle) * radius;

                                ctx.fillText(data, x, y);
                            });
                        });
                    }
                }
            }
        });
//chart status site
        // Mengambil data untuk doughnut chart status site
        var statusData = [];
        var statusLabels = [];

        @foreach ($towerCountsByStatus as $towerCountStatus)
            statusData.push({{ $towerCountStatus->total }});
            statusLabels.push('{{ $towerCountStatus->final_status_site }}');
        @endforeach

        // Membuat doughnut chart status site
        var doughnutChart4 = new Chart(document.getElementById('doughnutChart4'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 205, 86)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'General Status Site Order XL {{ $tahun }}',
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            generateLabels: function(chart) {
                                var data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map(function(label, i) {
                                        var meta = chart.getDatasetMeta(0);
                                        var dataItem = data.datasets[0].data[i];
                                        var total = meta.total;
                                        var percentage = ((dataItem / total) * 100).toFixed(0);
                                        return {
                                            text: label + ' (' + percentage + '%)',
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: isNaN(dataItem) || meta.data[i].hidden,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    }
                },
                animation: {
                    onComplete: function() {
                        var doughnutHoleText4 = document.getElementById('doughnutHoleText4');
                        var chartArea4 = doughnutChart4.chartArea;
                        var centerX4 = (chartArea4.left + chartArea4.right) / 1.95;
                        var centerY4 = (chartArea4.top + chartArea4.bottom) / 2.5;
                        var towerCount = {{ $totalTowerCount }};

                        doughnutHoleText4.style.top = centerY4 + 'px';
                        doughnutHoleText4.style.left = centerX4 + 'px';
                        doughnutHoleText4.innerHTML = towerCount + '<br>Site';
                        doughnutHoleText4.classList.add('doughnut-hole-text');
                    },
                    onProgress: function(animation) {
                        var chartInstance = animation.chart;
                        var ctx = chartInstance.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.font.size, 'bold', Chart.defaults.font.family);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';

                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.getDatasetMeta(i);
                            meta.data.forEach(function(element, index) {
                                var data = dataset.data[index];
                                var startAngle = element.startAngle;
                                var endAngle = element.endAngle;
                                var angle = startAngle + (endAngle - startAngle) / 2;

                                var radius = element.outerRadius * 0.75; // Sesuaikan jarak teks dari pusat

                                var x = element.x + Math.cos(angle) * radius;
                                var y = element.y + Math.sin(angle) * radius;

                                ctx.fillText(data, x, y);
                            });
                        });
                    }
                }
            }
        });

    });
</script>

@endsection

@push('styles')
    <style>
        .tooltip-title {
            text-align: center;
            font-weight: bold;
        }

        .card-header {
            font-weight: bold;
            font-size: 17px;
        }

        .custom-info {
            position: absolute;
            right: 0px;
            background-color: white;
            border-radius: 3px;
            z-index: 1000;
            font-weight: bold;
            font-size: 15px;
            text-align: center;
        }

        .chart-container {
            width: 100%;
            height: 400px;
            padding: 30px;
            padding-top: 10px;
        }


        .tower-count-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }

        .doughnut-hole-text {
            position: absolute;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
        }

        #info-icon {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        #info-icon1 {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .popover-body {
            font-size: 14px; /* Ganti dengan ukuran font yang diinginkan */
        }

        .status-contain {
            margin-top: 15px;
            margin bottom: 15px;
        }

        .custom-popover {
            --bs-popover-max-width: 100px;
        }

        .contain-header {
            text-align: center;
        }

        .aging-button {
            pointer-events: none;
        }

        .category {
        display: flex;
        align-items: center;
        }

        .progress-bar {
        position: relative;
        height: 50px;
        background-color: #ccc;
        }

        .progress-bar::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: var(--progress);
        background-color: #ccc;
        transition: width 0.3s ease-in-out;
        }

        .category span {
        position: relative;
        z-index: 1;
        padding-left: 10px;
        }

        .progress-title {
            font-weight: bold;
            font-size: 17px;
            margin-bottom: 10px;
        }

        .vermid {
            vertical-align: middle;
        }

        .content {
            font-size: 14px;
        }

        .header-content {
            font-size: 15px;
        }
    </style>
