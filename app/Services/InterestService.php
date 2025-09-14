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
        $receiver_id = $data['receiver_id'];
        $sender_id = $data['sender_id'];
        $status = $data['status'];
        $message = $data['message'] ?? null;
        
        $interest = Interest::where(['receiver_id' => $receiver_id, 'sender_id' => $sender_id])->first();
        
        if ($interest) {
            // If interest already exists, remove it (toggle functionality)
            $interest->delete();
            $datas['message'] = 'Interest canceled successfully';
            $datas['response'] = [
                'action' => 'removed',
                'interested' => false
            ];
            return $datas;
        } else {
            // Create new interest
            Interest::create([
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'status' => $status,
                'message' => $message
            ]);
            $datas['message'] = 'Interest sent successfully';
            $datas['response'] = [
                'action' => 'added',
                'interested' => true
            ];
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