<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;

class ApiPolicyController extends Controller
{
    public function index(){
        try{
            $policies = Policy::all();
            if(!$policies){
                return response()->json(["status"=>false,"message"=>"Record not found..!","data"=>$policies]);
            }
            return response()->json(["status"=>true,"message"=>"Policy fetched successfully..!","data"=>$policies]);
        }catch(\Exception $e){
            return response()->json(["status"=>false,"message"=>"Policy fetched successfully..!","data"=>$policies]);
        }
        
    }
}
