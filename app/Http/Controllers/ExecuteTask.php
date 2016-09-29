<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Couchbasecluster;
use Couchbase;
use App\Http\Requests;
use App\Http\Controllers\QueryTask;
use CouchbaseN1qlQuery;
use DB;


class ExecuteTask extends Controller
{
    //
    /**
    *
    */
    public function init()
    {
      $cluster = new Couchbasecluster("127.0.0.1:8091",'Administrator', 'admin123','amitBuckets');
      return $cluster;
    }
    /**
    * Set the default configuration params on init.
    */
    public function openConnection()
    {
        $cluster = ExecuteTask::init();
        if($cluster)
        {
          return $cluster;
        }
        else {
          return $cluster;
        }
    }
    /**
    *
    */
    public function openBucket()
    {
      $cluster = ExecuteTask::openConnection();
      if($cluster)
      {
        $bucketManager = $cluster->openBucket('amitBuckets');
      }
      return $bucketManager;
    }
    /**
    *
    */
    public function createBucket($bucketName)
    {
      $cluster = ExecuteTask::openConnection();
      if($cluster)
      {
        $clusterManager->createBucket($bucketName, ['bucketType' => 'couchbase', 'saslPassword' => '', 'flushEnabled' => true]);
        $bucketManager = $cluster->openBucket('testing')->manager();
        $bucketManager->createN1qlPrimaryIndex();
      }

    }
    /**
    *
    */
    public function Insert($key,$array)
    {
      $con = ExecuteTask::openBucket();
      if($con != "Fail")
      {
        $result = $con->insert($key,$array);
      }
      return $result;
    }
    /**
    *
    */
    public function Upsert($key,$array)
    {
      $con = ExecuteTask::openBucket();
      if($con != "Fail")
      {
        $result = $con->upsert($key,$array);
      }
      return $result;
    }
    /**
    *
    */
    public function Replace($key,$array)
    {
      $con = ExecuteTask::openBucket();
      if($con != "Fail")
      {
        $result = $con->replace($key, $array);
      }
      return $result;
    }
    /**
    * @param  document name
    * @return remove response document
    */
    public function remove($doc_name)
    {
      $bucket = ExecuteTask::openBucket();
      $result = $bucket->remove($doc_name);
      return $result->value;
    }
    /**
    *
    */
    public function getbydocname($doc_name)
    {
      $bucket = ExecuteTask::openBucket();
      $result = $bucket->get($doc_name);
      return $result;
    }
    /**
    *
    */
    public function createPrimaryIndex($PIname)
    {
      try
      {
          $con = ExecuteTask::openConnection();
          // Do not override default name, fail if it is exists already, and wait for completion
          $res = $con->manager()->createN1qlPrimaryIndex($PIname, false, false);
          return $res;
      }
      catch (CouchbaseException $e) {
        printf("Couldn't create index. Maybe it already exists? (code: %d)\n", "asd");
      }
    }
    /**
    *
    */
    public function RunQuery($moodSQ,$action_param,$params)
    {
      $bucket = ExecuteTask::openBucket();
      $query = new CouchbaseN1qlQuery;
      $execquery = $query->fromString(ExecuteTask::sendQuery($moodSQ));
      if($action_param == 0)
      {
      }
      elseif ($action_param == 1) {
        $execquery->namedParams($params);
      }
      $result_rows = $bucket->query($execquery);
      return $result_rows->rows;
    }
    /**
    *
    */
    public function sendQuery($mood)
    {
      switch ($mood) {
        case 'get_all':
          return "SELECT meta(a).id AS documentId,a.* FROM amitBuckets a;";
          break;
        case 'getallkeys':
          return "SELECT meta(a).id AS documentId FROM amitBuckets a;";
          break;
        default:
          # code...
          break;
      }
    }
    /**
    *
    */

}
