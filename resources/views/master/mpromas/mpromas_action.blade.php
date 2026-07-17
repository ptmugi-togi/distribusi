<a href="{{ route('mpromas.detail', $row->opron) }}"
    class="badge bg-primary"
    data-tooltip="true"
    title="Detail">
    <i class="bi bi-info-circle"></i>
</a>

<a href="{{ route('mpromas.edit', $row->opron) }}"
    class="badge bg-warning"
    data-tooltip="true"
    title="Edit">
    <i class="bi bi-pencil"></i>
</a>

<form id="delete-promas-{{ $row->opron }}"
      action="{{ route('mpromas.delete', $row->opron) }}"
      method="POST"
      style="display:inline;">
    @csrf
    @method('DELETE')

    <a class="badge bg-danger btn-delete-promas"
       data-opron="{{ $row->opron }}"
       data-prona="{{ $row->prona }}"
       style="cursor:pointer"
       data-tooltip="true"
       title="Delete">
        <i class="bi bi-trash"></i>
    </a>
</form>