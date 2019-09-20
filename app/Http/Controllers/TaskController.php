<?php

namespace App\Http\Controllers;
use App\User;
use App\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
class TaskController extends Controller
{
    public function index()
    {
      return view('home');
    }
    
    public function show(Request $request)
    {
      $this->middleware('token');

      $user = User::where('token', $request->headers->get('authorization'))->get()->first();
      //dd($request->headers->get('authorization'));
      //->whereDate('created_at', Carbon::now()->format('Y-m-d'))
      return $user->tasks()->orderBy('id', 'desc')->get()->toArray();
    }

    public function update(Request $request)
    {
        $this->middleware('token');
        
        $this->validate($request, [
            'text' => 'max:255', 
            'status' => 'numeric|max:1', 
        ]);

        $data = $request->all();
        $user = User::where('token', $request->headers->get('authorization'))->get()->first();
        //$task = new $user->tasks;
        //dd($task::create($data));
        $task = $user->tasks()->find($request->id);
        //dd($task);
        $task->fill($data);
        $task->save();
    }
    public function create(Request $request)
    {
       //dd($request->headers->get('authorization'));
        try
        {
            $this->validate($request, [
                'text' => 'required|max:255', 
            ]);
            $data = $request->only('text');
            $user = User::where('token', $request->headers->get('authorization'))->get()->first();
            $task = new Task($data);
            //$task = new $user->tasks;
            //dd($task::create($data));
            $user->tasks()->save($task);
            //dd($user->tasks);
            //::create($user, $data);
            return $user->tasks()->orderBy('id', 'desc')->get()->toArray();
        }
        catch(ValidationException $e)
        {
            dd($e->getErrors()->all());
        }
        catch(Exception $e)
        {
            dd($this->respondInternalError());
        }
    }
}
