<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/3/8
 * Time: 09:15
 */

namespace Home\Model;


use Think\Model;

/**
 * 可做的任务列表,带增长的ID字段
 * Class VCanDoTaskModel
 * @package Home\Model
 */
class VCanDoTaskWithAutoIdModel extends Model {

    protected $trueTableName = "v_can_do_task_with_auto_id";

}