<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;

use DB;
use Image;
class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.settings.index');
    }

    public function settingsData()
    {
        return Setting::first();
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $setting = Setting::first();
        
        DB::beginTransaction();
        try {

            $data = $request->all();

            if(isset($request->app_logo)){
                if($request->file('app_logo')){
                    $attachment = $request->file('app_logo');   

                    $filename = 'app_logo.png';
                    $data['app_logo'] = $filename;

                    $destinationPath = public_path('/img');
                    $img = Image::make( ($attachment));

                    //Convert Image Size
                    $img->resize(500, 500, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'. $filename);

                }
            }

            if(isset($request->app_icon)){
                if($request->file('app_icon')){
                    $attachment = $request->file('app_icon');   

                    $filename = 'app_icon.png';
                    $data['app_icon'] = $filename;

                    $destinationPath = public_path('/img');
                    $img = Image::make( ($attachment));

                    //Convert Image Size
                    $img->resize(500, 500, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'. $filename);

                }
            }

            if($setting){
                $setting->update($data);
            }else{
                $setting = Setting::create($data);
            }

            DB::commit();
            return $status_data = [
                'status'=>'success',
                'setting'=>$setting,
            ];
           
        }
        catch (Exception $e) {
            DB::rollBack();
            return 'error';
        } 
    }


}
