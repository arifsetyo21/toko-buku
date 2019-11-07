@extends("layouts.global")

@section("title")
Books List
@endsection

@section("pageTitle")
   Books List
@endsection

@section("content")

   @if(session('status'))
      <div class="alert alert-success">
         {{session('status')}}
      </div>
   @endif
   
   <div class="row">
      <div class="col-md-6">
         <form action="{{route('books.index')}}">
         <div class="input-group mb-3">
            <input
               type="text"
               value="{{Request::get('keyword')}}"
               name="keyword"
               class="form-control col-md-12"
               placeholder="Filter berdasarkan judul"
               >
         </div>
      </div>
      <div class="col-md-4 float-left">
         <button type="submit" class="btn btn-primary">
            Filter
         </button>
      </div>
         </form>
      <div class="col-md-2 text-right">
         <a class="btn btn-primary" href="{{route('books.create')}}">Create Book</a>
      </div>
   </div>
   <div class="row mb-2">
      <div class="col-md-12">
         <ul class="nav nav-pills">
            <li class="nav-item">
               <a class="nav-link {{Request::get('status') == NULL && Request::path() == 'books' ? 'active' : ''}}" href="{{route('books.index')}}">All</a>
            </li>
            <li class="nav-item">
               <a class="nav-link {{Request::get('status') == 'publish' ? 'active' : ''}}" href="{{route('books.index', ['status' => 'publish'])}}">Publish</a>
            </li>
            <li class="nav-item">
               <a class="nav-link {{Request::get('status') == 'draft' ? 'active' : ''}}" href="{{route('books.index', ['status' => 'draft'])}}">Publish</a>
            </li>
            <li class="nav-item">
               <a class="nav-link {{Request::path() == 'books/trash' ? 'active' : ''}}" href="{{route('books.trash')}}">Trash</a>
            </li>
         </ul>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12">
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th><b>Cover</b></th>
                  <th><b>Title</b></th>
                  <th><b>Author</b></th>
                  <th><b>Status</b></th>
                  <th><b>Categories</b></th>
                  <th><b>Stock</b></th>
                  <th><b>Price</b></th>
                  <th><b>Action</b></th>
               </tr>
            </thead>
            <tbody>
               @foreach($books as $book)
                  <tr>
                     <td>
                        @if($book->cover)
                           <img src="{{asset('storage/' . $book->cover)}}" alt="" width="70px">
                        @else
                           <img src="{{asset('storage/no-image.png')}}" alt="">
                        @endif
                     </td>
                     <td>{{$book->title}}</td>
                     <td>{{$book->author}}</td>
                     <td>
                        @if($book->status == "DRAFT")
                           <span class="badge bg-dark text-white">{{$book->status}}</span>
                        @else
                           <span class="badge badge-success">{{$book->status}}</span>
                        @endif
                     </td>
                     <td>
                        <ul class="list-group">
                           @foreach($book->categories as $category)
                              <li class="list-group-item">{{$category->name}}</li>
                           @endforeach
                        </ul>
                     </td>
                     <td>{{$book->stock}}</td>
                     <td>{{$book->price}}</td>
                     <td>
                        <a class="btn btn-info text-white btn-sm" href="{{route('books.edit', ['id' => $book->id])}}">Edit</a>
                        <form class="d-inline" onsubmit="return confirm ('Move to trash?')" action="{{route('books.destroy', ['id'=>$book->id])}}" method="POST">
                           @csrf
                           <input type="hidden" value="DELETE" name="_method">
                           <button type="submit" class="btn btn-danger text-white btn-sm">Delete</button>
                        </form>
                        <a class="btn btn-primary btn-sm" href="{{route('books.show', ['id' => $book->id])}}">Detail</a>
                     </td>
                  </tr>
               @endforeach
            </tbody>
            <tfoot>
               <tr>
                  <td colspan=10>
                     {{$books->appends(Request::all())->links()}}
                  </td>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
@endsection