{{-- Mengextends template layout --}}
@extends("layouts.global")

@section("title") Categoies List @endsection

@section("pageTitle")
   Category Detail
@endsection

@section("content")
   <div class="col-md-8">
      <div class="card">
         <div class="card-body">
            <b>Name : </b> <br>
            {{$category->name}}
            <br><br>
            @if($category->image)
               <img src="{{asset('storage/' . $category->image)}}" width="128px" alt="image {{$category->name}}">
            @else
               <img src="{{asset('storage/no-image.png')}}" alt="" width="128px"> 
            @endif
            <br><br>
            <b>Slug : </b><br>
            {{$category->slug}}
            <br>
         </div>
      </div>
   </div>
@endsection