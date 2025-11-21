@extends('include.header')
@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/form-wizard.css') }}">
<div class="page-header">
    <div class="page-title">
        <h4>User List</h4>
        <h6>(<span class='mandadory'>*</span>) indicates required field.</h6>
    </div>
</div>
<form action="{{ is_null($user) ? url('users') : url('users/'.$user->id) }}" method="POST" enctype="multipart/form-data" id="mainForm">
    @csrf
    @if(!is_null($user))
        <input type="hidden" name="_method" value="PUT" />
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ is_null($user) ? "New" : "Edit" }} User</h4>
                </div>
                <div class="card-body">
                    <div id="progrss-wizard" class="twitter-bs-wizard">
                        <ul class="twitter-bs-wizard-nav nav nav-pills nav-justified">
                            <li class="nav-item">
                                <a href="#progress-seller-details" class="nav-link bg-transparent active" data-section="Personal" data-toggle="tab">
                                    <div class="step-icon">
                                        <i class="far fa-user"></i>
                                    </div>
                                </a>
                                <small>Personal Details</small>
                            </li>
                            <li class="nav-item">
                                <a href="#progress-company-document" class="nav-link bg-transparent" data-section="Whatsapp" data-toggle="tab">
                                    <div class="step-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                </a>
                                <small>Whatsapp Details</small>
                            </li>
                            <li class="nav-item">
                                <a href="#progress-login-detail" class="nav-link bg-transparent" data-section="Email" data-toggle="tab">
                                    <div class="step-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </a>
                                <small>Login Details</small>
                            </li>
                            <li class="nav-item">
                                <a href="#progress-payment-detail" class="nav-link bg-transparent" data-section="Payment" data-toggle="tab">
                                    <div class="step-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                </a>
                                <small>Payment Details</small>
                            </li>
                        </ul>
                        <div id="bar" class="progress mt-4">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"></div>
                        </div>
                        <form id="userForm">
                            @csrf
                            <div class="tab-content twitter-bs-wizard-tab-content mt-4">
                                <div class="tab-pane active" id="progress-seller-details">
                                    <div class="mb-4">
                                        <h5>Personal Details</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="progresspill-firstname-input" class="form-label">Name<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{ is_null($user) ? '' : $user->name }}" />
                                            <small id="name-error"></small>
                                        </div>
                                        <div class="col-lg-2 mb-3">
                                            <label class="form-label">Mobile No.<span class="text-danger ms-1">*</span></label>
                                            <input type="number" class="form-control" name="mobile_no" id="mobile_no" value="{{ is_null($user) ? '' : $user->phone }}" />
                                            <small id="mobile_no-error"></small>
                                        </div>
                                        <div class="col-lg-2 mb-3">
                                            <label class="form-label">State<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="state" id="state" value="{{ is_null($user) ? 'Maharasthra' : $user->state }}" />
                                            <small id="state-error"></small>
                                        </div>
                                        <div class="col-lg-2 mb-3">
                                            <label class="form-label">City<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="city" id="city" value="{{ is_null($user) ? '' : $user->city }}" />
                                            <small id="city-error"></small>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <label class="form-label">Address<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="address" id="address" value="{{ is_null($user) ? '' : $user->address }}" />
                                            <small id="address-error"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="progress-company-document">
                                    <div>
                                        <div class="mb-4">
                                            <h5>Location Details</h5>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-8 mb-3">
                                                <label class="form-label">Company<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="company_name" id="company_name" value="{{ is_null($user) ? '' : $user->company_name }}" />
                                                <small id="company_name-error"></small>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label for="progresspill-firstname-input" class="form-label">Contact Person Name<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="contact_person_name" id="contact_person_name" value="{{ is_null($user) ? '' : $user->contact_person_name }}" />
                                                <small id="contact_person_name-error"></small>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Industry<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="industry" id="industry" value="{{ is_null($user) ? '' : $user->industry }}" />
                                                <small id="industry-error"></small>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Service Name<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="service_name" id="service_name" value="{{ is_null($user) ? '' : $user->service_name }}" />
                                                <small id="service_name-error"></small>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Zipcode<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="zipcode" id="zipcode" value="{{ is_null($user) ? '' : $user->zipcode }}" />
                                                <small id="zipcode-error"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="progress-login-detail">
                                    <div class="mb-4">
                                        <h5>Login Details</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="progresspill-firstname-input" class="form-label">Email<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="email" id="email" value="{{ is_null($user) ? '' : $user->email }}" />
                                            <small id="email-error"></small>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label class="form-label">Username<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="username" id="username" value="{{ is_null($user) ? '' : $user->username }}" />
                                            <small id="username-error"></small>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label class="form-label">Password<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="password" id="password" value="{{ is_null($user) ? '' : $user->password }}" />
                                            <small id="password-error"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="progress-payment-detail">
                                    <div class="mb-4">
                                        <h5>Payment Details</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">
                                            <label for="progresspill-firstname-input" class="form-label">Plan<span class="text-danger ms-1">*</span></label>
                                            <select class="select" name="plan_id" id="plan_id">
                                                <option value="">Choose option</option>
                                                @if(!$plans->isEmpty())
                                                    @foreach($plans as $plan)
                                                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <small id="plan_id-error"></small>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label class="form-label">Amount</label>
                                            <input type="number" class="form-control" id="amount" disabled />
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label class="form-label">Duration <small>(in month)</small></label>
                                            <input type="number" class="form-control" id="duration" disabled />
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label class="form-label">Total Whatsapp Messages</label>
                                            <input type="number" class="form-control" id="whatsapp" disabled />
                                        </div>
                                    </div>
                                    <ul class="pager wizard twitter-bs-wizard-pager-link">
                                        <li class="next">
                                            <button type="submit" class="btn btn-primary" id="payBtn">Pay</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/jquery.bootstrap.wizard.min.js') }}"></script>
<script src="{{ asset('assets/js/prettify.js') }}"></script>
<script src="{{ asset('assets/js/form-wizard1.js') }}"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
	var page_title = "User List";
    $(document).ready(function(){
        $('#plan_id').change(function () {
            let planId = $(this).val();

            $.ajax({
                url: "{{ url('/get-plan-amount/') }}/"+planId,
                type: "GET",
                success: function (res) {
                    $("#amount").val(res.amount);
                    $("#duration").val(res.duration);
                    $("#whatsapp").val(res.whatsapp);
                }
            });
        });
        $("#payBtn").click(function (e) {
            e.preventDefault();

            let planId = $("#plan_id").val();
            $.ajax({
                url: "{{ route('create.order') }}",
                type: "POST",
                data: {
                    plan_id: planId,
                    _token: "{{ csrf_token() }}"
                },
                beforeSend:function(xhr){
                    $("#payBtn").html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>').attr("disabled",true);
                },
                success: function (res) {
                    var options = {
                        "key": res.key,
                        "amount": res.amount * 100,
                        "currency": "INR",
                        "name": "{{ config('constant.app_name') }}",
                        "description": "Plan Purchase",
                        "order_id": res.order_id,
                        "handler": function (response) {

                            // VERIFY PAYMENT
                            $.ajax({
                                url: "{{ route('payment.verify') }}",
                                type: "POST",
                                data: {
                                    name: $.trim($("#name").val()),
                                    mobile_no: $.trim($("#mobile_no").val()),
                                    state: $.trim($("#state").val()),
                                    city: $.trim($("#city").val()),
                                    address: $.trim($("#address").val()),
                                    company_name: $.trim($("#company_name").val()),
                                    contact_person_name: $.trim($("#contact_person_name").val()),
                                    industry: $.trim($("#industry").val()),
                                    service_name: $.trim($("#service_name").val()),
                                    zipcode: $.trim($("#zipcode").val()),
                                    email: $.trim($("#email").val()),
                                    username: $.trim($("#username").val()),
                                    password: $.trim($("#password").val()),
                                    plan_id: planId,
                                    _token: "{{ csrf_token() }}",
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    razorpay_order_id: response.razorpay_order_id,
                                    razorpay_signature: response.razorpay_signature,
                                },
                                success: function (data) {
                                    if (data.status === "success") {
                                        alert("Payment successful!");
                                        window.location.href = "{{ url('users') }}";
                                    } else {
                                        alert("Payment failed!");
                                    }
                                }
                            });
                        }
                    };

                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                }
            });
        });
    });
</script>
@endsection
