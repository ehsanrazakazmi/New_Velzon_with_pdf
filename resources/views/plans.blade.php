@extends('layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') Pages @endslot
@slot('title') Stripe  @endslot
@endcomponent

<div class="container">
  <section>
    <div class="container py-5">
       
    <header class="text-center mb-5 text-white">
        <div class="row">
          <div class="col-lg-12 mx-auto">
            <h1>Cashier Stripe Subscription</h1>
            <h3>PRICING</h3>
          </div>
        </div>
      </header>
   
      <div class="row text-center d-flex justify-content-center">
          
   
          @foreach($plans as $plan)
          <div class="col-lg-4 mb-5 mb-lg-0">
              <div class="bg-white p-5 rounded-lg shadow">
              <h1 class="h6 text-uppercase font-weight-bold mb-4">{{ $plan->name }}</h1>
              <h2 class="h1 font-weight-bold">${{ $plan->price }}<span class="text-small font-weight-normal ml-2">/ month</span></h2>
   
              <div class="custom-separator my-4 mx-auto bg-primary"></div>
   
              <ul class="list-unstyled my-5 text-small text-left font-weight-normal">
                  <li class="mb-3">
                  <i class="fa fa-check mr-2 text-primary"></i>reporting tools for detailed insights</li>
                  <li class="mb-3">
                  <i class="fa fa-check mr-2 text-primary"></i>customer support with faster response times</li>
                  <li class="mb-3">
                  <i class="fa fa-check mr-2 text-primary"></i>Increased storage space for your files</li>
                  <li class="mb-3">
                  <i class="fa fa-check mr-2 text-primary"></i>Collaboration features for teamwork</li>
                  <li class="mb-3">
                  <i class="fa fa-check mr-2 text-primary"></i>branding options for professional look</li>
                  <li class="mb-3 text-muted">
                  <i class="fa fa-times mr-2"></i>
                  <del>Sed ut perspiciatis</del>
                  </li>
              </ul>
              <a href="{{ route('plans.show', $plan->slug) }}" class="btn btn-primary btn-block shadow rounded-pill">Buy Now</a>
              </div>
          </div>
          @endforeach
      </div>
    </div>
  </section>
     
</div>


@endsection
@section('script')
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
