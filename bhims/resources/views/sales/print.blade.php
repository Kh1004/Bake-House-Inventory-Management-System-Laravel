@extends('layouts.print')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold">Invoice #{{ $sale->invoice_number }}</h5>
                            <small class="text-muted">{{ $sale->created_at->format('M d, Y h:i A') }}</small>
                        </div>
                        <div class="text-end">
                            @if(file_exists(public_path('images/logo.png')))
                                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-height: 50px;" class="mb-2">
                            @endif
                            <p class="mb-0 fw-bold">{{ config('app.name') }}</p>
                            <p class="mb-0 small">123 Business Street, City</p>
                            <p class="mb-0 small">Phone: +123 456 7890</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted mb-3">Bill To</h6>
                            <p class="mb-1 fw-bold">{{ $sale->customer->name ?? 'Guest' }}</p>
                            @if($sale->customer)
                                <p class="mb-1 small">{{ $sale->customer->email ?? '' }}</p>
                                <p class="mb-1 small">{{ $sale->customer->phone ?? '' }}</p>
                            @endif
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-uppercase text-muted mb-3">Invoice Details</h6>
                            <p class="mb-1"><span class="text-muted">Date:</span> {{ $sale->created_at->format('M d, Y') }}</p>
                            <p class="mb-1"><span class="text-muted">Status:</span> 
                                <span class="badge bg-{{ $sale->payment_status === 'paid' ? 'success' : 'warning' }} text-uppercase">
                                    {{ ucfirst($sale->payment_status) }}
                                </span>
                            </p>
                            <p class="mb-1"><span class="text-muted">Payment Method:</span> {{ ucfirst($sale->payment_method) }}</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered mb-4">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">#</th>
                                    <th class="text-start">Item</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $index => $item)
                                <tr>
                                    <td class="text-start">{{ $index + 1 }}</td>
                                    <td class="text-start">{{ $item->product->name ?? 'Product Deleted' }}</td>
                                    <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Subtotal</td>
                                    <td class="text-end">{{ number_format($sale->subtotal, 2) }}</td>
                                </tr>
                                @if($sale->tax_amount > 0)
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Tax</td>
                                    <td class="text-end">{{ number_format($sale->tax_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if($sale->discount_amount > 0)
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Discount</td>
                                    <td class="text-end">-{{ number_format($sale->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Total</td>
                                    <td class="text-end fw-bold">{{ number_format($sale->total, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Amount Paid</td>
                                    <td class="text-end">{{ number_format($sale->amount_paid, 2) }}</td>
                                </tr>
                                @if($sale->change_amount > 0)
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Change</td>
                                    <td class="text-end">{{ number_format($sale->change_amount, 2) }}</td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>

                    @if($sale->notes)
                    <div class="mt-4">
                        <h6 class="text-uppercase text-muted mb-2">Notes</h6>
                        <p class="mb-0">{{ $sale->notes }}</p>
                    </div>
                    @endif

                    <div class="mt-5 pt-4 border-top text-center">
                        <p class="mb-1">Thank you for your business!</p>
                        <p class="text-muted small mb-0">If you have any questions about this invoice, please contact us</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-print when the page loads
    window.onload = function() {
        setTimeout(function() {
            window.print();
            // Close the window after printing
            setTimeout(function() {
                window.close();
            }, 100);
        }, 200);
    };

    // Handle print dialog close
    window.onafterprint = function() {
        window.close();
    };
</script>
@endpush

@endsection