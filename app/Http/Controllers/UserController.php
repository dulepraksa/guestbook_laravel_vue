<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Permission;
use App\GdprLog;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserCollection;
use App\Http\Requests\UserUpdateRequest;
use Tymon\JWTAuth\JWTAuth;
use Auth;
use \DateTime;
use \DateTimeZone;
use App\Middleware\ApiDataLogger;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles','type'])->paginate(10);
        return new UserCollection($users);
    }
    public function show($id)
    {
        // var_dump($_REQUEST);die();
        $user = User::with(['roles','visits','meetings','type'])->findOrFail($id);
        return response()->json($user);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'email' => $request->input('email'),
            'jmbg' => $request->input('jmbg'),
            'p_id' => $request->input('p_id'),
            'type_id' => $request->input('type_id')
        ]);
        $user->type;
        $user->syncRoles($request->role);

        return response()->json([
            'data' => $user,
            'message' => 'User updated'
        ]);

    }
    public function updateImage($id, Request $request) {

        $user = User::findOrFail($id);


        $this->validate($request,[
            'image' => 'required'
        ]);
        
        $image = $this->takePhoto($request->image);

        $user->update([
            'image' => $image[0],
            'image_path' => bcrypt($image[1]),
            'sub_folder' => $image[2]
        ]);

        return response()->json([
            'data' => $user
        ]);
    }
    public function updatePassword(Request $request,$id) {

        $user = User::findOrFail($id);
        
        $this->validate($request, [
            'password' => 'required|min:3'
        ]);

        $user->update([
            'password' => bcrypt($request->input('password'))
        ]);

        return response()->json($user);
    }
    public function destroy($id)
    {
        $user =  User::findOrFail($id);
        $user->visits()->delete();
        $user->delete();
    }
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        if(strlen($search) > 3) {
            $users = User::where('name','like','%'.$search.'%')
            ->orWhere('surname','like','%'.$search.'%')
            ->orWhere('email','like','%'.$search.'%')->orderBy('id')->with('type')->get();
                return $users;
        }
            
    }
}
