@extends("layouts.global")

@section("title")
Create New User
@endsection

@section("pageTitle")
   Create User
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
        action="{{route('users.store')}}" 
        method="POST">
        @csrf
        
        <label for="name">Name</label>
        <input 
            type="text" 
            class="form-control {{$errors->first('name') ? 'is-invalid' : ''}}"
            placeholder="Full Name"
            name="name"
            id="name"
            value="{{old('name')}}">
         <div class="invalid-feedback">{{$errors->first('name')}}</div>
        <br>
        <label for="username">Username</label>
        <input 
            type="text" 
            class="form-control {{$errors->first('username') ? 'is-invalid' : ''}}"
            placeholder="username"
            name="username"
            id="username"
            value="{{old('username')}}">
            <div class="invalid-feedback">{{$errors->first('username')}}</div>
        <br>
        <label for="">Roles</label>
        <br>
            <input 
               type="checkbox" 
               class="form-control {{$errors->first('roles') ? 'is-invalid' : ''}}"
               name="roles[]"
               id="ADMIN"
               value="ADMIN"
               >
            <label for="ADMIN">Administrator</label>
               <input 
                  type="checkbox" 
                  class="form-control {{$errors->first('roles') ? 'is-invalid' : ''}}"
                  name="roles[]"
                  id="STAFF"
                  value="STAFF"/>
            <label for="STAFF">Staff</label>
               <input 
                  type="checkbox" 
                  class="form-control {{$errors->first('roles') ? 'is-invalid' : ''}}"
                  name="roles[]"
                  id="CUSTOMER"
                  value="CUSTOMER"
                  >
            <label for="CUSTOMER">Customer</label>
            <div class="invalid-feedback">{{$errors->first('roles')}}</div>
        <br>
        <br>
        <label for="phone">Phone Number</label>
         <input 
            type="text"
            name="phone"
            id="phone"
            class="form-control {{$errors->first('phone') ? 'is-invalid' : ''}}"
            value="{{old('phone')}}">
         <div class="invalid-feedback">{{$errors->first('phone')}}</div>
         <br>
         <label for="address">Address</label>
         <input 
            type="text"
            name="address"
            id="address"
            class="form-control {{$errors->first('address') ? 'is-invalid' : ''}}"
            value="{{old('address')}}">
         <div class="invalid-feedback">{{$errors->first('address')}}</div>
         <br>
         <label for="avatar">Avatar Image</label>
         <br>
         <input
            id="avatar"
            name="avatar"
            type="file"
            class="form-control {{$errors->first('avatar') ? 'is-invalid' : ''}}"
            value="{{old('avatar')}}">
         <div class="invalid-feedback">{{$errors->first('avatar')}}</div>
            <hr class="my-3">
         <label for="email">Email</label>
         <input
            class="form-control {{$errors->first('email') ? 'is-invalid' : ''}}"
            placeholder="user@mail.com"
            type="text"
            name="email"
            id="email"
            value="{{old('email')}}"/>
         <div class="invalid-feedback">{{$errors->first('email')}}</div>
         <br>
         <label for="password">Password</label>
         <input
            class="form-control {{$errors->first('password') ? 'is-invalid' : ''}}"
            placeholder="password"
            type="password"
            name="password"
            id="password"/>
         <div class="invalid-feedback">{{$errors->first('password')}}</div>
         <br>
         <label for="password_confirmation">Password Confirmation</label>
         <input
            class="form-control {{$errors->first('password_confirmation') ? 'is-invalid' : ''}}"
            placeholder="password confirmation"
            type="password"
            name="password_confirmation"
            id="password_confirmation"/>
         <div class="invalid-feedback">{{$errors->first('password_confirmation')}}</div>
         <br>
         <input
            class="btn btn-primary"
            type="submit"
            value="Save"/>
      </form>
</div>
@endsection