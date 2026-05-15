<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class SortingController extends Controller
{
    /**
     * Change the position of a row in the given table.
     *
     * @param Request $req The request containing the necessary data.
     * @return \Illuminate\Http\JsonResponse The response indicating success or failure.
     */
    public static function changePosition(Request $req)
    {
        // Get the table name, move_id and move_to_id from the request.
        $tableName  = $req->table;
        $moveId     = $req->move_id;
        $moveToId   = $req->move_to_id;

        // Check if the table exists in the database.
        if (!$tableName || !Schema::hasTable($tableName)) {
            return response()->json(["error" => "Table does not exist"], 404);
        }

        // Validate the inputs: move_id and move_to_id must exist in the table and be integers.
        $validator = Validator::make($req->all(), [
            "move_id"    => "required|int|exists:$tableName,id",
            "move_to_id" => "required|int|exists:$tableName,id",
        ]);

        // If validation fails, return the validation errors.
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        // Retrieve the rows by their IDs.
        $row   = DB::table($tableName)->find($moveId);
        $toRow = DB::table($tableName)->find($moveToId);

        // If either row doesn't exist, return an error.
        if (!$row || !$toRow) {
            return response()->json(["error" => "Invalid ID"], 404);
        }

        // Get the current position and the new position for the move.
        $position    = $row->sorting;
        $newPosition = $toRow->sorting;

        // If the current position is less than the new position, move the row up.
        if ($position < $newPosition) {
            DB::table($tableName)
                ->whereBetween("sorting", [$position + 1, $newPosition])
                ->where("id", "!=", $moveId)
                ->decrement("sorting");
        } else {
            // If the current position is greater than the new position, move the row down.
            DB::table($tableName)
                ->whereBetween("sorting", [$newPosition, $position - 1])
                ->where("id", "!=", $moveId)
                ->increment("sorting");
        }

        // Update the moved row's position to the new position.
        DB::table($tableName)->where("id", $moveId)->update(["sorting" => $newPosition]);

        // Return a success message.
        return response()->json([
            "message" => "$tableName / id: $moveId ($position) - moved below: $moveToId - ($newPosition)"
        ]);
    }
}
