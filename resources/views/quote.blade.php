@extends('layout.master')
@section('body')
		<!-- Start Page Title Area -->
		<div class="page-title-area page-title-style-two">
			<div class="container">
				<div class="page-title-content">
					<h2>Pricing</h2>
					<ul>
						<li>
							<a href="{{ url('/') }}">
								<i class="bx bx-home"></i>
								Home
							</a>
						</li>
						<li class="active">Pricing</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Page Title Area -->

		<!-- Start Pricing Area -->
		<section class="pricing-area pt-100 pb-70">
			<div class="container">
				<div class="section-title">
					<span>Cotizaciones</span>
					<h2>Las cotizaciones en tiempo real de </h2>
				</div>

				<div class="row">
                    @foreach ($cashs as $exchange )
                    @if(($exchange->status)==1)
					<div class="col-lg-4 col-md-6">
						<div class="single-pricing overly-one">
							<div class="overly-two">
								<div class="pricing-title">
									<h3>{{$exchange->name}}</h3>
									<h2>{{$exchange->name}}</h2>
									<span>{{$exchange->name}}</span>
									<h4>{{$exchange->name}}</h4>
								</div>

								<ul>
									<li>30 Days Product Testing</li>
								</ul>

								<a href="#" class="default-btn">
									<span>Read More</span>
								</a>

								<div class="pricing-shape">
									<img data-cfsrc="assets/img/pricing-shape.png" alt="Image" style="display:none;visibility:hidden;"><noscript><img src="assets/img/pricing-shape.png" alt="Image"></noscript>
								</div>
								<div class="pricing-shape-2">
									<img data-cfsrc="assets/img/pricing-shape-2.png" alt="Image" style="display:none;visibility:hidden;"><noscript><img src="assets/img/pricing-shape-2.png" alt="Image"></noscript>
								</div>
							</div>
						</div>
					</div>
                    @endif
                    @endforeach
				</div>
			</div>
		</section>
		<!-- End Pricing Area -->

@endsection
