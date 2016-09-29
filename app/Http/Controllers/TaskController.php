<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ExecuteTask;
use App\Http\Requests;

class TaskController extends Controller
{
    //
    /**
    *
    */
    public function getdataAPI()
    {
      $link= json_decode (file_get_contents("http://api.geonames.org/weatherJSON?north=44.1&south=-9.9&east=-"));
      $username = "amit01";
      $message =  $link->status->message;
      $value =  $link->status->value;
      $inse_res = TaskController::createstore($username,$message,$value);
    }
    public function createstore($username,$message,$value)
    {
      $ret_res = TaskController::getlastKey();
      if(!empty($ret_res))
      {
        $cnt = count($ret_res)-1;
        $var = $ret_res[$cnt]->documentId;
        $newK = explode("::",$var);
        $newKey = intval($newK[1]);
        $newKey += 1;
        $key = "status::".$newKey;

      }
      else {
        $key = "status::1";
      }
      $ins_arr = array('username' => $username ,'message' => $message,'value' => $value, );
      $et = new ExecuteTask;
      $res = $et->Upsert($key,$ins_arr);
      $result_data = TaskController::retrivedata();
      return redirect('/')->with('getdata',$result_data);
    }
    /**
    *
    */
    public function Checkexists()
    {
      $key = "status_";
      $et = new ExecuteTask;
      $res = $et->exists($key);
      return $res;
    }
    public function getlastKey()
    {
      $et = new ExecuteTask;
      $res = $et->RunQuery('getallkeys',0,"");
      return $res;
    }
    /**
    *
    */
    public function storedata(Request $request)
    {
      $ret_res = TaskController::getlastKey();
      if(!empty($ret_res))
      {
        $cnt = count($ret_res)-1;
        $var = $ret_res[$cnt]->documentId;
        $newK = explode("::",$var);
        $newKey = intval($newK[1]);
        $newKey += 1;
        $key = "status::".$newKey;
      }
      else {
        $key = "status::1";
      }

      $in = $request->all();
      $username = $in['username'];
      $value = $in['value'];
      $message = $in['message'];
      $ins_arr = array('username' => $username ,'message' => $message,'value' => $value, );
      $et = new ExecuteTask;
      $res = $et->Insert($key,$ins_arr);
      $result_data = TaskController::retrivedata();
      return redirect('/')->with('getdata',$result_data);
    }
    /**
    *
    */
    public function editdata(Request $request)
    {
      $in = $request->all();
      $key = $in['arrow'];
      $username = $in['editusername'];
      $value = $in['editvalue'];
      $message = $in['editmessage'];
      $upd_arr = array('username' => $username ,'message' => $message,'value' => $value, );
      $et = new ExecuteTask;
      $res = $et->Upsert($key,$upd_arr);
      $result_data = TaskController::retrivedata();
      return redirect('/')->with('getdata',$result_data);

    }
    /**
    *
    */
    public function retrivedata()
    {
      $et = new ExecuteTask;
      $res = $et->RunQuery('get_all',0,"");
      return $res;
    }
    /**
    * @param  document name
    * @return remove response document
    */
    public function removedata(Request $request)
    {
      $in = $request->all();
      $id = $in['arrow'];
      $et = new ExecuteTask;
      $res = $et->remove($id);
      $result_data = TaskController::retrivedata();
      return redirect('/')->with('getdata',$result_data);
    }
    /**
    *
    */
    public function get_design()
    {
      $res = TaskController::retrivedata();
      return view('welcome')->with('getdata',$res);
    }
    public function createPrimaryIndex()
    {
      try
      {
        $et = new ExecuteTask;
        $res = $et->createPrimaryIndex("status");
        return $res;
      }
      catch (CouchbaseException $e) {
        printf("Couldn't create index. Maybe it already exists? (code: %d)\n", "asd");
      }

    }
}
