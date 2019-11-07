<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

   public function __construct(){
      $this->middleware(function($request, $next){
         if(Gate::allows('manage-orders')) return $next($request);
         abort(403, 'Anda tidak memiliki cukup hak akses');
      });
   }

   public function index(Request $request)
   {

      $status = $request->get("status");
      $buyer_email = $request->get("buyer_email");

      // Membuat list order
      $orders = \App\Order::with('user')->with('books')->whereHas('user', function($query) use ($buyer_email) {$query->where('email', 'LIKE', "%$buyer_email%");})->where('status', 'LIKE', "%$status%")->paginate(10);      

      return view('orders.index', ['orders' => $orders]);
   }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
      // Ambil data order berdasarkan id
      $order_to_edit = \App\Order::findOrFail($id);

      return view("orders.edit", ["order" => $order_to_edit]);
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
      // Ambil data menggunakan id yang akan di update
      $order_to_update = \App\Order::findOrFail($id);

      $order_to_update->status = $request->get("status");

      $order_to_update->save();

      return redirect()->route("orders.edit", ["id" => $id])->with("status", "Order status successfully updated");
   }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
