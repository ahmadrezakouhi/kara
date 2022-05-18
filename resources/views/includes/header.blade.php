<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-themed">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">کاراله - مدیریت بهره وری پروژه ها</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <ul class="navbar-nav mb-2 mb-lg-0">
      <!-- @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
        @else -->
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">خانه</a>
        </li>
        @if(Gate::check('isAdmin') || Gate::check('isManager'))
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          کاربران
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="{{ URL::to('user') }}" >همه</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item"  href="{{ URL::to('user/create') }}">ایجاد</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          پروژه ها
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="{{ URL::to('project') }}" >همه</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item"  href="{{ URL::to('project/create') }}">ایجاد</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          فعالیت ها
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="{{ URL::to('task') }}" >همه</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item"  href="{{ URL::to('task/create') }}">ایجاد</a></li>
          </ul>
        </li>
        <!-- <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          کارمندان
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="{{ URL::to('personnel') }}" >همه</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item"  href="{{ URL::to('personnel/create') }}">ایجاد</a></li>
          </ul>
        </li> -->
        @else(Gate::check('isUser'))
        <li class="nav-item">
          <a class="nav-link" href="{{ URL::to('personnel') }}" >کارمند</a>
        </li>
        @endif
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          {{ Auth::user()->fname . ' ' . Auth::user()->lname }}
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link class="dropdown-item" :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    خروج
                </x-responsive-nav-link>
              </form>
            </li>
          </ul>
        </li>
        <!-- @endguest -->
        <!-- <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled">Disabled</a>
        </li> -->
      </ul>
      <!-- <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form> -->
    </div>
  </div>
</nav>