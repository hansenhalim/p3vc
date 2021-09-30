@extends('dashboard.base')

@section('content')
  <div
    class="modal fade"
    id="paymentModal"
    tabindex="-1"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5
            class="modal-title"
            id="exampleModalCenterTitle"
          >Add Payments</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
          >
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <div class="col-sm-4 mb-3 mb-sm-0">
              <select
                class="custom-select"
                id="pymnt-mthd"
              ></select>
            </div>
            <div class="col">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">Rp.</span>
                </div>
                <input
                  id="pymnt-amnt"
                  type="text"
                  class="form-control"
                  placeholder="Amount"
                >
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
          >Close</button>
          <button
            type="button"
            class="btn btn-primary"
            onclick="addPayments(this)"
          >OK&nbsp;&check;</button>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="fade-in">
      <a
        class="btn btn-sm btn-secondary font-weight-bold mb-2"
        href="{{ route('customers.index') }}"
      ><i class="cil-chevron-circle-left-alt align-text-top"></i> Return</a>
      <div class="row">
        <div class="col-xl-4 col-md-6">
          <div class="card">
            <x-card-header>Customer Show</x-card-header>
            <div class="card-body">
              <x-alert></x-alert>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">CIF</label>
                <div class="col">
                  <input
                    class="form-control"
                    type="text"
                    value="{{ $customer->id }}"
                    disabled
                  >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Name</label>
                <div class="col">
                  <input
                    class="form-control"
                    type="text"
                    value="{{ $customer->name }}"
                    disabled
                  >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Phone</label>
                <div class="col">
                  <input
                    class="form-control"
                    type="text"
                    value="{{ $customer->phone_number }}"
                    disabled
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-9">
          <div class="card">
            <x-card-header>Unit List</x-card-header>
            <div class="d-flex justify-content-end mt-4 mx-4">
              <form>
                <label for="add-month">Add</label>
                <input
                  onchange="submit()"
                  type="number"
                  name="add-month"
                  id="add-month"
                  min="0"
                  max="99"
                  value="{{ request('add-month', 0) }}"
                >
                <label for="add-month">month(s) from now</label>
              </form>
            </div>
            <form
              action="{{ route('transactions.store') }}"
              method="post"
            >
              @csrf
              <div class="card-body">
                <table class="table table-responsive-md">
                  @forelse ($units as $unit)
                    <thead class="thead-dark">
                      <tr>
                        <th></th>
                        <th class="text-center">#</th>
                        <th>Name</th>
                        <th>Customer</th>
                        <th colspan="2">Cluster</th>
                        <th class="text-right">Area&nbsp;(m<sup>2</sup>)</th>
                        <th class="text-right">Debt</th>
                        <th class="text-right">Balance</th>
                        <th class="text-right">Credit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="table-light">
                        <th class="text-right"><input
                            type="checkbox"
                            class="unt-chck"
                            @if (!Auth::user()->hasRole('operator')) disabled @endif
                          ></th>
                        <th class="text-center">{{ $loop->iteration }}</th>
                        <th>{{ $unit->name }}</th>
                        <th>{{ $customer->name }}</th>
                        <th colspan="2">{{ $unit->cluster->name }}</th>
                        <td class="text-right">{{ $unit->area_sqm }}</td>
                        <td class="text-right">
                          @if ($unit->debt == 0)
                            {{ number_format($unit->debt) }}
                          @else
                            <a
                              href="{{ route('units.debt', $unit) }}"
                              style="color:red;"
                            >{{ number_format($unit->debt) }}</a>
                          @endif
                        </td>
                        <td class="text-right">{{ number_format($unit->balance) }}</td>
                        <td class="text-right">
                          {{ number_format($unit->cluster->cost * ($unit->cluster->per === 'mth' ?: $unit->area_sqm)) }}
                        </td>
                        <input
                          type="hidden"
                          name="units[{{ $loop->index }}][unit_id]"
                          value="{{ $unit->id }}"
                        >
                        <input
                          type="hidden"
                          name="units[{{ $loop->index }}][unit_previous_id]"
                          value="{{ $unit->previous_id }}"
                        >
                      </tr>
                    </tbody>
                    <thead class="thead-light">
                      <tr>
                        <th colspan="6"></th>
                        <th class="text-center">#</th>
                        <th>Period</th>
                        <th class="text-right">Credit</th>
                        <th class="text-right">Fine</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($unit->months as $month)
                        <tr
                          class="mth"
                          id="{{ $unit->previous_id . $month['period']->format('my') }}"
                        >
                          <th colspan="5"></th>
                          <th class="text-right"><input
                              type="checkbox"
                              class="mth-chck"
                              @if (!Auth::user()->hasRole('operator')) disabled @endif
                            ></th>
                          <th class="text-center">{{ $loop->iteration }}</th>
                          <td>{{ $month['period']->formatLocalized('%B %Y') }}</td>
                          <td class="text-right">{{ number_format($month['credit']) }}</td>
                          <td class="text-right">{{ number_format($month['fine']) }}</td>
                          <input
                            disabled
                            type="hidden"
                            class="mth-hdn"
                            name="units[{{ $loop->parent->index }}][months][{{ $loop->index }}][period]"
                            value="{{ $month['period']->format('Y-m-d') }}"
                          >
                          <input
                            disabled
                            type="hidden"
                            class="mth-hdn"
                            name="units[{{ $loop->parent->index }}][months][{{ $loop->index }}][payments][1][payment_id]"
                            value="1"
                          >
                          <input
                            disabled
                            type="hidden"
                            class="mth-hdn"
                            name="units[{{ $loop->parent->index }}][months][{{ $loop->index }}][payments][1][amount]"
                            value="{{ $month['credit'] }}"
                          >
                          <input
                            disabled
                            type="hidden"
                            class="mth-hdn"
                            name="units[{{ $loop->parent->index }}][months][{{ $loop->index }}][payments][2][payment_id]"
                            value="2"
                          >
                          <input
                            disabled
                            type="hidden"
                            class="mth-hdn"
                            name="units[{{ $loop->parent->index }}][months][{{ $loop->index }}][payments][2][amount]"
                            value="{{ $month['fine'] }}"
                          >
                        </tr>
                        <tr
                          class="d-none table-secondary table-sm"
                          data-month="{{ $unit->previous_id . $month['period']->format('my') }}"
                        >
                          <th
                            colspan="9"
                            class="text-right"
                          >TAGIHAN</th>
                          <th class="text-right">{{ number_format($month['credit'] + $month['fine']) }}</th>
                        </tr>
                        {{-- <tr class="table-secondary table-borderless table-sm" data-month="{{ $unit->previous_id.$month['period']->format('my') }}">
                            <th colspan="9" class="text-right"><i class="cil-trash text-danger" onclick="removePayment(this)" style="cursor: pointer;"></i>&nbsp;OTHER</th>
                            <th class="text-right">{{ number_format('250000') }}</th>
                            <input 
                              type="hidden"
                              class="mth-hdn" 
                              name="units[{{ $loop->parent->index }}][months][{{ $loop->index }}][payments][2][payment_id]" 
                              value="4"
                            >
                            <input 
                              type="hidden"
                              class="mth-hdn" 
                              name="units[{{ $loop->parent->index }}][months][{{ $loop->index }}][payments][2][amount]" 
                              value="250000"
                            >
                          </tr> --}}
                        <tr
                          class="d-none table-secondary table-borderless table-sm"
                          data-month="{{ $unit->previous_id . $month['period']->format('my') }}"
                        >
                          <th
                            colspan="10"
                            class="text-right"
                          >
                            <button
                              data-toggle="modal"
                              data-target="#paymentModal"
                              data-payments="[@foreach ($payments as $payment) {{ $payment->id }}      @if (!$loop->last), @endif @endforeach]"
                              type="button"
                              class="btn btn-sm btn-square btn-outline-success"
                            ><i class="cil-wallet"></i>&nbsp;Add Payments</button>
                          </th>
                        </tr>
                        <tr
                          class="d-none table-secondary table-borderless table-sm"
                          data-month="{{ $unit->previous_id . $month['period']->format('my') }}"
                          data-parent-index="{{ $loop->parent->index }}"
                          data-index="{{ $loop->index }}"
                        >
                          <th
                            colspan="9"
                            class="text-right"
                          >Sisa</th>
                          <th class="text-right text-success">{{ number_format($month['credit'] + $month['fine']) }}
                          </th>
                        </tr>
                      @empty
                        <tr class="table-success">
                          <td
                            colspan="10"
                            style="text-align: center"
                          >No tunggak-tunggak club :)</td>
                        </tr>
                      @endforelse
                    </tbody>
                  @empty
                    <tr class="table-secondary">
                      <td
                        colspan="9"
                        style="text-align: center"
                      >Oops, nothing found here :(</td>
                    </tr>
                  @endforelse
                </table>
              </div>
              <div class="card-footer d-flex justify-content-end">
                @if (Auth::user()->hasRole('operator'))
                  <button
                    id="sbmt-btn"
                    type="submit"
                    class="btn btn-primary btn-secondary"
                    disabled
                  ></i>Submit</button>
                @else
                  <button
                    id="sbmt-btn-fk"
                    type="button"
                    class="btn btn-primary"
                    onclick="alert('Because you are not an operator')"
                  ></i>Just lookin' around</button>
                @endif
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


@endsection

@section('javascript')

  <script>
    submitBtn = document.getElementById('sbmt-btn')
    unitChecks = document.getElementsByClassName('unt-chck')
    monthChecks = document.getElementsByClassName('mth-chck')
    paymentMethod = document.getElementById('pymnt-mthd')
    paymentAmount = document.getElementById('pymnt-amnt')
    payments = {!! json_encode($payments, JSON_HEX_TAG) !!}

    //wait till page loaded :)
    document.addEventListener("DOMContentLoaded", function(event) {
      paymentModal = new coreui.Modal(document.getElementById('paymentModal'))
    })

    function validateSubmission(enable) {
      if (enable) {
        submitBtn.disabled = false
        submitBtn.classList.remove('btn-secondary')
      } else {
        submitBtn.disabled = true
        submitBtn.classList.add('btn-secondary')
      }
    }

    function echo() {
      console.log('echo function executed :)')
    }

    function addPayments(e) {
      method = parseInt(paymentMethod.value)
      amount = parseInt(paymentAmount.value)

      if (!(Number.isInteger(method) && Number.isInteger(amount))) return false
      paymentModal.hide()

      dataMonth = e.getAttribute('data-month')
      remainder = document.querySelectorAll('[data-month="' + dataMonth + '"]')
      remainder = remainder[remainder.length - 1]
      paymentButton = remainder.previousElementSibling
      paymentButtonAdd = paymentButton.getElementsByTagName('button')[0]
      paymentIds = JSON.parse(paymentButtonAdd.getAttribute('data-payments'))
      paymentIds = paymentIds.filter(paymentId => paymentId !== method)
      paymentButtonAdd.setAttribute('data-payments', JSON.stringify(paymentIds))

      tr = document.createElement('TR')
      tr.setAttribute('class', 'table-secondary table-borderless table-sm')
      tr.setAttribute('data-month', dataMonth)
      th = document.createElement('th')
      th.setAttribute('colspan', '9')
      th.setAttribute('class', 'text-right')
      th.innerHTML = '<i class="cil-trash text-danger" onclick="removePayment(this)" style="cursor: pointer;"></i>&nbsp;'
      th.innerHTML += payments.filter(payment => payment.id === method)[0].name
      tr.appendChild(th)
      th = document.createElement('TH')
      th.setAttribute('class', 'text-right')
      th.innerHTML = new Intl.NumberFormat().format(amount)
      tr.appendChild(th)
      input = document.createElement('INPUT')
      input.setAttribute('type', 'hidden')
      input.setAttribute('class', 'mth-hdn')
      input.setAttribute('name', 'units[' + remainder.getAttribute('data-parent-index') + '][months][' + remainder
        .getAttribute('data-index') + '][payments][' + method + '][payment_id]')
      input.setAttribute('value', method)
      tr.appendChild(input)
      input = document.createElement('INPUT')
      input.setAttribute('type', 'hidden')
      input.setAttribute('class', 'mth-hdn')
      input.setAttribute('name', 'units[' + remainder.getAttribute('data-parent-index') + '][months][' + remainder
        .getAttribute('data-index') + '][payments][' + method + '][amount]')
      input.setAttribute('value', amount)
      tr.appendChild(input)
      remainder.parentNode.insertBefore(tr, paymentButton)
      updateRemainder()
    }

    function removePayment(e) {
      paymentId = parseInt(e.parentNode.parentNode.getElementsByClassName('mth-hdn')[0].value)
      dataMonth = e.parentNode.parentNode.getAttribute('data-month')
      remainder = document.querySelectorAll('[data-month="' + dataMonth + '"]')
      paymentButton = remainder[remainder.length - 2].getElementsByTagName('button')[0]
      paymentIds = JSON.parse(paymentButton.getAttribute('data-payments'))
      paymentIds.push(paymentId)
      paymentIds.sort(function(a, b) {
        return a - b
      });
      paymentButton.setAttribute('data-payments', JSON.stringify(paymentIds))
      e.parentNode.parentNode.remove()
      updateRemainder()
    }

    function togglePayment(month, show = false) {
      for (hidden of month.getElementsByClassName('mth-hdn')) hidden.disabled = !show
      paymentSections = document.querySelectorAll('[data-month="' + month.getAttribute('id') + '"]')
      for (paymentSection of paymentSections) {
        show ? paymentSection.classList.remove('d-none') : paymentSection.classList.add('d-none')
        for (payment of paymentSection.getElementsByTagName('INPUT')) payment.disabled = !show
      }
      updateRemainder()
    }

    function updateRemainder() {
      billLessThanOrEqualToPaid = 0
      monthCheckedCount = 0
      for (monthCheck of monthChecks) {
        if (monthCheck.checked) {
          monthCheckedCount++
          month = monthCheck.parentNode.parentNode
          bill = parseInt(month.nextElementSibling.children[1].innerHTML.replace(/,/g, ''))
          paid = 0
          paymentDetails = document.querySelectorAll('[data-month="' + month.getAttribute('id') + '"]')
          remainder = paymentDetails[paymentDetails.length - 1]

          for (paymentDetail of paymentDetails)
            if (paymentDetail.getElementsByTagName('input').length) paid += parseInt(paymentDetail.getElementsByTagName(
              'input')[0].parentNode.lastElementChild.value)

          remainder.lastElementChild.innerHTML = new Intl.NumberFormat().format(Math.abs(bill - paid))
          bill <= paid ? remainder.lastElementChild.classList.remove('text-danger') : remainder.lastElementChild.classList
            .add('text-danger')
          billLessThanOrEqualToPaid += bill <= paid ? 0 : 1
        }
      }
      if (!monthCheckedCount) {
        validateSubmission(billLessThanOrEqualToPaid)
        return
      }
      validateSubmission(!billLessThanOrEqualToPaid)
    }

    paymentModal.addEventListener('show.coreui.modal', (event) => {
      paymentIds = JSON.parse(event.relatedTarget.getAttribute('data-payments'))
      paymentMethod.innerHTML = '<option disabled selected>[CHOOSE]</option>'
      paymentAmount.value = ''
      for (paymentId of paymentIds) {
        option = document.createElement('option')
        option.innerHTML = payments.filter(payment => payment.id === paymentId)[0].name
        option.value = payments.filter(payment => payment.id === paymentId)[0].id
        paymentMethod.appendChild(option)
      }
      event.target.querySelector('[onclick="addPayments(this)"]').setAttribute('data-month', event.relatedTarget
        .parentNode.parentNode.getAttribute('data-month'))
    })

    for (unitCheck of unitChecks) {
      unitCheck.addEventListener('change', function() {
        unitCheckWrapper = this.parentNode
        unit = unitCheckWrapper.parentNode.parentNode
        monthsHeader = unit.nextElementSibling
        months = monthsHeader.nextElementSibling
        unitMonthChecks = months.getElementsByClassName('mth-chck')

        for (unitMonthCheck of unitMonthChecks) unitMonthCheck.checked = this.checked

        months = months.getElementsByClassName('mth')
        for (month of months) togglePayment(month, this.checked)

      })
    }

    for (monthCheck of monthChecks) {
      monthCheck.addEventListener('change', function() {
        monthCheckWrapper = this.parentNode
        month = monthCheckWrapper.parentNode
        months = month.parentNode
        unitMonthChecks = months.getElementsByClassName('mth-chck')
        monthsHeader = months.previousElementSibling
        monthUnit = monthsHeader.previousElementSibling
        monthUnitCheck = monthUnit.getElementsByClassName('unt-chck')[0]

        checkFlag = true
        for (unitMonthCheck of unitMonthChecks)
          if (checkFlag) checkFlag = unitMonthCheck.checked
        monthUnitCheck.checked = checkFlag

        togglePayment(month, this.checked)

      })
    }
  </script>

@endsection
