<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-themed shadow ">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">کارا - سامانه مدیریت پروژه</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <ul class="navbar-nav mb-2 mb-lg-0  ">
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

        @can('isAdmin')
        <li class="nav-item dropdown">
          <a class="nav-link " aria-current="page" href="{{ route('user.index') }}">
          کاربران
          </a>

        </li>
        @endcan
        @if(Auth::check())
        <li class="nav-item ">
          <a class="nav-link " aria-current="page" href="{{ route('projects.index') }}">
          پروژه ها
          </a>

        </li>
        @endif




         @endguest

        @if(Auth::check())


        <li class="nav-item">
            <a class="nav-link " aria-current="page" href="{{ route('phases.owner') }}">فازها</a>
          </li>

          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="{{ route("sprints.owner") }}">اسپرینت ها</a>
          </li>

          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="{{ route('tasks.owner') }}"> کارها</a>
          </li>



        <li class="nav-item">
            <a class="nav-link " aria-current="page" href="{{ route('tasks.task-board') }}">کارتاپل</a>
          </li>

          @endif
          {{-- @endcan --}}


      </ul>

      @if(Auth::check())

      <ul class="navbar-nav ms-auto">
<li class="nav-item mt-1">
    <div class="ms-auto mt-2 rounded" style="width:24px;height:24px;background: {{ Auth::user()->background_color }}">

    </div>
</li>
    <li class="nav-item dropdown " >
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      <img src="{{ asset('images/img_avatar.png') }}" class="rounded-circle" alt="" width="30px">
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <li class="dropdown-item">
           <span class="dropdown-item-text"> {{ Auth::user()->fname . ' ' . Auth::user()->lname }}</span>

        </li>
        <li><hr class="dropdown-divider"></li>
        <li class="dropdown-item">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <li class="dropdown-item " :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();" style="cursor: pointer">
                خروج
          </li>
          </form>
        </li>

      </ul>
    </li>


      </ul>


    @endif

      <!-- <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form> -->
    </div>
  </div>
</nav>
