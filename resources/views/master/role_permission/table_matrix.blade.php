@php
    $active = $activePermissions ?? [];
@endphp

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th style="width: 30%;">Modul / Fitur Navbar ERP</th>
                <th class="text-center" style="width: 15%;">View (Lihat)</th>
                <th class="text-center" style="width: 15%;">Create (Tambah)</th>
                <th class="text-center" style="width: 15%;">Edit (Ubah)</th>
                <th class="text-center" style="width: 15%;">Delete (Hapus)</th>
                <th class="text-center" style="width: 10%;">Pilih Semua</th>
            </tr>
        </thead>
        <tbody>
            <!-- Modul Customer & Invoice -->
            <tr>
                <td><i class="bi bi-file-earmark-medical me-1"></i> Master Customer & Invoice</td>
                <td class="text-center">
                    <input class="form-check-input perm-{{ Str::slug($branch) }}-{{ Str::slug($role) }}-cust" 
                           type="checkbox" name="permissions[]" value="view_customers"
                           {{ in_array('view_customers', $active) ? 'checked' : '' }}>
                </td>
                <td class="text-center">
                    <input class="form-check-input perm-{{ Str::slug($branch) }}-{{ Str::slug($role) }}-cust" 
                           type="checkbox" name="permissions[]" value="create_customers"
                           {{ in_array('create_customers', $active) ? 'checked' : '' }}>
                </td>
                <td class="text-center">
                    <input class="form-check-input perm-{{ Str::slug($branch) }}-{{ Str::slug($role) }}-cust" 
                           type="checkbox" name="permissions[]" value="edit_customers"
                           {{ in_array('edit_customers', $active) ? 'checked' : '' }}>
                </td>
                <td class="text-center">
                    <input class="form-check-input perm-{{ Str::slug($branch) }}-{{ Str::slug($role) }}-cust" 
                           type="checkbox" name="permissions[]" value="delete_customers"
                           {{ in_array('delete_customers', $active) ? 'checked' : '' }}>
                </td>
                <td class="text-center">
                    <input class="form-check-input check-all-row" type="checkbox" data-target="perm-{{ Str::slug($branch) }}-{{ Str::slug($role) }}-cust">
                </td>
            </tr>
        </tbody>
    </table>
</div>