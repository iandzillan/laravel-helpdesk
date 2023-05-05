<table>
    <thead>
        <tr>
            <th colspan="13" align="center">
                <b>
                    SERVICE-LEVEL AGREEMENT REPORT ({{ $validate['from'] }} - {{ $validate['to'] }}) 
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
            <th>Status</th>
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
                <td>{{ ($ticket->technician == null) ? "--" : $ticket->technician->employee->name }}</td>
                <td>{{ $ticket->status }}</td>
                <td>{{ $ticket->progress_at }}</td>
                <td>{{ $ticket->finish_at }}</td>
                <td>{{ ($ticket->urgency == null) ? "--" : gmdate('H:i:s', $ticket->urgency->hours * 3600) }}</td>
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
            <th colspan="2" align="center"><b>TOTAL TICKET BASED ON STATUS</b></th>
        </tr>
        <tr>
            <th>Status</th>
            <th>Total Ticket</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($status as $item)
            <tr>
                <td>{{ $item['status'] }}</td>
                <td align="end">{{ $item['count'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td align="center"><b>TOTAL</b></td>
            <td align="end"><b>{{ $status->sum('count') }}</b></td>
        </tr>
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <th colspan="2" align="center"><b>TOTAL TICKET BASED ON CATEGORY</b></th>
        </tr>
        <tr>
            <th>Category</th>
            <th>Total Ticket</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td align="end">{{ $category->tickets_count }}</td>
            </tr>
        @endforeach
        <tr>
            <td align="center"><b>TOTAL</b></td>
            <td align="end"><b>{{ $categories->sum('tickets_count') }}</b></td>
        </tr>
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <th colspan="3" align="center"><b>TOTAL TICKET BASED ON SUB CATEGORY</b></th>
        </tr>
        <tr>
            <th>Category</th>
            <th>Sub Category</th>
            <th>Total Ticket</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($subcategories as $subcategory)
            <tr>
                <td>{{ $subcategory->category->name }}</td>
                <td>{{ $subcategory->name }}</td>
                <td align="end">{{ $subcategory->tickets_count }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" align="center"><b>Total</b></td>
            <td align="end"><b>{{ $subcategories->sum('tickets_count') }}</b></td>
        </tr>
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <th colspan="2" align="center"><b>TOTAL TICKET BASED ON DEPARTMENT</b></th>
        </tr>
        <tr>
            <th>Department</th>
            <th>Total Ticket</th>
        </tr>
    </thead>
    <tbody>
        @php
            $sum = 0;
        @endphp
        @foreach ($data_dept as $row)
            <tr>
                <td>{{ $row['department'] }}</td>
                <td align="end">{{ $row['tickets_count'] }}</td>
            </tr>
            @php
                $sum += $row['tickets_count'];
            @endphp
        @endforeach
        <tr>
            <td align="center"><b>TOTAL</b></td>
            <td align="end"><b>{{ $sum }}</b></td>
        </tr>
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <th colspan="3" align="center"><b>TOTAL TICKET BASED ON SUB DEPARTMENT</b></th>
        </tr>
        <tr>
            <th>Department</th>
            <th>Sub Department</th>
            <th>Total Ticket</th>
        </tr>
    </thead>
    <tbody>
        @php $sum1 = 0 @endphp
        @php $sum2 = 0 @endphp
        @foreach ($data_subdept as $row)
            <tr>
                <td>{{ $row['dept'] }}</td>
                <td>{{ $row['subdept'] }}</td>
                <td align="end">{{ $row['count'] }}</td>
            </tr>
            @php $sum1 += $row['count'] @endphp
        @endforeach
        <tr>
            <th colspan="3" align="center"><b>MANAGER</b></th>
        </tr>
        @foreach ($data_manager as $row)
            <tr>
                <td colspan="2">{{ $row['manager'] }} Manager</td>
                <td align="end">{{ $row['count'] }}</td>
            </tr>
            @php $sum2 += $row['count'] @endphp
        @endforeach
        <tr>
            <td colspan="2" align="center"><b>TOTAL</b></td>
            <td align="end"><b>{{ $sum1 + $sum2 }}</b></td>
        </tr>
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <th colspan="2" align="center"><b>TOTAL TICKET BASED ON SLA</b></th>
        </tr>
        <tr>
            <th>SLA</th>
            <th>Total Ticket</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sla as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td align="end">{{ $item['count'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td align="center"><b>TOTAL</b></td>
            <td align="end"><b>{{ $sla->sum('count') }}</b></td>
        </tr>
    </tbody>
</table>