<?php

namespace App\Services;

use App\Models\Billboard;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BillboardService {

    public function searchForTable($search, $sortField, $sortDirection)
    {
        $billboards = Billboard::search('description', $search)
        ->orderBy($sortField, $sortDirection)
        ->paginate(15);

        return $billboards;
    }

    public function findById($id)
    {
        return Billboard::findOrFail($id);
    }

    public function storeBillboard($description, $image)
    {
        try {
            DB::beginTransaction();
            $pathImage = Storage::disk('public')->put('billboard_images', $image);

            Billboard::create([
                'description' => $description,
                'image' => url('/storage/' . $pathImage),
            ]);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function updateBillboard($description, Billboard $billboard, $image=null)
    {
        try {
            DB::beginTransaction();

            if ($image) {
                $pathNewImage = Storage::disk('public')->put('billboard_images', $image);
                $oldImage = substr($billboard->image, strlen(url('/storage/')) + 1);

                $billboard->update([
                    'description' => $description,
                    'image' => url('/storage/' . $pathNewImage),
                ]);

                Storage::disk('public')->delete($oldImage);

            } else {
                $billboard->update([
                    'description' => $description,
                ]);
            }

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function destroyBillboard($id)
    {
        try {
            DB::beginTransaction();

            $deletingBill = Billboard::find($id);
            $deletingImage = substr($deletingBill->image, strlen(url('/storage/')) + 1);

            $deletingBill->delete();
            Storage::disk('public')->delete($deletingImage);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}
