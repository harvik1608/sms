@extends('include.header')
@section('content')
<div class="page-header">
            <div class="page-title">
                <h4>{{ $user->name }}</h4>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Basic Information</h4>
            </div>
            <div class="card-body profile-body">
                <form id="mainForm" method="POST" action="{{ route('submit.profile') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" value="{{ $user->name }}" id="name" name="name" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" value="{{ $user->email }}" id="email" name="email" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Mobile No.<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" value="{{ $user->phone }}" id="mobile_no" name="phone" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Business Name<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" value="{{ $user->company_name }}" id="company_name" name="company_name" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Address<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" value="{{ $user->address }}" id="address" name="address" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">State<span class="text-danger ms-1">*</span></label>
                                <select class="select" name="state" id="state" data-url="{{ url('/get-cities') }}">
                                    <option value="">Choose State</option>
                                    @if(!$states->isEmpty())
                                        @foreach($states as $state)
                                            @if($user->state == $state->id)
                                                <option value="{{ $state->id }}" selected>{{ $state->name }}</option>
                                            @else 
                                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <label id="state-error" class="error" for="state"></label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">City<span class="text-danger ms-1">*</span></label>
                                <select class="select" name="city" id="city">
                                    <option value="">Choose City</option>
                                </select>
                                <label id="city-error" class="error" for="city"></label>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-secondary shadow-none">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>  
<script>
	var page_title = "Profile";
    $(document).ready(function(){
        $("#mainForm").validate({
            rules:{
                name:{
                    required: true
                },
                email:{
                    required: true
                },
                phone:{
                    required: true
                },
                company_name:{
                    required: true
                },
                address:{
                    required: true
                },
                state:{
                    required: true
                },
                city:{
                    required: true
                }
            },
            messages:{
                name:{
                    required: "<small class='text-danger'><b>Name is required.</b></small>"
                },
                email:{
                    required: "<small class='text-danger'><b>Email is required.</b></small>"
                },
                phone:{
                    required: "<small class='text-danger'><b>Mobile No. is required.</b></small>"
                },
                company_name:{
                    required: "<small class='text-danger'><b>Business Name is required.</b></small>"
                },
                address:{
                    required: "<small class='text-danger'><b>Address is required.</b></small>"
                },
                state:{
                    required: "<small class='text-danger'><b>State is required.</b></small>"
                },
                city:{
                    required: "<small class='text-danger'><b>City is required.</b></small>"
                }
            }
        });
        $("#mainForm").submit(function(e){
            e.preventDefault();

            if($("#mainForm").valid()) {
                $.ajax({
                    url: $("#mainForm").attr("action"),
                    type: $("#mainForm").attr("method"),
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend:function(xhr){
                        xhr.setRequestHeader("csrf-token", $("input[name=_csrf]").val());
                        $("#mainForm button[type=submit]").html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>').attr("disabled",true);
                    },
                    success:function(response){
                        if(response.success) {
                            show_toast("Success!",response.message,"success");
                            setTimeout(function(){
                                window.location.reload();
                            },3000);
                        } else {
                            $("#mainForm button[type=submit]").html("Save").attr("disabled",false);
                            show_toast("Oops!",response.message,"error");
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#mainForm button[type=submit]").html("Save").attr("disabled",false);
                        if (xhr.status === 400) {
                            const res = xhr.responseJSON;
                            show_toast("Oops!",res.message,"error");
                        } else {
                            show_toast("Oops!","Something went wrong","error");
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
