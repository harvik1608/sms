@extends('include.header')
@section('content')
<div class="page-header">
            <div class="page-title">
                <h4>Change Password</h4>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>{{ $user->name }}</h4>
            </div>
            <div class="card-body profile-body">
                <form id="mainForm" method="POST" action="{{ route('submit.change.password') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Old Password<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" id="old_password" name="old_password" />
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">New Password<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" id="new_password" name="new_password" />
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Confirm Password<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" id="confirm_password" name="confirm_password" />
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-secondary shadow-none">Change</button>
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
                old_password:{
                    required: true
                },
                new_password:{
                    required: true
                },
                confirm_password:{
                    required: true,
                    equalTo: "#new_password"
                }
            },
            messages:{
                old_password:{
                    required: "<small class='text-danger'><b>Old Password is required.</b></small>"
                },
                new_password:{
                    required: "<small class='text-danger'><b>New Password is required.</b></small>"
                },
                confirm_password:{
                    required: "<small class='text-danger'><b>Confirm Password is required.</b></small>",
                    equalTo: "<small class='text-danger'><b>New Password & Confirm Password must same.</b></small>"
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
                            $("#mainForm button[type=submit]").html("Change").attr("disabled",false);
                            show_toast("Oops!",response.message,"error");
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#mainForm button[type=submit]").html("Change").attr("disabled",false);
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
