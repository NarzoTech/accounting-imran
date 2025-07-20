@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Investment Details') }}</title>
@endsection
@section('admin-content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="section_title">{{ __('Investment Details') }}</h4>
            <a href="{{ route('admin.investment.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i> {{ __('Back to List') }}
            </a>
        </div>

        <div class="card-body">
            <!-- Investor Info -->
            <h5 class="mb-3">{{ __('Investor Information') }}</h5>
            <table class="table table-bordered mb-4">
                <tbody>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <td>{{ $investment->investor->name }}</td>
                        <th>{{ __('Email') }}</th>
                        <td>{{ $investment->investor->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Phone') }}</th>
                        <td>{{ $investment->investor->phone ?? '-' }}</td>
                        <th>{{ __('Address') }}</th>
                        <td>{{ $investment->investor->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Notes') }}</th>
                        <td colspan="3">{{ $investment->investor->notes ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Investment Info -->
            <h5 class="mb-3">{{ __('Investment Information') }}</h5>
            <table class="table table-bordered mb-4">
                <tbody>
                    <tr>
                        <th>{{ __('Amount') }}</th>
                        <td>{{ number_format($investment->amount, 2) }}</td>
                        <th>{{ __('Expected Profit') }}</th>
                        <td>{{ number_format($investment->expected_profit, 2) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Total Repaid') }}</th>
                        <td>{{ number_format($investment->total_repaid, 2) }}</td>
                        <th>{{ __('Investment Date') }}</th>
                        <td>{{ $investment->investment_date->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Remarks') }}</th>
                        <td colspan="3">{{ $investment->remarks ?? '-' }}</td>
                    </tr>

                </tbody>
            </table>

            <!-- Repayments -->
            <h5 class="mb-3">{{ __('Repayment History') }}</h5>
            @if ($investment->repayments->count())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Notes') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($investment->repayments as $key => $repayment)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ number_format($repayment->amount, 2) }}</td>
                                <td>{{ $repayment->repayment_date->format('Y-m-d') }}</td>
                                <td>{{ $repayment->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">{{ __('No repayments found.') }}</p>
            @endif
        </div>
    </div>
@endsection
