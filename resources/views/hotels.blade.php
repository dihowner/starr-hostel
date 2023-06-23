@include('components.head')

<body class="app sidebar-mini light-mode default-sidebar">
    @include('sweetalert::alert')

    <div class="page">
        {{-- <div class="page-main"> --}}

            <div class="app-content main-content">
                <div class="side-app">

                    @include('components.topnav')

                    <div class="row">
                        <div class="col-lg-12">

                            @if ($errors->any())
                                <div class="alert alert-danger" style="font-size: 18px">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">

                                @foreach($hotels as $hotel)
                                    <div class="col-xl-4 col-lg-6 col-sm-6 mb-2">
                                        <div class="card item-card">
                                            <div class="card-body pb-0">
                                                <div class="text-center">
                                                    <img src="{{ asset($hotel->images) }}" alt="img" class="img-fluid w-100" style="max-height: 300px">
                                                </div>
                                                <div class="card-body px-0 ">
                                                    <div class="cardtitle">
                                                        <div>
                                                            {!! $hotel->rating_code !!}
                                                        </div>
                                                        <h5>{{ $hotel->hotel_name}}</h5>
                                                    </div>
                                                    <div class="cardprice">
														{{-- <span class="type--strikethrough">$1,457</span> --}}
														<strong>&#8358;{{ number_format($hotel->price, 2)}}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center border-top p-4">
                                                <a href="{{ route('view-hostel', ['id' => $hotel->id]) }}" class="btn btn-light btn-svgs mt-1 mb-1">
                                                    {{-- <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/>
                                                        <path d="M12 6c-3.79 0-7.17 2.13-8.82 5.5C4.83 14.87 8.21 17 12 17s7.17-2.13 8.82-5.5C19.17 8.13 15.79 6 12 6zm0 10c-2.48 0-4.5-2.02-4.5-4.5S9.52 7 12 7s4.5 2.02 4.5 4.5S14.48 16 12 16z" opacity=".3"/>
                                                        <path d="M12 4C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 13c-3.79 0-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6s7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17zm0-10c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7zm0 7c-1.38 0-2.5-1.12-2.5-2.5S10.62 9 12 9s2.5 1.12 2.5 2.5S13.38 14 12 14z"/>
                                                    </svg> --}}
                                                    <i class="fa fa-eye fs-20"></i> &nbsp; View Details
                                                </a>

                                                @if(in_array($hotel->id, $userCartHotelIDs))
                                                    <button class="btn btn-danger btn-svgs mt-1 mb-1 removeCart" data-prop="{{ $hotel }}">
                                                        <i class="fa fa-trash"></i>
                                                        Remove Cart
                                                    </button>
                                                @else
                                                    <button class="btn btn-dark btn-svgs mt-1 mb-1 addCart" data-prop="{{ $hotel }}">
                                                        <svg  class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                                            <path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.55 11l2.76-5H6.16l2.37 5z" opacity=".3"/>
                                                            <path d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                                                        </svg>
                                                        Add to cart
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/vendors/jquery-3.5.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).on("click", ".removeCart", function() {
            var cartInfo = JSON.parse($(this).attr('data-prop'));
            console.log(cartInfo.id);

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to remove "+cartInfo.hotel_name+" from your cart list. You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Remove it!',
                cancelButtonText: 'No, Cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).html("<i class='fa fa-spinner fa-spin'></i> Loading").attr("disabled", true)
                    // Perform the delete action here
                    window.location.href = '/delete-cart/' + cartInfo.id;
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Your cart is safe', 'info');
                }
            });
        });

        $(document).on("click", ".addCart", function() {
            var cartInfo = JSON.parse($(this).attr('data-prop'));
            console.log(cartInfo.id);

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to add "+cartInfo.hotel_name+" to your cart list. Proceed ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Add it!',
                cancelButtonText: 'No, Cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).html("<i class='fa fa-spinner fa-spin'></i> Loading").attr("disabled", true)
                    // Perform the delete action here
                    window.location.href = '/add-cart/' + cartInfo.id;
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Your cart is safe', 'info');
                }
            });
        });
    </script>

</body>
