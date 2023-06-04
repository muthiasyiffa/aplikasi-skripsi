<style>
    .custom-table th,
    .custom-table td {
    width: auto;
    white-space: nowrap;
    }
</style>

<table class="table custom-table">
    <thead>
        <tr>
            <th>PID</th>
            <th>Site ID Tenant</th>
            <th>Site Name</th>
            <th>Regional</th>
            <th>Pulau</th>
            <th>Area</th>
            <th>SOW2</th>
            <th>Kategori Tower</th>
            <th>Demografi</th>
            <th>Tenant Existing</th>
            <th>Status LMS</th>
            <th>Status XL</th>
            <th>Final Status Site</th>
            <th>SPK Date</th>
            <th>WO Date</th>
            <th>RFI Date</th>
            <th>Aging SPK to WO</th>
            <th>Aging WO to RFI</th>
            <th>Aging RFI to BAK</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($salesOrders as $result)
        <tr>
            <td>{{ $result->pid }}</td>
            <td>{{ $result->site_id_tenant }}</td>
            <td>{{ $result->site_name }}</td>
            <td>{{ $result->regional }}</td>
            <td>{{ $result->pulau }}</td>
            <td>{{ $result->area }}</td>
            <td>{{ $result->sow2 }}</td>
            <td>{{ $result->kat_tower }}</td>
            <td>{{ $result->demografi }}</td>
            <td>{{ $result->tenant_existing }}</td>
            <td>{{ $result->status_lms }}</td>
            <td>{{ $result->status_xl }}</td>
            <td>{{ $result->final_status_site }}</td>
            <td>{{ $result->spk_date }}</td>
            <td>{{ $result->wo_date }}</td>
            <td>{{ $result->rfi_date }}</td>
            <td>{{ $result->aging_spk_to_wo }}</td>
            <td>{{ $result->aging_wo_to_rfi }}</td>
            <td>{{ $result->aging_rfi_to_bak }}</td>
        </tr>
        @endforeach
    </tbody>
</table>