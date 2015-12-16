<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/11/7
 * Time: 14:14
 */

namespace Home\Controller;


use Admin\Api\MemberApi;
use Admin\Model\PictureModel;
use Uclient\Api\UserApi;

class AvatarController extends HomeController{

    protected $Accept_Type = array('avatar','gallery','other');

    public function upload()
    {
        $this->checkLogin();

        if (IS_POST) {

            $uid =  $this->userinfo['uid'];
            $type = "avatar";

            if(!in_array($type,$this->Accept_Type)){
                $this->error("文件类型不支持!",'',true);
            }

            if($uid <= 0){
                $this->error("用户ID非法!",'',true);
            }

            $result = apiCall(UserApi::GET_INFO,array($uid));
            if(!$result['status']){
                $this->error("用户ID不存在!",'',true);
            }
            if(!isset($_FILES['image'])){
                $this->error("文件对象必须为image!",'',true);
            }

            $result['info'] = "";
            //2.再上传到自己的服务器，
            //TODO:也可以上传到QINIU上
            /* 返回标准数据 */

            /* 调用文件上传组件上传文件 */
            $Picture = new PictureModel();

            $extInfo = array('uid' => $uid,'imgurl' => C('SITE_URL'),'type'=>$type);

            $info = $Picture->upload(
                $_FILES,
                C('PICTURE_UPLOAD'),

                C('PICTURE_UPLOAD_DRIVER')
            );

            /* 记录图片信息 */
            if($info){
                $info['image']['imgurl'] = C('SITE_URL').$info['image']['path'];

                if($type == 'avatar'){

                    //保存到用户信息表
                    $result = $this->saveToUserHead($uid,$info['image']['imgurl']);

                    if($result['status']){
                        $this->success($info['image'],'',true);
                    }else{
                        $this->error($result['info'],'',true);
                    }
                }

            } else {
                $this->error($Picture->getError(),'',true);
            }

        }
    }

    /**
     * 保存图片ID到用户信息的头像字段中
     * @param $uid      用户ID
     * @param $img_url
     * @return mixed
     * @internal param 图片ID $pic_id
     */
    public function saveToUserHead($uid,$img_url){

        $result = apiCall(MemberApi::SAVE_BY_ID,array($uid,array('head'=>$img_url)));
        if($result['status']){
            $this->updateAvatar($img_url);
        }
        return $result;
    }

}