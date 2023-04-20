<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Created At</th>
            <th>Ticket Number</th>
            <th>User</th>
            <th>Subject</th>
            <th>Category</th>
            <th>Sub Category</th>
            <th>Technician</th>
            <th>Progress At</th>
            <th>Finish At</th>
            <th>SLA Duration</th>
            <th>Actual Duration</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tickets as $ticket)
            
        @empty
            <tr>
                <td colspan="12">No data</td>
            </tr>
        @endforelse
    </tbody>
</table>