<table>
    <thead>
        <tr>
            <th colspan="12" align="center">
                <b>
                    Service-Level Agreement Report From {{ $validate['from'] }} - {{ $validate['to'] }} 
                </b>
            </th>
        </tr>
        <tr>
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
        </tr>
    </thead>
    <tbody>
        @forelse ($tickets as $ticket)
            <tr>
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
            </tr>
        @empty
            <tr>
                <td colspan="12">No data</td>
            </tr>
        @endforelse
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <td colspan="2">
                <b>Total Ticket Based on Category</b>
            </td>
        </tr>
        <tr>
            <td>Category</td>
            <td>Total Ticket</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->tickets_count }}</td>
            </tr>
        @endforeach
        <tr>
            <td align="center">
                <b>Total</b>
            </td>
            <td>
                <b>{{ $categories->sum('tickets_count') }}</b>
            </td>
        </tr>
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <td colspan="4">
                <b>Total Ticket Based on Sub Category</b>
            </td>
        </tr>
        <tr>
            <td>Category</td>
            <td>Sub Category</td>
            <td>Total Ticket</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
            @php ($first = true) @endphp
            @foreach ($category->subCategories as $subcategory)
                <tr>
                    @if($first == true) 
                        <td rowspan="{{ $category->subCategories->count() }}">{{ $category->name }}</td>
                        @php ($first = false) @endphp
                    @endif
                    <td>{{ $subcategory->name }}</td>
                    <td>{{ $subcategory->tickets->whereBetween('created_at', [$validate['from'], $validate['to']])->count() }}</td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="2" align="center">
                <b>Total</b>
            </td>
            <td>
                <b>{{ $categories->sum('tickets_count') }}</b>
            </td>
        </tr>
    </tbody>
</table>