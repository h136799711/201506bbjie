<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/6/1
 * Time: 16:16
 */

namespace Money\Logic;


use Admin\Api\MessageApi;
use Admin\Api\MsgboxApi;
use Admin\Model\MsgboxModel;

class MessageLogic {

    /**
     *  系统的uid
     */
    const SYSTEM_UID = 0;


    /**
     * 发送消息
     * @param $from_uid | 从谁
     * @param $to_uid | 发给谁
     * @param $type  | 消息类型
     * @param $title | 消息标题
     * @param $content | 消息内容
     * @param $summary  |　消息摘要
     * @param int $send_time | 发送时间
     * @return mixed
     */
    public function sendMsg($from_uid,$to_uid,$type,$title,$content,$summary,$send_time=0){
        $entity = array(
            'dtree_type'=>$type,
            'content'=>$content,
            'title'=>$title,
            'create_time'=>time(),
            'send_time'=>$send_time,
            'from_id'=>$from_uid,
            'summary'=>$summary,
            'status'=>1,
            'extra'=>'',
        );

        $result = apiCall(MessageApi::ADD, array($entity));
        if($result['status']){

            $msg_id = $result['info'];
            $msg = array('to_id'=>$to_uid,'msg_status'=>MsgboxModel::NOT_READ,'msg_id'=>$msg_id);

            $result = apiCall(MsgboxApi::ADD, array($msg));

        }
        return $result;

    }
}