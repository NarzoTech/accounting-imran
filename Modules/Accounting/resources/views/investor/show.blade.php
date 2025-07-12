@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Investor Details') }}</title>
@endsection
@section('admin-content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="section_title">{{ $investor->name }}</h4>
            <div>
                <a href="{{ route('admin.investor.edit', $investor->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.repayment.create', ['investor_id' => $investor->id]) }}"
                    class="btn btn-success btn-sm">
                    <i class="fas fa-dollar-sign"></i> Add Repayment
                </a>
            </div>
        </div>
        <div class="card-body">
            <h5 class="mb-3">{{ __('Investor Details') }}</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>{{ __('Name') }}:</strong> {{ $investor->name }}</p>
                    <p><strong>{{ __('Email') }}:</strong> {{ $investor->email ?? '-' }}</p>
                    <p><strong>{{ __('Phone') }}:</strong> {{ $investor->phone ?? '-' }}</p>
                    <p><strong>{{ __('Address') }}:</strong> {{ $investor->address ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>{{ __('Total Investment') }}:</strong> {{ number_format($totalInvested, 2) }}</p>
                    <p><strong>{{ __('Total Repaid') }}:</strong> {{ number_format($totalRepaid, 2) }}</p>
                    <p><strong>{{ __('Due Amount') }}:</strong> {{ number_format($totalInvested - $totalRepaid, 2) }}</p>
                    <p><strong>{{ __('Notes') }}:</strong> {{ $investor->notes ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>{{ __('Investments') }}</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>Investment Date</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($investor->investments as $investment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ number_format($investment->amount, 2) }}</td>
                            <td>{{ $investment->investment_date }}</td>
                            <td>{{ $investment->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No investments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>{{ __('Repayments') }}</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>Repayment Date</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($investor->repayments as $repayment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ number_format($repayment->amount, 2) }}</td>
                            <td>{{ $repayment->repayment_date }}</td>
                            <td>{{ $repayment->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No repayments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
