<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use \App\Http\Controllers\Api\Admin\AdminsController;
use \App\User;
use App\Http\Resources\Admin\UserCollection;
use App\Http\Resources\Admin\User as UserResource;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends AdminsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('role',1)->orderBy('id', 'desc')->paginate(config('constant.pagination.per_page'));
        return new UserCollection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::withoutGlobalScopes()->find($id);
        if(!$user) {
            return $this->sendError(trans('responses.msgs.no_content'), config('constant.header_code.no_content'));
        }
        return $this->sendResponse(new UserResource($user), trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {        
        $user = User::withoutGlobalScopes()->find($id);
        if(!$user) {
            return $this->sendError(trans('responses.msgs.no_content'), config('constant.header_code.no_content'));
        }
        $request->validate(['status' => 'required|in:'.config('constant.user.active.key').','.config('constant.user.block.key')]);          
        $userTokens = $user->tokens;
        foreach ($userTokens as $token) {
            $token->revoke();
        }
        $user->update($request->only(['status']));
        return $this->sendResponse(new UserResource($user), trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function export() 
    {
        $filename = 'exports/users'.time().'.csv';
        Excel::store(new UsersExport, $filename, 's3');
        $path = config('constant.S3_URL').$filename;
        //$this->sendResponse($path,'Request completed successfully.');
        return $this->sendResponse($path, trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

    public function block($id) 
    {
        $user = User::find($id);
        $user->status = 0;
        $user->save();
        $users = User::where('role',1)->orderBy('id', 'desc')->paginate(config('constant.pagination.per_page'));
        return new UserCollection($users);
    }

    public function unblock($id) 
    {
        $user = User::find($id);
        $user->status = 1;
        $user->save();
        $users = User::where('role',1)->orderBy('id', 'desc')->paginate(config('constant.pagination.per_page'));
        return new UserCollection($users);
    }
}
