<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\User as UserResources;
use DB; 

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $users=User::all();
        //  return response()->json($users,201);
          return UserResources::collection($users);
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
        //
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
        //
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

    public function getAllDrivers(){
        $allDrivers = DB::table('users')->where('whichUser','=','driver')->get();
        return $allDrivers;
    }

    public function getAllUsers(){
        $allUsers = DB::table('users')->where('whichUser','=','user')->get();
        return $allUsers;
    }
 
    public function getAvailableDrivers(){
        $availableDrivers = DB::table('users')
            ->where('whichUser','=','driver')
            ->where('isAvailable','=','available')
            ->orderBy('updated_at', 'DESC')
            ->get();
        return $availableDrivers;
    }

    public function addDriver(Request $request){
        
        // $photo = $request->file('file');
        // $name = time().'.'.$photo->getClientOriginalExtension();
        // $destinationPath = 'assets/images';
        // $photo->move($destinationPath, $name);
        $image = $request->file('image');
        $new_image_name =rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'),$new_image_name);
 
        $driver=DB::table('users')->insert(
            [   
                'image'=> $new_image_name,
                'password'=> bcrypt($request->password),
                'email' => $request->email,
                'phone'=> $request->phone,
                'username'=> $request->userName,
                'city'=> $request->city,
                'state'=>$request->state,
                'zip'=>$request->zip,
                'country'=>$request->country,
                'age'=>$request->age,
                'experience'=>$request->experience,
                'cabNumber'=>$request->cabNumber,
                'gender'=>$request->gender,
                'vacancy'=>$request->vacancy,
                'address'=>$request->address,
                'whichUser'=> "driver",
            ]
        );
        if ($driver) {
            $document = [
                "result"=>"success",
                "message"=>"Record saved successfully",
                "title"=>"Success",
            ];
        }else{
            $document = [
                "result"=>"error",
                "message"=>"Record saving Failed!",
                "title"=>"Error",
            ];
        }
        return response()->json($document,200);
    }

    public function deleteDriver(Request $request, $id){
        // return $id;
        $deleted = DB::table('users')->where('id', '=', $id)->delete();
    if($deleted){
            $document = [
                "result"=>"success",
                "message"=>"Record deleted successfully",
                "title"=>"Success",
            ];
        }else{
            $document = [
                "result"=>"error",
                "message"=>"Record deleting Failed!",
                "title"=>"Error",
            ];
        }
        return response()->json($document,200);
    }
    public function editDriver(Request $request, $id){
        // return $request;
        if($request->hasFile('image')){
            // return "ASdasasf";
            // $photo = $request->file('file');
            // $name = time().'.'.$photo->getClientOriginalExtension();
            // $destinationPath = 'assets/images';
            // $photo->move($destinationPath, $name);

            $image = $request->file('image');
            $new_image_name =rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'),$new_image_name);

            $updated = DB::table('users')->where('id',$id)->update(
                [
                    'image' => $new_image_name,
                    'userName' => $request->userName,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'phone' => $request->phone,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'currentLocation' => $request->currentLocation,
                    'cabNumber' => $request->cabNumber,
                    'experience' => $request->experience,
                    'address' => $request->address,
                    'age' => $request->age,
                    'vacancy' => $request->vacancy,
                    'gender' => $request->gender,
                    'isAvailable'=>$request->isAvailable,
                ]);
        }else{
            // return "fafafaa";
            $updated = DB::table('users')->where('id',$id)->update(
                [
                    'userName' => $request->userName,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'phone' => $request->phone,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'currentLocation' => $request->currentLocation,
                    'cabNumber' => $request->cabNumber,
                    'experience' => $request->experience,
                    'address' => $request->address,
                    'age' => $request->age,
                    'vacancy' => $request->vacancy,
                    'gender' => $request->gender,
                    'isAvailable'=>$request->isAvailable,
                ]);
                $update=true;
        }
        if($updated==0 && $update==true){
            $document = [
                "result"=>"success",
                "message"=>"Record updated successfully",
                "title"=>"Success",
            ];
        }elseif($updated){
                $document = [
                    "result"=>"success",
                    "message"=>"Record updated successfully",
                    "title"=>"Success",
                ];
            }else{
                $document = [
                    "result"=>"error",
                    "message"=>"Record update Failed!",
                    "title"=>"Error",
                ];
            }
            return response()->json($document,200);
    }

    public function deleteUser(Request $request, $id){
        // return $id;
        $deleted = DB::table('users')->where('id', '=', $id)->delete();
    if($deleted){
            $document = [
                "result"=>"success",
                "message"=>"Record deleted successfully",
                "title"=>"Success",
            ];
        }else{
            $document = [
                "result"=>"error",
                "message"=>"Record deleting Failed!",
                "title"=>"Error",
            ];
        }
        return response()->json($document,200);
    }

    public function getDriver($id){
        $driver = DB::table('users')->where('id','=',$id)->get();
        return $driver;
    }
    public function editUser(Request $request, $id){
        if($request->hasFile('file')){
            $photo = $request->file('file');
            $name = time().'.'.$photo->getClientOriginalExtension();
            $destinationPath = 'assets/images';
            $photo->move($destinationPath, $name);

            $updated = DB::table('users')->where('id',$id)->update(
                [
                    'image' => $name,
                    'userName' => $request->userName,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'country' => $request->country,
                    'address' => $request->address,
                ]);
        }else{
            // return "fafafaa";
            $updated = DB::table('users')->where('id',$id)->update(
                [
                    'userName' => $request->userName,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'country' => $request->country,
                    'address' => $request->address,
                ]);
            $update=true;
        }
        if($updated==0 && $update==true){
            $document = [
                "result"=>"success",
                "message"=>"Record updated successfully",
                "title"=>"Success",
            ];
        }elseif($updated){
            $document = [
                "result"=>"success",
                "message"=>"Record updated successfully",
                "title"=>"Success",
            ];
        }else{
            $document = [
                "result"=>"error",
                "message"=>"Record update Failed!",
                "title"=>"Error",
            ];
        }
        return response()->json($document,200);
    }

    public function getUser($id){
        $user= DB::table('users')->where('id','=',$id)->get();
        return $user;
    }
}

