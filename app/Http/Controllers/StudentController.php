<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
 
    public function index()
    {
        $students = Students::all(); 
        if ($students->isNotEmpty()) {
            $data = [
                'status' => 200,
                'students' => $students,
            ];
        } else {
            $data = [
                'status' => 404,
                'message' => 'No records found',
            ];
        }
        
        return response()->json($data, 200);
    }


    public function store(Request $request)
    {
   
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:91',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|digits_between:10,15',
        ]);


        if ($validation->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validation->messages(),
            ], 422);
        }

       
        $student = Students::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        if ($student) {
            return response()->json([
                'status' => 200,
                'message' => 'Student has been added successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong',
            ], 500);
        }
    }
    public function show($id){
        $student = Students::find($id);
        if($student){
          return response()->json([
                'status' => 200,
                'message' => $student,
            ], 200);

        }
        else{
            return response()->json([
                'status' => 404,
                'message'=> "No such Student Found!"
            ],404);
        }
    }
 public function edit($id)
{
    $student = Students::find($id);
    if ($student) {
        return response()->json([
            'status' => 200,
            'message' => $student,
        ], 200);
    } else {
        return response()->json([
            'status' => 404,
            'message' => "No such Student Found!",
        ], 404);
    }
}

public function update(Request $request, $id)
{
   
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:191',
        'email' => 'required|email|unique:students,email,' . $id,
        'phone' => 'required|min:10|max:15',
    ]);

  
    if ($validator->fails()) { 
        return response()->json([
            'status' => 422,
            'errors' => $validator->messages(),  
        ], 422);
    } else {
        $student = Students::find($id);

        if ($student) {
          
            $student->update($request->all());

            return response()->json([
                'status' => 200,
                'message' => 'Student has been updated successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No student ID Found',
            ], 404);
        }
    }
}

}
