<?php

/**
 * Description of Utils
 *
 * @author Administrator
 */
class Utils {

    /**
     * 返回已$key值作为键的数组
     * @param type $array
     * @param type $key
     * @return type
     */
    public static function indexArray($array, $key) {
        $result = array();
        foreach ($array as $element) {
            $result[$element [$key]] = $element;
        }
        return $result;
    }

    /**
     * 返回已$from值做为键 $to做为值的一维关联数组
     * @param type $array
     * @param type $from
     * @param type $to
     * @return type
     */
    public static function mapArray($array, $from, $to) {
        $result = array();
        foreach ($array AS $element) {
            $result[$element[$from]] = $element[$to];
        }
        return $result;
    }
    /**
     * 隐藏电话号码中间4位
     * @param type $phone
     */
    public static function hidePhone($phone){
        return substr_replace($phone,'****',3,4); 
    }
    /**
     * 隐藏邮件地址中间3位
     * @param type $email
     */
    public static function hideEmail($email){
        return substr_replace($email,'****',3,3); 
    }
    /**
     * 隐藏QQ中间3位
     * @param type $qq 
     */
    public static function hideQq($qq){
        return substr_replace($qq,'****',3,3); 
    }
    
     /**
     * 向页面输出信息
     * @param int $code       错误代码, 0. 失败 1.成功， 默认为1
     * @param string $msg    信息内容， 默认为空
     * @param array $data    是否附带额外数据
     * @param boolean $return  是否返回信息内容，默认为true
     * @param boolean $exit  是否停止执行，默认为true
     */
    public static function showMsg($code = 1, $msg = '', $data = array(), $return = false, $exit = true) {
        $out = array('code' => $code, 'msg' => $msg);
        if (is_array($data) && count($data))
            $out['data'] = $data;
        if ($return)
            return $out;
        else
            echo json_encode($out);

        $exit && exit();
    }
    
    /**
     * 发送短信
     * @param type $phone 电话号码
     * @param type $msg 短信内容
     * @param type $method get/post
     * @return type 发送结果描述
     */
    public static function sendMessage($phone,$msg,$method='get'){  
        $msg = urlencode($msg); 
        $sms = Yii::app()->params['SMS'];
        $result = "";  
        switch ($method){
            case 'get':
                $sUrl = $sms['url']."?expid=0&uid=".$sms['uid']."&auth=".$sms['auth']."&encode=".$sms['encode']."&mobile=".$phone."&msg=".$msg;
                $result = file_get_contents($sUrl);
                break;
            case 'post':
                $ch = curl_init();
                $timeout = 5; 
                $postdata = "expid=0&uid=".$sms['uid']."&auth=".$sms['auth']."&encode=".$sms['encode']."&mobile=".$phone."&msg=".$msg;
                curl_setopt($ch, CURLOPT_URL, $sms['url']);
                curl_setopt($ch, CURLOPT_POST, 1);
                $this_header = array("content-type: application/x-www-form-urlencoded;charset=UTF-8");
                curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); // Post提交的数据包,好像不起作用,need to do  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $result = curl_exec($ch);
                curl_close($ch);
                break;
        }  
        $arr = explode(",",$result,2);
        $iReturnCode = abs($arr[0]);
        if($iReturnCode==0){
            $columns=array("cust_id"=>Yii::app()->request->getParam('cust_id'),
                       "phone"=>$phone,
                       "content"=>  urldecode($msg),
                       "creator"=>Yii::app()->user->id,
                       "create_time"=>time());
            Yii::app()->db->createCommand()->insert("{{message}}", $columns);
        }   
        $ret = Yii::app()->params['SMS_RETURN_CODE'][$iReturnCode];
        return $ret;
    }
    
    /**
     * 把yii ar findall 返回的数据对像转换成数组  
     * @param type $obj
     */
    public static  function objtoarray($obj)
    {
        $res = array();
        if(is_array($obj) && !empty($obj))
        {
            foreach ($obj as $key => $value) {
              $res[] =   $value->attributes;
            }
        }
        return $res;
    }
    
   /**
    * yii 批量插入数据的方法
    */
  public  static function insertSeveral($table, $array_columns)
{
    $sql = '';
    $params = array();
    $i = 0;
    foreach ($array_columns as $columns) {
        $names = array();
        $placeholders = array_values($columns);
        $names = array_keys($columns);  
        if (!$i) {
            $sql = 'INSERT INTO ' . Yii::app()->db->tablePrefix.$table
                . ' (' . implode(', ', $names) . ') VALUES ('
                . implode(', ', $placeholders) . ')';
        } else {
            $sql .= ',(' . implode(', ', $placeholders) . ')';
        }
        $i++;
    }
    return Yii::app()->db->createCommand($sql)->execute();
}

            
}
