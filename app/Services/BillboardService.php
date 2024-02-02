<?php

namespace App\Services;

use App\Models\Billboard;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BillboardService {

    public function searchForTable($search, $sortField, $sortDirection)
    {
        $billboards = Billboard::with(['category' => function ($query) {
            $query->select('id','title');
        },
        'tags' => function ($query) {
            $query->select('tag_id','title');
        }])->search('title', $search)->orderBy($sortField, $sortDirection)->paginate(15);

        return $billboards;
    }

    public function findById($id)
    {
        return Billboard::with('category', 'tags')->findOrFail($id);
    }

    public function storeBillboard($description, $image, $category_id, $tags=[])
    {
        try {
            DB::beginTransaction();
            $pathImage = Storage::disk('public')->put('billboard_images', $image);

            $b = Billboard::create([
                'description' => $description,
                'image' => url('/storage/' . $pathImage),
                'category_id' => $category_id,
            ]);

            if (isset($tags)) {
                $b->tags()->attach($tags);
            }

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function updateBillboard($description, Billboard $billboard, $image=null, $category_id, $tags=[])
    {
        try {
            DB::beginTransaction();

            if ($image) {
                $pathNewImage = Storage::disk('public')->put('billboard_images', $image);
                $oldImage = substr($billboard->image, strlen(url('/storage/')) + 1);

                $billboard->update([
                    'description' => $description,
                    'image' => url('/storage/' . $pathNewImage),
                    'category_id' => $category_id,
                ]);

                Storage::disk('public')->delete($oldImage);

            } else {
                $billboard->update([
                    'description' => $description,
                    'category_id' => $category_id,
                ]);
            }

            if (isset($tags) && $billboard->tags->isEmpty()) {
                $billboard->tags()->attach($tags);
            }
            if (isset($tags) && $billboard->tags->isNotEmpty()) {
                $billboard->tags()->sync($tags);
            }

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function destroyBillboard($billboard)
    {
        try {
            DB::beginTransaction();

            $deletingImage = substr($billboard->image, strlen(url('/storage/')) + 1);

            $billboard->delete();
            Storage::disk('public')->delete($deletingImage);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}
