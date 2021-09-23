@if (session('status'))
  <div class="alert alert-success alert-dismissible fade show">
    {!! session('status') !!}
    <button
      type="button"
      class="close"
      data-dismiss="alert"
    >
      <span>&times;</span>
    </button>
  </div>
@endif
