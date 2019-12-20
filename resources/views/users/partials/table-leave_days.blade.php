<table class="table table-striped">
    <thead>
        <tr>
            <th>Vom</th>
            <th>bis</th>
            <th>Tage</th>
        </tr>
    </thead>

    <tbody>
        @foreach($leave_days as $appointment)
            <tr>
                <td>{{ formatDate( $appointment->date_from, "d.m.Y") }}</td>
                <td>{{ formatDate( $appointment->date_to, "d.m.Y") }}</td>
                <td>{{ $appointment->diffInDays }}</td>
            </tr>
        @endforeach
    </tbody>

</table>