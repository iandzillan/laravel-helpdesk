<table>
    <thead>
        <tr>
            <th colspan="13" align="center">
                <b>
                    SERVICE-LEVEL AGREEMENT REPORT ({{ $from }} - {{ $to }}) 
                </b>
            </th>
        </tr>
        <tr>
            <th><b>Created At</b></th>
            <th><b>Ticket Number</b></th>
            <th><b>User</b></th>
            <th><b>Subject</b></th>
            <th><b>Category</b></th>
            <th><b>Sub Category</b></th>
            <th><b>Technician</b></th>
            <th><b>Status</b></th>
            <th><b>Progress At</b></th>
            <th><b>Finish At</b></th>
            <th><b>SLA Duration</b></th>
            <th><b>On work Duration</b></th>
            <th><b>Pending Duration</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tickets as $ticket)
            <tr>
                <td>{{ $ticket['created_at'] }}</td>
                <td>{{ $ticket['ticket_number'] }}</td>
                <td>{{ $ticket['user'] }}</td>
                <td>{{ $ticket['user'] }}</td>
                <td>{{ $ticket['category'] }}</td>
                <td>{{ $ticket['subcategory'] }}</td>
                <td>{{ $ticket['technician'] }}</td>
                <td>{{ $ticket['status'] }}</td>
                <td>{{ $ticket['progress_at'] }}</td>
                <td>{{ $ticket['finish_at'] }}</td>
                <td>{{ $ticket['urgency'] }}</td>
                <td>{{ $ticket['onwork'] }}</td>
                <td>{{ $ticket['pending'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <th colspan="2" align="center"><b>TOTAL TICKET BASED ON STATUS</b></th>
        </tr>
        <tr>
            <th><b>Status</b></th>
            <th><b>Total Ticket</b></th>
        </tr>
    </thead>
    <tbody>
        @php $statusSum = 0; @endphp
        @foreach ($status as $item)
            <tr>
                <td>{{ $item['status'] }}</td>
                <td align="end">{{ $item['count'] }}</td>
            </tr>
            @php $statusSum += $item['count']; @endphp
        @endforeach
        <tr>
            <td><b>TOTAL</b></td>
            <td align="end"><b>{{ $statusSum }}</b></td>
        </tr>
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <th colspan="2" align="center"><b>TOTAL TICKET BASED ON CATEGORY</b></th>
        </tr>
        <tr>
            <th><b>Category</b></th>
            <th><b>Total Ticket</b></th>
        </tr>
    </thead>
    <tbody>
        @php $categorySum = 0; @endphp
        @foreach ($categories as $category)
            <tr>
                <td>{{ $category['category'] }}</td>
                <td align="end">{{ $category['count'] }}</td>
            </tr>
            @php $categorySum += $category['count']; @endphp
        @endforeach
        <tr>
            <td><b>TOTAL</b></td>
            <td align="end"><b>{{ $categorySum }}</b></td>
        </tr>
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <th colspan="3" align="center"><b>TOTAL TICKET BASED ON SUB CATEGORY</b></th>
        </tr>
        <tr>
            <th><b>Category</b></th>
            <th><b>Sub Category</b></th>
            <th><b>Total Ticket</b></th>
        </tr>
    </thead>
    <tbody>
        @php $subcategorySum = 0; @endphp
        @foreach ($subcategories as $subcategory)
            <tr>
                <td>{{ $subcategory['category'] }}</td>
                <td>{{ $subcategory['subcategory'] }}</td>
                <td align="end">{{ $subcategory['count'] }}</td>
            </tr>
            @php
                $subcategorySum += $subcategory['count'];
            @endphp
        @endforeach
        <tr>
            <td colspan="2"><b>Total</b></td>
            <td align="end"><b>{{ $subcategorySum }}</b></td>
        </tr>
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <th colspan="2" align="center"><b>TOTAL TICKET BASED ON DEPARTMENT</b></th>
        </tr>
        <tr>
            <th><b>Department</b></th>
            <th><b>Total Ticket</b></th>
        </tr>
    </thead>
    <tbody>
        @php $deptSum = 0; @endphp
        @foreach ($depts as $dept)
            <tr>
                <td>{{ $dept['dept'] }}</td>
                <td align="end">{{ $dept['count'] }}</td>
            </tr>
            @php
                $deptSum += $dept['count'];
            @endphp
        @endforeach
        <tr>
            <td><b>TOTAL</b></td>
            <td align="end"><b>{{ $deptSum }}</b></td>
        </tr>
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <th colspan="3" align="center"><b>TOTAL TICKET BASED ON SUB DEPARTMENT</b></th>
        </tr>
        <tr>
            <th><b>Department</b></th>
            <th><b>Sub Department</b></th>
            <th><b>Total Ticket</b></th>
        </tr>
    </thead>
    <tbody>
        @php $subdeptSum = 0 @endphp
        @php $managerSum = 0 @endphp
        @foreach ($subdepts as $subdept)
            <tr>
                <td>{{ $subdept['dept'] }}</td>
                <td>{{ $subdept['subdept'] }}</td>
                <td align="end">{{ $subdept['count'] }}</td>
            </tr>
            @php $subdeptSum += $subdept['count'] @endphp
        @endforeach
        <tr>
            <th colspan="3"><b>MANAGER</b></th>
        </tr>
        @foreach ($managers as $manager)
            <tr>
                <td colspan="2">{{ $manager['manager'] }} Manager</td>
                <td align="end">{{ $manager['count'] }}</td>
            </tr>
            @php $managerSum += $manager['count'] @endphp
        @endforeach
        <tr>
            <td colspan="2"><b>TOTAL</b></td>
            <td align="end"><b>{{ $subdeptSum + $managerSum }}</b></td>
        </tr>
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <th colspan="2" align="center"><b>TOTAL TICKET BASED ON SLA</b></th>
        </tr>
        <tr>
            <th><b>SLA</b></th>
            <th><b>Total Ticket</b></th>
        </tr>
    </thead>
    <tbody>
        @php $slaSum = 0; @endphp
        @foreach ($slas as $sla)
            <tr>
                <td>{{ $sla['name'] }}</td>
                <td align="end">{{ $sla['count'] }}</td>
            </tr>
            @php $slaSum += $sla['count'] @endphp
        @endforeach
        <tr>
            <td><b>TOTAL</b></td>
            <td align="end"><b>{{ $slaSum }}</b></td>
        </tr>
    </tbody>
</table>