@extends('layouts.management')

@section('content')
<div class="container">
    @if (Auth::check())
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Management Dashboard') }}</div>

                <div class="card-body">
                    <h1>Management Dashboard</h1>
                    <p>Welcome to the management dashboard. Here you can find information about our management practices and policies.</p>
                    <h2>Our Management Team</h2>
                    <p>Our management team is composed of experienced professionals who are dedicated to ensuring the success of our organization.</p>
                    <h2>Our Policies</h2>
                    <p>We have a number of policies in place to ensure that our organization runs smoothly and efficiently. These policies cover a wide range of topics, including employee conduct, workplace safety, and data security.</p>
                    <h2>Contact Us</h2>
                    <p>If you have any questions or concerns about our management practices or policies, please do not hesitate to contact us.</p>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Management Dashboard') }}</div>

                <div class="card-body">
                    <p>You must be logged in to view this page.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
