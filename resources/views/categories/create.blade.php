@extends("layouts.global")

@section("title") Create Category @endsection

@section("pageTitle") Create Category @endsection

@section("content") 
<div class="col-md-8">
   @if(session('status'))
      <div class="alert alert-success">
         {{session('status')}}
      </div>
   @endif

      <form 
         enctype="multipart/form-data" 
         class="bg-white shadow-sm p-3" 
         action="{{route('categories.store')}}" 
         method="POST">
         @csrf

         <label for="name">Category Name</label>
         <input class="form-control" type="text" name="name" id="name">
         <br>
         <label for="image">Category Image</label>
         <input class="form-control" type="file" name="image" id="image">
         <br>
         <button type="submit" class="btn btn-primary">Save</button>
      </form>
   </div>

@endsection