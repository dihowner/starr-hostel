<div class="app-header header top-header mb-4">
    <div class="container-fluid">
        <div class="d-flex">
            
            <div class="d-flex order-lg-2 fs-18">
                <a href="{{ route('home') }}" class="nav-link pr-0">
                    <i class='fa fa-home'></i> &nbsp; Home
                </a>

                <a href="{{ route('view-cart') }}" class="nav-link pr-0">
                    <i class='fa fa-shopping-cart'></i> &nbsp; My Carts (<strong class="text-danger">{{ $totalCart }}</strong>)
                </a>
            </div>

            <div class="d-flex order-lg-2 ml-auto">
                @auth              
                    <a href="{{ route('logout') }}" class="nav-link pr-0">
                        <i class='fa fa-sign-out'></i> &nbsp; Logout ({{ $userDetail->name }})
                    </a>
                @else                
                    <a href="{{ route('login') }}" class="nav-link pr-0">
                        <i class='fa fa-sign-in'></i> &nbsp; Sign In
                    </a>          
                    <a href="{{ route('register') }}" class="nav-link pr-0">
                        <i class='fa fa-user-plus'></i> &nbsp; Join Us
                    </a>
                @endauth
            </div>

        </div>
    </div>
</div>