<div class="col-md-12">
	@if ($errors->any())
	  <div class="alert alert-danger">
	    @foreach ($errors->all() as $error)
	      <p>{{ $error }}</p>
	    @endforeach
	  </div>
	@endif
</div>