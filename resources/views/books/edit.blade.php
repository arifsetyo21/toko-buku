{{-- Mengextends template layout global --}}
@extends("layouts.global")

{{-- @section digunakan untuk mengisi @yield yang berada pada parent view--}}
@section("title") Book Update @endsection

@section("pageTitle")
   Book Edit
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
         action="{{route('books.update', ['id' => $book->id])}}" 
         method="POST">

         @csrf

         <input type="hidden" name="_method" value="PUT">

         <label for="title">Book title</label>
         <input class="form-control" type="text" name="title" id="title" value="{{ $book->title }}">
         <br>

         @if($book->cover)
            <span>Current image</span><br>
            <img src="{{asset('storage/' . $book->cover)}}" alt="" width="100px">
            <br>
         @else
            <img src="{{asset('storage/no-image.png')}}" alt="" width="100px">
            <br>
         @endif

         <br>
         <label for="slug">Slug</label><br>
         <input type="text" class="form-control" value="{{$book->slug}}" disabled>
         <br>
         <label for="cover">Books Image</label>
         <input class="form-control" type="file" name="cover" id="cover">
         <br>
         <label for="description">Description</label>
         <br>
         <textarea name="description" id="description" class="form-control" placeholder="Give a description about this book">{{$book->description}}</textarea>
         <br>
         <label for="categories">Categories</label>
         <div class="row">
            <div class="col-md-4">
               <ul class="">
                  @foreach ($book->categories as $category)
                     <li class="">{{$category->name}}</li>
                  @endforeach
               </ul>
            </div>
         </div>
         <select multiple class="form-control" name="categories[]" id="categories">
            @foreach($categories as $category)
               <option value="{{$category->id}}">{{$category->name}}</option>
            @endforeach
         </select>
         <br>
         <br>
         <label for="stock">Stock</label>
         <br>
         <input type="number" class="form-control" id="stock" name="stock" min="0" value="{{$book->stock}}">
         <br>
         <label for="author">Author</label>
         <br>
         <input type="text" class="form-control" name="author" id="author" placeholder="Book author" value="{{$book->author}}">
         <br> 
         <label for="publisher">Publisher</label>
         <br>
         <input type="text" class="form-control" name="publisher" id="publisher" placeholder="Book publisher" value="{{$book->publisher}}">
         <br>
         <label for="price">Price</label>
         <br>
         <input type="number" class="form-control" name="price" id="price" placeholder="Book price" value="{{$book->price}}">
         <br>
         <label for="status">Status</label>
         <select name="status" id="status" class="form-control">
            <option value="PUBLISH" {{$book->status == 'PUBLISH' ? 'selected' : ''}}>PUBLISH</option>
            <option value="DRAFT" {{$book->status == 'DRAFT' ? 'selected' : ''}}>DRAFT</option>
         </select>
         <br>
         <button type="submit" class="btn btn-primary">UPDATE</button>
      </form>
   </div>
@endsection