<?php

namespace App\Http\Controllers\Admin;

use App\Models\Policy;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class PolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Policies";
        $departments = Department::get();
        $policies = Policy::with('department')->get();
        return view('backend.policies',compact('title','departments','policies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    

     public function store(Request $request)
{

    $this->validate($request, [
        'name' => 'required',
        'description' => 'max:255',
        'department' => 'required',
        'files.*' => 'required|file|max:2048', // Validate each file in the array
    ]);


    $files = [];
    if ($request->hasFile('files')) {
        foreach ($request->file('files') as $file) {
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/policies/' . $request->subject), $fileName);
                $files[] = $fileName;
            }
        }
    }
    
    // Add this to debug
    //dd($files);
    

    Policy::create([
        'name' => $request->name,
        'description' => $request->description,
        'department_id' => $request->department,
        'file' => $files,
    ]);

    return back()->with('success', 'Policy has been added successfully');
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
    public function destroy(Request $request)
    {
        $policy = Policy::find($request->id);
        $policy->delete();
        return back()->with('success',"Policy has been deleted successfully!!");
    }
}
