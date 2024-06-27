<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = DB::select('EXEC Sp_GetTask');
        
        // Implementar la paginación manual
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;
        $currentItems = array_slice($tasks, ($currentPage - 1) * $perPage, $perPage);
        $paginatedTasks = new LengthAwarePaginator($currentItems, count($tasks), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        return view('home', ['tasks' => $paginatedTasks]);
    }

    public function store(Request $request)
    {
        $title = $request->input('title');
        $description ='Tarea Nueva';
        DB::statement('EXEC sp_CreateTask ?, ?', [$title,$description]);

        return response()->json(['message' => 'Task created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $title = $request->input('title');
        $description = 'Tarea Actualizada';
        DB::statement('EXEC sp_UpdateTask ?, ?, ?', [$id, $title, $description]);

        return response()->json(['message' => 'Task updated successfully'], 200);
    }

    public function destroy($id)
    {
        DB::statement('EXEC Sp_DeleteTask ?', [$id]);

        return response()->json(['message' => 'Task deleted successfully'], 200);
    }

    public function complete(Request $request, $id)
    {
        DB::statement('EXEC Sp_CompleteTask ?, ?', [$id,'Tarea Completada']);

        return response()->json(['message' => 'Task completed successfully'], 200);
    }

    public function getToken()
    {
        return response()->json(['csrf_token' => csrf_token()]);
    }
}
