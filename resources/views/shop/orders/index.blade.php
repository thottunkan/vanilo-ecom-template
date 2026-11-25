@extends('shop.layout.shop-layout')

@section('title', 'My Orders')

@section('content')

<div class="container py-5">
{{-- Page Header --}}
<div class="mb-4">
    <h2 class="fw-bold">My Orders</h2>
    <p class="text-muted mb-0">
        View your order history and current order status.
    </p>
</div>

{{-- Order Tabs --}}
<ul class="nav nav-tabs mb-4" id="orderTabs" role="tablist">

    {{-- Pending --}}
    <li class="nav-item" role="presentation">
        <button
            class="nav-link active"
            id="pending-tab"
            data-bs-toggle="tab"
            data-bs-target="#pending"
            type="button"
            role="tab"
            aria-controls="pending"
            aria-selected="true"
        >
            Pending
            <span class="badge bg-warning text-dark ms-1">
                {{ $pendingOrders->count() }}
            </span>
        </button>
    </li>

    {{-- Delivered --}}
    <li class="nav-item" role="presentation">
        <button
            class="nav-link"
            id="delivered-tab"
            data-bs-toggle="tab"
            data-bs-target="#delivered"
            type="button"
            role="tab"
            aria-controls="delivered"
            aria-selected="false"
        >
            Delivered
            <span class="badge bg-success ms-1">
                {{ $deliveredOrders->count() }}
            </span>
        </button>
    </li>

    {{-- Cancelled --}}
    <li class="nav-item" role="presentation">
        <button
            class="nav-link"
            id="cancelled-tab"
            data-bs-toggle="tab"
            data-bs-target="#cancelled"
            type="button"
            role="tab"
            aria-controls="cancelled"
            aria-selected="false"
        >
            Cancelled
            <span class="badge bg-danger ms-1">
                {{ $cancelledOrders->count() }}
            </span>
        </button>
    </li>

</ul>


{{-- Tab Content --}}
<div class="tab-content" id="orderTabsContent">

    {{-- ========================= --}}
    {{-- Pending Orders --}}
    {{-- ========================= --}}
    <div
        class="tab-pane fade show active"
        id="pending"
        role="tabpanel"
        aria-labelledby="pending-tab"
    >

        @forelse($pendingOrders as $order)

            <div class="card shadow-sm mb-3">

                <div class="card-body">

                    {{-- Order Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>
                            <h6 class="fw-bold mb-1">
                                Order #{{ $order->number }}
                            </h6>

                            <small class="text-muted">
                                {{ $order->ordered_at?->format('d M Y, h:i A') }}
                            </small>
                        </div>

                        <span class="badge bg-warning text-dark">
                            Pending
                        </span>

                    </div>

                    {{-- Items --}}
                    @foreach($order->items as $item)

                        <div class="d-flex justify-content-between align-items-center border-top py-3">

                            <div>
                                <div class="fw-semibold">
                                    {{ $item->name }}
                                </div>

                                <small class="text-muted">
                                    Qty: {{ $item->quantity }}
                                </small>
                            </div>

                            <div class="fw-semibold">
                                {{ number_format($item->total, 2) }}
                                {{ $order->currency }}
                            </div>

                        </div>

                    @endforeach

                    {{-- Subtotal --}}
                    <div class="border-top pt-3 d-flex justify-content-between">
                        <span class="text-muted">Subtotal</span>
                        <span>
                            {{ number_format($order->total(), 2) }}
                            {{ $order->currency }}
                        </span>
                    </div>

                    {{-- Tax --}}
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Tax</span>
                        <span>
                            {{ number_format($order->tax_total, 2) }}
                            {{ $order->currency }}
                        </span>
                    </div>

                    {{-- Grand Total --}}
                    <div class="pt-2 d-flex justify-content-between">
                        <strong>Grand Total</strong>
                        <strong>
                            {{ number_format($order->grand_total, 2) }}
                            {{ $order->currency }}
                        </strong>
                    </div>

                </div>
            </div>

        @empty

            <div class="text-center py-5">
                <h5 class="text-muted">No pending orders</h5>
                <p class="text-muted mb-0">
                    You don't have any pending orders.
                </p>
            </div>

        @endforelse

    </div>


    {{-- ========================= --}}
    {{-- Delivered Orders --}}
    {{-- ========================= --}}
    <div
        class="tab-pane fade"
        id="delivered"
        role="tabpanel"
        aria-labelledby="delivered-tab"
    >

        @forelse($deliveredOrders as $order)

            <div class="card shadow-sm mb-3">

                <div class="card-body">

                    {{-- Order Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>
                            <h6 class="fw-bold mb-1">
                                Order #{{ $order->number }}
                            </h6>

                            <small class="text-muted">
                                {{ $order->ordered_at?->format('d M Y, h:i A') }}
                            </small>
                        </div>

                        <span class="badge bg-success">
                            Delivered
                        </span>

                    </div>

                    {{-- Items --}}
                    @foreach($order->items as $item)

                        <div class="d-flex justify-content-between align-items-center border-top py-3">

                            <div>
                                <div class="fw-semibold">
                                    {{ $item->name }}
                                </div>

                                <small class="text-muted">
                                    Qty: {{ $item->quantity }}
                                </small>
                            </div>

                            <div class="fw-semibold">
                                {{ number_format($item->total, 2) }}
                                {{ $order->currency }}
                            </div>

                        </div>

                    @endforeach

                    {{-- Subtotal --}}
                    <div class="border-top pt-3 d-flex justify-content-between">
                        <span class="text-muted">Subtotal</span>
                        <span>
                            {{ number_format($order->total(), 2) }}
                            {{ $order->currency }}
                        </span>
                    </div>

                    {{-- Tax --}}
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Tax</span>
                        <span>
                            {{ number_format($order->tax_total, 2) }}
                            {{ $order->currency }}
                        </span>
                    </div>

                    {{-- Grand Total --}}
                    <div class="pt-2 d-flex justify-content-between">
                        <strong>Grand Total</strong>
                        <strong>
                            {{ number_format($order->grand_total, 2) }}
                            {{ $order->currency }}
                        </strong>
                    </div>

                </div>
            </div>

        @empty

            <div class="text-center py-5">
                <h5 class="text-muted">No delivered orders</h5>
                <p class="text-muted mb-0">
                    You don't have any delivered orders yet.
                </p>
            </div>

        @endforelse

    </div>


    {{-- ========================= --}}
    {{-- Cancelled Orders --}}
    {{-- ========================= --}}
    <div
        class="tab-pane fade"
        id="cancelled"
        role="tabpanel"
        aria-labelledby="cancelled-tab"
    >

        @forelse($cancelledOrders as $order)

            <div class="card shadow-sm mb-3">

                <div class="card-body">

                    {{-- Order Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>
                            <h6 class="fw-bold mb-1">
                                Order #{{ $order->number }}
                            </h6>

                            <small class="text-muted">
                                {{ $order->ordered_at?->format('d M Y, h:i A') }}
                            </small>
                        </div>

                        <span class="badge bg-danger">
                            Cancelled
                        </span>

                    </div>

                    {{-- Items --}}
                    @foreach($order->items as $item)

                        <div class="d-flex justify-content-between align-items-center border-top py-3">

                            <div>
                                <div class="fw-semibold">
                                    {{ $item->name }}
                                </div>

                                <small class="text-muted">
                                    Qty: {{ $item->quantity }}
                                </small>
                            </div>

                            <div class="fw-semibold">
                                {{ number_format($item->total, 2) }}
                                {{ $order->currency }}
                            </div>

                        </div>

                    @endforeach

                    {{-- Subtotal --}}
                    <div class="border-top pt-3 d-flex justify-content-between">
                        <span class="text-muted">Subtotal</span>
                        <span>
                            {{ number_format($order->total(), 2) }}
                            {{ $order->currency }}
                        </span>
                    </div>

                    {{-- Tax --}}
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Tax</span>
                        <span>
                            {{ number_format($order->tax_total, 2) }}
                            {{ $order->currency }}
                        </span>
                    </div>

                    {{-- Grand Total --}}
                    <div class="pt-2 d-flex justify-content-between">
                        <strong>Grand Total</strong>
                        <strong>
                            {{ number_format($order->grand_total, 2) }}
                            {{ $order->currency }}
                        </strong>
                    </div>

                </div>
            </div>

        @empty

            <div class="text-center py-5">
                <h5 class="text-muted">No cancelled orders</h5>
                <p class="text-muted mb-0">
                    You don't have any cancelled orders.
                </p>
            </div>

        @endforelse

    </div>

</div>

</div>

@endsection