@extends('include.header')
@section('content')
<div class="page-header">
    <div class="page-title">
        <h4>City List</h4>
        <h6>(<span class='mandadory'>*</span>) indicates required field.</h6>
    </div>
</div>
<form action="{{ is_null($city) ? url('cities') : url('cities/'.$city->id) }}" method="POST" enctype="multipart/form-data" id="mainForm">
    @csrf
    @if(!is_null($city))
        <input type="hidden" name="_method" value="PUT" />
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ is_null($city) ? "New" : "Edit" }} City</h4>
                </div>
                <div class="card-body profile-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" value="{{ is_null($city) ? '' : $city->name }}" />
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">State</label>
                            <select class="select" name="state_id" id="state_id">
                                <option value="">Choose option</option>
                                @if(!$states->isEmpty())
                                    @foreach($states as $state)
                                        @if(!is_null($city) && $city->state_id == $state->id)
                                            <option value="{{ $state->id }}" selected>{{ $state->name }}</option>
                                        @else 
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            <label id="state_id-error" class="error" for="state_id"></label>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Status</label>
                            <select class="select" name="is_active" id="is_active">
                                <option value="1" {{ !is_null($city) && $city->is_active == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !is_null($city) && $city->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-end mt-2">
                        <button type="submit" class="btn btn-primary">SUBMIT</button>
                        <a href="{{ url('cities') }}" class="btn btn-secondary" id="backBtn">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
	var page_title = "City List";
    $(document).ready(function(){
        $("#mainForm").validate({
            rules:{
                name:{
                    required: true
                },
                state_id:{
                    required: true
                }
            },
            messages:{
                name:{
                    required: "<small class='text-danger'><b>City Name is required.</b></small>"
                },
                state_id:{
                    required: "<small class='text-danger'><b>State is required.</b></small>"
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
                                window.location.href = $("#backBtn").attr("href");
                            },3000);
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#mainForm button[type=submit]").html("SUBMIT").attr("disabled",false);
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
