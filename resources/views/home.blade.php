@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card m-3 map-container">
                    <div id="map" style="height: 500px;"></div>
                </div>
                <div class="row row-cols-1 row-cols-md-2 g-4 px-4">
                    <div class="col">
                        <div class="card chart-cd shadow-sm">
                            <div class="chart-container">
                                <canvas id="doughnutChart" width="400" height="400"></canvas>
                                <div id="doughnutHoleText"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card chart-cd shadow-sm">
                            <div class="chart-container">
                                <canvas id="coloChart"></canvas>
                            </div>
                        </div>
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
    
    document.addEventListener('DOMContentLoaded', function () {
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

                                layer.bindTooltip(tooltipContent);
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

        // Mengambil data untuk doughnut chart
        var sowData = [];
        var sowLabels = [];

        @foreach ($towerCountsBySow as $towerCountSow)
            sowData.push({{ $towerCountSow->total }});
            sowLabels.push('{{ $towerCountSow->sow2 }}');
        @endforeach

        // Membuat doughnut chart
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
                        text: 'Total Tower Leased',
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
                                        var percentage = ((dataItem / total) * 100).toFixed(2);
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
                        var centerX = (chartArea.left + chartArea.right) / 2;
                        var centerY = (chartArea.top + chartArea.bottom) / 2.5;
                        var towerCount = {{ $totalTowerCount }};

                        doughnutHoleText.style.top = centerY + 'px';
                        doughnutHoleText.style.left = centerX + 'px';
                        doughnutHoleText.innerHTML = towerCount + '<br>Site';
                        doughnutHoleText.classList.add('doughnut-hole-text');
                    }
                }
            }
        });

        // Mengambil data tower colo per area dari controller
        var coloDataByArea = {!! json_encode($coloDataByArea) !!};

        // Membuat array untuk label area dan data jumlah tower colo
        var areas = Object.keys(coloDataByArea);
        var coloCounts = Object.values(coloDataByArea);

        // Membuat bar chart
        var ctx = document.getElementById('coloChart').getContext('2d');
        var coloChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: areas,
                datasets: [{
                    label: 'Tower COLO',
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
                        text: 'Tower Collocation Based on Area',
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
                maintainAspectRatio: false
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

        .col {
            padding: 15px;
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
        
    </style>

