<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIController;
use Illuminate\Http\Request;
use \App\Schedule;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\Schedules;
use App\Http\Resources\OpenTokResource;

use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\Session;
use OpenTok\Role;


class SchedulesController extends APIController
{
    public function index()
    {
        $modelUser = request()->user();
        if(request()->query('model_id')) {

            $userExist = \App\User::find(request()->query('model_id'));
            if($userExist){

                $modelUser = $userExist;

            }else{
                
                return $this->sendError(trans('responses.msgs.no_content'), config('constant.header_code.no_content'));
            }
        }

        $scheduleQuery = $modelUser->schedules();
        if(request()->query('fromDate')){
            $scheduleQuery->where('fromTime','like','%'.request()->query('fromDate').'%');
        }

        return new Schedules($scheduleQuery->withCount('bookings')->orderBy('fromTime','asc')->paginate(config('constant.pagination.per_page')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $schedule = new Schedule;
        
        $schedule->user_id = $request->user()->id;
        $schedule->creditPointsPerMinute = $request->creditPointsPerMinute;
        $schedule->fromTime = new \Carbon\Carbon($request->fromTime);
        $schedule->toTime = new \Carbon\Carbon($request->toTime);
        $schedule->save();

        return $this->sendResponse(new ScheduleResource($schedule), trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($date)
    {
        $opentok = new OpenTok(env('TOKBOX_API_KEY'), env('TOKBOX_APP_SECRET'));
        print $opentok->generateToken('1_MX40NjkyMDYwNH5-MTYwMTIxMzQwMDU5MH5LTUErQlJPMGFKVytkSWsvSGZOcFV1M3Z-fg');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function generateCustomersToken($scheduleId){
        $schedule = Schedule::find($scheduleId);
        if(!$schedule){
            $this->noContent();
        }elseif(!$schedule->channelSessionId){
            $this->sendError('There is no published stream for this meeting.');
        }else{
            $opentok = new OpenTok(env('TOKBOX_API_KEY'), env('TOKBOX_APP_SECRET'));
            $channelToken = $opentok->generateToken($schedule->channelSessionId);
            return $this->sendResponse(['channelToken'=>$channelToken], trans('responses.msgs.success'), config('constant.header_code.ok'));
        }
    }

    public function update(Request $request, $id)
    {
        $opentok = new OpenTok(env('TOKBOX_API_KEY'), env('TOKBOX_APP_SECRET'));
        $session = $opentok->createSession(array( 'mediaMode' => MediaMode::ROUTED ));
        $sessionId = $session->getSessionId();
        $token = $session->generateToken(array(
            'role'       => Role::MODERATOR,
            'expireTime' => time()+(7 * 24 * 60 * 60), // in one week
            'data'       => 'name=Johnny',
            'initialLayoutClassList' => array('focus')
        ));

        $schedule = Schedule::find($id);

        if($schedule->channelSessionId && $schedule->channelToken){
            return $this->sendResponse(new OpenTokResource($schedule), trans('responses.msgs.success'), config('constant.header_code.ok'));
        }else{
            $schedule->channelSessionId = $sessionId;
            $schedule->channelToken = $token;
            $schedule->save();

            return $this->sendResponse(new OpenTokResource($schedule), trans('responses.msgs.success'), config('constant.header_code.ok'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schdule)
    {
        $schedule->delete();
        return $this->sendResponse([], trans('responses.msgs.success'), config('constant.header_code.ok'));
    }
}
