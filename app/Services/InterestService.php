<?php

namespace App\Services;

use App\Models\Interest;

class InterestService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function storeIntrest($data)
    {
        $receiver_id=$data['receiver_id'];
        $sender_id=$data['sender_id'];
        $status=$data['status'];
        $Interest = Interest::where(['receiver_id' => $receiver_id, 'sender_id' => $sender_id])->first();
        // print_r($receiver_id);die;
        if ($Interest) {
                $data = Interest::where([
                    'receiver_id' => $receiver_id,
                    'sender_id' => $sender_id])
                    ->delete();
                $datas['message']='Interest Canceled Successfully';
                $datas['response']=true;
                return $datas;
        } else {
            Interest::create($data);
            $datas['message']='Interest sended successfully';
            $datas['response']=true;
            return $datas;
        }
     }
     public function acceptedIntrest($data){
        $receiver_id=$data['receiver_id'];
        $sender_id=$data['sender_id'];
        $status=$data['status'];
        $Interest = Interest::where(['receiver_id' => $sender_id, 'sender_id' => $receiver_id])->first();
        if ($Interest) {
                $data = Interest::where([
                    'receiver_id' => $sender_id,
                    'sender_id' => $receiver_id])
                    ->update([
                        'status'=>$status,
                    ]);
                    if($data){
                        $datas['message']='Interest Accepted Successfully';
                        $datas['response']=true;  
                    }else{
                        $datas['message']='Something Went wrong';
                        $datas['response']=false;
                    }
                
                return $datas;
        } else {
            $datas['message']='No Data Found';
            $datas['response']=false;
            return $datas;
        }
     }
     public function declinedIntrest($data){
        $receiver_id=$data['receiver_id'];
        $sender_id=$data['sender_id'];
        $status=$data['status'];
        $Interest = Interest::where(['receiver_id' => $sender_id, 'sender_id' => $receiver_id])->first();
        if ($Interest) {
                $data = Interest::where([
                    'receiver_id' => $sender_id,
                    'sender_id' => $receiver_id])
                    ->update([
                        'status'=>$status,
                    ]);
                    if($data){
                        $datas['message']='Interest Declined Successfully';
                        $datas['response']=true;  
                    }else{
                        $datas['message']='Something Went wrong';
                        $datas['response']=false;
                    }
                return $datas;
        } else {
            $datas['message']='No Data Found';
            $datas['response']=false;
            return $datas;
        }
     }
     public function repliedIntrest($data){
        $receiver_id=$data['receiver_id'];
        $sender_id=$data['sender_id'];
        $status=$data['status'];
        $message=$data['message'];
        $Interest = Interest::where(['receiver_id' => $sender_id, 'sender_id' => $receiver_id])->first();
        if ($Interest) {
                $data = Interest::where([
                    'receiver_id' => $sender_id,
                    'sender_id' => $receiver_id])
                    ->update([
                        'status'=>$status,
                        'message'=>$message
                    ]);
                    if($data){
                        $datas['message']='Interest Replied Successfully';
                        $datas['response']=true;  
                    }else{
                        $datas['message']='Something Went wrong';
                        $datas['response']=false;
                    }
                return $datas;
        } else {
            $datas['message']='No Data Found';
            $datas['response']=false;
            return $datas;
        }
     }
    }