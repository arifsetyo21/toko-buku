<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{

   public function __construct(){
      $this->middleware(function($request, $next){
         if(Gate::allows('manage-categories')) return $next($request);
         abort(403, 'Anda tidak memiliki cukup hak akses');
      });
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index(Request $request)
   {
      // bagi data yang didapatkan menjadi paginate
      $categories = \App\Category::paginate(10);

      if($request->get("nameKeyword")){
         $keyword = $request->get("nameKeyword");
         $categories = \App\Category::where("name", "LIKE", "%$keyword%")->paginate(10);
      }
      // Mengembalikan view categories.index
      return view("categories.index", ["categories" => $categories]);
   }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function create()
   {
      // Mengembalikan view categories.create
      return view("categories.create");
   }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request)
   {
      // inisialisasi class Category
      $new_category = new \App\Category;
      // Tangkap request dari categories.create
      $new_category->name = $request->get("name");
      // Catat siapa yang buat
      $new_category->created_by = \Auth::user()->id;
      // buat category name menjadi karakter slug (tidak menyalahi aturan URL)
      $new_category->slug = Str::slug($request->get("name"));
      
      // cek ketersediaan file gambar upload
      if($request->file("image")) {
         // simpan gambar di directory storage/categories dengan sifat public, dan isi $file dengan nama file yang telah digenerate
         $image_path = $request->file("image")->store("categories", "public");
         $new_category->image = $image_path;
      }

      // simpan
      $new_category->save();
      // redirect ke categories.create
      return redirect()->route("categories.create")->with("status", "category successfully created");
   }  

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function show($id)
   {
      // Cari category dengan parameter id
      $category_to_show = \App\Category::findOrFail($id);
      // return nilai yang dipilih
      return view("categories.show", ["category" => $category_to_show]);
   }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function edit($id)
   {
      // Cari category dengan parameter id
      $category_to_edit = \App\Category::findOrFail($id);
      
      // Kembalikan nilai yang dipunya untuk diupdate
      return view("categories.edit", ["category" => $category_to_edit]);
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
      // Ambil data category dengan parameter id
      $category_to_edit = \App\Category::findOrFail($id);

      // Ganti nama category dengan data baru
      $category_to_edit->name = $request->get("name");
      // Catat siapa yang buat
      $category_to_edit->updated_by = \Auth::user()->id;
      // buat category name menjadi karakter slug (tidak menyalahi aturan URL)
      $category_to_edit->slug = Str::slug($request->get("name"));
      
         // cek apakah ada gambar yang diupload
        if($request->file("image")){
            // Cek ketersediaan di value image yang dipunyai sebelumnya dan image yang ada di app/public
           if($category_to_edit->image && file_exists(storage_path('app/public' . $category_to_edit->image))) {
               // Jika ada, lakukan delete terlebih dahulu agar image tidak menumpuk di storage
               \Storage::delete("public/" . $category_to_edit->image);
           }
            // Simpan image di directory storage/categories dengan sifat public
            $image_path = $request->file("image")->store("categories", "public");
            // ganti path image yang lama dengan yang baru
            $category_to_edit->image = $image_path;
        }
         // Simpan
         $category_to_edit->save();
         // return dengan denga notifikasi sukses
         return redirect()->route("categories.edit", ["id" => $id])->with("status", "category successfully created");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
   {
      // Ambil categori berdasarkan id
      $category_to_delete = \App\Category::findOrFail($id);
      // Lakukan Softdeletes
      $category_to_delete->delete();
      // Redirect ke index
      return redirect()->route("categories.trash")->with("status", "Category successfull move to trash");
   }

   public function trash()
   {
      // cari Category yang memiliki nilai pada kolom delete_at
      $category_trashed = \App\Category::onlyTrashed()->paginate(10);
      // kembalikan hasilnya ke view
      return view("categories.trash", ["categories" => $category_trashed]);
   }

   public function restore($id)
   {
      // cari category yang memiliki id
      $category_to_restore = \App\Category::withTrashed()->findOrFail($id);
      // Cek kategori apakah dalam keadaan trashed()
      if($category_to_restore->trashed()){
         // Restore categori
         $category_to_restore->restore();
      } else {
         // kembalikan ke categories.index dengan notif gagal
         return redirect()->route("categories.index")->with("status", "Category is not trash");
      }
      // Kembalikan ke categories.index dengan notif success
      return redirect()->route("categories.index")->with("status", "Category Successfully restored");
   }

   public function deletePermanent($id)
   {
      // Ambil category dengan id yang cocok, jangan lupa tambahkan withTrashed() untuk cek apakah kolom delete_at ada terisi
      $category_to_delete = \App\Category::withTrashed()->find($id);

      if(!$category_to_delete->trashed()){
         // Kembali ke categories.index dengan pesan gagal
         return redirect()->route("categories.trash")->with("status", "Can't delete permanent active category");
      } else {
         // delete 
         $category_to_delete->forceDelete();
         // kembali ke categories.trash
         return redirect()->route("categories.trash")->with("status", "Category Permanent deleted");
      }
   }

   // mengambil data via ajax, sehingga kita perlu siapkan route untuk ajax ini
   public function ajaxSearch(Request $request){

      $keyword = $request->get("q");
      $categories = \App\Category::where("name", "LIKE", "%$keyword%")->get();
      return $categories;
   }
}
