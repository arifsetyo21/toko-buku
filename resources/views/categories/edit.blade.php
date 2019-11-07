{{-- Mengextends template layout global --}}
@extends("layouts.global")

{{-- @section digunakan untuk mengisi @yield yang berada pada parent view--}}
@section("title") Category Update @endsection

@section("pageTitle")
   Category Edit
@endsection

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
         action="{{route('categories.update', ['id' => $category->id])}}" 
         method="POST">
         @csrf
         <input type="hidden" name="_method" value="PUT">

         <label for="name">Category Name</label>
         <input class="form-control" type="text" name="name" id="name" value="{{ $category->name }}">
         <br>
         @if($category->image)
            <span>Current image</span><br>
            <img src="{{asset('storage/' . $category->image)}}" alt="" width="100px">
            <br>
         @else
            <img src="{{asset('storage/no-image.png')}}" alt="" width="100px">
            <br>
         @endif
         <label for="image">Category Image</label>
         <input class="form-control" type="file" name="image" id="image">
         <br>
         <button type="submit" class="btn btn-primary">Save</button>
      </form>
   </div>
@endsection