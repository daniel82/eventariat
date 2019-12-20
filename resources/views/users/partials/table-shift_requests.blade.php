@include("admin.common.add-new-button", ["href"=> action("ShiftRequestController@create"), "label"=> "Neuer Antrag" ])

<table class="table table-striped">
    <thead>
        <tr>
            <th>Status</th>
            <th>Type</th>
            <th>Vom</th>
            <th>bis</th>
        </tr>
    </thead>

    <tbody>
        @foreach($shift_requests as $item )
            <tr>
                <td><span class="ev-shift-request__status status-{{ $item->status }}"><i class="fa fa-circle" aria-hidden="true"></i></span></td>
                <td>{{ $item->type_hr }}</td>
                <td>{{ formatDate( $item->date_from, "d.m.Y") }}</td>
                <td>{{ formatDate( $item->date_to, "d.m.Y") }}</td>
            </tr>
        @endforeach
    </tbody>

</table>