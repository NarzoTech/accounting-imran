@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Edit Template') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">


            <div class="section-body">
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
                                                $name = '{{ user_name }}';
                                            @endphp
                                            <td>{{ $name }}</td>
                                            <td>{{ __('User Name') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $email = '{{ app_name }}';
                                            @endphp
                                            <td>{{ $email }}</td>
                                            <td>{{ __('Application Name') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $phone = '{{ password }}';
                                            @endphp
                                            <td>{{ $phone }}</td>
                                            <td>{{ __('Password') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="section-body">
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
            </div>
        </section>
    </div>
@endsection
