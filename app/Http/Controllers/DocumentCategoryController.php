<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DocumentCategory;
use DB;

class DocumentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.document_categories.index');
    }

    public function indexData(Request $request){

        $limit = $request->limit;

        $document_category = DocumentCategory::with('tag_info')->orderBy('category_description','ASC');

        if(isset($request->search)){
            $document_category->where('category_description', 'LIKE', '%' . $request->search . '%');
        }

        return $document_category->paginate($limit);
    }

    public function document_categories(){
        return DocumentCategory::select('id','category_description')
                                    ->whereHas('tag_info',function($q){
                                        $q->where('id','1');
                                    })            
                                    ->orderBy('category_description','ASC')->get();
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
            'category_description' => 'required',
            'status' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();
            $document_category = DocumentCategory::where('id',$data['id'])->first();
            if($document_category){
                unset($data['id']);
                $document_category->update($data);
                DB::commit();
                $document_category = DocumentCategory::with('tag_info')->where('id',$document_category->id)->first();
                return $status_data = [
                    'status'=>'success',
                    'document_category'=>$document_category,
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'category_description' => 'required',
            'status' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();
            if($document_category = DocumentCategory::create($data)){
                DB::commit();
                return $status_data = [
                    'status'=>'success',
                    'document_category'=>$document_category,
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
}
