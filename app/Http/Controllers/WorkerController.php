<?php

namespace App\Http\Controllers;

use App\Address;
use App\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Worker = Worker::with('address')->get();
        return $Worker;
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
        $rules = array( 
            'firstName'  => 'required',
            'midleName'  => 'required',
            'lastName' => 'required',
            'mobile' => 'required|numeric',
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
            $path = $request->file('file')->storeAs('public/avatars/workers',$fileNameToStore);

        }
        $vendor =  Worker::create([
            'first_name' => $request->firstName,
            'midle_name' => $request->midleName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'adhar_no' => $request->adhar,
            'about' => $request->about,
            'avtar' => $fileNameToStore,
            'skill' => $request->skill,
            'qualification' => $request->qualif,
            'avtar_full_path' => $path,
        ]);

        Address::create([
            'user_id' => $vendor->id,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'pine' => $request->pine,
            'type' => 'permanent',
        ]);

        Address::create([
            'user_id' => $vendor->id,
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
     * @param  \App\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function show(Worker $worker)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function edit(Worker $worker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Worker $worker)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function destroy(Worker $worker)
    {
        //
    }
}
