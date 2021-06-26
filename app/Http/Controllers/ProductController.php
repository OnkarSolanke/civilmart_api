<?php

namespace App\Http\Controllers;

use App\Product;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function __construct(){
        $this->middleware(['auth.vendor','auth:api'])->except('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::get();
        if($request->has('key')){
            $productsFilted = [];

            foreach ($products as $key => $product) {
                $a = $product->name . ' '. $product->description;
                $search = $request->key;
                if(preg_match("/{$search}/i", $a)) {
                    array_push($productsFilted,$product);
                }
            }
            $products = $productsFilted;
        }
        return response()->json([
            'products' =>  $products,
            'units' => Unit::get()
        ]);
    }
    public function materailSearch(Request $request)
    {
        if($request->has('key')){
            return Product::where('name',$request->key)->get();
        }
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
        // dd($request->all());
        $rules = array( 
            'name'  => 'required',
            'unit' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        );
        $v = Validator::make($request->all(), $rules);
        if ( ! $v->passes()){
            $messages = $v->messages();
        
            $verrors =  $messages->all();
            return array(
                'status' => 'Error',
                'status_code' => 305,
                'massage' => $verrors
            );
        }

        $fileNameToStore = '';
        $path = '';
        if($request->hasFile('file')){

            // Get filename with the extension
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('file')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('file')->storeAs('public/avatars/products',$fileNameToStore);

        }
       $product = Product::create([
           'user_id' => Auth::user()->id,
           'name' => $request->name,
           'price' => $request->price,
           'unit' => $request->unit,
           'available' => $request->stock,
           'description' => $request->dics,
           'avtar' => $fileNameToStore,
           'avtar_full_path' => $path,
       ]);
       return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
