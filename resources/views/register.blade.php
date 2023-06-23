@include('components.head')

<body class="h-100vh page-style1 light-mode default-sidebar">	    
    <div class="page">
        <div class="page-single">
            
            @include('components.topnav')

            <div class="container">
                <div class="row">
                    <div class="col mx-auto">
                        <div class="row justify-content-center">
                            <div class="col-md-7 col-lg-5">
                                <div class="card card-group mb-0">
                                    <div class="card p-4">
                                        <div class="card-body">
                                            <div class="text-center title-style mb-6">
                                                <h1 class="mb-2">Create Account</h1>
                                                <hr>
                                                <p class="text-muted"></p>
                                            </div>

                                            @if ($errors->any() || session()->has('error'))
                                                <div class="alert alert-danger" style="font-size: 18px">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                    @if (session()->has('error'))
                                                        <li>{{ session()->get('error') }}</li>
                                                    @endif
                                                </ul>
                                                </div>
                                            @endif

                                            <form method="POST" action="{{ route("process-register") }}">
                                                @csrf
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" name="full_name" placeholder="Full Name">
                                                </div>
                                                
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" name="username" placeholder="Enter Username">
                                                </div>
                                                
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" name="phone_number" placeholder="Phone Number">
                                                </div>
                                                
                                                <div class="input-group mb-3">
                                                    <input type="email" class="form-control" name="emailaddress" placeholder="Email Address">
                                                </div>
                                                
                                                <div class="input-group mb-3">
                                                    <input type="password" class="form-control" name="password" placeholder="Enter Password">
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-lg btn-primary btn-block"><i class="fe fe-arrow-right"></i> Register</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center pt-4">
                                    <div class="font-weight-normal fs-16">Already a member <a class="btn-link font-weight-normal" href="{{ route('login') }}">Login Here</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</body>