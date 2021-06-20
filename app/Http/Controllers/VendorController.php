<?php

namespace App\Http\Controllers;

use App\Address;
use App\User;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendor = Vendor::with('address')->get();
        return $vendor;
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
        $user = User::where('email', '=', $request->email)->first();
        $rules = array( 
            'firstName'  => 'required',
            'midleName'  => 'required',
            'lastName' => 'required',
            'mobile' => 'required|numeric',
            'adhar' => 'required|numeric',
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
        // dd(isset($user));
        if (isset($user)) {
            return array(
                'status' => 'Error',
                'status_code' => 305,
                'massage' => ['Email Id Already Exist']
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
            $path = $request->file('file')->storeAs('public/avatars/vendors',$fileNameToStore);

        }

        $user = User::create([
            'name' => $request->firstName . ' ' . $request->lastName,
            'email' => $request->email,
            'password' => bcrypt('plus@123'),
        ]);

       $vendor =  Vendor::create([
            'first_name' => $request->firstName,
            'midle_name' => $request->midleName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'user_id' => $user->id,
            'adhar_no' => $request->adhar,
            'about' => $request->about,
            'avtar' => $fileNameToStore,
            'avtar_full_path' => $path,
        ]);

        Address::create([
            'user_id' => $user->id,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'pine' => $request->pine,
            'type' => 'permanent',
        ]);

        Address::create([
            'user_id' => $user->id,
            'address' => $request->tempAddress,
            'city' => $request->tempCity,
            'country' => $request->tempCountry,
            'pine' => $request->tempPine,
            'type' => 'temporary',
        ]);
        return $vendor;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        //
    }
}
