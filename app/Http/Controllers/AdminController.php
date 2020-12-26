<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Verta;
use hash;
use Redirect;
use Auth;
use Carbon;
use Session;
use App\instagrams;
use App\Sapp;
use App\likee;
use App\domains;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;



class AdminController extends Controller
{
    //

    public function index(){

        $countInstagram = instagrams::get()->count();
        $countLikee = likee::get()->count();
        $countDomains = domains::get()->count();

        return view('admin.index', compact('countInstagram', 'countLikee', 'countDomains'));
    }

    public function instagramList(){

		$instagramLists = instagrams::get();

        return view('admin.instagram-list', compact('instagramLists'));
        
        // return view('admin.index', compact());
    }

    public function instagramAdd(){

        return view('admin.instagram-add');

    }

    public function instagramInsert(Request $request){

        $validator = Validator::make($request->all(), [
            'userName' => 'required'
        ],
        [
            // set your custom error messages here 
              'userName.required' => 'فیلد نام کابری (:attribute) حتما باید وارد شود.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $input = $request->all();
        $getUserName = $input['userName'];
        $val = null;

        $val = json_decode($this->get_https_content("https://www.instagram.com/".$getUserName."/?__a=1"), true);

        // dd($val);

        if (empty($val)) {
            // return 'does not eixst this username in instagram.';

            $arrayInstagramResult = array(
                
            );


        }else{

            // return 'has';
            // print_r($val);

            $_userName = $getUserName;
            $_full_name = $val['graphql']['user']['full_name'];
            $_edge_followed = $val['graphql']['user']['edge_followed_by']['count'];
            $_biography = $val['graphql']['user']['biography']; 

            $_is_business_account = $val['graphql']['user']['is_business_account']; 
            $_business_category_name = $val['graphql']['user']['business_category_name']; 
            $_is_private = $val['graphql']['user']['is_private'];
            $_is_verified = $val['graphql']['user']['is_verified'];
            $_edge_follow = $val['graphql']['user']['edge_follow']['count'];
            $_profile_pic_url = $val['graphql']['user']['profile_pic_url'];
            $_edge_owner_to_timeline_media = $val['graphql']['user']['edge_owner_to_timeline_media']['count'];

            $arrayInstagramResult = array(
                array(
                    '_userName' => $_userName,
                    '_full_name' => $_full_name,
                    '_edge_followed' => $_edge_followed,
                    '_biography' => $_biography,
                    '_business_category_name' => $_business_category_name,
                    '_is_verified' => $_is_verified,
                    '_is_business_account' => $_is_business_account,
                    '_is_private' => $_is_private,
                    '_edge_follow' => $_edge_follow,
                    '_profile_pic_url' => $_profile_pic_url,
                    '_edge_owner_to_timeline_media' => $_edge_owner_to_timeline_media,

                ),
            );



            $instagramTable = new instagrams;

            $instagramTable->name =                         $_full_name;
            $instagramTable->username =                     $_userName;
            $instagramTable->follower_count =               $_edge_followed;
            $instagramTable->edge_follow =                  $_edge_follow;
            $instagramTable->biography =                    $_biography;
            $instagramTable->is_business_account =          $_is_business_account;
            $instagramTable->business_category_name =       $_business_category_name;
            $instagramTable->is_private =                   $_is_private;
            $instagramTable->is_verified =                  $_is_verified;
            $instagramTable->profile_pic_url =              $_profile_pic_url;
            $instagramTable->edge_owner_to_timeline_media = $_edge_owner_to_timeline_media;
            $instagramTable->description =                  "";
            $instagramTable->type =                         "";
            $instagramTable->status =                       "1";

            if (instagrams::where('username', '=', $_userName)->exists()) {
                // user found . then updated record
                $instagramTable = instagrams::find(1);
                $instagramTable->name =                         $_full_name;
                $instagramTable->username =                     $_userName;
                $instagramTable->follower_count =               $_edge_followed;
                $instagramTable->edge_follow =                  $_edge_follow;
                $instagramTable->biography =                    $_biography;
                $instagramTable->is_business_account =          $_is_business_account;
                $instagramTable->business_category_name =       $_business_category_name;
                $instagramTable->is_private =                   $_is_private;
                $instagramTable->is_verified =                  $_is_verified;
                $instagramTable->profile_pic_url =              $_profile_pic_url;
                $instagramTable->edge_owner_to_timeline_media = $_edge_owner_to_timeline_media;                
                $instagramTable->save();

             }else{
                $instagramTable->save();
             }
    
                      

            // return "Name : <br>".$val->graphql->user->full_name. '																				
            // <br>|memberCount : <br>'.$val->graphql->user->edge_followed_by->count. '																
            // <br>|biography : <br>'.$val->graphql->user->biography;            
        }


        // return $arrayInstagramResult;

        // return redirect()->back()->with('arrayInstagramResult', $arrayInstagramResult);
        // return redirect()->route('instagram-add', [$arrayInstagramResult]);
        // return redirect()->back()->with(compact('arrayInstagramResult'));
        return view('admin.instagram-add', compact('arrayInstagramResult'));


        // return $val->graphql->user->full_name;
        // dd ($val);


        // dd($request);

    }

    public function get_https_content($url=NULL,$method="GET"){
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0');
        curl_setopt($ch, CURLOPT_URL,$url);
        return curl_exec($ch);
    }

    public function instagramUpdating(){

        // return date("Y-m-d");

        $nowDateTime = Carbon\Carbon::now();
        $instagramLists = instagrams::whereDate('updated_at', '<', date("Y-m-d"))->get();
        return view('admin.instagram-updating', compact('instagramLists'));

    }

    public function instagramUpdatingProcess_old(){

        // return date("Y-m-d");
        $data[] = array();
        $data['resultUpdatedNow'] = null;
        $data['resultUpdatedFaild'] = null;
        
        
        

        $instagramListsTodayDate = instagrams::whereDate('updated_at', '<', date("Y-m-d"))->get();

        foreach ($instagramListsTodayDate as $key => $value) {
            // echo $value->username;
            $result = json_decode($this->get_https_content("https://www.instagram.com/".$value->username."/?__a=1"), true);

            if (empty($result)) {
                
                array_push($data['resultUpdatedFaild'], $value->username);
    
            }else{
    
                $_userName = $value->username;
                $_full_name = $result['graphql']['user']['full_name'];
                $_edge_followed = $result['graphql']['user']['edge_followed_by']['count'];
                $_biography = $result['graphql']['user']['biography']; 
    
                $_is_business_account = $result['graphql']['user']['is_business_account']; 
                $_business_category_name = $result['graphql']['user']['business_category_name']; 
                $_is_private = $result['graphql']['user']['is_private'];
                $_is_verified = $result['graphql']['user']['is_verified'];
                $_edge_follow = $result['graphql']['user']['edge_follow']['count'];
                $_profile_pic_url = $result['graphql']['user']['profile_pic_url'];
                $_edge_owner_to_timeline_media = $result['graphql']['user']['edge_owner_to_timeline_media']['count'];
    
    
                $instagramTable = instagrams::where('username', '=', $_userName)->first();
    
                $instagramTable->name =                         $_full_name;
                $instagramTable->username =                     $_userName;
                $instagramTable->follower_count =               $_edge_followed;
                $instagramTable->edge_follow =                  $_edge_follow;
                $instagramTable->biography =                    $_biography;
                $instagramTable->is_business_account =          $_is_business_account;
                $instagramTable->business_category_name =       $_business_category_name;
                $instagramTable->is_private =                   $_is_private;
                $instagramTable->is_verified =                  $_is_verified;
                $instagramTable->profile_pic_url =              $_profile_pic_url;
                $instagramTable->edge_owner_to_timeline_media = $_edge_owner_to_timeline_media;
                $instagramTable->description =                  "";
                $instagramTable->type =                         "";
                $instagramTable->status =                       "10";
    
                $instagramTable->save();

                $data['resultUpdatedNow'] .= $_userName.'*';
                // array_push($data);
                   
            }
    

            
        }

        return ($data);
    }

    public function instagramUpdatingProcess(){

        // return date("Y-m-d");
        $data[] = array();
        $data['resultUpdatedNow'] = null;
        $data['resultUpdatedFaild'] = null;
        
        
        
        //original command
        $instagramListsTodayDate = instagrams::whereDate('updated_at', '<', date("Y-m-d"))->get();


        //return $instagramListsTodayDate;

        return $this->getMultipleCurlRequest($instagramListsTodayDate);


        foreach ($instagramListsTodayDate as $key => $value) {
            // echo $value->username;


            $result = json_decode($result, true);


            // $result = json_decode($this->get_https_content("https://www.instagram.com/".$value->username."/?__a=1"), true);

            if (empty($result)) {
                
                array_push($data['resultUpdatedFaild'], $value->username);
    
            }else{
    
                $_userName = $value->username;
                $_full_name = $result['graphql']['user']['full_name'];
                $_edge_followed = $result['graphql']['user']['edge_followed_by']['count'];
                $_biography = $result['graphql']['user']['biography']; 
    
                $_is_business_account = $result['graphql']['user']['is_business_account']; 
                $_business_category_name = $result['graphql']['user']['business_category_name']; 
                $_is_private = $result['graphql']['user']['is_private'];
                $_is_verified = $result['graphql']['user']['is_verified'];
                $_edge_follow = $result['graphql']['user']['edge_follow']['count'];
                $_profile_pic_url = $result['graphql']['user']['profile_pic_url'];
                $_edge_owner_to_timeline_media = $result['graphql']['user']['edge_owner_to_timeline_media']['count'];
    
    
                $instagramTable = instagrams::where('username', '=', $_userName)->first();
    
                $instagramTable->name =                         $_full_name;
                $instagramTable->username =                     $_userName;
                $instagramTable->follower_count =               $_edge_followed;
                $instagramTable->edge_follow =                  $_edge_follow;
                $instagramTable->biography =                    $_biography;
                $instagramTable->is_business_account =          $_is_business_account;
                $instagramTable->business_category_name =       $_business_category_name;
                $instagramTable->is_private =                   $_is_private;
                $instagramTable->is_verified =                  $_is_verified;
                $instagramTable->profile_pic_url =              $_profile_pic_url;
                $instagramTable->edge_owner_to_timeline_media = $_edge_owner_to_timeline_media;
                $instagramTable->description =                  "";
                $instagramTable->type =                         "";
                $instagramTable->status =                       "10";
    
                $instagramTable->save();

                $data['resultUpdatedNow'] .= $_userName.'*';
                // array_push($data);
                   
            }
    

            
        }

        return ($data);
    }    

    public function getMultipleCurlRequest($list){

        $node_count = count($list);

        $curl_arr = array();
        $master = curl_multi_init();
        
        for($i = 0; $i < $node_count; $i++)
        {
            $url = "https://www.instagram.com/".$list[$i]->username."/?__a=1";
            echo $url.'<br>';
            // $url =$list[$i];
            $curl_arr[$i] = curl_init($url);
            curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
            curl_multi_add_handle($master, $curl_arr[$i]);
        }
        
        do {
            curl_multi_exec($master,$running);
        } while($running > 0);
        
        echo "results: ";
        for($i = 0; $i < $node_count; $i++)
        {
            $results = curl_multi_getcontent  ( $curl_arr[$i]  );
            echo( $i . "\n" . $results . "\n");
        }
        echo 'done';
        // URL from which data will be fetched
        
    }

    public function instagramProfile($id){

        $findSelect = instagrams::find($id);

        $arrayInstagramResult = array(
            array(
                '_userName' => $findSelect->username,
                '_full_name' => $findSelect->name,
                '_edge_followed' => $findSelect->follower_count,
                '_biography' => $findSelect->biography,
                '_business_category_name' =>    $findSelect->business_category_name,
                '_is_verified' => $findSelect->is_verified,
                '_is_business_account' =>   $findSelect->is_business_account,
                '_is_private' =>    $findSelect->is_private,
                '_edge_follow' =>   $findSelect->edge_follow,
                '_profile_pic_url' =>   $findSelect->profile_pic_url,
                '_edge_owner_to_timeline_media' =>  $findSelect->edge_owner_to_timeline_media,

            ),
        );    
        
        // return $arrayInstagramResult;

        return view('admin.instagram-add', compact('arrayInstagramResult'));

    }



    
    public function likeeInsertShow(){

        return view('admin.likee-add');

    }



    public function likeeInsert(Request $request){

        $arrayList = $request->userName;

        // 349419378
        // 386796338
        // ygne_amiri

        // $splitedArray = explode("\n", $arrayList);
        $splitedArray = array_map('trim',array_filter(explode("\n",$arrayList)));

        $getResult = $this->get_https_content_likeeArray($splitedArray);

        return $getResult;

        $insertedUserName = array();
        $updatedUserName = array();

        foreach ($getResult as $key => $value) {
    
            if (isset($value['info'])) {
        
                $jsonInfo = $value['info'];
                $jsonCount = $value['count'];
                $jsonPost = $value['post'];
                $jsonInfo = json_decode($jsonInfo);
                $jsonCount = json_decode($jsonCount, true);
                $jsonPost = json_decode($jsonPost, true);

                $likeeTable = new likee;

                        if (likee::where('username', '=', $jsonInfo->yyuid)->exists()) {
                            // user found . then updated record
                            $rowId = likee::where('userName', '=', $jsonInfo->yyuid)->first();
                            // return $rowId;
                            // exit();

                            // $likeeTable = new likee;

                            $likeeTable = likee::find($rowId->id);

                            $likeeTable->nickName =                         $jsonInfo->nick_name;
                            $likeeTable->userName =                     $jsonInfo->yyuid;
                            $likeeTable->birthday =               $jsonInfo->birthday;
                            $likeeTable->countryCode =                  $jsonInfo->exactCountryCode;
                            $likeeTable->bio =                    $jsonInfo->bio;
                            $likeeTable->gender =          $jsonInfo->gender;
                            $likeeTable->age =       $jsonInfo->age;
                            $likeeTable->fansCount =                   $jsonCount['data']['fansCount'];
                            $likeeTable->followCount =                  $jsonCount['data']['followCount'];
                            $likeeTable->likeCount =              $jsonPost['data']['postInfoMap'][$jsonInfo->uid]['allLikeCount'];
                            $likeeTable->videoNums = $jsonPost['data']['postInfoMap'][$jsonInfo->uid]['videoNums'];
                            $likeeTable->image = $jsonInfo->data1;

                            $likeeTable->save();
                            array_push($updatedUserName, $jsonInfo->yyuid);

                        }else{

                            $likeeTable->nickName =                         $jsonInfo->nick_name;
                            $likeeTable->userName =                     $jsonInfo->yyuid;
                            $likeeTable->birthday =               $jsonInfo->birthday;
                            $likeeTable->countryCode =                  $jsonInfo->exactCountryCode;
                            $likeeTable->bio =                    $jsonInfo->bio;
                            $likeeTable->gender =          $jsonInfo->gender;
                            $likeeTable->age =       $jsonInfo->age;
                            $likeeTable->fansCount =                   $jsonCount['data']['fansCount'];
                            $likeeTable->followCount =                  $jsonCount['data']['followCount'];
                            $likeeTable->likeCount =              $jsonPost['data']['postInfoMap'][$jsonInfo->uid]['allLikeCount'];
                            $likeeTable->videoNums = $jsonPost['data']['postInfoMap'][$jsonInfo->uid]['videoNums'];
                            $likeeTable->image = $jsonInfo->data1;

                            $likeeTable->save();
                            
                            array_push($insertedUserName, $jsonInfo->yyuid);
                          
                        }                    

           
                }
                // else{
                //     //user not found
                //     $likeeTable = new likee;

                //     $likeeTable->nickName = $jsonInfo->nick_name;
                //     $likeeTable->save();
                    
                // }
            
        } 



            Session::flash('message', " تعداد اطلاعات مورد بررسی :  ". count($getResult)." <br>
            رکوردهای بروزرسانی : ".implode( ", ", $updatedUserName )."<br>
            رکوردهای جدید : ".implode( ", ", $insertedUserName )."<br>
            "); 

            Session::flash('alert-class', 'alert-success'); 
            return redirect()->back();


    }




    public function likee(){
        // $aaa = $this->get_https_content_likee();

        print_r($aaa);
        exit();

        foreach ($aaa as $key => $value) {
	
            // echo $value['count'];
                if (isset($value['info'])) {
        
                    $jsonInfo = $value['info'];
                    $jsonCount = $value['count'];
                    $jsonPost = $value['post'];
                    $jsonInfo = json_decode($jsonInfo);
                    $jsonCount = json_decode($jsonCount, true);
                    $jsonPost = json_decode($jsonPost, true);


                    $likeeTable = new likee;

                    $likeeTable->nickName =                         $jsonInfo->nick_name;
                    $likeeTable->userName =                     $jsonInfo->yyuid;
                    $likeeTable->birthday =               $jsonInfo->birthday;
                    $likeeTable->countryCode =                  $jsonInfo->exactCountryCode;
                    $likeeTable->bio =                    $jsonInfo->bio;
                    $likeeTable->gender =          $jsonInfo->gender;
                    $likeeTable->age =       $jsonInfo->age;
                    $likeeTable->fansCount =                   $jsonCount['data']['fansCount'];
                    $likeeTable->followCount =                  $jsonCount['data']['followCount'];
                    $likeeTable->likeCount =              $jsonPost['data']['postInfoMap'][$jsonInfo->uid]['allLikeCount'];
                    $likeeTable->videoNums = $jsonPost['data']['postInfoMap'][$jsonInfo->uid]['videoNums'];
                    $likeeTable->image = $jsonInfo->data1;

                    $likeeTable->save();
           
        
                    // echo '<tr>';
                    //         // echo '<td>'.$resultInfo->uid.'</td>';
                    //         echo '<td><img class="rounded-circle" src="'.$jsonInfo->data1.'" width="60"/>'.$jsonInfo->nick_name.'</td>';
                    //         echo '<td>'.$jsonInfo->yyuid.'</td>';
                    //         echo '<td>'.$jsonInfo->birthday.'</td>';
                    //         echo '<td>'.$jsonInfo->exactCountryCode.'</td>';
                    //         echo '<td>'.$jsonInfo->bio.'</td>';
                    //         echo '<td>'.$jsonInfo->gender.'</td>';
                    //         echo '<td>'.$jsonInfo->age.'</td>';	
                    //         echo '<td>'.$jsonCount['data']['fansCount'].'</td>';	
                    //         echo '<td>'.$jsonCount['data']['followCount'].'</td>';	
                    //         echo '<td>'.$jsonPost['data']['postInfoMap'][$jsonInfo->uid]['allLikeCount'].'</td>';	
                    //         echo '<td>'.$jsonPost['data']['postInfoMap'][$jsonInfo->uid]['videoNums'].'</td>';	
                    // echo '</tr>';
                }
                // else{
                //     //user not found
                //     $likeeTable = new likee;

                //     $likeeTable->nickName = $jsonInfo->nick_name;
                //     $likeeTable->save();
                    
                // }
            
        }        

    }

    public function likeeList(){
        $likeeLists = likee::get();

        return view('admin.likee-list', compact('likeeLists'));
    }

    // not user dynamically.
    public function get_https_content_likee($url=NULL){

        $urls = array();
        //set the urls
        
        $urls[] = '349419378';
        $urls[] = '386796338';
        $urls[] = 'ygne_amiri';

        
        // array of curl handles
        $multiCurl = array();
        // data to be returned
        $result = array();
        // multi handle
        $mh = curl_multi_init();
        foreach ($urls as $i => $id) {
        
            // echo $id;
          // URL from which data will be fetched
          $fetchURL = 'https://likee.com/user/@'.$id.'/?lang=en';
          $multiCurl[$i] = curl_init();
          curl_setopt($multiCurl[$i], CURLOPT_URL,$fetchURL);
          curl_setopt($multiCurl[$i], CURLOPT_HEADER,0);
          curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER,1);
        
            curl_setopt($multiCurl[$i], CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($multiCurl[$i], CURLOPT_VERBOSE, 0);
            curl_setopt($multiCurl[$i], CURLOPT_ENCODING,  '');
            curl_setopt($multiCurl[$i], CURLOPT_NOPROGRESS,  0);
            curl_setopt($multiCurl[$i], CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        
          curl_multi_add_handle($mh, $multiCurl[$i]);
        }
        $index=null;
        do {
          curl_multi_exec($mh,$index);
        } while($index > 0);
        // get content and remove handles
        foreach($multiCurl as $k => $ch) {
          $result[$k] = curl_multi_getcontent($ch);
        
          preg_match("'userinfo\":(.*?),\"userinfoJson'si", $result[$k], $match);
        
                if($match){
        
                    // $result[$k] = $match[1];
                    $jsonUid = json_decode($match[1]);
                        // get following count 
                        $url_getUserFollow = 'https://likee.com/official_website/UserApi/getUserFollow/';
                        $url_getUserPostInfo = 'https://likee.com/official_website/UserApi/getUserPostInfo/';
        
                        $resultFan = array();
        
                            $postdata = http_build_query(
                                array(
                                    'uid' => $jsonUid->uid )
                            );
                            $opts = array('http' =>
                                array(
                                    'method'  => 'POST',
                                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                                    'content' => $postdata
                                )
                            );
                            $context  = stream_context_create($opts);
                            $resultFan = file_get_contents($url_getUserFollow, false, $context);
        
                            $context  = stream_context_create($opts);
                            $resultPost = file_get_contents($url_getUserPostInfo, false, $context);
        
        
                            $arr = array();
                            $arr['info'] = $match[1];
                            $arr['count'] = $resultFan;
                            $arr['post'] = $resultPost;
        
                            $result[$k] = ($arr);
        
        
                            // array_push($result, $resultFan);
        
        
        
                }
          curl_multi_remove_handle($mh, $ch);
        }
        
        
        
        
            return $result;
        // close
        curl_multi_close($mh);
        
        
    }

    public function get_https_content_likeeArray($urls){
        
        // array of curl handles
        $multiCurl = array();
        // data to be returned
        $result = array();
        // multi handle
        $mh = curl_multi_init();
        foreach ($urls as $i => $id) {
        
            // echo $id;
          // URL from which data will be fetched
          $fetchURL = 'https://likee.com/user/@'.$id.'/?lang=en';
          $multiCurl[$i] = curl_init();
          curl_setopt($multiCurl[$i], CURLOPT_URL,$fetchURL);
          curl_setopt($multiCurl[$i], CURLOPT_HEADER,0);
          curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER,1);
        
            curl_setopt($multiCurl[$i], CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($multiCurl[$i], CURLOPT_VERBOSE, 0);
            curl_setopt($multiCurl[$i], CURLOPT_ENCODING,  '');
            curl_setopt($multiCurl[$i], CURLOPT_NOPROGRESS,  0);
            curl_setopt($multiCurl[$i], CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        
          curl_multi_add_handle($mh, $multiCurl[$i]);
        }
        $index=null;
        do {
          curl_multi_exec($mh,$index);
        } while($index > 0);
        // get content and remove handles
        foreach($multiCurl as $k => $ch) {
          $result[$k] = curl_multi_getcontent($ch);
        
          preg_match("'userinfo\":(.*?),\"userinfoJson'si", $result[$k], $match);

        //   dd($result[$k]);
        
                if($match){
        
                    // $result[$k] = $match[1];
                    $jsonUid = json_decode($match[1]);
                        // get following count 
                        $url_getUserFollow = 'https://likee.com/official_website/UserApi/getUserFollow/';
                        $url_getUserPostInfo = 'https://likee.com/official_website/UserApi/getUserPostInfo/';
        
                        $resultFan = array();
        
                            $postdata = http_build_query(
                                array(
                                    'uid' => $jsonUid->uid )
                            );
                            $opts = array('http' =>
                                array(
                                    'method'  => 'POST',
                                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                                    'content' => $postdata
                                )
                            );
                            $context  = stream_context_create($opts);
                            $resultFan = file_get_contents($url_getUserFollow, false, $context);
        
                            $context  = stream_context_create($opts);
                            $resultPost = file_get_contents($url_getUserPostInfo, false, $context);
        
        
                            $arr = array();
                            $arr['info'] = $match[1];
                            $arr['count'] = $resultFan;
                            $arr['post'] = $resultPost;
        
                            $result[$k] = ($arr);
        
        
                            // array_push($result, $resultFan);
        
        
        
                }
          curl_multi_remove_handle($mh, $ch);
        }
        
        
        
        
            return $result;
        // close
        curl_multi_close($mh);
        
        
    }    






   public function alexaCheckShow(){
         return view('admin.alexa-add');
   }

   public function alexaInsert(Request $request){

    // $responsCode = $this->getHttpResponsCode($request->userName);
    // // dd( $responsCode);
    // $a = explode("\r\n", $responsCode);
    // $arr = array();
    // foreach ($a as $kk){
    //     $sp = explode(": ", $kk);
    //     // $arr[$sp[0]] = $sp[1];
    //     $arr[$sp[0]] = isset($sp[1])? $sp[1] : $sp[0];
    // }
    // return  $arr;
    // $a = json_encode($a, true);
    // dd($a);
    // return $a[4];
    // exit();

        $arrayList = $request->userName;
        $updatedDomain = array();
        $insertedDomain = array();

        // yjc.ir
        // iribcs.ir
        // google.com

        $splitedArray = array_map('trim',array_filter(explode("\n",$arrayList)));

        if (count($splitedArray) > 20) {
            Session::flash('message', " تعداد اطلاعات مورد بررسی :  ". count($splitedArray)." <br>
            علت خطا : تعداد بیشتر از حد مجاز دامنه برای واکشی<br>"
            ); 
            Session::flash('alert-class', 'alert-danger'); 
            return redirect()->back();
        }

        $getResult = $this->get_alexa_checkRank($splitedArray);

        // return $getResult;

        //[{"name":"naser-zare.ir","ranklocal":null,"rankglobal":null,
        //"location":"http:\/\/naser-zare.ir\/cgi-sys\/suspendedpage.cgi","statuscode":"HTTP\/1.1 302 Found"}]

        foreach ($getResult as $key => $value) {       

            // dd($value);            

            // echo $value['name'].$value['ranklocal'][0].'<br>';

            $domainTable = new domains;

            $host = explode('.', $value['name']); // explade yjc . ir
            $redirect_ = $value['location']? 1: 0;
            $globalRank = ($value['rankglobal'][0] && strtolower($value['rankglobal'][0]) !='n')? $value['rankglobal'][0]: null;
            $localRank =  ($value['ranklocal'][0] && strtolower($value['ranklocal'][0]) !='n')? $value['ranklocal'][0]: null;
            // return $globalRank.$localRank;

            // if (domains::where('url', '=', $host[0])->exists()) {
            if (domains::where('full_url', '=', $value['name'])->exists()) {
                // domain found ... then updated record
                $rowId = domains::where('full_url', '=',  $value['name'])->first();
                // return $rowId;
                // exit();

                // $domainTable = new likee;

                $domainTable = domains::find($rowId->id);

                $domainTable->globalrank = $globalRank;
                $domainTable->localrank = $localRank;
                $domainTable->redirect = $redirect_;
                $domainTable->redirect_to = $value['location'];
                $domainTable->status_code = $value['statuscode'];
                $domainTable->title =  $value['metaDescriptionUrl'];
                $domainTable->expertion_date = $value['expireDate'];

                $domainTable->save();
                array_push($updatedDomain, $value['name']);

            }else{

                // $host = explode('.', $value['name']);
                $domainTable->full_url = $value['name'];
                $domainTable->url = $host[0];
                $domainTable->dot = $host[1];
                $domainTable->globalrank = $globalRank;
                $domainTable->localrank = $localRank;
                $domainTable->title =  $value['metaDescriptionUrl'];
                // $domainTable->howis = 
                $domainTable->expertion_date = $value['expireDate'];
                $domainTable->redirect = $redirect_;
                $domainTable->redirect_to = $value['location'];
                $domainTable->status_code = $value['statuscode'];
                // $domainTable->description = 

                $domainTable->save();
                
                array_push($insertedDomain, $value['name']);
                
            }           
        }


        Session::flash('message', " تعداد اطلاعات مورد بررسی :  ". count($getResult)." <br>
        رکوردهای بروزرسانی : ".implode( ", ", $updatedDomain )."<br>
        رکوردهای جدید : ".implode( ", ", $insertedDomain )."<br>
        "); 

        Session::flash('alert-class', 'alert-success'); 
        return redirect()->back();

        // return  $getResult;

   }

   public function get_alexa_checkRank($urls){
            $result = array();
            $arr0 = array();
            global $ranklocal, $rankglobal, $metaDescriptionUrl, $expireDate;

            foreach ($urls as $i => $url){

                if($socket =@ fsockopen($url, 80, $errno, $errstr, 3)) {
                    // echo 'online!';
                    $xml = @simplexml_load_file("http://data.alexa.com/data?cli=10&url=".$url);
                    // $get_headers = @get_headers('http://'.$url, 1);
                    $responsCode = $this->getHttpResponsCode($url);
                    // dd( $responsCode);
                    
                    $a = explode("\r\n", $responsCode);
                    $arr0 = array();
                    foreach ($a as $kk){
                        $sp = explode(": ", $kk);
                        // $arr0[$sp[0]] = $sp[1];
                        $ss = substr($sp[0], 0, 3);
                        $arr0[$ss] = isset($sp[1])? $sp[1] : $sp[0];
                
                    }            

                    // return $arr0;
            
                    if(isset($xml->SD)){
                        //echo 'ALEXA RankGlobal : '.$xml->SD->REACH->attributes().'<br>';
                        //echo 'ALEXA IranRank : '.$xml->SD->COUNTRY[0]['RANK'].'<br>'; 
                        
                        //   $arr = [
                        //     'name' => $url,
                        //     'local' => isset($xml->SD->POPULARITY[0]['TEXT'])? $xml->SD->POPULARITY[0]['TEXT'] : 'not',
                        //     'global' => isset($xml->SD->COUNTRY[0]['RANK'])? $xml->SD->COUNTRY[0]['RANK'] : 'not',
                        // ];

                            // $arr = array('name' => $url);
                            // $arr = array_add($arr, 'rankgglobal', isset($xml->SD->POPULARITY[0]['TEXT'])? $xml->SD->POPULARITY[0]['TEXT'] : 'not');
                            // $arr = array_add($arr, 'rankglobal', isset($xml->SD->COUNTRY[0]['RANK'])? $xml->SD->COUNTRY[0]['RANK'] : 'not');
                            $rankglobal = isset($xml->SD->POPULARITY[0]['TEXT'])? $xml->SD->POPULARITY[0]['TEXT'] : 'not';
                            $ranklocal = isset($xml->SD->COUNTRY[0]['RANK'])? $xml->SD->COUNTRY[0]['RANK'] : 'not';

                            // $headerCode = isset($get_headers[0])? $get_headers[0] : 'not';
                            // $location = isset($get_headers['Location'])? $get_headers['Location'] : 'not';
                            // $result = parse_url($location);
                            // $location = ($result['host'] == $url)? $get_headers['Location'] : 'ok';                    

                            // $arr = Arr::add(['name' => $url, 'ranklocal' => $ranklocal, 'rankglobal' => $rankglobal, 'location' => $location], 'statuscode', $headerCode);
                            
                        
                            // $arr = array();
                            // $arr['name'] = $url;
                            // $arr['rankglobal'] = isset($xml->SD->POPULARITY[0]['TEXT'])? $xml->SD->POPULARITY[0]['TEXT'] : 'not';
                            // $arr['ranklocal'] = isset($xml->SD->COUNTRY[0]['RANK'])? $xml->SD->COUNTRY[0]['RANK'] : 'not';
                    
                                        
                    }

                    if (isset($arr0)) {

                        $headerCode = (@$arr0['HTT']) ? $arr0['HTT'] : 'Server Down';
                        // $locarion_filter = str_replace(array('http://','https://','www.', '/'), '', @$arr0['Loc']);



                        $parse = @parse_url(@$arr0['Loc']);
                        // return $url .' -> '. $parse['host'];




                        // if ($url == $locarion_filter) {
                        if ($url == @$parse['host']) {
                            $location = null; // redirect nashode va code 301 dare. roye https redirect shode.
                        }else{
                            $location = @$arr0['Loc']; // redirect shode roye other domain.
                        }
                    }

                        // get meta tag description in the sorce url
                    $tags = @get_meta_tags('http://'. $url .'/');
                    if(isset($tags['description'])){
                        $metaDescriptionUrl = $tags['description'];
                    }else{
                        $metaDescriptionUrl = null;
                    }

                    // $metaDescriptionUrl = null;

                        // get expire date domain from howis request
                    $dataHowis = json_decode(@file_get_contents('https://www.namecheap.com/domains/contactlookup-api/whois/lookupraw/'.$url), 1);
                    $suffixUrl = explode('.', $url);
                    if ($suffixUrl[1] == 'ir') {
                        preg_match('/(?<=expire-date:).*?(?=source)/', str_replace("\n","",$dataHowis), $matches);
                    }elseif($suffixUrl[1] == 'com' || 'net'){
                        preg_match('/(?<=Expiration Date:).*?(?=Registrar:)/', str_replace("\n","",$dataHowis), $matches);
                    }
                    
                        // var_dump($matches);
                    if(isset($matches)){
                        $expireDate = isset($matches[0])? $matches[0]: null;
                    }else{
                        $expireDate = null;
                    }

                    // $expireDate = null;

                    $arr = Arr::add(['name' => $url, 'ranklocal' => $ranklocal, 'rankglobal' => $rankglobal, 'location' => $location, 'metaDescriptionUrl' => $metaDescriptionUrl, 'expireDate' => $expireDate], 'statuscode', $headerCode);
                    
                    $result[$i] = $arr;

                

                fclose($socket);
                
                } else {
                    //echo 'offline.';
                    $arr = Arr::add(['name' => $url, 'ranklocal' => null, 'rankglobal' => null, 'location' => null, 'metaDescriptionUrl' => null, 'expireDate' => null], 'statuscode', 'Server Down');
                    $result[$i] = $arr;
                    
                } //end if

            }//end foreach

            
            // http://data.alexa.com/data?cli=10&url=aboatashtv.ir

                
  
        return $result;
    }





   
    public function domainList(){

        $allDomains = domains::get();
        return view('admin.domain-list',compact('allDomains'));
   }

   public function getHttpResponsCode($url){

      $ch = curl_init(); // create a new CURL resource
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_NOBODY, true);
      curl_setopt($ch, CURLOPT_HEADER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      $st= curl_exec($ch);
      curl_close($ch);
      return $st;

        

   }

   public function alexaCheckBatchWithSchedule(){
        $domainTableTodayDate = domains::where('updated_at', '<', date("Y-m-d"))
                                        ->inRandomOrder()
                                        ->take(2)
                                        ->get(['full_url']);
        // $domainTableTodayDate = domains::where('id', '=', 8)->get();
        // $instagramLists = instagrams::whereDate('updated_at', '<', date("Y-m-d"))->get();

        // return count($domainTableTodayDate);

        // چک کردن توسط زمان بندی انجام میششود هر دقیقه و گر رکوردی دیگر برای امروز یافت نشد زمان بندی تمام شود و یا استاپ شود.

        // $arrayList = $request->userName;
        $updatedDomain = array();
        $insertedDomain = array();
        $splitedArray = array();
        // yjc.ir
        // iribcs.ir

        foreach ($domainTableTodayDate as $val){
            $splitedArray[] = $val->full_url;
        }
        // var_dump($splitedArray);
        // return $splitedArray;
        // echo $splitedArray;

        // return $splitedArray;

        $getResult = $this->get_alexa_checkRank($splitedArray);

        return $getResult;

        file_put_contents(storage_path('logs/_domain/Schedule_domain'.date('m-d-Y_hia').'.log'), print_r($getResult, true));

        // dd($getResult);

        //[{"name":"naser-zare.ir","ranklocal":null,"rankglobal":null,
        //"location":"http:\/\/naser-zare.ir\/cgi-sys\/suspendedpage.cgi","statuscode":"HTTP\/1.1 302 Found"}]

        foreach ($getResult as $key => $value) {

            // dd($value);

            // echo $value['name'].$value['ranklocal'][0].'<br>';

            

            $host = explode('.', $value['name']); // explode yjc . ir
            $redirect_ = $value['location']? 1: 0;
            $globalRank = ($value['rankglobal'][0] && strtolower($value['rankglobal'][0]) !='n')? $value['rankglobal'][0]: null;
            $localRank =  ($value['ranklocal'][0] && strtolower($value['ranklocal'][0]) !='n')? $value['ranklocal'][0]: null;
            // return $globalRank.$localRank;

            if (domains::where('url', '=', $host[0])->exists()) {
                // domain found ... then updated record
                $rowId = domains::where('url', '=',  $host[0])->first();
                // return $rowId;
                // exit();

                $domainTable = new domains;

                $domainTable = domains::find($rowId->id);

                $domainTable->globalrank = $value['rankglobal'][0];
                $domainTable->localrank = $value['ranklocal'][0];
                $domainTable->redirect_to = $value['location'];
                $domainTable->status_code = $value['statuscode'];
                $domainTable->title =  $value['metaDescriptionUrl'];
                $domainTable->expertion_date = $value['expireDate'];

                $domainTable->save();
                // Update the "updated_at" column only
                $domainTable->touch();
                array_push($updatedDomain, $value['name']);

            }      
        }

        return $updatedDomain;

        // Session::flash('message', " تعداد اطلاعات مورد بررسی :  ". count($getResult)." <br>
        // رکوردهای بروزرسانی : ".implode( ", ", $updatedDomain )."<br>
        // رکوردهای جدید : ".implode( ", ", $insertedDomain )."<br>
        // "); 

        // Session::flash('alert-class', 'alert-success'); 
        // return redirect()->back();       
   }

   public function authority(){
        return view('admin.authority-add');
   }


   public function authorityChecker(Request $request){
        //    return 'ok';
        $arrayList = $request->userName;
        $updated = array();
        $inserted = array();
        // yjc.ir
        // iribcs.ir

        $splitedArray = array_map('trim',array_filter(explode("\n",$arrayList)));

        $getResult = $this->get_domain_authority($splitedArray);

        // dd($getResult);

        // $countStatusSuccessed = count(array_filter($getResult,function($element) {
        //     return $element['status']=='200';
        //   }));

        // $countStatusFailed = count(array_filter($getResult,function($element) {
        //     return $element['status']=='403';
        // }));

        // return $getResult;

        foreach ($getResult as $key => $value) {      
            
            $domainTable = new domains;

            if (domains::where('full_url', '=', $value['url'])->exists()) {
                // domain found ... then updated record
                $rowId = domains::where('full_url', '=',  $value['url'])->first();
                // return $rowId;
                // exit();

                $domainTable = domains::find($rowId->id);

                //$domainTable->openrank = $value['openrank'];
                $domainTable->domain_authority = $value['DomainAuthority'];
                $domainTable->page_authority = $value['pageAuthority'];
                $domainTable->save();
                array_push($updated, $value['url']);

            }else{

                if ($value['url']) {
                    $host = explode('.', $value['url']);
                    $domainTable->full_url = $value['url'];
                    $domainTable->url = $host[0];
                    $domainTable->dot = $host[1];
                    //$domainTable->openrank = $value['openrank'];
                    $domainTable->domain_authority = $value['DomainAuthority'];
                    $domainTable->page_authority = $value['pageAuthority'];
                    $domainTable->save();
                    array_push($inserted, $value['url']);
                }
    
            }           
        }   // End foreach


        Session::flash('message', " تعداد اطلاعات مورد بررسی :  ". count($getResult)." <br>
        رکوردهای بروزرسانی : ".implode( ", ", $updated )."<br>
        رکوردهای جدید : ".implode( ", ", $inserted )."<br>
        مدت زمان پردازش : (s)".substr($getResult['spentTime'], 0, 3)."<br>
        "); 

        Session::flash('alert-class', 'alert-success'); 
        return redirect()->back();

   }

   public function get_domain_authority($urls){

        $result = array();
        $dataDA = array();
        
        $arr = array();
        global $countStatusSuccessed, $countStatusFailed;

        $countStatusSuccessed = 0;
        $countStatusFailed = 0;

        //Create a custom stream context that has a HTTP timeout
        //of 3 seconds.
        $streamContext = stream_context_create(
            array('http'=>
                array(
                    'timeout' => 3,  //3 seconds
                )
            )
        );

        $t = microtime( TRUE );

        // $dataDA = json_decode(@file_get_contents('https://api.openrank.io/?key=rBHOEfwVhh4g7gJhBgV33i/wv7qpdea5uvwyokYI/4Y&d='.implode('|',$urls), false, $streamContext),true);

        // $arrayResultOpenRank = array();
        // foreach ($dataDA['data'] as $key => $value) {
           
        //     $arrayResultOpenRank = Arr::add(['url' => $key, 'openrank' => $value['openrank']], null, null);
        //     $result[] = $arrayResultOpenRank;
 
        // }
  




        // ok ths command for this link : https://prod.sureoakdat...
        foreach ($urls as $i => $url){
        
            // get domain & page authority request
            if($socket =@ fsockopen($url, 80, $errno, $errstr, 3)) {
                
                $dataDA = json_decode(@file_get_contents('https://prod.sureoakdata.com/api/v1/domain-authority-checker?websiteUrl='.$url, false, $streamContext),true);
                if($dataDA === FALSE) {
                    // print "Site down"; // or other problem
                    // $dataDA = array();
                    $dataDA['status'] = '403';
                    $dataDA['domainAuthority'] = 'null';
                    $dataDA['externalEquityLinks'] = 'null';
                    $dataDA['prettyExternalEquityLinks'] = 'null';
                    $dataDA['pageAuthority'] = 'null';
                    $dataDA = json_decode(json_encode($dataDA));
                } else {
                    // $t = microtime( TRUE ) - $t;
                    // print "It took $t seconds!";
                    $dataDA['status'] = '200';
                    // array_push($dataDA['status'], "403");
                    // $dataDA = array_push($dataDA, $dataDA['status'] = "403");
                    // $dataDA += (string)['status' => "403"];
                    $dataDA = json_decode(json_encode($dataDA));
                }

                fclose($socket);
            } 
            else 
            {   
                $dataDA = array();
                $dataDA['status'] = '403';
                $dataDA['domainAuthority'] = 'null';
                $dataDA['externalEquityLinks'] = 'null';
                $dataDA['prettyExternalEquityLinks'] = 'null';
                $dataDA['pageAuthority'] = 'null';

                $dataDA = json_decode(json_encode($dataDA));
            } 

            ($dataDA->status=="200"? $countStatusSuccessed++:null);
            ($dataDA->status=='403'? $countStatusFailed++:null);

            // $arr = Arr::add(['data' => $dataDA], 'pageAuthority', 'null');
            $arr = Arr::add(['status' => $dataDA->status , 'url' => $url, 'DomainAuthority' => $dataDA->domainAuthority, 'externalEquityLinks' => $dataDA->externalEquityLinks, 'prettyExternalEquityLinks' => $dataDA->prettyExternalEquityLinks], 'pageAuthority', $dataDA->pageAuthority);
            $result[$i] = $arr;
            sleep(rand(1,3)); // this should halt for 3 seconds for every loop
        }   //End Foreach

            $t = microtime( TRUE ) - $t;
            // $result['countStatusSuccessed'] = $countStatusSuccessed;
            // $result['countStatusFailed'] = $countStatusFailed;
            $result['spentTime'] = $t;
            // return $dataDA;
            return $result;
        
   }


   public function robot_authorityCheckSchedule(){

        //$domainTableTodayDate = domains::where('updated_at', '<', date("Y-m-d"))->inRandomOrder()->take(5)->get(['full_url']);
        $domainTableTodayDate = domains::
                                         where('updated_at', '<', date("Y-m-d"))->
                                         where('status_code', '!=', 'server down')->
                                         Where(function ($query) {
                                            $query->orWhere('redirect_to', '=', null)
                                                  ->orWhere('redirect_to', '=', '');
                                        })->
                                        //  orWhereNull('redirect_to')->
                                         //orWhere('redirect_to', '')->
                                         inRandomOrder()->take(5)->
                                         get(['full_url']);

        // $domainTableTodayDate = domains::where('id', '=', 88)->get(['full_url']);
        // return count($domainTableTodayDate);

        // چک کردن توسط زمان بندی انجام میششود هر دقیقه و گر رکوردی دیگر برای امروز یافت نشد زمان بندی تمام شود و یا استاپ شود.

        $updated = array();
        $inserted = array();
        $splitedArray = array();
        // yjc.ir
        // iribcs.ir

        foreach ($domainTableTodayDate as $key => $value) {
            array_push($splitedArray, $value->full_url);
        }
        // return $domainTableTodayDate['full_url'];
        // return $splitedArray;

        // ****** for https://prod.sureoakdata.com/api/v1/domain-authority-checker?websiteUrl=
        //$getResult = $this->get_domain_authority($splitedArray);
        //$getResult = $this->get_domain_authority_moz_multiCurl($splitedArray);

        $getResult = $this->get_domain_authority_sureoakdata_multiCurl($splitedArray, 'sureoakdata');
        // return $getResult;

        file_put_contents(storage_path('logs/_domain/robotScheduleAuthority'.date('m-d-Y_hia').'.log'), print_r($getResult, true));
        // return $getResult;

        foreach ($getResult as $key => $value) {
            
            $domainTable = new domains;

            if (domains::where('full_url', '=', $value['url'])->exists()) {
                // domain found ... then updated record
                $rowId = domains::where('full_url', '=',  $value['url'])->first();
                // return $rowId;
                // exit();

                $domainTable = domains::find($rowId->id);
                //$domainTable->openrank = $value['openrank'];
                $domainTable->domain_authority = $value['DA'];
                $domainTable->page_authority = $value['PA'];
                $domainTable->save();

                array_push($updated, $value['url']);

            }else{

                if ($value['url']) {
                    $host = explode('.', $value['url']);
                    $domainTable->full_url = $value['url'];
                    $domainTable->url = $host[0];
                    $domainTable->dot = $host[1];
                    //$domainTable->openrank = $value['openrank'];
                    $domainTable->domain_authority = $value['DA'];
                    $domainTable->page_authority = $value['PA'];
    
                    $domainTable->save();
                    
                    array_push($inserted, $value['url']);
                }
    
            }           
        }   // End foreach

        return $updated;

   }


   public function mozAuthority(){

        $domainTableTodayDate = domains::inRandomOrder()->take(1)->get(['full_url']);
        // return $domainTableTodayDate;

        $updated = array();
        $inserted = array();
        $splitedArray = array();
        // yjc.ir
        // iribcs.ir

        foreach ($domainTableTodayDate as $key => $value) {
            array_push($splitedArray, $value->full_url);
        }
        // return $domainTableTodayDate['full_url'];
        // return $splitedArray;

        // $getResult = $this->get_domain_authority_moz($splitedArray);
        // return $getResult;

        $json = array();
        $urls = array('yjc.ir', 'iribnews.ir', 'irib.ir', 'irinn.ir', 'tv3.ir', 'tv5.ir');
        foreach ($urls as $i => $value) {
            // create & initialize a curl session
            $request_headers = array(
                "authority: analytics.moz.com",
                "cache-control: max-age=0",
                "upgrade-insecure-requests: 1",
                "user-agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Mobile Safari/537.36",
                "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
                "sec-fetch-site: none",
                "sec-fetch-mode: navigate",
                "sec-fetch-user: ?1",
                "sec-fetch-dest: document",
                "cookie: __cfduid=dc0efb0a634d8576d3d7e9d5ada4646751607241911; ajs_anonymous_id=^%^22a2c79375-e14c-40c0-bf0f-186e63a16b22^%^22; _gcl_au=1.1.965080288.1607241917; _fbp=fb.1.1607241917265.1820973479; __hssrc=1; hubspotutk=0424de13417f4f90facb2ff8d9b20aec; __stripe_mid=784d7ea6-8e5e-4fd6-b8c0-5c0bffe95e6f9775da; __hstc=103427807.0424de13417f4f90facb2ff8d9b20aec.1607241916866.1607249646087.1607505503443.4; mozauth=XjxvzniQ4u1Tt3OgCquPbRm1CttkzltFtzWGg2PHqbWm3SMQaa6Jy4cGgS9SSiTE; ajs_user_id=16734711; _uetvid=c696b860379911eba4e52f6773029ccd; _ga_LGQZKGRBE5=GS1.1.1607505503.4.1.1607507312.0; _ga=GA1.1.1116164280.1607241917; __cf_bm=ffe35b82a4ed8ad4f62fbca447fa966993a38526-1607835936-1800-AUl57Zb8Dhgy6AdgdBDykh6Aob/YthiqZnET05xzXQSFZZDUwdGbicQAJM++r93XI4iytJNSnh5/5Bg5Ljasl6Y="
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://analytics.moz.com/listerine/api/1.4/idina/url_metrics?site=".$value);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);

            $season_data = curl_exec($ch);

            if (curl_errno($ch)) {
                print "Error: " . curl_error($ch);
                exit();
            }

            // Show me the result
            curl_close($ch);
            //$json .= json_decode($season_data, true);
            $json[] .= ($season_data);

        }

        return $json;
   }

   public function get_domain_authority_moz($urls){

    $result = array();
    $dataDA = array();
    
    $arr = array();
    global $countStatusSuccessed, $countStatusFailed;

    $countStatusSuccessed = 0;
    $countStatusFailed = 0;

    //Create a custom stream context that has a HTTP timeout
    //of 3 seconds.
    $streamContext = stream_context_create(
        array('http'=>
            array(
                'timeout' => 3,  //3 seconds
            )
        )
    );

    $t = microtime( TRUE );

    $dataDA = json_decode(@file_get_contents('https://analytics.moz.com/listerine/api/1.4/idina/url_metrics?site='.$urls, false, $streamContext),true);

    return $dataDA;

    $arrayResultOpenRank = array();
    foreach ($dataDA['data'] as $key => $value) {
       
        $arrayResultOpenRank = Arr::add(['url' => $key, 'openrank' => $value['openrank']], null, null);
        $result[] = $arrayResultOpenRank;

    }





    // ok ths command for this link : https://prod.sureoakdat...
    // foreach ($urls as $i => $url){
    
    //     // get domain & page authority request
    

    //     //  https://api.openrank.io/?key=sdsdsdsd&google.com  free api for rank API
    //     //  https://api.openrank.io/?key=rBHOEfwVhh4g7gJhBgV33i/wv7qpdea5uvwyokYI/4Y&d=google.com%7Cbing.com

    //     //  https://moz.com/domain-analysis?site=iribcs.ir      other free

    //     if($socket =@ fsockopen($url, 80, $errno, $errstr, 3)) {
            
    //         $dataDA = json_decode(@file_get_contents('https://prod.sureoakdata.com/api/v1/domain-authority-checker?websiteUrl='.$url, false, $streamContext),true);
    //         if($dataDA === FALSE) {
    //             // print "Site down"; // or other problem
    //             // $dataDA = array();
    //             $dataDA['status'] = '403';
    //             $dataDA['domainAuthority'] = 'null';
    //             $dataDA['externalEquityLinks'] = 'null';
    //             $dataDA['prettyExternalEquityLinks'] = 'null';
    //             $dataDA['pageAuthority'] = 'null';
    //             $dataDA = json_decode(json_encode($dataDA));
    //         } else {
    //             // $t = microtime( TRUE ) - $t;
    //             // print "It took $t seconds!";
    //             $dataDA['status'] = '200';
    //             // array_push($dataDA['status'], "403");
    //             // $dataDA = array_push($dataDA, $dataDA['status'] = "403");
    //             // $dataDA += (string)['status' => "403"];
    //             $dataDA = json_decode(json_encode($dataDA));
    //         }


    //         fclose($socket);
    //     } 
    //     else 
    //     {   
    //         $dataDA = array();
    //         $dataDA['status'] = '403';
    //         $dataDA['domainAuthority'] = 'null';
    //         $dataDA['externalEquityLinks'] = 'null';
    //         $dataDA['prettyExternalEquityLinks'] = 'null';
    //         $dataDA['pageAuthority'] = 'null';

    //         $dataDA = json_decode(json_encode($dataDA));
    //     } 

    //     ($dataDA->status=="200"? $countStatusSuccessed++:null);
    //     ($dataDA->status=='403'? $countStatusFailed++:null);

        

    //     // $arr = Arr::add(['data' => $dataDA], 'pageAuthority', 'null');
    //     $arr = Arr::add(['status' => $dataDA->status , 'name' => $url, 'DomainAuthority' => $dataDA->domainAuthority, 'externalEquityLinks' => $dataDA->externalEquityLinks, 'prettyExternalEquityLinks' => $dataDA->prettyExternalEquityLinks], 'pageAuthority', $dataDA->pageAuthority);
    //     $result[$i] = $arr;
    //     sleep(1); // this should halt for 3 seconds for every loop
    // }   //End Foreach

        $t = microtime( TRUE ) - $t;
        // $result['countStatusSuccessed'] = $countStatusSuccessed;
        // $result['countStatusFailed'] = $countStatusFailed;
        $result['spentTime'] = $t;
        // return $dataDA;
        return $result;
    
}


public function testJavad(){

    $urls = array('yjc.ir', 'iribnews.ir', 'irib.ir', 'irinn.ir', 'tv3.ir', 'tv5.ir');

    foreach ($urls as $i => $value) {

        $dataDA = json_decode(@file_get_contents('https://prod.sureoakdata.com/api/v1/domain-authority-checker?websiteUrl='.$url, false),true);
        if($dataDA === FALSE) {
            // print "Site down"; // or other problem
            // $dataDA = array();
            $dataDA['status'] = '403';
            $dataDA['domainAuthority'] = 'null';
            $dataDA['externalEquityLinks'] = 'null';
            $dataDA['prettyExternalEquityLinks'] = 'null';
            $dataDA['pageAuthority'] = 'null';
            $dataDA = json_decode(json_encode($dataDA));
        } else {
            // $t = microtime( TRUE ) - $t;
            // print "It took $t seconds!";
            $dataDA['status'] = '200';
            // array_push($dataDA['status'], "403");
            // $dataDA = array_push($dataDA, $dataDA['status'] = "403");
            // $dataDA += (string)['status' => "403"];
            $dataDA = json_decode(json_encode($dataDA));
        }

        $arr = Arr::add(['status' => $dataDA->status , 'name' => $value, 'DomainAuthority' => $dataDA->domainAuthority, 'externalEquityLinks' => $dataDA->externalEquityLinks, 'prettyExternalEquityLinks' => $dataDA->prettyExternalEquityLinks], 'pageAuthority', $dataDA->pageAuthority);
        sleep(rand(1,3));
        $result[$i] = $arr;
    }

        return $result;
}

// ================== instagram oreizi
public function instagramOreiziAutomaticly(){

        // return date("Y-m-d");
    $data[] = array();
    $data['resultUpdatedNow'] = null;
    $data['resultUpdatedFaild'] = null;
 
 
  $instagramListsTodayDate = instagrams::whereNull('updated_at')->inRandomOrder()->take(40)->get(['username']);


 foreach ($instagramListsTodayDate as $key => $value) {
     // echo $value->username;
     $result = json_decode($this->get_https_content("https://www.instagram.com/".$value->username."/?__a=1"), true);

     if (empty($result)) {
         
         //array_push($data['resultUpdatedFaild'], @$value->username);
         
         //$data = [];
        //$data['resultUpdatedFaild'] = $value->username;
        
         $data['resultUpdatedFaild'] .= $value->username.'*';

     }else{

         $_userName = $value->username;
         $_full_name = $result['graphql']['user']['full_name'];
         $_edge_followed = $result['graphql']['user']['edge_followed_by']['count'];
         $_biography = $result['graphql']['user']['biography']; 

         $_is_business_account = $result['graphql']['user']['is_business_account']; 
         $_business_category_name = $result['graphql']['user']['business_category_name']; 
         $_is_private = $result['graphql']['user']['is_private'];
         $_is_verified = $result['graphql']['user']['is_verified'];
         $_edge_follow = $result['graphql']['user']['edge_follow']['count'];
         $_profile_pic_url = $result['graphql']['user']['profile_pic_url'];
         $_edge_owner_to_timeline_media = $result['graphql']['user']['edge_owner_to_timeline_media']['count'];


         $instagramTable = instagrams::where('username', '=', $_userName)->first();

         $instagramTable->name =                         $_full_name;
         $instagramTable->username =                     $_userName;
         $instagramTable->follower_count =               $_edge_followed;
         $instagramTable->edge_follow =                  $_edge_follow;
         $instagramTable->biography =                    $_biography;
         $instagramTable->is_business_account =          $_is_business_account;
         $instagramTable->business_category_name =       $_business_category_name;
         $instagramTable->is_private =                   $_is_private;
         $instagramTable->is_verified =                  $_is_verified;
         $instagramTable->profile_pic_url =              $_profile_pic_url;
         $instagramTable->edge_owner_to_timeline_media = $_edge_owner_to_timeline_media;
         $instagramTable->description =                  "";
         $instagramTable->type =                         "";
         $instagramTable->status =                       "10";

         $instagramTable->save();

         $data['resultUpdatedNow'] .= $_userName.'*';
         // array_push($data);
            sleep(rand(1,3));
     }


     
 }

 return ($data);
}


    public function get_($url=NULL,$method="GET"){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/32.0');
        curl_setopt($ch, CURLOPT_URL,$url);
        return curl_exec($ch);
    }

    public function instagramOreizi(){
        $str = "__its.shaqayeq__,
        shaghayegh_syd";
        
        
            $array = explode(",",$str);
        
        echo "<table border=1>";
        
            foreach ($array as $value) {
        
                $value = trim($value);
                $val = json_decode($this->get_("https://www.instagram.com/".$value."/?__a=1"));
        
                echo "<tr>";
        
                if(!count((array)$val)) {
                    // gettype($val);
                    echo "<td>". $value ."</td><td>null</td>";
                    // echo "<td>null</td>" ;
                }else{
                    echo "<td>". $value ."</td><td>". $val->graphql->user->edge_followed_by->count .'</td>';
                    // echo "<td>". $val->graphql->user->edge_followed_by->count .'</td>';
                }
        
                echo "</tr>";
                
                sleep(1);
            }
        
            echo "</table>";
    }


    public function sappInsertShow(){
        return view('admin.sapp-add');
    }

    public function sapp_get_insert(Request $request){

        $arrayList = $request->userName;
        $getResult = array();
        $updated = array();
        $inserted = array();

        // return $arrayList;
        $splitedArray = array_map('trim',array_filter(explode("\n",$arrayList)));
        // return $splitedArray;
        $t = microtime( TRUE );

        foreach($splitedArray as $i => $userName){
            $getResult[] = $this->get_sapp_data($userName);
        }

        // اطلاعات آرایه بالا را میگیرد و در دیتابیس چک و ذخیره یا ائدیت میکند
        foreach ($getResult as $key => $value) {  
            
            // echo $value['username'].'<br>';
            
            $sappTable = new Sapp;

            if (Sapp::where('username', '=', $value['username'])->exists()) {
                // record found ... then updated record
                $rowId = Sapp::where('username', '=',  $value['username'])->first();
                // return $rowId;
                // exit();

                $sappTable = Sapp::find($rowId->id);
                $sappTable->member = $value['member'];
                $sappTable->save();
                // array_push($updated, $value['username']);
                $updated[] = $value['username'];

            }else{

                $sappTable->username = $value['username'];
                $sappTable->member = $value['member'];
                $sappTable->save();
                // array_push($inserted, $value['username']);
                $inserted[] = $value['username'];
            }
               
        }   // End foreach

        $t = microtime( TRUE ) - $t;
        $getResult['spentTime'] = $t;
        $getResult['updated'] = $updated;
        $getResult['inserted'] = $inserted;

        // return $getResult;
        Session::flash('message', " تعداد اطلاعات مورد بررسی :  ". count($splitedArray)." <br>
        رکوردهای <span style=color:#0423b0;> بروزرسانی</span> : ".implode( ", ", $updated )."<br>
        رکوردهای <span style=color:#e50834;> جدید</span> : ".implode( ", ", $inserted )."<br>
        "); 

        Session::flash('alert-class', 'alert-success'); 
        return redirect()->back();

    }

    public function get_sapp_data($id){

        $result = array();
        $dataDA = array();

        // $id = 'tv1.ir';
        // return 'https://sapp.ir/'.$id;
        
    
        //Create a custom stream context that has a HTTP timeout
        //of 3 seconds.
        $streamContext = stream_context_create(
            array('http'=>
                array(
                    'timeout' => 3,  //3 seconds
                )
            )
        );
    
        $t = microtime( TRUE );
    
        $dataDA = (@file_get_contents('https://sapp.ir/'.$id, false, $streamContext));

        //preg_match("'<div class=\"channel\">(.*?)</div>'si", $dataDA, $match);
        preg_match("'<div class=\"channel\">.*?<h4>(.*?)</h4>.*?</div>'si", $dataDA, $match);
        //if($match) echo "result=".$match[1];
    
        // return $match[1];

        if ($match[1]) {
            $result['status'] = '200';
            $result['username'] = $id;
            $result['member'] = $match[1];
        }else{
            $result['status'] = '403';
            $result['username'] = $id;
            $result['member'] = NUll;
        }

        // isset($match[1])? $result[$id] = $match[1] : $result[$id] = NULL;

    
        $t = microtime( TRUE ) - $t;
        $result['spentTime'] = $t;
        return $result;
        
    }


    public function get_domain_authority_moz_multiCurl($arrayList){

        $result = array();
        $node_count = count($arrayList);
        // return  $node_count;

        $curl_arr = array();
        $master = curl_multi_init();
        $t = microtime( TRUE );
        for($i = 0; $i < $node_count; $i++)
        {
            $url = "https://analytics.moz.com/listerine/api/1.4/idina/url_metrics?site=".$arrayList[$i];
            // echo $url.'<br>';
            // $url =$arrayList[$i];
            $curl_arr[$i] = curl_init($url);
            
            // create & initialize a curl session
            $request_headers = array(
                "authority: analytics.moz.com",
                "cache-control: max-age=0",
                "upgrade-insecure-requests: 1",
                "user-agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Mobile Safari/537.36",
                "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
                "sec-fetch-site: none",
                "sec-fetch-mode: navigate",
                "sec-fetch-user: ?1",
                "sec-fetch-dest: document",
                "cookie: __cfduid=dc0efb0a634d8576d3d7e9d5ada4646751607241911; ajs_anonymous_id=^%^22a2c79375-e14c-40c0-bf0f-186e63a16b22^%^22; _gcl_au=1.1.965080288.1607241917; _fbp=fb.1.1607241917265.1820973479; __hssrc=1; hubspotutk=0424de13417f4f90facb2ff8d9b20aec; __stripe_mid=784d7ea6-8e5e-4fd6-b8c0-5c0bffe95e6f9775da; __hstc=103427807.0424de13417f4f90facb2ff8d9b20aec.1607241916866.1607249646087.1607505503443.4; mozauth=XjxvzniQ4u1Tt3OgCquPbRm1CttkzltFtzWGg2PHqbWm3SMQaa6Jy4cGgS9SSiTE; ajs_user_id=16734711; _uetvid=c696b860379911eba4e52f6773029ccd; _ga_LGQZKGRBE5=GS1.1.1607505503.4.1.1607507312.0; _ga=GA1.1.1116164280.1607241917; __cf_bm=ffe35b82a4ed8ad4f62fbca447fa966993a38526-1607835936-1800-AUl57Zb8Dhgy6AdgdBDykh6Aob/YthiqZnET05xzXQSFZZDUwdGbicQAJM++r93XI4iytJNSnh5/5Bg5Ljasl6Y="
            );

            curl_setopt($curl_arr[$i], CURLOPT_URL, $url);
            curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_arr[$i], CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl_arr[$i], CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl_arr[$i], CURLOPT_REFERER, 'ref page');

            curl_setopt($curl_arr[$i], CURLOPT_HTTPHEADER, $request_headers);

            curl_multi_add_handle($master, $curl_arr[$i]);
        }
        
        do {
            curl_multi_exec($master,$running);
        } while($running > 0);
        
        // echo "results: ";
        for($i = 0; $i < $node_count; $i++)
        {
            //$results = curl_multi_getcontent  ($curl_arr[$i]);
            $result[] = curl_multi_getcontent($curl_arr[$i]);
            //echo( $i . "\n" . $results . "\n");
        }
        // echo 'done';
        $t = microtime( TRUE ) - $t;
        $result['spentTime'] = $t;
        return $result;
        // URL from which data will be fetched
    }


    public function get_domain_authority_sureoakdata_multiCurl($arrayList, $website=null){


        $result = array();
        $results = array();
        $sorceUrl = null;
        $node_count = count($arrayList);
        // return  $node_count;

        switch ($website) {

            case 'rubika':
                $sorceUrl = "https://rubika.ir/";
                    // create & initialize a curl session
                    $request_headers = array(
                        "cache-control: max-age=0",
                        "user-agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Mobile Safari/537.36",
                        "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
                    );
            break;

            case 'jamesdearmer':
                $sorceUrl = "https://jamesdearmer.com/tools/domain-authority/result.php/?url=";
                    // create & initialize a curl session
                    $request_headers = array(
                        "cache-control: max-age=0",
                        "user-agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Mobile Safari/537.36",
                        "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
                        "Referer: https://rushax.com/tools/domain-authority-checker/",
                    );
            break;

            case 'sureoakdata':
                $sorceUrl = "https://prod.sureoakdata.com/api/v1/domain-authority-checker?websiteUrl=";
                    // create & initialize a curl session
                    $request_headers = array(
                        "cache-control: max-age=0",
                        "user-agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Mobile Safari/537.36",
                        "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
                    );
            break;

            case 'duplichecker':
                    $sorceUrl = "https://www.duplichecker.com/page-authority-checker/ajax";
                    // create & initialize a curl session
                    $request_headers = array(
                        "Host: www.duplichecker.com",
                        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:83.0) Gecko/20100101 Firefox/83.0",
                        "Accept: application/json, text/javascript, */*; q=0.01",
                        "Accept-Language: en-US,en;q=0.5",
                        "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
                        "X-Requested-With: XMLHttpRequest",
                        "Origin: https://www.duplichecker.com",
                        "Connection: keep-alive",
                        "Cookie: cto_bidid=NtWC5V91bE9NT3NHUG90YUlOVzg4QUlmYmUxV3RMaGJqd1JRS1NIUUF0ekJQUGtoZ0F6UER1WHBJWnZpcnoxcmxlUlBwRUxTWFFadHZNV204MUxKQ2dQJTJGd293JTNEJTNE; cto_bundle=G1aP7V9pblQ1OXlZaFclMkJqVzVqYVBqQVdGWVJqY3dSbnJnVzJLcXl0MG9rOVB4UTVlU0RMOHNTb3JDUjlSUDdDZSUyQlFEY0llUjROUmtTWmRXbFZ4SE9jZUlEaDhORTNhWSUyRkZLOWhLZCUyQk94SUtoZ0lQYVJEaSUyRkJUUmVTTnM5dEQwYjJKY24; __utma=31398698.908567982.1600577581.1600577581.1608444347.2; __utmz=31398698.1600577581.1.1.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided); __gads=ID=a23e2fe731b6f204-228246751ca600ca:T=1600577661:R:S=ALNI_MbmMuZYFmbGUHY7k3-3F27jim5dyg; cto_bundle=pQDunV9UQjJ3QkpGOXdRYjFjSUVtS21EbWxPWXFzNmlYSmNwJTJCT3h5WThjSmtSaTc2U0MyTndzN291V2NFZ2JIVjYwSU9PJTJCWWVJc3JqSTdGWkVIb2Q1OTZQTnVJUFhKVEhiQWRJVDNEWDhoUDhtRXQlMkZGRUFtJTJGNG9WMWlkcVdLanIlMkJhbDklMkI2U0NCWlhCWSUyRmwyMFFveHVzdlBzZyUzRCUzRA; cto_bundle=pQDunV9UQjJ3QkpGOXdRYjFjSUVtS21EbWxPWXFzNmlYSmNwJTJCT3h5WThjSmtSaTc2U0MyTndzN291V2NFZ2JIVjYwSU9PJTJCWWVJc3JqSTdGWkVIb2Q1OTZQTnVJUFhKVEhiQWRJVDNEWDhoUDhtRXQlMkZGRUFtJTJGNG9WMWlkcVdLanIlMkJhbDklMkI2U0NCWlhCWSUyRmwyMFFveHVzdlBzZyUzRCUzRA; __cfduid=d88989e47b3528c2ccd37d69c2fdc31951608444343; XSRF-TOKEN=eyJpdiI6ImFQQlF0Q3RaeHNXT254OWtyUXZ4S2c9PSIsInZhbHVlIjoiRjJiV1l5cUhUWWNkc1lPYUtSTWZVT1l1a1VEM0hJNnBwRnNRN0dwXC93Z2JMRUJaTmE4TVBISFpWcCtza0laTGYiLCJtYWMiOiI5OWNlNTczZTRhNjAwNGM4YjRmMDI3MTQ2Zjg0MDM3OWQ2NjM3NDE3MmY0M2RmNTZlZTQ2M2MzNWY3YjUzZGMzIn0%3D; duplichecker_session=eyJpdiI6IjRRWVhEQU8zUWVYWGlqSW9XSGl3R2c9PSIsInZhbHVlIjoidG5pOHFDUk1pZnFKdEgxdnhwbExtd0J2UXltaFM0U1l2MERKT1NcL2RyUzEza0MrR3ZWUlhOWktaa2d4NTJEWkgiLCJtYWMiOiJlZTMyMDQzMmE5NDMyZTYxMDk5NjAxMDA2OWYyMzczNzYxN2RlMjFmMmVjMDYzNGRlZTZiMjUwNzM2ZDY5NjRkIn0%3D; __utmb=31398698.1.10.1608444347; __utmc=31398698; __utmt=1; _ublock=1",
                    );
            break;
            
            default:
                return  $result[] = 'select destination url sorce fetch';
                break;
        }

        $curl_arr = array();
        $master = curl_multi_init();
        $t = microtime( TRUE );

        for($i = 0; $i < $node_count; $i++)
        {
            $url = $sorceUrl.$arrayList[$i];
            // echo $url.'<br>';
            // $url =$arrayList[$i];
            $curl_arr[$i] = curl_init($url);

            curl_setopt($curl_arr[$i], CURLOPT_URL, $url);
            curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_arr[$i], CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl_arr[$i], CURLOPT_SSL_VERIFYPEER, 0);

            curl_setopt($curl_arr[$i], CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($curl_arr[$i],CURLOPT_CONNECTTIMEOUT,3);

            curl_multi_add_handle($master, $curl_arr[$i]);    
        }
        
        do {
            curl_multi_exec($master,$running);
        } while($running > 0);
        
        // echo "results: ";
        for($i = 0; $i < $node_count; $i++)
        {

            if($socket =@fsockopen($arrayList[$i], 80, $errno, $errstr, 1)) {

                $result_0 = curl_multi_getcontent  ($curl_arr[$i]);
                $jsonResult = json_decode($result_0);
                // echo( $i . "\n". $arrayList[$i]. "\n" .$jsonResult->pageAuthority. "\n");
                $results['url'] = $arrayList[$i];
                $results['DA'] = @$jsonResult->domainAuthority;
                $results['PA'] = @$jsonResult->pageAuthority;
                $result[] = $results;

                //$result = curl_multi_getcontent($curl_arr[$i]);
                //echo( $i . "\n" . $result . "\n");

            }
        }
        // echo 'done';
        $t = microtime( TRUE ) - $t;
        $result['spentTime'] = $t;
        return $result;

        // URL from which data will be fetched
    }



    public function testCurlRaw(){
        $ch = curl_init();
        $headers  = [
                    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:83.0) Gecko/20100101 Firefox/83.0",
                    "Accept: */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
                    "Origin: https://rushax.com",
                    "Connection: keep-alive",
                    "Referer: https://rushax.com/tools/domain-authority-checker/",

                ];
        $postData = [
            'url' => 'android.com',
                ];
        curl_setopt($ch, CURLOPT_URL,"https://jamesdearmer.com/tools/domain-authority/result.php");
        // curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));           
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result     = curl_exec ($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return  $result;
    }

}


