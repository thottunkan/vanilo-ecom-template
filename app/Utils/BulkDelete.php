<?php
namespace App\Utils;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Exception;

class BulkDelete
{
    public static function perform(Request $request, Model $model, $flag = null)
    {
        try {
            if (!$request->ajax()) {
                return response()->json([
                    'deleted_items' => [],
                    'message' => 'Invalid request type'
                ], 400);
            }

            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer',
            ]);

            // Get model class from object
            $modelClass = get_class($model);

            // Get records before deleting
            $deletedItems = $modelClass::whereIn('id', $request->ids)
                ->get(['id']);

            if ($flag != null) {
                $modelClass::whereIn('id', $request->ids)
                    ->where($flag['colname'], $flag['colvalue'])
                    ->delete();
            } else {
                // Delete records
                $modelClass::whereIn('id', $request->ids)->delete();
            }


            return response()->json([
                'deleted_items' => $deletedItems->pluck('id'),
                'message' => 'Items deleted successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'deleted_items' => [],
                'message' => $e->getMessage()
            ], 500);
        }
    }
}