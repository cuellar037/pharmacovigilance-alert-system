@component('mail::message')
# Medication Reecall Alert

Dear {{ $customerName }}

We are writing to inform you that a medication you purchased has been flagged for a **recall or safety concern**.

**Medication:** {{ $medicationName }}
**Lot Number:** {{ $lotNumber }}
**Purchase Date:** {{ $purchaseDate }}

## Recommended Action

Please **stop using this medication immediately** and contact our pharmacy as soon as possible to discuss next steps, including a possible replacement or refund.

@component('mail::button', ['url' => config('app.url')])
Contact Pharmacy
@endcomponent

If you have any questions, please don't hesitate to reach out.

Thanks,<br>
{{ config('app.name') }}
@endcomponent