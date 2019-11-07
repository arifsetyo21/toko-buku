@extends('layouts.global')
@section('title') Edit User @endsection
@section('content')

@section("pageTitle")
   User Edit
@endsection

<div class="col-md-8">

   @if(session('status'))
      <div class="alert alert-success">
         {{session('status')}}
      </div>
   @endif

   <form enctype="multipart/form-data" class="bg-white shadow-sm p-3" action="{{route('users.update', ['id'=>$user->id])}}" method="POST">
      @csrf
      <input type="hidden" value="PUT" name="_method">
      <label for="name">Name</label>
      <input 
         value="{{$user->name}}"
         type="text" 
         class="form-control"
         placeholder="Full Name"
         name="name"
         id="name">
      <br>
      <label for="username">Username</label>
      <input 
         type="text" 
         value="{{$user->username}}"
         class="form-control text-muted"
         placeholder="username"
         name="username"
         id="username"
         disabled
         >
      <br>
      <label for="">Roles</label>
      <br>
      <input 
         type="checkbox" 
         {{in_array("ADMIN", json_decode($user->roles)) ? "checked" : ""}}
         class="form-control"
         name="roles[]"
         id="ADMIN"
         value="ADMIN"
         >
      <label for="ADMIN">Administrator</label>
      <input 
         type="checkbox" 
         {{in_array("STAFF", json_decode($user->roles, TRUE)) ? "checked" : ""}}
         class="form-control"
         name="roles[]"
         id="STAFF"
         value="STAFF"
         >
      <label for="STAFF">Staff</label>
      <input 
         type="checkbox" 
         {{in_array("CUSTOMER", json_decode($user->roles)) ? "checked" : ""}}
         class="form-control"
         name="roles[]"
         id="CUSTOMER"
         value="CUSTOMER"
         >
      <label for="CUSTOMER">Customer</label>
      <br>
      <br>
      <label for="phone">Phone Number</label>
      <input 
         type="text"
         value="{{$user->phone}}"
         name="phone"
         id="phone"
         class="form-control">
      <br>
      <label for="address">Address</label>
      <input 
         type="text"
         value="{{$user->address}}"
         name="address"
         id="address"
         class="form-control">
      <br>
      <label for="avatar">Avatar Image</label>
      <br>
      @if($user->avatar)
         <img src="{{asset('storage/' . $user->avatar)}}" alt="">
         <br>
      @else
         No Avatar
      @endif
      <input
         id="avatar"
         name="avatar"
         type="file"
         class="form-control">
         <small class="text-muted">Kosongkan jika tidak ingin merubah avatar</small>
         <hr class="my-3">
         
         <br>
      <label for="email">Email</label>
      <input
         class="form-control text-muted"
         value="{{$user->email}}"
         placeholder="user@mail.com"
         type="text"
         name="email"
         id="email"
         disabled/>
      <br>
      <label for="">Status</label>
      <br>
      <input 
         {{$user->status == "ACTIVE" ? "checked" : ""}}
         type="radio"
         value="ACTIVE"
         class="form-control"  
         id="active"
         name="status"
         >
         <label for="active">ACTIVE</label>
      <input 
         {{$user->status == "INACTIVE" ? "checked" : ""}}
         type="radio"
         value="INACTIVE"
         class="form-control"  
         id="inactive"
         name="status"
         >
         <label for="inactive">INACTIVE</label>
      <br>
      <label for="password">Password</label>
      <input
         class="form-control"
         placeholder="password"
         type="password"
         name="password"
         id="password"/>
      <br>
      <label for="password_confirmation">Password Confirmation</label>
      <input
         class="form-control"
         placeholder="password confirmation"
         type="password"
         name="password_confirmation"
         id="password_confirmation"/>
      <br>
      <input
         class="btn btn-primary"
         type="submit"
         value="Save"/>
   </form>
</div>
@endsection