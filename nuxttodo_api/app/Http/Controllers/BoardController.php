<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoardCreateRequest;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function store(BoardCreateRequest $request)
    {
        $data = $request->validated();
        $board = Board::create($data);
        return new BoardResource($board->load('tickets'));
    }

    public function show(Board $board)
    {
        $board->load('tickets');
        return new BoardResource($board->load('tickets'));
    }

    public function update(BoardCreateRequest $request, Board $board)
    {
        $data = $request->validated();
        $board->update($data);
        return new BoardResource($board->load('tickets'));
    }

    public function destroy(Board $board)
    {
        abort_if($board->project->user_id !== Auth::id(), 403, 'You are not allowed to delete this board');
        $board->delete();
        return response()->json([
            'message' => 'Board deleted successfully'
        ]);
    }
}
