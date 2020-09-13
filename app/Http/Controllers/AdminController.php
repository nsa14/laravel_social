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
        
        
        

        $instagramListsTodayDate = instagrams::whereDate('updated_at', '<', date("Y-m-d"))->get();

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

            if (domains::where('url', '=', $host[0])->exists()) {
                // domain found ... then updated record
                $rowId = domains::where('url', '=',  $host[0])->first();
                // return $rowId;
                // exit();

                // $domainTable = new likee;

                $domainTable = domains::find($rowId->id);

                $domainTable->globalrank = $value['rankglobal'][0];
                $domainTable->localrank = $value['ranklocal'][0];
                $domainTable->redirect_to = $value['location'];
                $domainTable->status_code = $value['statuscode'];
                $domainTable->title =  $value['metaDescriptionUrl'];
                $domainTable->expertion_date = $value['expireDate'];

                $domainTable->save();
                array_push($updatedDomain, $value['name']);

            }else{

                // $host = explode('.', $value['name']);

                $domainTable->url = $host[0];
                $domainTable->dot = $host[1];
                $domainTable->globalrank = $value['rankglobal'][0];
                $domainTable->localrank = $value['ranklocal'][0];
                $domainTable->title =  $value['metaDescriptionUrl'];
                // $domainTable->howis = 
                $domainTable->expertion_date = $value['expireDate'];
                // $domainTable->redirect = 
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

                        $headerCode = @$arr0['HTT'];
                        $locarion_filter = str_replace(array('http://','https://','www.', '/'), '', @$arr0['Loc']);
                        if ($url == $locarion_filter) {
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


                    $arr = Arr::add(['name' => $url, 'ranklocal' => $ranklocal, 'rankglobal' => $rankglobal, 'location' => $location, 'metaDescriptionUrl' => $metaDescriptionUrl, 'expireDate' => $expireDate], 'statuscode', $headerCode);
                    $result[$i] = $arr;
                }
  
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
        $domainTableTodayDate = domains::where('updated_at', '<', date("Y-m-d"))->get();
        // $instagramLists = instagrams::whereDate('updated_at', '<', date("Y-m-d"))->get();


        return $domainTableTodayDate;

        // $arrayList = $request->userName;
        // $updatedDomain = array();
        // $insertedDomain = array();
        // // yjc.ir
        // // iribcs.ir
        // $splitedArray = array_map('trim',array_filter(explode("\n",$arrayList)));

        // if (count($splitedArray) > 20) {
        //     Session::flash('message', " تعداد اطلاعات مورد بررسی :  ". count($splitedArray)." <br>
        //     علت خطا : تعداد بیشتر از حد مجاز دامنه برای واکشی<br>"
        //     );
        //     Session::flash('alert-class', 'alert-danger'); 
        //     return redirect()->back();
        // }

        // $getResult = $this->get_alexa_checkRank($splitedArray);

        // // return $getResult;

        // //[{"name":"naser-zare.ir","ranklocal":null,"rankglobal":null,
        // //"location":"http:\/\/naser-zare.ir\/cgi-sys\/suspendedpage.cgi","statuscode":"HTTP\/1.1 302 Found"}]

        // foreach ($getResult as $key => $value) {       

        //     // dd($value);            

        //     // echo $value['name'].$value['ranklocal'][0].'<br>';

        //     $domainTable = new domains;

        //     $host = explode('.', $value['name']); // explade yjc . ir

        //     if (domains::where('url', '=', $host[0])->exists()) {
        //         // domain found ... then updated record
        //         $rowId = domains::where('url', '=',  $host[0])->first();
        //         // return $rowId;
        //         // exit();

        //         // $domainTable = new likee;

        //         $domainTable = domains::find($rowId->id);

        //         $domainTable->globalrank = $value['rankglobal'][0];
        //         $domainTable->localrank = $value['ranklocal'][0];
        //         $domainTable->redirect_to = $value['location'];
        //         $domainTable->status_code = $value['statuscode'];
        //         $domainTable->title =  $value['metaDescriptionUrl'];
        //         $domainTable->expertion_date = $value['expireDate'];

        //         $domainTable->save();
        //         array_push($updatedDomain, $value['name']);

        //     }else{

        //         // $host = explode('.', $value['name']);

        //         $domainTable->url = $host[0];
        //         $domainTable->dot = $host[1];
        //         $domainTable->globalrank = $value['rankglobal'][0];
        //         $domainTable->localrank = $value['ranklocal'][0];
        //         $domainTable->title =  $value['metaDescriptionUrl'];
        //         // $domainTable->howis = 
        //         $domainTable->expertion_date = $value['expireDate'];
        //         // $domainTable->redirect = 
        //         $domainTable->redirect_to = $value['location'];
        //         $domainTable->status_code = $value['statuscode'];
        //         // $domainTable->description = 

        //         $domainTable->save();
                
        //         array_push($insertedDomain, $value['name']);
                
        //     }           
        // }


        // Session::flash('message', " تعداد اطلاعات مورد بررسی :  ". count($getResult)." <br>
        // رکوردهای بروزرسانی : ".implode( ", ", $updatedDomain )."<br>
        // رکوردهای جدید : ".implode( ", ", $insertedDomain )."<br>
        // "); 

        // Session::flash('alert-class', 'alert-success'); 
        // return redirect()->back();       
   }


}
