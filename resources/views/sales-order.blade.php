@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">{{ __('Sales Order') }} {{ $tahun }} </div>
                    <div class="col-md-6 p-4">
                        <div class="input-group">
                            <textarea class="form-control" placeholder="Search" id="search-input"></textarea>
                            <div class="input-group-append mx-2">
                                <button class="btn btn-outline-primary" type="button" id="search-button">Search</button>
                                <button class="btn btn-primary" id="export-button">Export to Excel</button>
                            </div>
                        </div>
                    </div>
                <div class="table-container">
                    <div id="data-table" class="table table-striped px-3"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Overview Sales Order') }}
                    {{ $tahun }}    
                </div>
                <div class="card m-4 map-container">
                    <div id="map" style="height: 500px;"></div>
                </div>
                <div class="row row-cols-1 row-cols-md-2 g-4 px-4 mt-1">
                    <div class="col-md-5">
                        <div class="card chart-cd shadow-sm pb-4">
                            <div class="chart-container">
                                <div id="doughnutHoleText"></div>
                                <canvas id="doughnutChart" width="400" height="400"></canvas>
                            </div>
                            <div class="table-container mb-2">
                                <div class="card ms-4 me-2 card-tab">
                                    <div class="card-header sub">B2S Based on Area</div>
                                    <table class="table table-bordered px-2">
                                        <thead>
                                            <tr class="header-content">
                                                <th class="contain-header">Area</th>
                                                <th class="contain-header">Jumlah site</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($towerCountsByAreaB2S as $towerCount)
                                            <tr class="content">
                                                <td class="contain-header vermid">{{ $towerCount->area }} </td>
                                                <td class="contain-header vermid">{{ $towerCount->total }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card ms-2 me-4 card-tab">
                                    <div class="card-header sub">COLO Based on Area</div>
                                    <table class="table table-bordered px-2">
                                        <thead>
                                            <tr class="header-content">
                                                <th class="contain-header">Area</th>
                                                <th class="contain-header">Jumlah site</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($towerCountsByAreaSow as $towerCount)
                                            <tr class="content">
                                                <td class="contain-header vermid">{{ $towerCount->area }} </td>
                                                <td class="contain-header vermid">{{ $towerCount->total }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card chart-cd shadow-sm">
                            <button type="button" id="info-icon" class="btn btn-light" data-bs-toggle="popover" data-bs-placement="bottom">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <div class="chart-container">
                                <canvas id="coloChart"></canvas>
                            </div>
                            <div class="card mx-3 mb-3">
                                <div class="card-body">
                                <h5 class="progress-title">Key Insight</h5>
                                <p class="card-text">Dari total tower colo sebanyak <b>{{ $coloTotal }} Site.</b> terdapat kategori tower Bangun Mandiri atau biasa disebut B2S dan kategori tower Telkom Group. Kedua kategori tersebut <b>bukan</b> merupakan site akuisisi.</p>
                                <p class="card-text"><b>Total Site Akuisisi : {{ $akuisisiTotal }} Site </b></p>
                                <p class="card-text"><b>Titan :</b> Site akuisisi dari <b>ISAT</b><br> <b>Edelweiss 1A, 1B, 2, dan 3 :</b> Site akuisisi dari <b>TSEL</b><br><b>UNO :</b> Site akuisisi dari <b>Telkom</b><br> <b>Akuisisi :</b> Site akuisisi dari <b>perusahaan kecil</b><br></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3 g-4 px-4 mt-1">
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
                                <canvas id="tenantChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card chart-cd shadow-sm">
                            <div class="chart-container pb-6">
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
                    <div class="card-header">Aging Order SPK to WO</div>
                    <div class= "filter">
                        <label for="filterCategory">Filter Data Aging</label>
                        <select id="filterCategorySPKWO">
                            <option value="all">All Categories</option>
                            <option value="Low Attention">Low Attention</option>
                            <option value="Attention">Attention</option>
                            <option value="Need More Attention">Need More Attention</option>
                        </select>
                        <select id="filterStatusSPKWO">
                            <option value="all">All Status</option>
                            @foreach($towerCountsByStatusXL as $towerCountByStatusXL)
                                @if($towerCountByStatusXL->status_xl !== "DROP")
                                    <option value="{{ $towerCountByStatusXL->status_xl }}">{{ $towerCountByStatusXL->status_xl }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="card m-4">
                        <div class="table-container">
                            <table id="agingSPKTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="contain-header">No.</th>
                                        <th class="contain-header">PID</th>
                                        <th class="contain-header">Site ID</th>
                                        <th class="contain-header">Site Name</th>
                                        <th class="contain-header">Status Site</th>
                                        <th class="contain-header">Aging</th>
                                        <th class="contain-header">Category Aging</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                    @endphp
                                    @foreach($salesOrders as $salesOrder)
                                        @if($salesOrder->status_xl !== 'DROP')
                                            <tr>
                                                <td class="contain-header vermid">{{ $counter++ }}</td>
                                                <td class="contain-header vermid">{{ $salesOrder->pid }}</td>
                                                <td class="contain-header vermid">{{ $salesOrder->site_id_tenant }}</td>
                                                <td class="contain-header vermid">{{ $salesOrder->site_name }}</td>
                                                <td class="contain-header vermid">{{ $salesOrder->status_xl }}</td>
                                                <td class="contain-header vermid">
                                                    @if ($salesOrder->aging_spk_to_wo === 'Not yet SPK' || $salesOrder->aging_spk_to_wo === 'Not yet WO')
                                                        {{ $salesOrder->aging_spk_to_wo }}
                                                    @else
                                                        {{ $salesOrder->aging_spk_to_wo }} days
                                                    @endif
                                                </td>
                                                <td class="contain-header vermid">
                                                    @if($salesOrder->aging_spk_to_wo <= 4)
                                                        <button class="btn btn-success aging-button">Low Attention</button>
                                                    @elseif($salesOrder->aging_spk_to_wo >= 5 && $salesOrder->aging_spk_to_wo <= 7)
                                                        <button class="btn btn-warning aging-button">Attention</button>
                                                    @elseif($salesOrder->aging_spk_to_wo > 7)
                                                        <button class="btn btn-danger aging-button">Need More Attention</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="legend-container">
                        <label>Legend:</label>
                        <div class="legend-item">
                            <button class="btn btn-success aging-button btn-sm">Low Attention</button>
                            <span>0 - 4 days</span>
                        </div>
                        <div class="legend-item">
                            <button class="btn btn-warning aging-button btn-sm">Attention</button>
                            <span>5 - 7 days</span>
                        </div>
                        <div class="legend-item">
                            <button class="btn btn-danger aging-button btn-sm">Need More Attention</button>
                            <span> > 7 days / Not yet SPK / Not yet WO</span>
                        </div>
                    </div>
                </div>
                
                <div class="card m-4">
                    <div class="card-header">Aging Order WO to RFI</div>
                    <div class= "filter">
                        <label for="filterCategory">Filter Data Aging</label>
                        <select id="filterCategoryWORFI">
                            <option value="all">All Categories</option>
                            <option value="Low Attention">Low Attention</option>
                            <option value="Attention">Attention</option>
                            <option value="Need More Attention">Need More Attention</option>
                        </select>
                        <select id="filterStatusWORFI">
                            <option value="all">All Status</option>
                            @foreach($towerCountsByStatusXL as $towerCountByStatusXL)
                                @if($towerCountByStatusXL->status_xl !== "DROP")
                                    <option value="{{ $towerCountByStatusXL->status_xl }}">{{ $towerCountByStatusXL->status_xl }}</option>
                                @endif
                            @endforeach
                        </select>
                        <select id="filterSOWWORFI">
                            <option value="all">All SOW</option>
                            <option value="B2S">B2S</option>
                            <option value="COLO">COLO</option>
                        </select>
                    </div>
                    <div class="card m-4">
                        <div class="table-container">
                            <table id="agingWOTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="contain-header">No.</th>
                                        <th class="contain-header">PID</th>
                                        <th class="contain-header">Site ID</th>
                                        <th class="contain-header">Site Name</th>
                                        <th class="contain-header">SOW2</th>
                                        <th class="contain-header">Status Site</th>
                                        <th class="contain-header">Aging</th>
                                        <th class="contain-header">Category Aging</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                    @endphp
                                    @foreach($salesOrders as $salesOrder)
                                        @if($salesOrder->status_xl !== 'DROP')
                                            <tr>
                                                <td class="contain-header vermid">{{ $counter++ }}</td>
                                                <td class="contain-header vermid">{{ $salesOrder->pid }}</td>
                                                <td class="contain-header vermid">{{ $salesOrder->site_id_tenant }}</td>
                                                <td class="contain-header vermid">{{ $salesOrder->site_name }}</td>
                                                <td class="contain-header vermid">{{ $salesOrder->sow2 }}</td>
                                                <td class="contain-header vermid">{{ $salesOrder->status_xl }}</td>
                                                <td class="contain-header vermid">
                                                    @if ($salesOrder->aging_wo_to_rfi === 'Not yet WO' || $salesOrder->aging_wo_to_rfi === 'Not yet RFI')
                                                        {{ $salesOrder->aging_wo_to_rfi }}
                                                    @else
                                                        {{ $salesOrder->aging_wo_to_rfi }} days
                                                    @endif
                                                </td>
                                                <td class="contain-header vermid">
                                                    @if($salesOrder->sow2 === 'B2S')
                                                        @if($salesOrder->aging_wo_to_rfi <= 60)
                                                            <button class="btn btn-success aging-button">Low Attention</button>
                                                        @elseif($salesOrder->aging_wo_to_rfi >= 61 && $salesOrder->aging_wo_to_rfi <= 85)
                                                            <button class="btn btn-warning aging-button">Attention</button>
                                                        @elseif($salesOrder->aging_wo_to_rfi > 85)
                                                            <button class="btn btn-danger aging-button">Need More Attention</button>
                                                        @endif
                                                    @endif
                                                    @if($salesOrder->sow2 === 'COLO')
                                                        @if($salesOrder->aging_wo_to_rfi <= 20)
                                                            <button class="btn btn-success aging-button">Low Attention</button>
                                                        @elseif($salesOrder->aging_wo_to_rfi >= 21 && $salesOrder->aging_wo_to_rfi <= 40)
                                                            <button class="btn btn-warning aging-button">Attention</button>
                                                        @elseif($salesOrder->aging_wo_to_rfi > 40)
                                                            <button class="btn btn-danger aging-button">Need More Attention</button>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="legend-container">
                        <label>Legend B2S :</label>
                        <div class="legend-item">
                            <button class="btn btn-success aging-button btn-sm">Low Attention</button>
                            <span>0 - 60 days</span>
                        </div>
                        <div class="legend-item">
                            <button class="btn btn-warning aging-button btn-sm">Attention</button>
                            <span>61 - 85 days</span>
                        </div>
                        <div class="legend-item">
                            <button class="btn btn-danger aging-button btn-sm">Need More Attention</button>
                            <span> > 85 days / Not yet WO / Not yet RFI</span>
                        </div>
                    </div>
                    <div class="legend-container">
                        <label>Legend COLO :</label>
                        <div class="legend-item">
                            <button class="btn btn-success aging-button btn-sm">Low Attention</button>
                            <span>0 - 20 days</span>
                        </div>
                        <div class="legend-item">
                            <button class="btn btn-warning aging-button btn-sm">Attention</button>
                            <span>21 - 40 days</span>
                        </div>
                        <div class="legend-item">
                            <button class="btn btn-danger aging-button btn-sm">Need More Attention</button>
                            <span> > 40 days / Not yet WO / Not yet RFI</span>
                        </div>
                    </div>
                </div>

                <div class="card m-4">
                    <div class="card-header">Aging Order RFI - BAK</div>
                    <div class= "filter">
                        <label for="filterCategory">Filter Data Aging</label>
                        <select id="filterCategoryRFIBAK">
                            <option value="all">All Categories</option>
                            <option value="Low Attention">Low Attention</option>
                            <option value="Attention">Attention</option>
                            <option value="Need More Attention">Need More Attention</option>
                        </select>
                        <select id="filterStatusRFIBAK">
                            <option value="all">All Status</option>
                            <option value="RFI-NY BAUF">RFI-NY BAUF</option>
                            <option value="RFI-BAUF DONE">RFI-BAUF DONE</option>
                        </select>
                    </div>
                    <div class="card m-4">
                        <div class="table-container">
                            <table id="agingRFITable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="contain-header">No.</th>
                                        <th class="contain-header">PID</th>
                                        <th class="contain-header">Site ID</th>
                                        <th class="contain-header">Site Name</th>
                                        <th class="contain-header">Status Site</th>
                                        <th class="contain-header">Aging</th>
                                        <th class="contain-header">Category Aging</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                    @endphp
                                    @foreach($salesOrders as $salesOrder)
                                        @if($salesOrder->status_xl == "RFI-NY BAUF" || $salesOrder->status_xl == "RFI-BAUF DONE")
                                            <tr>
                                                <td class="contain-header vermid">{{ $counter++ }}</td>
                                                <td class="contain-header vermid">{{ $salesOrder->pid }}</td>
                                                <td class="contain-header vermid">{{ $salesOrder->site_id_tenant }}</td>
                                                <td class="contain-header vermid">{{ $salesOrder->site_name }}</td>
                                                <td class="contain-header vermid">{{ $salesOrder->status_xl }}</td>
                                                <td class="contain-header vermid">
                                                    @if ($salesOrder->aging_rfi_to_bak === 'Not yet RFI')
                                                        {{ $salesOrder->aging_rfi_to_bak }}
                                                    @else
                                                        {{ $salesOrder->aging_rfi_to_bak }} days
                                                    @endif
                                                </td>
                                                <td class="contain-header vermid">
                                                    @if($salesOrder->aging_rfi_to_bak <= 14)
                                                        <button class="btn btn-success aging-button">Low Attention</button>
                                                    @elseif($salesOrder->aging_rfi_to_bak >= 15 && $salesOrder->aging_rfi_to_bak <= 25)
                                                        <button class="btn btn-warning aging-button">Attention</button>
                                                    @elseif($salesOrder->aging_rfi_to_bak > 25)
                                                        <button class="btn btn-danger aging-button">Need More Attention</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="legend-container">
                        <label>Legend:</label>
                        <div class="legend-item">
                            <button class="btn btn-success aging-button btn-sm">Low Attention</button>
                            <span>0 - 14 days</span>
                        </div>
                        <div class="legend-item">
                            <button class="btn btn-warning aging-button btn-sm">Attention</button>
                            <span>15 - 25 days</span>
                        </div>
                        <div class="legend-item">
                            <button class="btn btn-danger aging-button btn-sm">Need More Attention</button>
                            <span> > 25 days / Not yet RFI</span>
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
        var labelsTenant = Object.keys(coloDataByTenantExisting).map(function(label) {
            if (label === '') {
                return 'NPA';
            } else {
                return 'Bertenant ' + label;
            }
        });
        var coloCountsbyTenant = Object.values(coloDataByTenantExisting);

        // Membuat bar chart
        var ctx = document.getElementById('tenantChart').getContext('2d');
        var tenantChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsTenant,
                datasets: [{
                    data: coloCountsbyTenant,
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
                        text: 'Collocation cut off {{ $tahun - 1 }} Based Site Condition',
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

                                var total = coloCountsbyTenant.reduce((total, count) => total + count, 0);

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
                        text: 'Collocation cut off {{ $tahun - 1 }} Based Site Acquisition',
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

//tabel aging SPK-WO
    $(document).ready(function() {
        function updateNumbering() {
            $("#agingSPKTable tbody tr:visible").each(function(index) {
                $(this).find(".contain-header:first").text(index + 1);
            });
        }

        function applyFilterAndSort() {
            var selectedCategory = $("#filterCategorySPKWO").val();
            var selectedStatus = $("#filterStatusSPKWO").val();
            $("#agingSPKTable tbody tr").hide();

            $("#agingSPKTable tbody tr").each(function() {
                var category = $(this).find(".contain-header button").text();
                var status = $(this).find(".contain-header:nth-child(5)").text();
                var aging = $(this).find(".contain-header:nth-child(6)").text().trim();

                var showRow = (selectedCategory === "all" || category === selectedCategory) &&
                    (selectedStatus === "all" || status === selectedStatus);

                if (showRow) {
                    $(this).show();
                }
            });

            var rows = $("#agingSPKTable tbody tr:visible").get();
            rows.sort(function(a, b) {
                var aValue = $(a).find(".contain-header:nth-child(6)").text().trim();
                var bValue = $(b).find(".contain-header:nth-child(6)").text().trim();

                if (aValue === "Not yet SPK" || aValue === "Not yet WO") {
                    return -1; // Pindahkan a ke atas
                } else if (bValue === "Not yet SPK" || bValue === "Not yet WO") {
                    return 1; // Pindahkan b ke atas
                } else {
                    var aAging = parseInt(aValue.replace(' days', ''));
                    var bAging = parseInt(bValue.replace(' days', ''));
                    return aAging - bAging;
                }
            });

            $.each(rows, function(index, row) {
                $("#agingSPKTable tbody").append(row);
            });

            updateNumbering();
        }

        $("#filterCategorySPKWO, #filterStatusSPKWO").on("change", function() {
            applyFilterAndSort();
        });

        applyFilterAndSort();
    });

//tabel aging WO-RFI
    $(document).ready(function() {
        function updateNumbering() {
            $("#agingWOTable tbody tr:visible").each(function(index) {
                $(this).find(".contain-header:first").text(index + 1);
            });
        }

        function applyFilterAndSort() {
            var selectedCategory = $("#filterCategoryWORFI").val();
            var selectedStatus = $("#filterStatusWORFI").val();
            var selectedSOW = $("#filterSOWWORFI").val();
            $("#agingWOTable tbody tr").hide();

            $("#agingWOTable tbody tr").each(function() {
                var category = $(this).find(".contain-header button").text();
                var sow = $(this).find(".contain-header:nth-child(5)").text().trim();
                var status = $(this).find(".contain-header:nth-child(6)").text();
                var aging = $(this).find(".contain-header:nth-child(7)").text().trim();

                var showRow = (selectedCategory === "all" || category === selectedCategory) &&
                    (selectedStatus === "all" || status === selectedStatus) &&
                    (selectedSOW === "all" || sow === selectedSOW);

                if (showRow) {
                    $(this).show();
                }
            });

            var rows = $("#agingWOTable tbody tr:visible").get();
            rows.sort(function(a, b) {
                var aValue = $(a).find(".contain-header:nth-child(7)").text().trim();
                var bValue = $(b).find(".contain-header:nth-child(7)").text().trim();

                if (aValue === "Not yet WO" || aValue === "Not yet RFI") {
                    return -1; // Pindahkan a ke atas
                } else if (bValue === "Not yet WO" || bValue === "Not yet RFI") {
                    return 1; // Pindahkan b ke atas
                } else {
                    var aAging = parseInt(aValue.replace(' days', ''));
                    var bAging = parseInt(bValue.replace(' days', ''));
                    return aAging - bAging;
                }
            });

            $.each(rows, function(index, row) {
                $("#agingWOTable tbody").append(row);
            });

            updateNumbering();
        }

        $("#filterCategoryWORFI, #filterStatusWORFI, #filterSOWWORFI").on("change", function() {
            applyFilterAndSort();
        });

        applyFilterAndSort();
    });

//tabel aging RFI-BAK
    $(document).ready(function() {
        function updateNumbering() {
            $("#agingRFITable tbody tr:visible").each(function(index) {
                $(this).find(".contain-header:first").text(index + 1);
            });
        }

        function applyFilterAndSort() {
            var selectedCategory = $("#filterCategoryRFIBAK").val();
            var selectedStatus = $("#filterStatusRFIBAK").val();
            $("#agingRFITable tbody tr").hide();

            $("#agingRFITable tbody tr").each(function() {
                var category = $(this).find(".contain-header button").text();
                var status = $(this).find(".contain-header:nth-child(5)").text();
                var aging = $(this).find(".contain-header:nth-child(6)").text().trim();

                var showRow = (selectedCategory === "all" || category === selectedCategory) &&
                    (selectedStatus === "all" || status === selectedStatus);

                if (showRow) {
                    $(this).show();
                }
            });

            var rows = $("#agingRFITable tbody tr:visible").get();
            rows.sort(function(a, b) {
                var aValue = $(a).find(".contain-header:nth-child(6)").text().trim();
                var bValue = $(b).find(".contain-header:nth-child(6)").text().trim();

                if (aValue === "Not yet RFI") {
                    return -1; // Pindahkan a ke atas
                } else if (bValue === "Not yet RFI") {
                    return 1; // Pindahkan b ke atas
                } else {
                    var aAging = parseInt(aValue.replace(' days', ''));
                    var bAging = parseInt(bValue.replace(' days', ''));
                    return aAging - bAging;
                }
            });

            $.each(rows, function(index, row) {
                $("#agingRFITable tbody").append(row);
            });

            updateNumbering();
        }

        $("#filterCategoryRFIBAK, #filterStatusRFIBAK").on("change", function() {
            applyFilterAndSort();
        });

        applyFilterAndSort();
    });
//search & export
    $(document).ready(function() {
        // Fungsi untuk melakukan pencarian
        function search() {
            var tahun = '{{ $tahun }}'; // Ambil nilai tahun dari PHP

            var keywords = $('#search-input').val().split('\n'); // Memisahkan berdasarkan baris baru

            // Mengirim permintaan AJAX ke URL pencarian dengan kata kunci dan tahun sebagai parameter
            $.ajax({
                url: '/sales-order/' + tahun + '/search',
                type: 'GET',
                data: { keywords: keywords }, // Menggunakan 'keywords' sebagai parameter
                success: function(response) {
                    // Mengganti konten tabel dengan hasil pencarian
                    $('#data-table').html(response);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        // Meng-handle klik tombol pencarian
        $('#search-button').click(function() {
            search();
        });

        // Meng-handle aksi ketika tombol Enter ditekan pada input pencarian
        $('#search-input').keypress(function(event) {
            if (event.which === 13) { // 13 adalah kode tombol Enter
                event.preventDefault();
                search();
            }
        });

        // Fungsi untuk melakukan ekspor ke Excel
        function exportToExcel() {
            var tahun = '{{ $tahun }}'; // Ambil nilai tahun dari PHP

            // Mengarahkan pengguna ke URL ekspor ke Excel dengan tahun sebagai parameter
            window.location.href = '/sales-order/' + tahun + '/export';
        }

        // Meng-handle klik tombol ekspor
        $('#export-button').click(function() {
            exportToExcel();
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

        .sub {
            font-size: 14px;
            text-align: center;
        }

        .card-tab {
            display: inline-block;
            vertical-align: top;
            width: 50%;
        }

        .table-container {
            display: flex;
            justify-content: space-between;
            max-height: 700px;
            overflow: auto;
        }

        .table-container thead {
            position: sticky;
            top: 0;
            background-color: white;
        }

        .filter {
            align-items: center;
            padding-left: 29px;
            padding-top: 30px;
            font-size: 15px;
            font-weight: bold;
        }
        
        .filter label {
            margin-right: 10px;
        }

        .legend-container {
            display: flex;
            align-items: center;
            padding-left: 29px;
            padding-bottom: 15px;
            font-weight: bold;
        }

        .legend-container label {
            font-size: 15px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            padding-left: 10px;
        }

        .legend-item button {
            margin-right: 5px;
        }

        .table-container {
            display: flex;
            justify-content: space-between;
            max-height: 400px;
            overflow: auto;
        }

        .table-container thead {
            position: sticky;
            top: 0;
            background-color: white;
        }

        .input-group-append{
            margin-top: 10px;
        }
    </style>
