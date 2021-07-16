@if ($total)
  <x-table-empty-space></x-table-empty-space>
  <x-payment-header name="RINCIAN {{ strtoupper($name) }}"></x-payment-header>
  <x-payment-list :payments="$payments"></x-payment-list>
  <x-payment-total name="Total {{ $name }}" :amount="$total" class="pddt"></x-payment-total>
@endif
