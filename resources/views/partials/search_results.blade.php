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
            <th>Site ID Tenant</th>
            <th>Site Name</th>
            <th>Regional</th>
            <th>Pulau</th>
            <th>Area</th>
            <th>Kat Jenis Order</th>
            <th>SOW2</th>
            <th>Longitude</th>
            <th>Latitude</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($searchResults as $result)
        <tr>
            <td>{{ $result->site_id_tenant }}</td>
            <td>{{ $result->site_name }}</td>
            <td>{{ $result->regional }}</td>
            <td>{{ $result->pulau }}</td>
            <td>{{ $result->area }}</td>
            <td>{{ $result->kat_jenis_order }}</td>
            <td>{{ $result->sow2 }}</td>
            <td>{{ $result->longitude }}</td>
            <td>{{ $result->latitude }}</td>
        </tr>
        @endforeach
    </tbody>
</table>