{{-- Mengextends template layout --}}
@extends("layouts.global")

@section("title") Categoies List @endsection

@section("pageTitle")
   Categories Trashed List
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
               <a class="nav-link" href="{{route('categories.index')}}">Published</a>
            </li>
            <li class="nav-item">
               <a class="nav-link active" href="{{route('categories.trash')}}">Trash</a>
            </li>
         </ul>
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
                  <a class="btn btn-info text-white btn-sm" href="{{route('categories.restore', ['id' => $category->id])}}">Restore</a>
                  <form class="d-inline" onsubmit="confirm('Pemanent delete category?')" action="{{route('categories.delete-permanent', ['id'=>$category->id])}}" method="POST">
                     <input type="hidden" value="DELETE" name="_method">
                     @csrf
                     <button type="submit" class="btn btn-danger text-white btn-sm">Delete Permanent</button>
                  </form>
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