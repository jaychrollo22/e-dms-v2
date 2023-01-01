 <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
     <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
         <a class="navbar-brand brand-logo me-5" href="/"><img src="{{asset('img/app_logo.png')}}" class="me-2" alt="logo" /></a>
         <a class="navbar-brand brand-logo-mini" href="/"><img src="{{asset('img/app_icon.png')}}" alt="logo" /></a>
     </div>
     <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
         <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
             <span class="icon-menu"></span>
         </button>
         <ul class="navbar-nav navbar-nav-right">
             <li class="nav-item nav-profile dropdown">
                 <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                     <img src="{{ asset('img/default.png') }}" alt="profile" />
                 </a>
                 <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                     <a class="dropdown-item" href="/user-profile">
                         <i class="ti-user text-primary"></i>
                         User Profile
                     </a>
                     @if(roleValidation())
                     <a class="dropdown-item" href="/home-user">
                         <i class="ti-layout text-primary"></i>
                         As User
                     </a>
                     <a class="dropdown-item" href="/home">
                         <i class="ti-layout text-primary"></i>
                         As Administrator
                     </a>
                     @endif

                     <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                         <i class="ti-power-off text-primary"></i>
                         Logout
                     </a>
                 </div>
             </li>
         </ul>
         <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
             <span class="icon-menu"></span>
         </button>
     </div>
 </nav>

 <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
     @csrf
 </form>
