{{-- Mengextends template layout --}}
@extends("layouts.global")

@section("title") Categoies List @endsection

@section("pageTitle")
   Categories List
@endsection

@section("content")

@if(session('status'))
<div class="row">
   <div class="col-md-12">
      <div class="alert alert-success">
         {{session("status")}}
      </div>
   </div>
</div>
@endif

   <div class="row">
      <div class="col-md-6">
         <form action="{{route('categories.index')}}" method="get">
            <div class="input-group">
            <input 
               type="text"
               class="form-control"
               placeholder="filter by name"
               name="nameKeyword">
            <button 
               class="btn btn-primary"
               type="submit">
                  <span class="oi oi-magnifying-glass"></span>Filter
            </button>
            </div>
         </form>
      </div>
      <div class="col-md-3">
         <ul class="nav nav-pills card-header-pills">
            <li class="nav-item">
               <a class="nav-link active" href="{{route('categories.index')}}">Published</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="{{route('categories.trash')}}">Trash</a>
            </li>
         </ul>
      </div>
      <div class="col-md-3">
         <div class="text-right">
            <a class="btn btn-primary" href="{{route('categories.create')}}">Create Category</a>
         </div>
      </div>
   </div>
   
   <br>
   <div class="row">
      <div class="col-md-12">
         <table class="table table-bordered">
            <thead>
               <td><b>Name</b></td>
               <td><b>Slug</b></td>
               <td><b>Image</b></td>
               <td><b>Action</b></td>
            </thead>
            <tbody>
            @foreach($categories as $category)
               <tr>
                  <td>{{$category->name}}</td>
                  <td>{{$category->slug}}</td>
                  <td>
                  @if($category->image)
                     <img src="{{asset('storage/'. $category->image)}}" alt="" width="50px">
                  @else
                     N/A
                  @endif
                  </td>
                  <td>
                  <a class="btn btn-info text-white btn-sm" href="{{route('categories.edit', ['id' => $category->id])}}">Edit</a>
                  <form class="d-inline" onclick="return confirm('Move Category to Trash?')" action="{{route('categories.destroy', ['id'=>$category->id])}}" method="POST">
                     @csrf
                     <input type="hidden" value="DELETE" name="_method">
                     <input type="submit" class="btn btn-danger text-white btn-sm" value="Delete">
                  </form>
                  <a class="btn btn-primary btn-sm" href="{{route('categories.show', ['id' => $category->id])}}">Detail</a>
                  </td>
               </tr>
            @endforeach
            </tbody>
            <tfoot>
               <tr>
                  <td colSpan=10>
                     {{$categories->appends(Request::all())->links()}}
                  </td>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
@endsection