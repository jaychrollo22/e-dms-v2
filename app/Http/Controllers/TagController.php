<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tag;
use DB;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.tags.index');
    }

    public function indexData(Request $request){

        $limit = $request->limit;

        $tag = Tag::orderBy('description','ASC');

        if(isset($request->search)){
            $tag->where('description', 'LIKE', '%' . $request->search . '%');
        }

        return $tag->paginate($limit);
    }

    public function tags(){
        return Tag::select('id','description')->orderBy('description','ASC')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'description' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();
            $tag = Tag::where('id',$data['id'])->first();
            if($tag){
                unset($data['id']);
                $tag->update($data);
                DB::commit();
                $tag = Tag::where('id',$tag->id)->first();
                return $status_data = [
                    'status'=>'success',
                    'tag'=>$tag,
                ];
            }else{
                return $data = [
                    'status'=>'error'
                ];
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            return 'error';
        } 
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
}
