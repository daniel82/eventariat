<style type="text/css">

  body
  table,
  td
  {
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    font-size: 12px;
  }

  .new-date td
  {
    padding-top: 20px;
  }

  .bold
  {
    font-weight: bold;
  }

  table,
  tr,
  tbody
  {
    width: 100%;
  }

  w-25
  {
    width: 25%;
  }

  w-10
  {
    width: 10%;
  }


</style>
<table>
  <tbody>
    @foreach ( $items as $date => $col )
      <tr class="new-date">
        <td colspan="5">
          <div class="bold">
            <span style="margin-right: 30px">{{ $col["date"] }}</span>
            <span class="bold">{{ ((isset($col["forecast"]))) ? $col["forecast"]["temperature"]." °" : "" }}</span>
          </div>
        </td>
      </tr>


      @if ( isset($col["appointments"]) && count($col["appointments"])  )
        @foreach ( $col["appointments"] as $a )
          <tr>
            <td style="width: 140px" >{{ ( isset($a["tooltip_location"])) ? $a["tooltip_location"] : '---' }}</td>
            <td class="w-10">{{ ( isset($a["date_from"])) ? formatDate($a["date_from"], "H:i") : '' }}</td>
            <td  class="w-10">{{ ( isset($a["date_to"])) ? formatDate( $a["date_to"], "H:i") : '' }}</td>
            <td  class="w-25">{{ ( isset($a["title"])) ? $a["title"] : '' }}</td>
            <td  class="w-10">{{ ( isset($a["type_text"])) ? $a["type_text"] : '' }}</td>
          </tr>

        @endforeach
      @else
        <tr class="appointment-row">
          <td  colspan="6">Keine Termine</td>
        </tr>
      @endif

    @endforeach

  </tbody>
</table>