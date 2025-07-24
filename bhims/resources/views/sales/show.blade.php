@extends('layouts.app')

@section('content')
@push('styles')
<style>
    :root {
        --primary-color: #4361ee;
        --primary-hover: #3a56d4;
        --secondary-color: #6c757d;
        --success-color: #198754;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --border-radius: 0.5rem;
        --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        
        /* Dark mode colors */
        --dark-bg: #1a1a1a;
        --dark-card-bg: #2d2d2d;
        --dark-text: #e0e0e0;
        --dark-border: #444;
        --dark-hover: #3a3a3a;
    }
    
    .btn {
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-size: 0.75rem;
        transition: all 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-sm {
        padding: 0.4rem 0.8rem;
        border-radius: var(--border-radius);
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
    }
    
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
    }
    
    .btn-outline-secondary {
        color: var(--secondary-color);
        border-color: var(--secondary-color);
    }
    
    .btn-outline-secondary:hover {
        background-color: var(--secondary-color);
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-outline-danger {
        color: var(--danger-color);
        border-color: var(--danger-color);
    }
    
    .btn-outline-danger:hover {
        background-color: var(--danger-color);
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-outline-info {
        color: #0dcaf0;
        border-color: #0dcaf0;
    }
    
    .btn-outline-info:hover {
        background-color: #0dcaf0;
        color: white;
        transform: translateY(-1px);
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
        padding: 0.4em 0.75em;
        border-radius: 50rem;
        font-size: 0.7rem;
        text-transform: uppercase;
    }
    
    .card {
        border: none;
        box-shadow: var(--box-shadow);
        border-radius: var(--border-radius);
        overflow: hidden;
    }
    
    .card-header {
        background-color: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem 1.5rem;
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table > :not(:first-child) {
        border-top: 2px solid #f8f9fa;
    }
    
    .table > thead > tr > th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        padding: 0.75rem 1rem;
        background-color: #f8f9fa;
        border-bottom-width: 1px;
    }
    
    .table > tbody > tr > td {
        padding: 1rem;
        vertical-align: middle;
        border-color: #f1f3f7;
    }
    
    .table > tbody > tr:hover > td {
        background-color: rgba(67, 97, 238, 0.03);
    }
    
    .table > tfoot > tr > td {
        font-weight: 500;
        padding: 0.75rem 1rem;
        border-color: #f1f3f7;
    }
    
    .table > tfoot > tr:last-child > td {
        border-bottom: none;
        font-size: 1.1rem;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    /* Print Styles */
    /* Dark mode styles */
    @media (prefers-color-scheme: dark) {
        body {
            background-color: var(--dark-bg);
            color: var(--dark-text);
        }
        
        /* Make total amount white in dark mode */
        .text-primary, h4.text-primary, h4.text-primary .text-primary {
            color: white !important;
        }
        
        .card, .card-header, .card-footer {
            background-color: var(--dark-card-bg);
            border-color: var(--dark-border);
            color: var(--dark-text);
        }
        
        .table {
            color: var(--dark-text);
        }
        
        .table thead th {
            background-color: #333;
            color: var(--dark-text);
            border-color: var(--dark-border);
        }
        
        .table tbody td {
            border-color: var(--dark-border);
        }
        
        .table-hover tbody tr:hover {
            background-color: var(--dark-hover);
        }
        
        .bg-light, .table-light, .table-light > th, .table-light > td {
            background-color: #2a2a2a !important;
            color: var(--dark-text);
        }
        
        .text-muted {
            color: #a0a0a0 !important;
        }
        
        .border, .border-top, .border-end, .border-bottom, .border-start,
        .table-bordered, .table-bordered th, .table-bordered td {
            border-color: var(--dark-border) !important;
        }
        
        .bg-white {
            background-color: var(--dark-card-bg) !important;
        }
        
        .bg-light.bg-opacity-50, .bg-opacity-50 {
            background-color: rgba(45, 45, 45, 0.5) !important;
        }
        
        .text-dark {
            color: var(--dark-text) !important;
        }
        
        .badge.bg-light {
            background-color: #444 !important;
            color: var(--dark-text) !important;
        }
    }
    
    @media print {
        @page {
            size: A4;
            margin: 15mm 10mm;
        }
        
        body {
            background: white !important;
            color: #000 !important;
            font-size: 12pt;
            line-height: 1.4;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .container {
            max-width: 100% !important;
            padding: 0 !important;
        }
        
        /* Hide elements not needed in print */
        .d-print-none,
        .no-print {
            display: none !important;
        }
        
        /* Ensure the card takes full width */
        .card {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
        }
        
        /* Improve table layout */
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        
        .table th,
        .table td {
            padding: 8px !important;
            border: 1px solid #dee2e6 !important;
        }
        
        /* Ensure text is black */
        body, h1, h2, h3, h4, h5, h6, p, span, div {
            color: #000 !important;
        }
        
        /* Add page break after the invoice */
        .page-break {
            page-break-after: always;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
            margin: 0 !important;
        }
        
        .card-header {
            padding: 1rem 0 !important;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #eee !important;
        }
        
        .card-body {
            padding: 0 !important;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th, .table td {
            padding: 0.5rem !important;
            border: 1px solid #dee2e6 !important;
        }
        
        .table thead th {
            background-color: #f8f9fa !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact;
        }
        
        .text-muted {
            color: #6c757d !important;
        }
        
        .text-end {
            text-align: right !important;
        }
        
        .text-center {
            text-align: center !important;
        }
        
        .mb-4 {
            margin-bottom: 1.5rem !important;
        }
        
        .mt-4 {
            margin-top: 1.5rem !important;
        }
        
        .p-4 {
            padding: 1.5rem !important;
        }
        
        .border {
            border: 1px solid #dee2e6 !important;
        }
        
        .rounded-3 {
            border-radius: 0.5rem !important;
        }
        
        /* Invoice header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        
        /* Hide elements not needed in print */
        .no-print {
            display: none !important;
        }
        
        /* Ensure table headers are visible */
        thead {
            display: table-header-group;
        }
        
        /* Prevent page breaks inside rows */
        tr {
            page-break-inside: avoid;
        }
    }
</style>
@endpush


<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                            <i class="fas fa-receipt text-primary fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Sale #{{ $sale->invoice_number }}</h5>
                            <small class="text-muted">{{ $sale->created_at->format('M d, Y h:i A') }}</small>
                        </div>
                    </div>
                    <div class="mt-2 mt-md-0 d-flex flex-wrap gap-2">
                        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Sales
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="bg-light bg-opacity-50 p-4 rounded-3 h-100 border-start border-4 border-primary">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                        <i class="fas fa-user-circle text-primary"></i>
                                    </div>
                                    <h6 class="mb-0 text-uppercase text-primary fw-bold">Customer Information</h6>
                                </div>
                                <div class="ps-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="text-muted me-2"><i class="fas fa-user"></i></span>
                                        <span class="text-dark">{{ $sale->customer->name ?? 'Guest' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="text-muted me-2"><i class="fas fa-envelope"></i></span>
                                        <a href="mailto:{{ $sale->customer->email ?? '#' }}" class="text-decoration-none">
                                            {{ $sale->customer->email ?? 'N/A' }}
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-muted me-2"><i class="fas fa-phone"></i></span>
                                        <a href="tel:{{ $sale->customer->phone ?? '' }}" class="text-decoration-none">
                                            {{ $sale->customer->phone ?? 'N/A' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light bg-opacity-50 p-4 rounded-3 h-100 border-start border-4 border-info">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-info bg-opacity-10 p-2 rounded-circle me-3">
                                        <i class="fas fa-info-circle text-info"></i>
                                    </div>
                                    <h6 class="mb-0 text-uppercase text-info fw-bold">Sale Information</h6>
                                </div>
                                <div class="ps-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Date</span>
                                        <span class="fw-medium">{{ $sale->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Status</span>
                                        <span class="badge rounded-pill bg-{{ $sale->payment_status === 'paid' ? 'success' : ($sale->payment_status === 'pending' ? 'warning' : 'danger') }} px-3 py-1">
                                            <i class="fas fa-{{ $sale->payment_status === 'paid' ? 'check-circle' : ($sale->payment_status === 'pending' ? 'clock' : 'times-circle') }} me-1"></i>
                                            {{ ucfirst($sale->payment_status) }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Payment Method</span>
                                        <span class="fw-medium">{{ ucfirst($sale->payment_method) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Sold By</span>
                                        <span class="fw-medium">{{ $sale->user->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-bold text-uppercase text-muted">Order Items</h6>
                            <span class="badge bg-light text-dark">{{ $sale->items->count() }} items</span>
                        </div>
                        <div class="border rounded-3 overflow-hidden">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center text-uppercase text-muted small fw-bold">#</th>
                                        <th width="45%" class="ps-3 text-uppercase text-muted small fw-bold">Product</th>
                                        <th width="15%" class="text-end text-uppercase text-muted small fw-bold">Unit Price</th>
                                        <th width="15%" class="text-center text-uppercase text-muted small fw-bold">Quantity</th>
                                        <th width="20%" class="text-end pe-3 text-uppercase text-muted small fw-bold">Total</th>
                                    </tr>
                                </thead>
                            <tbody>
                                @foreach($sale->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->product->name ?? 'Product Deleted' }}</td>
                                    <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Subtotal</strong></td>
                                    <td class="text-end">{{ number_format($sale->subtotal, 2) }}</td>
                                </tr>
                                <tr @if($sale->tax_amount == 0) class="d-none" @endif>
                                    <td colspan="4" class="text-end"><strong>Tax</strong></td>
                                    <td class="text-end">
                                        @if($sale->tax_amount > 0)
                                            Rs. {{ number_format($sale->tax_amount, 2) }}
                                        @else
                                            Free
                                        @endif
                                    </td>
                                </tr>
                                <tr @if($sale->discount_amount == 0) class="d-none" @endif>
                                    <td colspan="4" class="text-end"><strong>Discount</strong></td>
                                    <td class="text-end">
                                        @if($sale->discount_amount > 0)
                                            -Rs. {{ number_format($sale->discount_amount, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr class="border-top">
                                    <td colspan="4" class="text-end">
                                        <h5 class="mb-0 fw-bold">Total Amount</h5>
                                        <small class="text-muted">Including tax and discounts</small>
                                    </td>
                                    <td class="text-end">
                                        <h4 class="mb-0 fw-bold text-primary">
                                            Rs. {{ number_format($sale->total, 2) }}
                                        </h4>
                                        @if($sale->tax_amount > 0 || $sale->discount_amount > 0)
                                        <small class="text-muted d-block">
                                            Subtotal: Rs. {{ number_format($sale->subtotal, 2) }}
                                            @if($sale->tax_amount > 0) • Tax: Rs. {{ number_format($sale->tax_amount, 2) }}@endif
                                            @if($sale->discount_amount > 0) • Discount: -Rs. {{ number_format($sale->discount_amount, 2) }}@endif
                                        </small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Amount Paid</strong></td>
                                    <td class="text-end">Rs. {{ number_format($sale->amount_paid, 2) }}</td>
                                </tr>
                                @if($sale->change_amount > 0)
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Change</strong></td>
                                    <td class="text-end">Rs. {{ number_format($sale->change_amount, 2) }}</td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>

                    @if($sale->notes)
                    <div class="mt-4">
                        <h6>Notes</h6>
                        <p class="mb-0">{{ $sale->notes }}</p>
                    </div>
                    @endif
                </div>

                <div class="card-footer bg-white border-top py-3 d-print-none">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                        <div class="d-flex flex-wrap gap-2 mb-2 mb-sm-0">
                            <a href="{{ route('sales.print', $sale) }}" target="_blank" class="btn btn-outline-primary btn-sm d-flex align-items-center">
                                <i class="fas fa-print me-1"></i> Print Receipt
                            </a>
                            <form action="{{ route('sales.email', $sale) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-info btn-sm d-flex align-items-center">
                                    <i class="fas fa-envelope me-1"></i> Email Receipt
                                </button>
                            </form>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this sale? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center">
                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Add animation to buttons
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
            
            button.addEventListener('mousedown', function() {
                this.style.transform = 'translateY(1px)';
            });
            
            button.addEventListener('mouseup', function() {
                this.style.transform = 'translateY(-2px)';
            });
        });
        
        // Add confirmation for delete
        const deleteForms = document.querySelectorAll('form[action*="/delete"]');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to delete this sale? This action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
