<table class="table table-striped">
    <thead>
        <tr>
            <th>Vom</th>
            <th>bis</th>
            <th><span class="d-none d-sm-block">Urlaubstage</span> <span class="d-block d-sm-none">Urlaub</span></th>
            <th><span class="d-none d-sm-block">Gesamt</span> <span class="d-block d-sm-none">Gesamt</span></th>
        </tr>
    </thead>

    <tbody>
        @foreach($leave_days as $appointment)
            <tr>
                <td>{{ formatDate( $appointment->date_from, "d.m.Y") }}</td>
                <td>{{ formatDate( $appointment->date_to, "d.m.Y") }}</td>
                <td>{{ $appointment->diffInDaysNetto }} T.</td>
                <td>{{ $appointment->diffInDays }} T.</td>
            </tr>
        @endforeach
    </tbody>

</table>