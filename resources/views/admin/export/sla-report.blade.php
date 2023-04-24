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
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $ticket->created_at }}</td>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->user->employee->name }}</td>
                <td>{{ $ticket->subject }}</td>
                <td>{{ $ticket->subCategory->category->name }}</td>
                <td>{{ $ticket->subCategory->name }}</td>
                <td>{{ $ticket->technician->employee->name }}</td>
                <td>{{ $ticket->progress_at }}</td>
                <td>{{ $ticket->finish_at }}</td>
                <td>{{ $ticket->urgency->hours }}</td>
                <td>{{ $ticket->duration }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="12">No data</td>
            </tr>
        @endforelse
    </tbody>
</table>