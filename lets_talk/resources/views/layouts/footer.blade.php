<!-- Footer -->
<footer class="text-center text-white footer" style="padding-top:2rem;">
    <!-- Grid container -->
    <div class="" style="margin-top:0rem;padding-left:4rem;padding-right:4rem;padding-top:0rem;">
        <!-- Section: Links -->
        <section class="">
            <!-- Grid row-->
            <div class="row text-center d-flex justify-content-center p-0">
                <!-- Grid column -->
                <div class="col-md-2 col-md-offset-2">
                    <h6 class="text-uppercase font-weight-bold">
                        <a href="{{route('about_us')}}" class="text-white fw-bold" style="text-decoration: none;">About us</a>
                    </h6>
                </div>
                <!-- Grid column -->

                <!-- Grid column -->
                <div class="col-md-2">
                    <h6 class="text-uppercase font-weight-bold">
                        <a href="{{route('services')}}" class="text-white fw-bold" style="text-decoration: none;">Services</a>
                    </h6>
                </div>
                <!-- Grid column -->

                <!-- Grid column -->
                <div class="col-md-2">
                    <h6 class="text-uppercase font-weight-bold">
                        <a href="#" class="text-white fw-bold" style="text-decoration: none;">Help</a>
                    </h6>
                </div>
                <!-- Grid column -->

                <!-- Grid column -->
                <div class="col-md-2">
                    <h6 class="text-uppercase font-weight-bold">
                        <a href="mailto:letstalkmedellin@gmail.com" class="text-white" target="_blank" style="text-decoration: none;">Contact</a>
                    </h6>
                </div>
                <!-- Grid column -->
            </div>
            <!-- Grid row-->
        </section>
        <!-- Section: Links -->

        {{-- <hr class="mt-3 container bg-white"/> --}}

        <hr class="my-5"/>

        <!-- Section: Social -->
        <section class="text-center mb-0 p-0">
            <a href="" class="text-white fa-2x facebook">
                <i class="fa fa-facebook-f"></i>
            </a>
            <a href="" class="text-white fa-2x insta">
                <i class="fa fa-instagram"></i>
            </a>
        </section>
        <!-- Section: Social -->
    </div>
    <!-- Grid container -->

    <!-- Copyright -->
    <div class="text-center p-2 copy-footer">
        <p class="d-flex justify-content-center align-items-center">
            All Rights Reserved ©
            <a class="text-white" href="#" style="text-decoration: none;">Let's Talk</a> {{date('Y')}}
        </p>
    </div>
    <!-- Copyright -->
</footer>
{{-- FIN footer --}}

@yield('scripts')
<!-- SCRIPTS -->
{{-- <script src="{{asset('vendor/bootstrap/js/bootstrap.min.js')}}"></script> --}}
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/functions.js') }}"></script>
<script src="{{ asset('js/homeslider.j') }}s"></script>
<script src="{{ asset('js/jquery.grid-a-licious.js') }}"></script>
<script src="{{ asset('js/404.js') }}"></script>

{{-- Scripts Login Entrenador  --}}
<script src="{{asset('vendor/animsition/js/animsition.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap/js/popper.js')}}"></script>
<script src="{{asset('vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('vendor/daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('vendor/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('vendor/countdowntime/countdowntime.js')}}"></script>
<script src="{{asset('js/main.js')}}"></script>

{{-- Sweetalert --}}
<script src="{{asset('js/sweetalert2.all.js')}}"></script>
<script src="{{asset('js/sweetalert2.min.js')}}"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

@include('sweetalert::alert')
