@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Edit Investor') }}</title>
@endsection

@section('admin-content')
    <form action="{{ route('admin.investor.update', $investor->id) }}" method="post" id="create_form"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Main Form Area -->
            <div class="col-md-7 col-lg-8">
                <div class="card pb-5">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="section_title">{{ __('Edit Investor') }}</h4>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="name" label="{{ __('Name') }}"
                                    value="{{ old('name', $investor->name) }}" required="true" id="name" />
                            </div>
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="email" label="{{ __('Email') }}"
                                    value="{{ old('email', $investor->email) }}" type="email" id="email" />
                            </div>
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="phone" label="{{ __('Phone') }}"
                                    value="{{ old('phone', $investor->phone) }}" id="phone" />
                            </div>
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="address" label="{{ __('Address') }}"
                                    value="{{ old('address', $investor->address) }}" id="address" />
                            </div>
                            <div class="col-12">
                                <x-admin.form-textarea name="notes" label="{{ __('Notes') }}"
                                    value="{{ old('notes', $investor->notes) }}" id="notes" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-md-5 col-lg-4 small_mt_5">
                <x-admin.action-btn update="true" />
            </div>
        </div>
    </form>
@endsection
