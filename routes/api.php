<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' =>'jwt.auth', 'jwt.refresh'], function ($router) {

    Route::group(['middleware' => ['role:Visitor|Secretary|Administrator|GDPR-Admin']], function () {
        Route::get('/contract/{id}/{filename}', 'ContractController@downloadContract');
    });
    Route::group(['middleware' => ['role:Secretary|Administrator|GDPR-Admin|Staff']], function () {
        Route::get('/contract/{sub_folder}/{id}/{filename}', 'ContractController@downloadContract');
        Route::get('/users','UserController@index');
    });

    Route::group(['middleware' => ['role:Administrator']], function () {
        Route::delete('/user/{id}','UserController@destroy');
        Route::post('/role/user/{id}','UserController@assignRole');
        Route::put('/user/{id}','UserController@update');
        Route::delete('/visit/{visit}','VisitController@delete');
        Route::delete('/meeting/{meeting}','MeetingController@delete');
    });
    Route::group(['middleware' => ['role:GDPR-Admin|Secretary']], function(){
        Route::post('/contract','ContractController@store');
        Route::get('/contracts/{userId}','ContractController@index');
    });
    Route::group(['middleware' => ['role:Administrator|Secretary']], function () {        
        Route::delete('/room/{room}','RoomController@destroy');
        Route::post('/room', 'RoomController@store');
        Route::post('/type','TypeController@store');
        Route::delete('/type/{type}','TypeController@destroy');
    });

    Route::group(['middleware' => ['role:GDPR-Admin']], function(){
        Route::delete('/contract/{contract}','ContractController@destroy');
    });
    
    
    Route::get('/user/{id}','UserController@show')->middleware(\App\Http\Middleware\ApiDataLogger::class);
    
    Route::put('/user/image/{id}','UserController@updateImage');
    Route::get('/me','AuthController@me');
    Route::get('/logout','AuthController@logout');
    Route::post('/register','AuthController@register');
    Route::post('/visits','VisitController@store');
    Route::get('/searchUsers','UserController@search');
    Route::put('/close/visit/{id}','VisitController@closeVisit');
    Route::post('/meeting/create','MeetingController@store');
    Route::put('/visit/update/planned/{id}','VisitController@makeNormalVisit');
    Route::get('/roles', 'RoleController@index');

    Route::put('/visit/{id}/update','VisitController@update');
    Route::get('/meetings/count','MeetingController@countMeetings');
    Route::get('/visits/count', 'VisitController@countVisits');
    Route::patch('/remove/meeting/{id}/participant', 'MeetingController@detachParticipant');
    Route::get('/types','TypeController@index');
    Route::get('/visits/chartData','VisitController@visitChart');
    Route::get('/meetings/chartData','MeetingController@meetingChart');
    Route::get('/visits/planned/{userId}','VisitController@plannedForUser');
    Route::get('/meetings/user/{userId}','MeetingController@userMeetings');
    Route::post('/checkin','VisitController@checkin');
    Route::get('/meetings/user/{userId}/count', 'MeetingController@countUserMeetings');
    Route::get('/visits/user/{userId}/count', 'VisitController@countForUser');
    Route::patch('/user/{id}/password','UserController@updatePassword');
});
Route::get('/rooms','RoomController@index');
Route::post('/login','AuthController@login');
Route::get('/visits/filtered','VisitController@filter');
Route::patch('/meeting/update/empty/{id}','MeetingController@update');
Route::get('/yesterdayVisits','VisitController@filterByYesterday');
Route::get('/todayVisits','VisitController@filterByToday');
Route::get('/thisWeekVisits','VisitController@filterByThisWeek');
Route::get('/visits','VisitController@index');
Route::get('/activeVisits','VisitController@active');
Route::get('/meetings', 'MeetingController@index');
Route::get('/thisMonthMeetings','MeetingController@thisMonth');
Route::get('/thisWeekMeetings','MeetingController@thisWeek');
Route::get('/todayMeetings','MeetingController@today');
Route::get('/visits/planned','VisitController@planned');
Route::get('/visit/latest','VisitController@latest');
Route::patch('/meeting/{id}','MeetingController@update');
Route::get('/visits/notplanned/{userId}','VisitController@notPlanned');
Route::patch('/meeting/{id}/checkInUser/{userId}','MeetingController@checkIn');