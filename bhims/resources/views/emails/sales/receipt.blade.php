@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    # Thank you for your purchase!

    Here's your receipt for Sale #{{ $sale->invoice_number }}

    **Date:** {{ $sale->created_at->format('M d, Y h:i A') }}
    
    **Status:** 
    <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; background-color: {{ $sale->payment_status === 'paid' ? '#10b981' : ($sale->payment_status === 'pending' ? '#f59e0b' : '#ef4444') }}; color: white;">
        {{ ucfirst($sale->payment_status) }}
    </span>

    @component('mail::table')
        | Product | Qty | Price | Total |
        |:--------|:---:|------:|------:|
        @foreach($sale->items as $item)
            | {{ $item->product->name ?? 'Product Deleted' }} | {{ $item->quantity }} | {{ number_format($item->unit_price, 2) }} | {{ number_format($item->total_price, 2) }} |
        @endforeach
        | &nbsp; |  | **Subtotal** | {{ number_format($sale->subtotal, 2) }} |
        @if($sale->tax_amount > 0)
            | &nbsp; |  | **Tax** | {{ number_format($sale->tax_amount, 2) }} |
        @endif
        @if($sale->discount_amount > 0)
            | &nbsp; |  | **Discount** | -{{ number_format($sale->discount_amount, 2) }} |
        @endif
        | &nbsp; |  | **Total** | **{{ number_format($sale->total, 2) }}** |
    @endcomponent

    **Payment Method:** {{ ucfirst($sale->payment_method) }}
    
    **Amount Paid:** {{ number_format($sale->amount_paid, 2) }}
    
    @if($sale->change_amount > 0)
        **Change:** {{ number_format($sale->change_amount, 2) }}
    @endif

    @if($sale->notes)
        **Notes:**
        {{ $sale->notes }}
    @endif

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
