<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/5/31
 * Time: 15:37
 */

namespace Money\Logic;


use Home\Api\BbjmemberSellerApi;

class WalletLogic {



    /**
     * 从余额转移金额到冻结资金,
     * @param $seller_uid
     * @param int $added_money 必须大于0
     * @return array|mixed
     */
    public function addFrozenFromCoins($seller_uid,$added_money=0){

        $result = apiCall(BbjmemberSellerApi::GET_INFO,array(array('uid'=>$seller_uid)));

        if(!$result['status']){
            return array('status'=>false,'info'=>$result['info']);
        }

        if($added_money > 0){
            $old_coins = $result['info']['coins'];
            $old_frozen_money = $result['info']['frozen_money'];

            $new_coins = $old_coins - $added_money;
            $new_frozen_money = $old_frozen_money + $added_money;

            //检测余额是否足够
            if($new_coins < 0){
                return array('status'=>false,'info'=>'余额不足');
            }

            $entity = array(
                'coins'=>$new_coins,
                'frozen_money'=>$new_frozen_money,
            );

            $result = apiCall(BbjmemberSellerApi::SAVE_BY_ID,array($seller_uid,$entity));

            return $result;

        }else{
            return array('status'=>false,'info'=>'冻结资金不能为负');
        }

    }
}