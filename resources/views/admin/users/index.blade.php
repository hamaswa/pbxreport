@extends('admin.layouts.app')
@section('content-header')
<h1>
	Dashboard
</h1>
<ol class="breadcrumb">
	<li><a href="/admin"><i class="fa fa-dashboard"></i> Dashboard</a></li>
	<li class="active">User</li>
</ol>
@endsection
@section('content')
    <section class="content-header">
        <h1 class="pull-left">Users</h1>
        <h1 class="pull-right">
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box">
            <div class="box-body">
				@include('admin.users.table')
			</div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

@push('scripts')
<script>
	$(document).on('click', '#addExtension[data-remote]', function (e) { 
		$("#currentID").val($(this).data("remote"));
		//alert($("#currentID").val())
		var url = '{{url("/")}}'+'/admin/getextension';
		$.ajax({
			url: url,
			type: 'POST',
			data: {"userid":$(this).data("remote"), "_token": "{{ csrf_token() }}" , submit: true},
			success: function(res)
			{
				//alert(res)
				$("#userExt").html(res);
			},
			error: function (result, status, err) {
				alert(result.responseText);
				alert(status.responseText);
				alert(err.Message);
			}
		})
	});

	$(document).on('click', '#addExtBtn', function (e) { 
		if($("#ext").val()=="" || !$.isNumeric($("#ext").val()))
		{
			$("#ext").focus();
			return false;
		}
		//alert($("#currentID").val())
		var url = '{{url("/")}}'+'/admin/addextension';
		$.ajax({
			url: url,
			type: 'POST',
			data: {"user_id":$("#currentID").val(), "extension_no":$("#ext").val(), "_token": "{{ csrf_token() }}" , submit: true},
			success: function(res)
			{
				$("#ext").val("");
				//alert(res)
				var url = '{{url("/")}}'+'/admin/getextension';
				$.ajax({
					url: url,
					type: 'POST',
					data: {"userid":$("#currentID").val(), "_token": "{{ csrf_token() }}" , submit: true},
					success: function(res)
					{
						//alert(res)
						$("#userExt").html(res);
					},
					error: function (result, status, err) {
						alert(result.responseText);
						alert(status.responseText);
						alert(err.Message);
					}
				})
			},
			error: function (result, status, err) {
				alert(result.responseText);
				alert(status.responseText);
				alert(err.Message);
			}
		})
	});
	
	
	$(document).on('click', '#deleteExtension[data-remote]', function (e) { 
		if (confirm("Are you sure to delete this extension?"))
		{
			//alert($("#currentID").val())
			var url = '{{url("/")}}'+'/admin/deleteextension';
			$.ajax({
				url: url,
				type: 'POST',
				data: {"extension_no":$(this).data("remote"), "_token": "{{ csrf_token() }}" , submit: true},
				success: function(res)
				{
					//alert(res)
					var url = '{{url("/")}}'+'/admin/getextension';
					$.ajax({
						url: url,
						type: 'POST',
						data: {"userid":$("#currentID").val(), "_token": "{{ csrf_token() }}" , submit: true},
						success: function(res)
						{
							//alert(res)
							$("#userExt").html(res);
						},
						error: function (result, status, err) {
							alert(result.responseText);
							alert(status.responseText);
							alert(err.Message);
						}
					})
				},
				error: function (result, status, err) {
					alert(result.responseText);
					alert(status.responseText);
					alert(err.Message);
				}
			})
		}
		return false;
	});
</script>
@endpush

