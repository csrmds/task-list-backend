<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Google_Client;
use Google_Service_Calendar;

class TaskController extends Controller
{
    private $task;

    public function __construct() {
        $this->task= new Task;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks= Task::all();

        return response(json_encode($tasks));
    }

    public function getTasks(Request $request) {

        try {
            $userData= Auth::user();
            $filtroConcluidas= $request->query('concluidas') || false;

            $query= Task::where('user_id', $userData['id']);

            $tasks= $query->when($filtroConcluidas, function($query) {
                return $query->where('status', '!=', 'concluido');
            })
            ->orderBy('agenda_inicio')->get();

            return response()->json([
                'success'=> true,
                'data'=> $tasks
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao listar tarefas',
                'error'=> $th
            ]);
        }

    }

    protected function setTask($data) {
        $user= Auth::user();

        $this->task->resumo= $data['resumo'];
        $this->task->agenda_inicio= $data['agenda_inicio'];
        $this->task->status= $data['status'];
        $this->task->google_calendar_id= $data['google_calendar_id'];
        $this->task->google_calendar_link= $data['google_calendar_link'];
        $this->task->user_id= $user['id'];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $this->setTask($request->input('taskData'));
            $this->task->save();
            return response()->json([
                'success'=> true,
                'message'=> 'Tarefa criada com sucesso',
                'data'=> $this->task
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao criar a tarefa',
                'error'=> $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        try {
            $taskData= $request->input('taskData');
            $id= $taskData['id'];
            
            $task= Task::find($id);
            $task->resumo= $taskData['resumo'];
            $task->agenda_inicio= $taskData['agenda_inicio'];
            $task->status= $taskData['status'];
            
            $task->save();
            return response()->json([
                'success'=> true,
                'message'=> 'Tarefa atualizada com sucesso',
                'data'=> $task
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao atualizar a tarefa',
                'error'=> $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //logger("task destroy request: ", [$request->input('taskData')]);
        try {
            $taskData= $request->input('taskData');
            $id= $taskData['id'];
            $deleted= Task::destroy($id);
            return response()->json([
                'success'=> true,
                'message'=> 'Tarefa deletada com sucesso',
                'data'=> $deleted
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao deletar a tarefa',
                'error'=> $e->getMessage()
            ]);
        }

    }
}
