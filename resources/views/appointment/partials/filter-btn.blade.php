<button type="button" class="wf-dropdown-toggle"  aria-haspopup="true" aria-expanded="false" @click="toggleDropdownMenu('{{$filter}}')">
  {{ $filter_label }}
    <span class="wf-toggle-icon pull-right">
      <i class="fa fa-chevron-down default" aria-hidden="true"></i>
      <i class="fa fa-check selected" aria-hidden="true"></i>
    </span>
</button>