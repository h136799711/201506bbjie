<?php
namespace Admin\Api;
use Admin\Model\InformationRecordsModel;
use Common\Api\Api;


/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/9/9
 * Time: 10:11
 */
class InformationRecordsApi extends Api{

    const ADD="Admin/InformationRecords/add";

    const QUERY="Admin/InformationRecords/query";

    const GET_INFO="Admin/InformationRecords/getInfo";

    const DELETE="Admin/InformationRecords/delete";

    const SAVE_BY_ID="Admin/InformationRecords/saveById";

    const QUERY_WITH_NAME="Admin/InformationRecords/queryWithName";


    protected function _init(){
       $this->model=new InformationRecordsModel();
    }

    /**
     * 站内信专用
     * $type 1 发信 2 收信
     */
    public function queryWithName($map = null,$order = false,$type=1){
        $page = array('curpage'=>0,'size'=>10);

        $params = false;
        $fields = false;
        $query = $this->model;
        if(!is_null($map)){
            $query = $query->where($map);
        }
        if(!($order === false)){
            $query = $query->order($order);
        }
        if(!($fields === false)){
            $query = $query->field($fields);
        }
        if($type==1){
            $join='common_member b ON a.receiver = b.uid';
        }else{
            $join='common_member b ON a.uid = b.uid';
        }




        $list = $query -> page($page['curpage'] . ',' . $page['size'])->alias('a')->join($join) -> select();


        if ($list === false) {
            $error = $this -> model -> getDbError();
            return $this -> apiReturnErr($error);
        }

        $count = $this -> model -> where($map) -> count();
        // 查询满足要求的总记录数
        $Page = new \Think\Page($count, $page['size']);

        //分页跳转的时候保证查询条件
        if ($params !== false) {
            foreach ($params as $key => $val) {
                $Page -> parameter[$key] = urlencode($val);
            }
        }

        // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page -> show();

        return $this -> apiReturnSuc(array("show" => $show, "list" => $list));
    }



}