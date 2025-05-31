<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Block;


class BlockController extends Controller
{


    public function batchUpdate(Request $request)
    {
        $blocks = $request->input('blocks');

        // Extract the IDs of the blocks that should remain
        $blockIdsToKeep = collect($blocks)->pluck('id')->filter()->toArray();

        // Delete blocks that are not in the list of IDs to keep
        Block::whereNotIn('id', $blockIdsToKeep)
            ->where('page_id', $blocks[0]['page_id']) // Ensure only blocks for the current page are deleted
            ->delete();

        // Process the remaining blocks (update or create)
        foreach ($blocks as $blockData) {
            // Ensure content is stored as JSON
            $blockData['content'] = is_array($blockData['content']) || is_object($blockData['content'])
                ? json_encode($blockData['content'])
                : json_encode([]);

            if ($blockData['id']) {
                // Update existing block
                Block::where('id', $blockData['id'])->update([
                    'title' => $blockData['title'],
                    'content' => $blockData['content'],
                    'order' => $blockData['order'],
                ]);
            } else {
                // Create new block
                Block::create([
                    'title' => $blockData['title'],
                    'page_id' => $blockData['page_id'],
                    'content' => $blockData['content'],
                    'order' => $blockData['order'],
                ]);
            }
        }

        return response()->json(['message' => 'Blocks updated successfully!']);
    }

    // public function index()
    // {
    //     $blocks = Block::all();
    //     return response()->json($blocks);
    // }
    public function showByPageId($pageId)
    {
        $blocks = Block::where('page_id', $pageId)->orderBy('order')->get();
        return response()->json($blocks);
    }
}