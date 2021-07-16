<tr>
  <td colspan="2" class="space">&nbsp;</td>
  <td class="blue @if($loop->first) pddt @endif @if($loop->last) subheader @endif">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
  <td class="@if($loop->first) pddt @endif @if($loop->last) subheader @endif">{{ $payment->name }}</td>
  <td class="right @if($loop->first) pddt @endif @if($loop->last) subheader @endif"><x-price :amount="$payment->pivot->amount"></x-price></td>
  <td></td>
</tr>