<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        // $this->user = $this->guard()->user();
        $this->user = $this->guard()->user();

       
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todo = DB::table('todos') -> get(['title', 'body', 'completed', 'created_by']);
        $todos = $this->user->todos()->get(['title', 'body', 'completed', 'created_by']);
        
        return response()->json($todo->toArray());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'body' => 'required|string',
            'completed' => 'required|boolean'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $todo = new Todo();

        $todo->title = $request->title;
        $todo->body = $request->body;
        $todo->completed = $request->completed;

        if ($this->user->todos()->save($todo)) {
            return response()->json([
                'status' => true,
                'todo' => $todo
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Oops, the todo could not be save.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        return $todo;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'body' => 'required|string',
            'completed' => 'required|boolean'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }


        $todo->title = $request->title;
        $todo->body = $request->body;
        $todo->completed = $request->completed;

        if ($this->user->todos()->save($todo)) {
            return response()->json([
                'status' => true,
                'todo' => $todo
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Oops, the todo could not be updated.'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
       if($todo -> delete()){
        return response()->json([
            'status' => true,
            'todo' => $todo
        ]);
       }else {
        return response()->json([
            'status' => false,
            'message' => 'Oops, the todo could not be deleted.'
        ]);
    }
    }

    public function guard()
    {
        return Auth::guard();
    }
}