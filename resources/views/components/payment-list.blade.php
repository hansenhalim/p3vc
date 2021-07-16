@foreach ($payments as $payment)
  <x-payment-item :payment="$payment" :loop="$loop"></x-payment-item>
@endforeach
