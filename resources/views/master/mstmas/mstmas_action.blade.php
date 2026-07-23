<a href="{{ route('mstmas.detail', ['braco' => $row->braco, 'cusno' => $row->cusno]) }}"
    class="badge bg-primary"
    data-tooltip="true"
    title="Detail">
    <i class="bi bi-info-circle"></i>
</a>

<a href="{{ route('mstmas.edit', ['braco' => $row->braco, 'cusno' => $row->cusno]) }}"
    class="badge bg-warning"
    data-tooltip="true"
    title="Edit">
    <i class="bi bi-pencil"></i>
</a>