<div class="c-wrapper">
  <header class="c-header c-header-light c-header-fixed c-header-with-subheader">
    <button class="c-header-toggler c-class-toggler d-lg-none mr-auto" type="button" data-target="#sidebar"
      data-class="c-sidebar-show"><span class="c-header-toggler-icon"></span></button>
    <button class="c-header-toggler c-class-toggler ml-3 d-md-down-none" type="button" data-target="#sidebar"
      data-class="c-sidebar-lg-show" responsive="true"><span class="c-header-toggler-icon"></span></button>
    <?php
    use App\MenuBuilder\FreelyPositionedMenus;
    if (isset($appMenus['top menu'])) {
    FreelyPositionedMenus::render($appMenus['top menu'], 'c-header-', 'd-md-down-none');
    }
    ?>
    <ul class="c-header-nav ml-auto mr-4">
      <li class="c-header-nav-item dropdown"><a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button"
          aria-haspopup="true" aria-expanded="false">
          <div class="c-avatar"><img class="c-avatar-img" src="{{ url('/assets/img/avatars/0.jpg') }}"
              alt="user@email.com"></div>
        </a>
        <div class="dropdown-menu dropdown-menu-right pt-0">
          <div class="dropdown-header bg-light py-2"><strong>{{ Auth::user()->name }}</strong></div>
          <a class="dropdown-item" href="{{ route('passwords.edit') }}">
            <svg class="c-icon mr-2">
              <use xlink:href="{{ url('/icons/sprites/free.svg#cil-settings') }}"></use>
            </svg> Change Password
          </a>
          <form action="{{ url('/logout') }}" method="POST"> 
            @csrf 
            <button type="submit" class="dropdown-item"><i class="cil-account-logout mr-2"></i> Logout</button>
          </form>
        </div>
      </li>
    </ul>
    <div class="c-subheader px-3">
      <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <?php $segments = ''; ?>
        @for ($i = 1; $i <= count(Request::segments()); $i++)
          <?php $segments .= '/' . Request::segment($i); ?>
          @if ($i < count(Request::segments())) 
            <li class="breadcrumb-item">{{ Request::segment($i) }}</li>
          @else
            <li class="breadcrumb-item active">{{ Request::segment($i) }}</li> 
          @endif 
        @endfor
      </ol>
    </div>
  </header>
