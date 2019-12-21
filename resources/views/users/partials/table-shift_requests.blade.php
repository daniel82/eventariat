@include("admin.common.add-new-button", ["href"=> action("ShiftRequestFrontendController@create"), "label"=> "Neuer Antrag" ])

@if ( $shift_requests && $shift_requests->count() )
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Status</th>
                <th>Type</th>
                <th>Vom</th>
                <th>bis</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach($shift_requests as $item )
                <tr>
                    <td class="ev-edit-col">
                        @if ( $item->status == 0 )
                          @include("frontend.partials.edit-button", ["controller" => "ShiftRequestFrontendController", "object" => $item])
                        @else
                          @include("frontend.partials.show-button", ["controller" => "ShiftRequestFrontendController", "object" => $item])
                        @endif
                    </td>

                    <td><span class="ev-shift-request__status status-{{ $item->status }}"><i class="fa fa-circle" aria-hidden="true"></i></span></td>

                    <td>{{ $item->type_hr }}</td>
                    <td>{{ formatDate( $item->date_from, "d.m.Y") }}</td>
                    <td>{{ formatDate( $item->date_to, "d.m.Y") }}</td>
                    <td class="ev-action-col">
                      @if ($item->status == 0)
                        @include("admin.common.delete-button", ["controller" => "ShiftRequestFrontendController", "object" => $item])
                      @endif
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
@else
    <p>Keine Antr&auml;ge vorhanden</p>

@endif