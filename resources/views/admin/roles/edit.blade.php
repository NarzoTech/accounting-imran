@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Edit Role') }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card admin_role_area">
                <div class="card-header d-flex justify-content-between">
                    <x-admin.form-title :text="__('Edit Role')" />
                    <div>
                        @adminCan('role.view')
                            <x-admin.back-button :href="route('admin.role.index')" />
                        @endadminCan
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form role="form" action="{{ route('admin.role.update', $role->id) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="col-12 form-control-validation">
                                    <label class="form-label" for="modalRoleName">{{ __('Role Name') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="modalRoleName" name="name" class="form-control"
                                        placeholder="Enter a role name" tabindex="-1" value="{{ $role->name }}" />
                                </div>
                                <div class="col-12">
                                    <h5 class="mb-3 mt-5">{{ __('Role Permissions') }}</h5>
                                    <!-- Permission table -->
                                    <div class="table-responsive">
                                        <table class="table table-flush-spacing mb-0 border-top">
                                            <tbody>
                                                <tr>
                                                    <td class="text-nowrap fw-medium text-heading">
                                                        {{ __('Administrator Access') }} <i
                                                            class="icon-base bx bx-info-circle" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Allows a full access to the system"></i>
                                                    </td>
                                                    <td>

                                                        <div class="d-flex justify-content-end">
                                                            <div class="form-check mb-0">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="permission_all" value="1"
                                                                    {{ App\Models\Admin::roleHasPermission($role, $permissions) ? 'checked' : '' }} />
                                                                <label class="form-check-label permission_all"
                                                                    for="permission_all">
                                                                    {{ __('Select All Permission') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                @php $i=1; @endphp
                                                @foreach ($permission_groups as $groupName => $permissions)
                                                    <tr>
                                                        <td class="text-nowrap fw-medium text-heading">
                                                            {{ ucfirst($groupName) }}</td>
                                                        <td>
                                                            @foreach ($permissions as $permission)
                                                                <div class="form-check mb-0 me-4 me-lg-12">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="permission_checkbox_{{ $permission->id }}"
                                                                        value="{{ $permission->name }}"
                                                                        name="permissions[]"
                                                                        {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} />
                                                                    <label class="form-check-label"
                                                                        for="permission_checkbox_{{ $permission->id }}">
                                                                        {{ implode(' ', array_map('ucfirst', explode('.', $permission->name))) }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Permission table -->
                                </div>
                                <div class="col-12 mt-5">
                                    <x-admin.update-button :text="__('Update')" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        "use strict"
        $('input[name^="permession_group"]').on('change', function() {
            permission_all_checked();
        });

        $('#permission_all').on('click', function() {
            $('input[type=checkbox]').prop('checked', $(this).prop('checked'));
        });

        function CheckPermissionByGroup(classname, checkthis) {
            const groupIdName = $("#" + checkthis.id);
            const classCheckBox = $('.' + classname + ' input');

            classCheckBox.prop('checked', groupIdName.prop('checked'));
        }

        $('input[name^="permissions"]').on('change', function() {
            const roleId = $(this).data('role-id');
            checkGroupName(roleId)
            permission_all_checked();
        });

        function checkGroupName(roleId) {
            const groupCheckbox = $('#' + roleId + 'management');
            const groupPermissions = $('input[name^="permissions"][data-role-id="' + roleId + '"]');
            const checkedPermissionsCount = groupPermissions.filter(':checked').length;
            const totalPermissionsCount = groupPermissions.length;
            groupCheckbox.prop('checked', checkedPermissionsCount === totalPermissionsCount);
        }

        function permission_all_checked() {
            var allCheckboxesChecked = $('input[type=checkbox]').not('#permission_all').length ===
                $('input[type=checkbox]:checked').not('#permission_all').length;
            $('#permission_all').prop('checked', allCheckboxesChecked);
        }

        $('.permession_group').each(function() {
            const roleId = $(this).data('role-id');
            checkGroupName(roleId);
        });
    </script>
@endpush
