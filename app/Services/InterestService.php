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
        $message=$data['message'];
        // $Interest = Interest::where(['receiver_id' => $receiver_id, 'sender_id' => $sender_id])->first();
        // if ($Interest) {
        //     if($Interest->status === 1){
        //         $data = Interest::where([
        //             'receiver_id' => $receiver_id,
        //             'sender_id' => $sender_id])
        //             ->update([
        //             'receiver_id' => $receiver_id,
        //             'sender_id' => $sender_id,
        //             'status' => $status,
        //             'message' => $message
        //         ]);
        //         $datas['message']='Not Interested Updated Successfully';
        //         $datas['response']=true;
        //         return $datas;
        //     }elseif($Interest->status == 2){
        //         $data = Interest::where([
        //             'receiver_id' => $receiver_id,
        //             'sender_id' => $sender_id])
        //             ->update([
        //             'receiver_id' => $receiver_id,
        //             'sender_id' => $sender_id,
        //             'status' => $status,
        //             'message' => $message
        //         ]);
        //         $datas['message']='Interested Updated successfully';
        //         $datas['response']=true;
        //         return $datas;
        //     }
        // } else {
            Interest::create([
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'status' => $status,
                'message' => $message,
            ]);
            $datas['message']='Interested stored successfully';
            $datas['response']=true;
            return $datas;
        // }
        
    
     }
    }