@extends("layouts.global")

@section("title")
Users List
@endsection

@section("pageTitle")
   Users List
@endsection

@section("content")

   @if(session('status'))
      <div class="alert alert-success">
         {{session('status')}}
      </div>
   @endif
   
   <div class="row">
      <div class="col-md-6">
         <form action="{{route('users.index')}}">
         <div class="input-group mb-3">
            <input
               type="text"
               value="{{Request::get('keyword')}}"
               name="keyword"
               class="form-control col-md-12"
               placeholder="Filter berdasarkan email"
               >
         </div>
      </div>
      <div class="col-md-4">
         <input {{Request::get('status') == 'ACTIVE' ? 'checked' : ''}}
            value="ACTIVE"
            name="status"
            type="radio"
            class="form-control"
            id="active"
            type="text">
         <label for="active">Active</label>
         <input {{Request::get('status') == 'INACTIVE' ? 'checked' : ''}}
            value="INACTIVE"
            name="status"
            type="radio"
            class="form-control"
            id="inactive"
            type="text">
         <label for="inactive">Inactive</label>

         <button type="submit" class="btn btn-primary">
            Filter
         </button>
      </div>
         </form>
      <div class="col-md-2 text-right">
         <a class="btn btn-primary" href="{{route('users.create')}}">Create User</a>
      </div>
   </div>

   <table class="table table-bordered">
      <thead>
         <tr>
            <th><b>Name</b></th>
            <th><b>Username</b></th>
            <th><b>Email</b></th>
            <th><b>Avatar</b></th>
            <th><b>Status</b></th>
            <th><b>Action</b></th>
         </tr>
      </thead>
      <tbody>
         @foreach($users as $user)
            <tr>
               <td>{{$user->name}}</td>
               <td>{{$user->username}}</td>
               <td>{{$user->email}}</td>
               <td>
                  @if($user->avatar)
                     <img src="{{asset('storage/' . $user->avatar)}}" alt="" width="70px">
                  @else
                     N/A
                  @endif
               </td>
               <td>
                  @if($user->status == "ACTIVE")
                     <span class="badge badge-success">
                        {{$user->status}}
                     </span>
                  @else
                     <span class="badge badge-danger">
                        {{$user->status}}
                     </span>
                  @endif
               </td>
               <td>
                  <a class="btn btn-info text-white btn-sm" href="{{route('users.edit', ['id' => $user->id])}}">Edit</a>
                  <form class="d-inline" onsubmit="confirm('Delete this user permanently?')" action="{{route('users.destroy', ['id'=>$user->id])}}" method="POST">
                     @csrf
                     <input type="hidden" value="DELETE" name="_method">
                     <button type="submit" class="btn btn-danger text-white btn-sm">Delete</button>
                  </form>
                  <a class="btn btn-primary btn-sm" href="{{route('users.show', ['id' => $user->id])}}">Detail</a>
               </td>
            </tr>
         @endforeach
      </tbody>
      <tfoot>
         <tr>
            <td colspan=10>
               {{$users->appends(Request::all())->links()}}
            </td>
         </tr>
      </tfoot>
   </table>
@endsection