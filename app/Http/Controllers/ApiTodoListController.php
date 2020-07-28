<?php

namespace App\Http\Controllers;

use App\TodoList;
use Illuminate\Http\Request;

class ApiTodoListController extends Controller
{
    public function getList()
    {
        $result = null;
        if (\request('search')) {
            $result = TodoList::where('content', 'like', '%' . request('search') . '%')->orderBy('id', 'desc')->get();
        } else {
            $result = TodoList::orderBy('id', 'desc')->get();
        }
        return response()->json($result);
    }

    public function postCreate(Request $request)
    {
        TodoList::create($request->all());
        return response()->json(['status' => true, 'message' => 'Data berhasil ditambahkan']);
    }

    public function postUpdate(Request $request, $id)
    {
        $result = TodoList::findOrFail($id);
        $result->update($request->all());
        return response()->json(['status' => true, 'message' => 'Data berhasil diupdate']);
    }

    public function postDelete($id)
    {
        $result = TodoList::find($id);
        $result->delete();
        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
    }

    public function getRead($id)
    {
        $row = TodoList::find($id);
        return response()->json($row);
    }
}
