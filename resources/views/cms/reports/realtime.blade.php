@extends('layouts.app')
@section('content-header')
<h1>
	Real Time
</h1>
<ol class="breadcrumb">
	<li><a href="{{URL::asset('/')}}cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
	<li class="active">Real Time report</li>
</ol>
@endsection
@section('content')
<div class="row">
   <div class="col-xs-12">
      <div class="box">
         <div class="box-header">
            <h3 class="box-title">Real Time report</h3>
            <div class="box-tools">
               <!--<div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                  <div class="input-group-btn">
                     <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
               </div>-->
            </div>
         </div>
         <!-- /.box-header -->
         <div class="box-body table-responsive no-padding">
            <table class="table table-hover" width="100%">
               <tbody>
                  <tr>
                    <th style="width:10%">Extension</th>
                    <th style="width:10%">Status</th>
                    <th style="width:10%">Information</th>
                  </tr>
               <tbody id="realBody">
               </tbody>
            </table>
         </div>
         <!-- /.box-body -->
      </div>
      <!-- /.box -->
   </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">	
	setInterval("getRealTime()",1000);
	function getRealTime()
	{
		var url = "{{ url('/cms/realtime/stats') }}"
		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'json',
			data: {method: '_GET', "_token": "{{ csrf_token() }}" , submit: true},
			success: function (response) {
				$("#realBody").html("");
				$.each(response,function(key,value){
					$("#realBody").append('<tr><td>'+value.extension+'</td><td>'+value.ext_status_text+'</td><td>'+value.info+'</td><tr>');
				});
			},
			error: function (result, status, err) {
				///alert(result.responseText);
				///alert(status.responseText);
				///alert(err.Message);
			},
		});
	}
	
	var url = "{{ url('/cms/realtime/stats') }}"
	$.ajax({
		url: url,
		type: 'GET',
		dataType: 'json',
		data: {method: '_GET', "_token": "{{ csrf_token() }}" , submit: true},
		success: function (response) {
			$("#realBody").html("");
			$.each(response,function(key,value){
				$("#realBody").append('<tr><td>'+value.extension+'</td><td>'+value.ext_status_text+'</td><td>'+value.info+'</td><tr>');
			});
		},
		error: function (result, status, err) {
			///alert(result.responseText);
			///alert(status.responseText);
			///alert(err.Message);
		},
	});
</script>
@endpush


