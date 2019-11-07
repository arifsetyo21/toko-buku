<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

   public function __construct(){
      $this->middleware(function($request, $next){
         if(Gate::allows('manage-users')) return $next($request);
         abort(403, 'Anda tidak memiliki cukup hak akses');
      });
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   // menambah parameter pada index()
   public function index(Request $request)
   {
      //menggunakan helper paginate
      $users = \App\User::paginate(10);
      // menambahkan kata kunci pencarian
      $filterKeyword = $request->get("keyword");

      // cek isi $user->status
      $status = $request->get("status");
      // cek apakah $filterKeyword ada isinya
      if($filterKeyword){
         if($status){
            // jika ada eksekusi query where LIKE
            $users = \App\User::where('email', 'LIKE', "%$filterKeyword%")
            // filter status
            ->where("status", $status)
            // tampilkan 10 record per halaman
            ->paginate(10);
         } else {
            // jika ada eksekusi query where LIKE
            $users = \App\User::where('email', 'LIKE', "%$filterKeyword%")->paginate(10);
         }
      }

      // Mengembalikan view users.index dengan data users
      return view("users.index", ["users" => $users]);
   }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Mengembalikan users.create sebagai view 
        return view("users.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request)
   {
      // Menambahkan validasi
      \Validator::make($request->all(), [
         "name" => "required|min:5|max:100",
         "username" => "required|min:5|max:20|unique:users",
         "roles" => "required",
         "phone" => "required|digits_between:10,12",
         "address" => "required|min:20|max:200",
         "avatar" => "required",
         "email" => "required|email|unique:users",
         "password" => "required",
         "password_confirmation" => "required|same:password"
      ])->validate();
      
      //Menangkap request dan menyimpan ke database
      $new_user = new \App\User;
      $new_user->username = $request->get('username');
      $new_user->address = $request->get('address');
      $new_user->email = $request->get('email');
      $new_user->password = \Hash::make($request->get('password'));
      $new_user->name = $request->get('name');
      $new_user->roles = json_encode($request->get('roles'));
      $new_user->phone = $request->get('phone');

      if ($request->file('avatar')) {
         $image_path = $request->file('avatar')->store('avatars', 'public');
         $new_user->avatar = $image_path;
      }

      $new_user->save();
      return redirect()->route("users.create")->with("status", "user successfully created");

   }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function show($id)
   {
      //cari user berdasarkan id
      $user = \App\User::findOrFail($id);
      // redirect ke halaman detail user
      return view('users.show', ['user' => $user]);

   }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Cari data berdasarkan id, lempar ke view
        $user = \App\User::findOrFail($id);

        return view("users.edit", ["user" => $user]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function update(Request $request, $id)
   {
      //ambil request dari user.edit
      $user = \App\User::findOrFail($id);
      $user->name = $request->get("name");
      $user->roles = json_encode($request->get("roles"));
      $user->address = $request->get("address");
      $user->phone = $request->get("phone");
      $user->status = $request->get("status");

      // cek apakah terdapat file upload 'avatar', jika ada check lagi apakah user memiliki $user->avatar di storage?
      // semisal ada lakukan delete pada file tersebut
      if($request->file('avatar')) {
         if($user->avatar && file_exists(storage_path('app/public/' . $user->avatar))){
            \Storage::delete('public/' . $user->avatar);
         }
         // store file gambar ke directory storage/app/public/avatars/
         $image_path = $request->file('avatar')->store('avatars', 'pubilc');
         // set user->avatar dengan nama $file dengan nama yang sudah di acak
         $user->avatar = $image_path;
      }

      // simpan user
      $user->save();
      // alihkan ke users.edit dan beri notifikasi
      return redirect()->route('users.edit', ['id' => $id])->with('status', 'User successfully updated');
   }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
   {
      //instansiasi class user berdasarkan id
      $user = \App\User::findOrFail($id);
      //hapus user
      $user->delete();

      // redirect user ke users.index, dan beri notifikasi  
      return redirect()->route('users.index', ['id' => $id])->with('status', 'User successfully deleted');
   }
}
