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
                        <div class="col-lg-4 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" value="{{ $user->name }}" id="name" name="name" />
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" value="{{ $user->email }}" id="email" name="email" />
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Mobile No.<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" value="{{ $user->phone }}" id="mobile_no" name="phone" />
                            </div>
                        </div>
                        <div class="col-lg-8 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Company Name<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" value="{{ $user->company_name }}" id="company_name" name="company_name" />
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">State<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" value="{{ $user->state }}" id="state" name="state" />
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">City<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" value="{{ $user->city }}" id="city" name="city" />
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
                    required: "<small class='text-danger'><b>Whatsapp No. is required.</b></small>"
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
