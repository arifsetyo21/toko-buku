<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class BookController extends Controller
{
   public function __construct(){
      $this->middleware(function($request, $next){
         if(Gate::allows('manage-books')) return $next($request);
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
      // ambil request status buku untuk menampilkan sesuai dengan status
      $status = $request->get("status");
      $keyword = $request->get("keyword") ? $request->get("keyword") : '';

      // cek apakah request buku berdasarkan status buku
      if($status){
         $books = \App\Book::with("categories")->where("title", "LIKE", "%$keyword%")->where("status", strtoupper($status))->paginate(10);
      } else {
         // index books
         $books = \App\Book::with("categories")->where("title", "LIKE", "%$keyword%")->paginate(10);
      }

      // Kembalikan ke view
      return view("books.index", ["books" => $books]);
   }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function create()
   {
      $categories = \App\Category::all();
      // Mengembalikan form create
      return view("books.create", ["categories" => $categories]);
   }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request)
   {
      // Menangkap data yang dikirim
      $new_book = new \App\Book;
      $new_book->title = $request->get("title");
      $new_book->description = $request->get("description");
      $new_book->author = $request->get('author');
      $new_book->publisher = $request->get('publisher');
      $new_book->price = $request->get('price');
      $new_book->stock = $request->get('stock');

      // ubah title menjadi slug
      $new_book->slug = Str::slug($request->get("title"));

      // ambil nilai action
      $new_book->status = $request->get("save_action");

      // catat siapa yang buat
      $new_book->created_by = \Auth::user()->id;

      // cek ketersediaan data cover
      $cover = $request->file("cover");
      if ($cover) {
         $cover_path = $request->file("cover")->store("covers", "public");

         $new_book->cover = $cover_path;
      }

      // Save data
      $new_book->save();

      $new_book->categories()->attach($request->get("categories"));

      if ($request->get("save_action") == "PUBLISH") {
         return redirect()->route("books.create")->with("status", "Book successfully saved and published");
      } else {
         return redirect()->route("books.create")->with("status", "Book successfully saved as draft");
      }
   }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function edit($id)
   {
      // Ambil category
      $categories = \App\Category::all();

      // Ambil id untuk di edit
      $book_to_edit = \App\Book::findOrFail($id);

      return view("books.edit", ["book" => $book_to_edit, "categories" => $categories]);
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
      //tangkap book_i
      $book_to_update = \App\Book::findOrFail($id);

      $book_to_update->title = $request->get("title");
      $book_to_update->description = $request->get("description");
      $book_to_update->author = $request->get('author');
      $book_to_update->publisher = $request->get('publisher');
      $book_to_update->price = $request->get('price');
      $book_to_update->stock = $request->get('stock');

      // ubah title menjadi slug
      $book_to_update->slug = Str::slug($request->get("title"));

      // ambil nilai action
      $book_to_update->status = $request->get("status");

      // catat siapa yang buat
      $book_to_update->updated_by = \Auth::user()->id;

      // cek ketersediaan data cover
      $new_cover = $request->file("cover");
      if ($new_cover) {
         if($book_to_update->cover && file_exists(storage_path("app/public" . $book_to_update->cover))){
            \Storage::delete("public/" . $book_to_update->cover);
         }

         $cover_path = $request->file("cover")->store("covers", "public");

         $book_to_update->cover = $cover_path;
      }

      // Save data
      $book_to_update->save();
      
      $book_to_update->categories()->sync($request->get("categories"));

      return redirect()->route("books.edit", ["id" => $book_to_update->id])->with("status", "Book successfully updated");
   }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
   {
      // Ambil data sesuai $id
      $book_to_destroy = \App\Book::findOrFail($id);

      // Delete
      $book_to_destroy->delete();

      // redirect ke halaman index
      return redirect()->route("books.index")->with("status", "Book successfully move to trash");
   }

   public function trash(){
      // Ambil semua data table buku, yang ada ditrash       
      $books_trashed = \App\Book::onlyTrashed()->paginate(10);
      // Kembalikan ke view
      return view("books.trash", ["books_trashed" => $books_trashed]);
   }

   public function restore($id){
      // Ambil buku trashed yang memiliki id sesuai request
      $book_to_restore = \App\Book::withTrashed()->findOrFail($id);

      // jika ada, lakukan restore
      if($book_to_restore){
         $book_to_restore->restore();
         return redirect()->route("books.index")->with("status", "Book successfully restored");
      } else {
         return redirect()->route("books.index")->with("status", "Book is not in trash");
      }
   }

   public function deletePermanent($id){
      // ambil id buku
      $book_to_destroy = \App\Book::withTrashed()->findOrFail($id);

      // cek apakah buku telah di softdelete
      if(!$book_to_destroy->trashed()){
         // jika false, kembalikan dengan status_type alert
         return redirect()->route("books.trash")->with("status", "Book is not in trash!")->with("status_type", "alert");
      } else {
         // jika true, lakukan detach(), kemudian forceDelete()
         $book_to_destroy->categories()->detach();
         $book_to_destroy->forceDelete();
      }

      // kembalikan ke halaman awal
      return redirect()->route("books.trash")->with("status", "Book permanently deleted");
   }
}
