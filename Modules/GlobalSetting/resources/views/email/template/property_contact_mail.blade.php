@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Edit Template') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-admin.form-title :text="__('Edit Template')" />

                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <th>{{ __('Variable') }}</th>
                            <th>{{ __('Meaning') }}</th>
                        </thead>
                        <tbody>
                            <tr>
                                @php
                                    $name = '{{ name }}';
                                @endphp
                                <td>{{ $name }}</td>
                                <td>{{ __('User Name') }}</td>
                            </tr>
                            <tr>
                                @php
                                    $name = '{{ property_name }}';
                                @endphp
                                <td>{{ $name }}</td>
                                <td>{{ __('Property Name') }}</td>
                            </tr>

                            <tr>
                                @php
                                    $message = '{{ time }}';
                                @endphp
                                <td>{{ $message }}</td>
                                <td>{{ __('Time') }}</td>
                            </tr>

                            <tr>
                                @php
                                    $message = '{{ date }}';
                                @endphp
                                <td>{{ $message }}</td>
                                <td>{{ __('Date') }}</td>
                            </tr>
                            <tr>
                                @php
                                    $email = '{{ email }}';
                                @endphp
                                <td>{{ $email }}</td>
                                <td>{{ __('User Email') }}</td>
                            </tr>
                            <tr>
                                @php
                                    $phone = '{{ phone }}';
                                @endphp
                                <td>{{ $phone }}</td>
                                <td>{{ __('User Phone') }}</td>
                            </tr>
                            <tr>
                                @php
                                    $subject = '{{ subject }}';
                                @endphp
                                <td>{{ $subject }}</td>
                                <td>{{ __('User Subject') }}</td>
                            </tr>
                            <tr>
                                @php
                                    $message = '{{ message }}';
                                @endphp
                                <td>{{ $message }}</td>
                                <td>{{ __('Message') }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.update-email-template', $template->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <x-admin.form-input id="subject" name="subject" label="{{ __('Subject') }}"
                                value="{{ $template->subject }}" required="true" />
                        </div>
                        <div class="form-group">
                            <x-admin.form-editor id="message" name="message" label="{{ __('Message') }}"
                                value="{!! $template->message !!}" required="true" />
                        </div>
                        <x-admin.update-button :text="__('Update')" />
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
