@extends('website::pages.user.layout.app')
@section('title', __('Order List'))
@section('user-content')
    <div class="dashboard_content">
        <h2 class="dashboard_title">{{ __('dashboard order') }}</h2>
        <div class="dashboard_order wow fadeInUp" data-wow-duration="1.5s">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th class="serial">{{ __('Serial') }}</th>
                                    <th class="package">{{ __('Package') }}</th>
                                    <th class="date">{{ __('Purchase Date') }}</th>
                                    <th class="date">{{ __('Expired Date') }}</th>
                                    <th class="price">{{ __('Price') }}</th>
                                    <th class="action">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $index => $order)
                                    <tr>
                                        <td class="serial">{{ $orders->firstItem() + $index }}</td>
                                        <td class="package">
                                            <div>{{ $order->subscription->plan_name }}</div>
                                            @if ($order->status == 1)
                                                <small class="active text-success">{{ __('Active') }}</small>
                                            @endif
                                        </td>
                                        <td class="date">{{ formattedDate(date($order->purchase_date)) }}</td>

                                        <td class="date">{{ formattedDate(date($order->expired_date)) }}</td>

                                        <td class="price">{{ currency($order->amount_usd) }}</td>
                                        <td class="action">
                                            <a href="{{ route('website.user.invoice', $order->order_id) }}"><i
                                                    class="fal fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($orders->lastPage() > 1)
                        <div class="row mt_25">
                            <div class="col-12">
                                <div id="pagination_area">
                                    {{ $orders->links('vendor.pagination.frontend') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
