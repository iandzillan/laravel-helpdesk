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
            <th>On work Duration</th>
            <th>Pending Duration</th>
            <th>Success Rate (%)</th>
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
                <td>{{ gmdate('H:i:s', $ticket->urgency->hours * 3600) }}</td>
                <td>{{ gmdate('H:i:s', $ticket->trackings->where('status', '!=', 'Ticket Continued')->sum('duration')) }}</td>
                <td>{{ gmdate('H:i:s', $ticket->trackings->where('status', 'Ticket Continued')->sum('duration')) }}</td>
                <td>
                    @if ($ticket->trackings->where('status', '!=', 'Ticket Continued')->sum('duration') > $ticket->urgency->hours * 3600)
                        50
                    @else
                        100
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="12">No data</td>
            </tr>
        @endforelse
    </tbody>
</table>