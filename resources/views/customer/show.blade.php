@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-4 col-md-6">
          <a class="btn btn-link mb-2" href="{{ route('customers.index') }}">&lt;&lt; Return</a>
          <div class="card">
            <div class="card-header">Customer Show</div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success">
                  {!! session('status') !!}
                </div>
              @endif
              <div class="form-group row">
                <label class="col-md-3 col-form-label">CIF</label>
                <div class="col">
                  <input class="form-control" type="text" value="{{ $customer->id }}" disabled>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Name</label>
                <div class="col">
                  <input class="form-control" type="text" value="{{ $customer->name }}" disabled>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Phone</label>
                <div class="col">
                  <input class="form-control" type="text" value="{{ $customer->phone_number }}" disabled>
                  @error('phone_number')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-8">
          <div class="card">
            <div class="card-header">Unit List</div>
            <div class="card-body">
              <table class="table table-responsive-md">
                @foreach ($units as $unit)
                  <thead class="thead-dark">
                    <tr>
                      <th></th>
                      <th class="text-center">#</th>
                      <th>Name</th>
                      <th>Customer</th>
                      <th colspan="2">Cluster</th>
                      <th class="text-right">Area&nbsp;(m<sup>2</sup>)</th>
                      <th class="text-right">Balance</th>
                      <th class="text-right">Credit</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="table-light">
                      <th class="text-right"><input type="checkbox" class="unt-chck"></th>
                      <th class="text-center">{{ $loop->iteration }}</th>
                      <th><a class="text-dark" href="{{ route('units.show', ['unit' => $unit->id]) }}">{{ $unit->name }}</a></th>
                      <th><a class="text-dark" href="{{ route('customers.show', ['customer' => $unit->customer->id]) }}">{{ $unit->customer->name }}</a></th>
                      <th colspan="2"><a class="text-dark" href="{{ route('clusters.show', ['cluster' => $unit->cluster->id]) }}">{{ $unit->cluster->name }}</a></th>
                      <td class="text-right">{{ $unit->area_sqm }}</td>
                      <td class="text-right">{{ $unit->balance }}</td>
                      <td class="text-right">{{ number_format($unit->cluster->prices->last()->cost * ($unit->cluster->prices->last()->per == 'sqm' ? $unit->area_sqm : 1)) }}</td>
                    </tr>
                    <thead class="thead-light">
                      <tr>
                        <th colspan="5"></th>
                        <th class="text-center">#</th>
                        <th>Periode</th>
                        <th class="text-right">Iuran</th>
                        <th class="text-right">Denda</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($unit->months as $month)
                        <tr class="mth">
                          <th colspan="4"></th>
                          <th class="text-right"><input type="checkbox" class="mth-chck"></th>
                          <th class="text-center">{{ $loop->iteration }}</th>
                          <td>{{ $month['period']->format('M Y') }}</td>
                          <td class="text-right">{{ number_format($month['credit']) }}</td>
                          <td class="text-right">{{ number_format($month['fine']) }}</td>
                        </tr>
                        <tr class="pymnt-sctn table-secondary">
                          <th colspan="8" class="text-right">Tagihan</th>
                          <th class="text-right">{{ number_format($month['credit'] + $month['fine']) }}</th>
                        </tr>
                        <tr class="pymnt-sctn table-secondary table-borderless">
                          <th colspan="10" class="text-right"><button class="btn btn-square btn-outline-success"><i class="cil-wallet"></i>&nbsp;Add Payments</button></th>
                        </tr>
                        <tr class="pymnt-sctn table-secondary table-borderless">
                          <th colspan="8" class="text-right">Sisa</th>
                          <th class="text-right text-danger">{{ number_format($month['credit'] + $month['fine']) }}</th>
                        </tr>
                      @empty
                        <tr class="table-success">
                          <td colspan="10" style="text-align: center">No tunggak-tunggak club :)</td>
                        </tr>
                      @endforelse
                      <tr style="background: white">
                        <td colspan="10"></td>
                      </tr>
                    </tbody>
                  </tbody>
                @endforeach
              </table>
            </div>
            <div class="card-footer d-flex justify-content-between">
              <button id="dbg-btn" type="button"></i>debug-btn</button>
              <button id="sbmt-btn" type="submit" class="btn btn-primary btn-secondary" disabled></i>Submit</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    submitBtn = document.getElementById('sbmt-btn')
    debugBtn = document.getElementById('dbg-btn')
    unitChecks = document.getElementsByClassName('unt-chck')
    monthChecks = document.getElementsByClassName('mth-chck')
    paymentSection = document.getElementsByClassName('pymnt-sctn')

    function validateSubmission() {
      submitBtn.disabled = !submitBtn.disabled
      submitBtn.classList.toggle('btn-secondary')
    }

    function echo() {
      console.log('echo function executed :)')
    }

    function togglePayment(month) {      
      console.log(month)
    }
    
    debugBtn.addEventListener('click', validateSubmission)

    for (unitCheck of unitChecks) {
      unitCheck.addEventListener('change', function() {
        unitCheckWrapper  = this.parentNode
        unit              = unitCheckWrapper.parentNode.parentNode
        monthsHeader      = unit.nextElementSibling
        months            = monthsHeader.nextElementSibling
        unitMonthChecks   = months.getElementsByClassName('mth-chck')

        if (this.checked) for (unitMonthCheck of unitMonthChecks) unitMonthCheck.checked = true
        else for (unitMonthCheck of unitMonthChecks) unitMonthCheck.checked = false

        months = months.getElementsByClassName('mth')
        for (month of months) {
          togglePayment(month)
        }
        
      })
    }

    for (monthCheck of monthChecks) {
      monthCheck.addEventListener('change', function () {
        monthCheckWrapper = this.parentNode
        month             = monthCheckWrapper.parentNode
        months            = month.parentNode
        unitMonthChecks   = months.getElementsByClassName('mth-chck')
        monthsHeader      = months.previousElementSibling
        monthUnit         = monthsHeader.previousElementSibling
        monthUnitCheck    = monthUnit.getElementsByClassName('unt-chck')[0]

        checked = true
        for (unitMonthCheck of unitMonthChecks) if (checked) checked = unitMonthCheck.checked
        monthUnitCheck.checked = checked

        togglePayment(month)
      })
    }
    
  </script>

@endsection

@section('javascript')

@endsection
