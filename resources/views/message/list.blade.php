@extends('include.header')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
<div class="page-header">
    <div class="add-item d-flex">
        <div class="page-title">
            <h4 class="fw-bold">Message List</h4>
            <h6></h6>
        </div>
    </div>
    @if(Auth::user()->is_approved == 1)
        <div class="page-btn">
            <a href="javascript:;" onclick="open_modal()" class="btn btn-secondary text-white">Send Message</a>
        </div>
    @endif
</div>
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
        <div class="search-set">
            <div class="search-input">
                <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" id="tblList">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">Contact Name</th>
                        <th width="15%">Message Type</th>
                        <th width="10%">Is Sent</th>
                        <th width="5%">Sent On</th>
                        <th width="10%" class="no-sort"></th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="send-message">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="page-wrapper-new p-0">
				<div class="content">
					<form action="{{ route('admin.message.send') }}" method="POST" enctype="multipart/form-data" id="mainForm">
						@csrf
						<div class="modal-header">
							<div class="page-title">
								<h4>Send Message</h4>
							</div>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-lg-12 mb-3">
		                            <label class="form-label">Send To</label>
		                            <select class="select" name="send_to[]" id="send_to" multiple>
		                            	<option value="all">All</option>
                                        @if(!$contacts->isEmpty())
                                            @foreach($contacts as $contact)
                                                <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                            @endforeach
                                        @endif
		                            </select>
                                    <small id="send_to-error"></small>
		                        </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Message Type</label>
                                    <select class="select" name="message_type" id="message_type">
                                        <option value="1">Email</option>
                                        <option value="2">Whatsapp</option>
                                        <option value="3">Both</option>
                                    </select>
                                </div>
								<div class="col-lg-12 mb-3 subject">
		                            <label class="form-label">Subject<span class="text-danger ms-1">*</span></label>
		                            <input type="text" class="form-control" name="subject" id="subject" />
                                    <small id="subject-error"></small>
		                        </div>
		                        <div class="col-lg-12 mb-3">
		                            <label class="form-label">Message<span class="text-danger ms-1">*</span></label>
		                            <textarea class="form-control" name="content" id="content" rows="5"></textarea>
                                    <small id="content-error"></small>
		                        </div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn me-2 btn-secondary fs-13 fw-medium p-2 px-3 shadow-none" data-bs-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary fs-13 fw-medium p-2 px-3">Send</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
	var page_title = "Message List";
	$(document).ready(function(){
        $('#tblList').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('admin.messages.load') }}",
                "type": "GET",
                "data": function(d) {
                    // You can send extra parameters if needed
                    // d.extraParam = 'value';
                }
            },
            "bFilter": true,
            "sDom": 'fBtlpi',
            "ordering": true,
            "columns": [
                { data: 'id' },
                { data: 'name' },
                { data: 'message_type' },
                { data: 'is_sent' },
                { data: 'sent_on' },
                { 
                    data: 'actions', 
                    orderable: false, 
                    searchable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('action-table-data'); // Add custom class to <td>
                    }
                }
            ],
            "language": {
                search: ' ',
                sLengthMenu: '_MENU_',
                searchPlaceholder: "Search",
                // sLengthMenu: 'Row Per Page _MENU_ Entries',
                info: "_START_ - _END_ of _TOTAL_ items",
                paginate: {
                    next: ' <i class="fa fa-angle-right"></i>',
                    previous: '<i class="fa fa-angle-left"></i>'
                },
            },
            initComplete: (settings, json) => {
                $('.dataTables_filter').appendTo('#tableSearch');
                $('.dataTables_filter').appendTo('.search-input');
            }  
        });
        $("#message_type").change(function(){
        	if($(this).val() == "2") {
        		$(".subject").hide(500);
        	} else {
        		$(".subject").show(500);
        	}
        });
        $("#mainForm").submit(function(e){
            var isError = 0;
            if($("#send_to").val() == "") {
                isError = 1;
                $("#send_to-error").html("Please choose at least one contact");
            } else {
                $("#send_to-error").html("");
            }
            if($("#message_type").val() != 2) {
                if($("#subject").val() == "") {
                    isError = 1;
                    $("#subject-error").html("Please enter subject");
                } else {
                    $("#subject-error").html("");
                }    
            }
            if($("#content").val() == "") {
                isError = 1;
                $("#content-error").html("Please enter message");
            } else {
                $("#content-error").html("");
            }
            if(isError > 0) {
                return false;
            }
        });
    });
	function open_modal()
    {
    	$("#send-message").modal("show");
    }
</script>
@endsection
