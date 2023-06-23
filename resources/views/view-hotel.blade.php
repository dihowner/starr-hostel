@php
    $hotelMeta = $viewHotel->meta;
@endphp
@include('components.head')
<link href="{{ asset('assets/plugins/gallery/gallery.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/tabs/style.css') }}" rel="stylesheet">

<body class="app sidebar-mini light-mode default-sidebar">
    @include('sweetalert::alert')

    <div class="page">
        <div class="page-main">
            <div class="app-content main-content">
                <div class="side-app">

                    @include('components.topnav')

                    <div class="row mt-4">
                        <div class="col-lg-12">

                            <div class="row mb-4">
                                <div class="col-lg-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4>To reserve this room, kindly fill this booking form</h4>

                                            @if ($errors->any())
                                                <div class="alert alert-danger" style="font-size: 18px">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <form action="{{ route('add-room-cart') }}" method="POST">
                                                @csrf

                                                <div class="d-flex justify-content-between">
                                                    <label class='fs-18'><b>Hotel Fee</b></label>
                                                    <span class='fs-18'>&#8358;{{ number_format($viewHotel->price, 2) }}</span>
                                                </div>

                                                <div class="form-group mb-2">
                                                    <input type="hidden" name="hotelId" value="{{ $viewHotel->id }}">
                                                </div>

                                                @if(in_array($viewHotel->id, $userCartHotelIDs))
                                                    <a href="{{ route('delete-cart', ['id' => $viewHotel->id]) }}" class="btn btn-danger btn-block">
                                                        <i class="fa fa-trash"></i>
                                                        Remove Cart
                                                    </a>
                                                @else
                                                    <button class="btn btn-dark btn-block mb-2" type="submit"><i class="fa fa-shopping-cart"></i> Reserve Room</button>
                                                @endif
                                            </form>

                                        </div>
                                    </div>


                                    <div class="demo-gallery">
                                        <ul id="lightgallery" class="list-unstyled row">

                                            @foreach (explode(",", $hotelMeta['additional_image']) as $roomImage)
                                                <li class="col-xs-6 col-sm-4 col-md-3" data-responsive="{{ asset($roomImage) }}" data-src="{{ asset($roomImage) }}"
                                                    data-sub-html="<h4>{{ $viewHotel->hotel_name }}</h4>" lg-event-uid="&amp;1">
                                                    <a href="#">
                                                        <img class="img-responsive" src="{{ asset($roomImage) }}" alt="Thumb-1" style="height: 100px">
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-lg-7">
                                    <div class="text-center mb-2">
                                        <strong class="fs-16">{{ $viewHotel->hotel_name }}</strong>
                                    </div>
                                    <img src="{{ asset($viewHotel->images) }}" class="img-fluid" style="height: 500px; width: 1500px;">

                                </div>
                                <div style="background: orangered; top: 36px; right: 18%; position: absolute; padding: 6px; width:5%; border-radius: 5px;">
                                    {!! $viewHotel->rating_code !!}
                                </div>
                            </div>

                            <div class="col-md-6 offset-md-3">
                                <div class="panel panel-primary">
                                    <div class="tab_wrapper first_tab">
                                        <ul class="tab_list">
                                            <li class="active" rel="hotel-describe">Description</li>
                                            <li rel="tab_1_4">Refund Policy</li>
                                        </ul>
                                        <div class="content_wrapper">
                                            <div title="hotel-describe" class="accordian_header hotel-describe active">Tab 1<span class="arrow"></span></div><div class="tab_content active first hotel-describe"
                                                style="display: block;" title="hotel-describe">
                                                <p>{!! nl2br($hotelMeta['description']) !!}</p>
                                            </div>
                                            <div title="tab_1_4" class="accordian_header tab_1_4 undefined">Tab 4<span class="arrow"></span></div><div class="tab_content last tab_1_4" title="tab_1_4">
                                                <p>
                                                    Thank you for considering our hotel for your accommodation needs. We strive to provide excellent service and a comfortable stay for all our guests.
                                                    <dl>
                                                        <dt>Cancellation Policy:</dt>
                                                        <dd>For cancellations made <b>7 days</b> prior to the scheduled check-in date, a full refund will be provided</dd>
                                                        <dd>For cancellations made between 1 - 3 days prior to the scheduled check-in date, a partial refund may be granted,
                                                            subject to the hotel's discretion.</dd>
                                                        <dd>Cancellations made within 2-3 days of the scheduled check-in date may not be eligible for a refund.</dd>

                                                        <dt>No-Show Policy:</dt>
                                                        <dd>In the event of a no-show (failure to check-in on the scheduled arrival date) without prior notification, no refund will be issued.</dd>

                                                        <dt>Early Departure:</dt>
                                                        <dd>If you decide to check-out earlier than your original departure date, a refund for the remaining nights may be provided,
                                                            subject to the hotel's policy. Please note that early departure fees may apply.</dd>

                                                        <dt>Force Majeure:</dt>
                                                        <dd>In case of unforeseen circumstances such as natural disasters, acts of terrorism, or other events beyond our control,
                                                            the hotel reserves the right to modify or cancel reservations and provide refunds as appropriate.</dd>

                                                        <dt>Early Departure:</dt>
                                                        <dd>Refunds for reservations made through third-party booking channels (e.g., travel agencies, online travel agencies)
                                                            will be subject to their respective terms and conditions.
                                                            Please refer to the specific booking channel's policy for more information.</dd>
                                                    </dl>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/vendors/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/tabs.js') }}"></script>
    <script src="{{ asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
    <script src="{{ asset('assets/plugins/gallery/picturefill.js') }}"></script>
    <script src="{{ asset('assets/plugins/gallery/lightgallery.js') }}"></script>
    <script src="{{ asset('assets/plugins/gallery/lg-pager.js') }}"></script>
    <script src="{{ asset('assets/plugins/gallery/lg-autoplay.js') }}"></script>
    <script src="{{ asset('assets/plugins/gallery/lg-fullscreen.js') }}"></script>
    <script src="{{ asset('assets/plugins/gallery/lg-zoom.js') }}"></script>
    <script src="{{ asset('assets/plugins/gallery/lg-hash.js') }}"></script>
    <script src="{{ asset('assets/plugins/gallery/lg-share.js') }}"></script>
    <script src="{{ asset('assets/js/gallery.js') }}"></script>


</body>


