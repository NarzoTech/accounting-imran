@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Email Settings') }}</title>
@endsection
@section('admin-content')
    <div class="card">
        <div class="card-header">
            <h4 class="section_title">
                {{ __('Email Configuration') }}
            </h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.update-email-settings') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <x-admin.form-input id="mail_host" name="mail_host" label="{{ __('Host') }}"
                                value="{{ $setting->mail_host }}" placeholder="{{ __('Enter Host') }}" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <x-admin.form-input type="number" id="mail_port" name="mail_port" label="{{ __('Port') }}"
                                value="{{ $setting->mail_port }}" placeholder="{{ __('Enter Port') }}" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <x-admin.form-input id="mail_username" name="mail_username" label="{{ __('Username') }}"
                                value="{{ $setting->mail_username }}" placeholder="{{ __('Enter Username') }}" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <x-admin.form-input id="mail_password" name="mail_password" label="{{ __('Password') }}"
                                value="{{ $setting->mail_password }}" placeholder="{{ __('Enter Password') }}" />
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                            <x-admin.form-input id="mail_sender_name" name="mail_sender_name"
                                label="{{ __('Sender Name') }}" value="{{ $setting->mail_sender_name }}"
                                placeholder="{{ __('Enter Sender Name') }}" />
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                            <x-admin.form-input type="email" id="mail_sender_email" name="mail_sender_email"
                                label="{{ __('Email') }}" value="{{ $setting->mail_sender_email }}"
                                placeholder="{{ __('Enter Sender Email') }}" />
                        </div>

                    </div>

                    <div class="col-md-12 col-lg-4">
                        <div class="form-group">
                            <x-admin.form-select id="mail_encryption" name="mail_encryption"
                                label="{{ __('Encryption Type') }}">
                                <x-admin.select-option :selected="$setting->mail_encryption == 'tls'" value="tls" text="{{ __('TLS') }}" />
                                <x-admin.select-option :selected="$setting->mail_encryption == 'ssl'" value="ssl" text="{{ __('SSL') }}" />
                            </x-admin.form-select>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="form-group">
                            <x-admin.form-switch name="is_queable" label="{{ __('Process Emails in Background') }}"
                                active_value="active" inactive_value="inactive" :checked="$setting->is_queable == 'active'" />
                        </div>
                    </div>
                </div>
                <x-admin.update-button :text="__('Update')" />
                {{-- Test Email Button --}}
                @if (
                    $setting->mail_username != 'mail_username' &&
                        $setting->mail_password != 'mail_password' &&
                        $setting->mail_port != 'mail_port')
                    @php($test_email = true)
                    <button class="btn btn-primary" data-bs-toggle="modal" type="button"
                        data-bs-target="#testEmail">{{ __('Test Mail Credentials') }}</button>
                @endif
            </form>
        </div>
    </div>
    <div class="card mt-5">
        <div class="card-header">
            <h4 class="section_title">
                {{ __('Email Templates') }}
            </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive list_table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('SN') }}</th>
                            <th>{{ __('Email Template') }}</th>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($templates as $index => $item)
                            <tr>
                                <td>{{ ++$index }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $item->name)) }}</td>
                                <td>{{ $item->subject }}</td>
                                <td>
                                    <x-admin.edit-button :href="route('admin.edit-email-template', $item->id)" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Test Email Modal --}}
    @if ($test_email ?? false)
        <div class="modal fade" tabindex="-1" role="dialog" id="testEmail">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Test Mail Credentials') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('Are You sure want to test your mail Credentials?') }}</p>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <form action="{{ route('admin.test-mail-credentials') }}" action="" method="POST">
                            @csrf
                            <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                            <x-admin.button type="submit" text="{{ __('Yes') }}" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $(document).on('change', '[name="is_queable"]', function() {
                $.ajax({
                    url: "{{ route('admin.update-general-setting') }}",
                    type: "PUT",
                    data: {
                        _token: "{{ csrf_token() }}",
                        is_queable: $(this).val()
                    },
                    success: function(response) {}
                })
            })
        });
    </script>
@endpush
