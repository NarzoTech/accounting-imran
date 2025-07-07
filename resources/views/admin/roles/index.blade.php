@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Roles List') }}</title>
@endsection
@section('admin-content')
    <h5 class="role_list_title">{{ __('Roles List') }}</h5>

    <!-- Role cards -->
    <div class="row g-6">
        @foreach ($roles as $role)
            <div class="col-xxl-3 col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-normal mb-0 text-body">{{ __('Total') }} {{ $role->users->count() }}
                                {{ __('users') }}</h6>
                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                @foreach ($role->users->take(3) as $user)
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="{{ $user->name }}" class="avatar pull-up">
                                        <img class="rounded-circle"
                                            src="{{ asset($user->image ?? $setting->default_avatar) }}" alt="Avatar" />
                                    </li>
                                @endforeach
                                @if ($role->users->count() > 3)
                                    <li class="avatar">
                                        <span class="avatar-initial rounded-circle pull-up" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="{{ $role->users->count() - 3 }} more">
                                            +{{ $role->users->count() - 3 }}
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="d-flex justify-content-between align-items-end">
                            <div class="role-heading">
                                <h5 class="mb-1">{{ $role->name }}</h5>
                                <a href="{{ route('admin.role.edit', $role->id) }}" class="role-edit-modal">
                                    <span>{{ __('Edit Role') }}</span>
                                </a>
                            </div>
                            <a href="javascript:void(0);" onclick="deleteData({{ $role->id }})"><i
                                    class="icon-base bx bxs-trash-alt icon-md text-danger"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @adminCan('role.create')
            <div class="col-xxl-3 col-lg-4 col-sm-6">
                <div class="card h-100">
                    <div class="row h-100">
                        <div class="col-sm-5">
                            <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-4 ps-6">
                                <img src="{{ asset('/backend/assets/img/illustrations/lady-with-laptop-light.png') }}"
                                    class="img-fluid" alt="Image" width="120"
                                    data-app-light-img="illustrations/lady-with-laptop-light.png"
                                    data-app-dark-img="illustrations/lady-with-laptop-dark.png" />
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="card-body text-sm-end text-center ps-sm-0">
                                <a href="{{ route('admin.role.create') }}"
                                    class="btn btn-sm btn-primary mb-4 text-nowrap add-new-role">{{ __('Add New Role') }}</a>
                                <p class="mb-0">
                                    {{ __('Add new role') }}, <br />
                                    {{ __("if it doesn't exist") }}.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endadminCan
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap">
                        <h4 class="section_title">{{ __('Total users with their roles') }}</h4>
                        <p class="w-100 mb-0">
                            {{ __("Find all of your company's administrator accounts and their associate roles") }}.
                        </p>
                    </div>
                    <a href="{{ route('admin.admin.create') }}" class="btn btn-primary">{{ __('Add New User') }}</a>
                </div>
                <div class="card-body">
                    <div class="card-datatable">
                        <div class="table-responsive">
                            <table class="table user_list_table">
                                <thead>
                                    <tr>
                                        <th>{{ __('SL') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Role') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>
                                                @foreach ($user->getRoleNames() as $role)
                                                    <span class="badge bg-label-success">{{ $role }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-label-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                                    {{ $user->status == 'active' ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            onclick="deleteUserData({{ $user->id }})"><i
                                                                class="bx bx-trash me-1"></i>
                                                            {{ __('Delete') }}</a>

                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            onclick="assignRole({{ $user->id }})"> <i
                                                                class="bx bxs-user-plus me-1"></i>
                                                            {{ __('Assign Role') }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @adminCan('role.delete')
        <x-admin.delete-modal />
    @endadminCan

    <div tabindex="-1" role="dialog" id="userDeleteModal" class='modal fade'>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('User Delete Confirmation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are You sure want to delete this user') }} ?</p>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <form id="userDeleteForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Yes, Delete') }}" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div tabindex="-1" role="dialog" id="assignRole" class='modal fade'>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Assign Role') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <form id="assignRoleForm" action="{{ route('admin.role.assign.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="user_id">
                        <x-admin.form-select id="role" name="role[]" label="{{ __('Role') }}" class="select2"
                            required="true" multiple data-dropdown-parent="#assignRole">
                            <x-admin.select-option value="" disabled text="{{ __('Select Role') }}" />
                            @foreach ($roles as $role)
                                <x-admin.select-option value="{{ $role->name }}" text="{{ $role->name }}" />
                            @endforeach
                        </x-admin.form-select>
                    </form>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                    <x-admin.button type="submit" text="{{ __('Update') }}" form="assignRoleForm" />

                </div>
            </div>
        </div>
    </div>
@endsection
@adminCan('role.delete')
    @push('js')
        <script>
            "use strict"

            function deleteData(id) {
                $("#deleteForm").attr("action", "{{ url('/admin/role/') }}" + "/" + id)
                $('#deleteModal').modal('show');
            }

            function deleteUserData(id) {
                $("#userDeleteForm").attr("action", "{{ url('/admin/user/') }}" + "/" + id)
                $('#userDeleteModal').modal('show');
            }

            function assignRole(id) {
                $('#assignRoleForm input[name="user_id"]').val(id);

                if (id) {
                    $.ajax({
                        type: "post",
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        url: "{{ url('/admin/role/assign') }}" + "/" + id,

                        success: function(response) {
                            if (response.success) {
                                $('#role').empty();
                                $('#role').append(response.data);
                                $('#assignRole').modal('show');
                            }

                        },
                        error: function(err) {
                            $('#update-btn').prop('disabled', false);
                            toastr.error("{{ __('Failed!') }}")
                            console.log(err);
                        },
                    })
                }


            }
        </script>
    @endpush
@endadminCan
