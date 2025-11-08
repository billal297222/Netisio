<?php

namespace App\Http\Controllers\API\KidMoney;

use App\Http\Controllers\Controller;
use App\Models\AddList;
use App\Models\Family;
use App\Models\Kid;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class AddListController extends Controller
{
    use ApiResponse;

    public function showFamilyMembers()
    {
        $kid = auth('kid')->user();

        $family = Family::where('id', $kid->family_id)->with('kids')->first();
        $parent = $kid->parent;

        if (!$family) {
            return $this->success([], 'No family found', 200);
        }

        $alreadyAdded = AddList::where('kid_id', $kid->id)->pluck('member_unique_id')->toArray();

        $members = [];

        if ($parent) {
            if (!in_array($parent->p_unique_id, $alreadyAdded)) {
                $members[] = [
                    'type' => 'parent',
                    'id' => $parent->id,
                    'unique_id' => $parent->p_unique_id,
                    'name' => $parent->full_name,
                    'avatar' => $parent->pavatar ? asset($parent->pavatar) : null,
                ];
            }
        }

        foreach ($family->kids as $fKid) {
            if ($fKid->id === $kid->id) continue;
            if (in_array($fKid->k_unique_id, $alreadyAdded)) continue;

            $members[] = [
                'type' => 'kid',
                'id' => $fKid->id,
                'unique_id' => $fKid->k_unique_id,
                'name' => $fKid->full_name ?? $fKid->username,
                'avatar' => $fKid->kavatar ? asset($fKid->kavatar) : null,
            ];
        }

        return $this->success($members, 'Family members list', 200);
    }

    public function addMember(Request $request)
    {
        $kid = auth('kid')->user();

        $request->validate([
            'member_type' => 'required|in:kid,parent',
            'member_unique_id' => 'required|string',
            'member_avatar' => 'nullable|string',
        ]);


        $exists = AddList::where('kid_id', $kid->id)
                        ->where('member_unique_id', $request->member_unique_id)
                        ->exists();

        if ($exists) {
            return $this->error('', 'Member already added', 400);
        }

        $addMember = AddList::create([
            'kid_id' => $kid->id,
            'member_type' => $request->member_type,
            'member_name' =>$request->member_name,
            'member_unique_id' => $request->member_unique_id,
            'member_avatar' => $request->member_avatar,
        ]);

        return $this->success($addMember, 'Member added successfully', 201);
    }

    public function removeMember($id)
    {
        $kid = auth('kid')->user();
        $member = AddList::where('kid_id', $kid->id)->where('id', $id)->first();

        if (!$member) {
            return $this->error('', 'Member not found', 404);
        }

        $member->delete();
        return $this->success('', 'Member removed successfully', 200);
    }

    public function addedList()
    {
        $kid = auth('kid')->user();
        $list = AddList::where('kid_id', $kid->id)->get()->map(function($item){
            return [
                'id' => $item->id,
                'type' => $item->member_type,
                'member_name' =>$item->member_name,
                'unique_id' => $item->member_unique_id,
                'avatar' => $item->member_avatar ? asset($item->member_avatar) : null,
            ];
        });

        return $this->success($list, 'Added list', 200);
    }
}
