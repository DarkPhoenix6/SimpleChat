@extends('template')

@section('pageCSS') 
	<link href="https://fonts.googleapis.com/css?family=Permanent+Marker" rel="stylesheet">
  <link rel="stylesheet" href="/css/chat.css">
@stop

@section('content')
<div class="jumbotron">
@if ($errors->any())
  @foreach ($errors->all() as $error)
    <p class="err-msg">{{ $error }}</p>
  @endforeach
@endif
	<h1 class="display-4 jumbo-title">{{ $name }}</h1>
	<div class="messages" id="messageLog"></div>
	<div class="input-group mb-3">
		<input type="text" id="message" class="form-control" placeholder="Say Something!" aria-describedby="basic-addon2" required>
		<div class="input-group-append">
			<button id="sendMessage" class="btn btn-success">➜</button>
		</div>
   @csrf
   
	</div>
   <div class="input-group mb-3"> 
     <!-- setuser and color -->
     <label for="userName">Username:</label>
     <input type="text" class="form-control" id="userName" required>
     <label for="userColor">Colour:</label>
     <input type="text" class="form-control" id="userColor" required>
     <div class="input-group-append">
       <button id="setUser" class="btn btn-success">Save</button>
     </div>
   </div>
   
</div>


@stop
@section('bootstrapJS')
<script src="/js/chat.js"></script>
@stop