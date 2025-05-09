<label class="switch {{ $canToggle ? '' : 'switch-disabled' }}">
    <input type="checkbox"
        class="status-toggle"
        data-id="{{ $user->id }}"
        {{ $user->status == 1 ? 'checked' : '' }}
        {{ $canToggle ? '' : 'disabled' }}>
    <span class="slider"></span>
    <span class="active font-dmsans fw-medium">Active</span>
    <span class="inactive font-dmsans fw-medium">Inactive</span>
</label>
