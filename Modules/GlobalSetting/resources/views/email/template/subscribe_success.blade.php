@extends('admin.layouts.master')
@section('title')
    <title>
        {{ __('Edit Template') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
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
                                    $name = '{{ payment_method }}';
                                @endphp
                                <td>{{ $name }}</td>
                                <td>{{ __('Payment Method') }}</td>
                            </tr>
                            <tr>
                                @php
                                    $name = '{{ amount }}';
                                @endphp
                                <td>{{ $name }}</td>
                                <td>{{ __('Amount') }}</td>
                            </tr>
                            <tr>
                                @php
                                    $name = '{{ payment_status }}';
                                @endphp
                                <td>{{ $name }}</td>
                                <td>{{ __('Payment Status') }}</td>
                            </tr>

                            <tr>
                                @php
                                    $name = '{{ order_status }}';
                                @endphp
                                <td>{{ $name }}</td>
                                <td>{{ __('Order Status') }}</td>
                            </tr>

                            <tr>
                                @php
                                    $name = '{{ order_date }}';
                                @endphp
                                <td>{{ $name }}</td>
                                <td>{{ __('Order Date') }}</td>
                            </tr>

                            <tr>
                                @php
                                    $name = '{{ order_details }}';
                                @endphp
                                <td>{{ $name }}</td>
                                <td>{{ __('Order Details') }}</td>
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
                            <label for="message">{{ __('Message') }} <span class="text-danger">*</span>
                            </label>
                            <textarea name="message" id="message" cols="30" rows="10" class='summernote'>{!! $template->message !!}</textarea>
                        </div>
                        <x-admin.update-button :text="__('Update')" />
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
