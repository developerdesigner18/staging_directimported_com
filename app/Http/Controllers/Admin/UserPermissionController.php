<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
class UserPermissionController extends Controller
{
    use ResponseTrait;
    public  function toggle(Request $request){
        try {
            $permission = UserPermission::find($request->id);

            if ($permission) {
                $permission->allowed = $request->allowed;
                $permission->save();

                return $this->sendSuccess('Permission updated');
            }

        }catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());        }
    }
}
