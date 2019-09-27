<?php

namespace App\Http\Controllers;
use App\Token;
use App\Task;
use App\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
class TaskController extends Controller
{
    public function index()
    {
      return view('home');
    }

    public function tags()
    {
        $tags = new Tag; 
        $tags = $tags->all();
        return response()->json($tags);
    }
    
    public function show(Request $request)
    {
      $this->middleware('token');

      $token = Token::where('token', $request->headers->get('authorization'))->get()->first();

      return $token->tasks()->orderBy('id', 'desc')->get()->toArray();
    }

    public function update(Request $request)
    {
        $this->middleware('token');
        
        $this->validate($request, [
            'text' => 'max:255', 
            'status' => 'numeric|max:1', 
        ]);

        $data = $request->all();
        $token = Token::where('token', $request->headers->get('authorization'))->get()->first();
        //$task = new $user->tasks;
        //dd($task::create($data));
        $task = $token->tasks()->find($request->id);
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
                'coords' => 'max:255', 
            ]);
            $data = $request->all();
            if (isset($data['created_at']))
            $data['created_at'] = date('Y-m-d H:i:s', $data['created_at']);
            //dd($data);
            $token = Token::where('token', $request->headers->get('authorization'))->get()->first();
            $task = new Task($data);
            //$task = new $user->tasks;
            //dd($task::create($data));
            $token->tasks()->save($task);
            //dd($user->tasks);
            //::create($user, $data);
            return $token->tasks()->orderBy('id', 'desc')->get()->toArray();
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
