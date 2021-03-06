<?php

class CustomerinfoController extends GController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}


	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new CustomerInfo;
		$deptArr = Userinfo::getDept();
		$deptArr = array('0'=>'--请选择部门--') + $deptArr;
		$category = Userinfo::getCategory();//类目
		if(isset($_POST['CustomerInfo']))
		{

			$model->attributes=$_POST['CustomerInfo'];
     		$model->assign_eno = Yii::app()->session['user']['eno'];//分配人
			$model->assign_time = time();//分配时间
			$model->create_time = time();
			$model->creator = Yii::app()->user->id;
			//$model->cust_type = 0;	//客户分类默认为0
			if($model->phone) $this->check_phone_ext($model->phone,0,1);//检查电话号码是否已经存在
			if($model->phone2) $this->check_phone_ext($model->phone2,0,2);
			if($model->phone3) $this->check_phone_ext($model->phone3,0,3);
			if($model->phone4) $this->check_phone_ext($model->phone4,0,4);
			if($model->phone5) $this->check_phone_ext($model->phone5,0,5);
 		    if($model->save()){
				Yii::app()->db->createCommand()->update('{{users}}',array('cust_num' =>new CDbExpression('cust_num+1')),"eno='{$model->eno}'");
				Utils::showMsg (1, '增加成功!');
			}else{
				$errors = $model->getErrors();
				$error = current($errors) ;
					Utils::showMsg (0, $error[0]);
			}
			
            Yii::app()->end();
		}
		
		$this->renderPartial('create',array(
			'model'=>$model,
			'deptArr'=>$deptArr,
			'category'=>$category
		));
	}

	public function actionGetGroup(){
		$deptid = yii::app()->request->getparam('deptid');
		$deptinfo = Userinfo::getGroupById($deptid);
		echo json_encode($deptinfo);
	}

	public function actiongetUsers()
	{
		$gid = Yii::app()->request->getparam('gid');
        $deptid = Yii::app()->request->getparam('deptid');
		$userinfo = Userinfo::getUserbygid($gid, $deptid);
		echo json_encode($userinfo);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id=0)
	{
		
		$id = $id ? $id: $_POST['CustomerInfo']['id'];
		$model=$this->loadModel($id);
		$eno = $model->eno ?$model->eno :0;
		$param['eno'] = (string)$eno;
		$userinfo = Users::model()->findByAttributes($param);
		$user_info['group_id'] = $userinfo?$userinfo->group_id:0;
		$user_info['dept_id']  = $userinfo?$userinfo->dept_id:0;
	    $user_info['name']     = $userinfo?$userinfo->name:0;
		$user_info['eno']     = $userinfo?$userinfo->eno:0;
		$user_info['group_arr'] = Userinfo::getGroupById($user_info['dept_id']);
		$user_info['user_arr'] = Userinfo::getUserbygid($user_info['group_id'],$user_info['dept_id']);	
		if(isset($_POST['CustomerInfo']))
		{
			$model->attributes=$_POST['CustomerInfo'];
			if($model->phone) $this->check_phone_ext($model->phone,$id,1);//检查电话号码是否已经存在
			if($model->phone2) $this->check_phone_ext($model->phone2,$id,2);
			if($model->phone3) $this->check_phone_ext($model->phone3,$id,3);
			if($model->phone4) $this->check_phone_ext($model->phone4,$id,4);
			if($model->phone5) $this->check_phone_ext($model->phone5,$id,5);
			$aNewEno = $model->eno;
			$aOldEno = $model->oldEno;
			if($aNewEno == $aOldEno){//没有修改所属工号
				if($model->save()){
					//exit("<script>alert(\"恭喜你, 数据修改成功。\");javascript:history.go(-2);</script>");
					Utils::showMsg (1, '恭喜你, 修改成功!');
				}
				else
				{
					$errors = $model->getErrors();
					$error = current($errors) ;
					Utils::showMsg (0, $error[0]);
				}
			}
			else{
				$model->assign_eno = Yii::app()->session['user']['eno'];//分配人
				$model->assign_time = time();//分配时间
				$sql = "update {{users}} set cust_num=cust_num+1 where eno='{$aNewEno}'";
				$sql2 = "update {{users}} set cust_num=cust_num-1 where eno='{$aOldEno}'";
				$transaction = Yii::app()->db->beginTransaction();
				try {
					if($model->save()){
						$res = Yii::app()->db->createCommand($sql)->execute();
						$res2 = Yii::app()->db->createCommand($sql2)->execute();
						$transaction->commit();
						//exit("<script>alert(\"恭喜你, 数据修改成功。\");javascript:history.go(-2);</script>");
						Utils::showMsg (1, '恭喜你, 修改成功!');
					}
					else
					{
						throw new CHttpException(400,Yii::t('yii','Your request is invalid.'));
					}
				} catch (Exception $exc) {
					$transaction->rollBack();//事务回滚
					$errors = $model->getErrors();
					$error = current($errors) ;
					Utils::showMsg (0, $error[0]);
					//exit("<script>alert(\"对不起, 本次操作失败, 请重新操作1。\");javascript:history.go(-2);</script>");	
				}	
			}
			Yii::app()->end();
		}
		$category = $this->getCategory();
		$deptArr = Userinfo::getDept();
		$deptArr = array('0'=>'--请选择部门--') + $deptArr;
		$this->renderPartial('update',array(
			'model'=>$model,
			'category'=>$category,
			'deptArr'=>$deptArr,
			'user_info'=>$user_info,
		));
	}

	/**
	 * 检查电话是否存在
	 */
	public function check_phone_ext($_phone,$id,$num){
		$addWhere = '';
		if($id) $addWhere = " and id<>$id";
		$phone = trim($_phone);
		$phone_0 = '0'.$phone;
		$res = CustomerInfo::model()->findAll("(phone in($phone,$phone_0) or phone2 in($phone,$phone_0) or phone3 in($phone,$phone_0) or phone4 in($phone,$phone_0) or phone5 in($phone,$phone_0) ) and `status`<>2 $addWhere");
		if($res){
			$gonghao = $res[0]['eno'];
			$custInfo = Users::model()->findAll('eno=:eno', array(':eno'=>$gonghao));
			$userName = $custInfo[0]['username'];
			if($res[0]['status'] == 0){
				Utils::showMsg (0, '电话'.$num.'已经存在于 '.$userName.' 库中。');
			}
			elseif($res[0]['status'] == 1){
				Utils::showMsg (0, '电话'.$num.'已经存在于 公海 中。');
			}
			
		}
	}
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$sql = "update {{customer_info}} set `status`=2 where id=$id";
		$sql2 = "update {{users}} set cust_num=cust_num-1 where eno='{$model->eno}'";//删除一条记录后对应的所属工号人员已分配减1
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$res = Yii::app()->db->createCommand($sql)->execute();
			$res2 = Yii::app()->db->createCommand($sql2)->execute();
			$transaction->commit();
			exit("<script>alert(\"恭喜你, 删除成功。\");javascript:history.go(-1);</script>");
		} catch (Exception $exc) {
			$transaction->rollBack();//事务回滚
			$errors = $model->getErrors();
			$error = current($errors) ;
			exit("<script>alert(\"对不起, 本次操作失败, 请重新操作。\");javascript:history.go(-2);</script>");	
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		//if(!isset($_GET['ajax']))
			//$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('CustomerInfo');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{	
		$aPageSize = isset($_SESSION['uPageSize']) ? $_SESSION['uPageSize'] : 10;
		$model=new CustomerInfo('search');
		$model->unsetAttributes();  // clear any default values
		if(Yii::app()->request->getParam('customerId')){
			$model->customerId = Yii::app()->request->getParam('customerId');
			//Yii::app()->db->createCommand()->delete('{{tip_info}}',"id in( {$model->customerId} )"); 
		}
		
		if(!isset($_GET['CustomerInfo_sort'])){//默认排序
			$_GET['CustomerInfo_sort'] = 'assign_time.desc';
		}
		
		if(isset($_GET['CustomerInfo']))
			$model->attributes=$_GET['CustomerInfo'];
		
		//部门组别人员三级联动
		$uInfo = Userinfo::secondlevel();
		//是否有删除权限
		$userid = Yii::app()->session['user']['id'];
		$isdel = Users::model()->findByPk($userid);
		$isdel = $isdel->isdel;
		
		$this->render('admin',array(
			'model'=>$model,	
			'deptArr'=>$uInfo['deptArr'],
			'groupArr'=>$uInfo['groupArr'],
			'infoArr'=>$uInfo['infoArr'],
			'user_info'=>$uInfo['user_info'],
			'aPageSize' => $aPageSize,
			'isdel' => $isdel,
		));
	}
    
	public function actionBatchCustomer(){
		$model = new CustomerInfo;
		if ($_FILES) {
	        $file = $_FILES['batchFile']['tmp_name'];
	        $fileArr = UploadExcel::upExcel($file);
	        $creator = Yii::app()->user->id;
			
			$assign_eno = Yii::app()->session['user']['eno'];//分配人
			$assign_time = time();
	        $create_time = time();
			$userArr = array();
 			$allUser = Users::model()->findAll( array(
				'select'=>array('eno','username'),
				'condition' => 'status=:status',
				'params' => array(':status'=>'1')
			));
			foreach ($allUser as $key => $value) {
				$userArr[$value['username']] = $value['eno'];
			}

	        if ($fileArr) {
				if(count($fileArr) > 600){
					exit("<script>alert(\"对不起, 为防止一次提交的数据太多消耗大量的服务器资源而影响到其他用户的使用, 一次提交的数据不能超过600条, 请修改后重新提交。\");
	        				javascript:history.go(-1);</script>");
				}
				$max_id = Customerinfo::model()->findAll(array('order'=>'id DESC','limit'=>1,));
				$maxid = $max_id[0]['id'] + 10;
	        	$sql = "insert into {{customer_info}} (id,cust_name,phone,qq,cust_type,memo,creator,create_time,eno,assign_eno,assign_time) values";
	        	$sql3 = "insert into {{note_info_p}} (note_type,cust_id,userid,create_time,memo) value ";
				$usql = '';
				$eno = '';
				$repetaInfo = '';
				$i=0;
				$names=$ph=$QQ=$ctype=$mome='';
				foreach ((array)$fileArr as $k => $v) {
	        		if (!$v[1] && !$v[2]) {
	        			exit("<script>alert(\"对不起, 第".$k."行中的电话和QQ二选一必填, 请填写后重新提交。\");
	        				javascript:history.go(-1);</script>");
	        		}
					elseif (!$v[5] || !array_key_exists($v[5], $userArr)) {
	        			exit("<script>alert(\"对不起, 第".$k."行中的 所属人员 不能为空或不存在, 请填写后重新提交。\");
	        				javascript:history.go(-1);</script>");
	        		}
	        		else{
						if(empty($v[0])){
							$v[0] = date('Y-m-d', $create_time);
						}
						if ($v[1]){
							$phone = trim($v[1]);
							$phone_0 = '0'.$phone;
							//$phoneSQL = "select cust_name from c_customer_info where (phone in($phone,$phone_0) or phone2 in($phone,$phone_0) or phone3 in($phone,$phone_0) or phone4 in($phone,$phone_0) or phone5 in($phone,$phone_0) ) and status<>2";
							$ret = CustomerInfo::model()->findAll("(phone in($phone,$phone_0) or phone2 in($phone,$phone_0) or phone3 in($phone,$phone_0) or phone4 in($phone,$phone_0) or phone5 in($phone,$phone_0) ) and `status`<>2");
							//$ret = Yii::app()->db->createCommand($phoneSQL)->execute();
							if($ret || !is_numeric($phone)){
								$repetaInfo .= $k.', ';
								continue;
							}
						}
						$ctype = trim($v[3]);//客户类别
						if(empty($ctype)){
							$ctype = 0;
						}
						else{
							if(!in_array($ctype, array('0','1','2','3','4','5','6','7','8','9','-1','-9'),true)){
								exit("<script>alert(\"对不起, 第".$k."行中的 类别 只能填0--9或-1,-9的数字, 请修正后重新提交。\");
									javascript:history.go(-1);</script>");
							}	
						}
						$memo = $v[1] ? "$v[0]".':电话'."$v[1]" : "$v[0]".':QQ'."$v[2]";
						$eno = $userArr[$v[5]];
						$phones = trim($v[1]);
						$names = trim($v[0]);
						$ph = trim($phones);
						$QQ = trim($v[2]);
						$ctype = trim($ctype);
						$mome = trim($v[4]);
	        			$sql .= "($maxid,'$names','$ph','$QQ','$ctype','$mome', $creator, $create_time,'$eno','$assign_eno',$assign_time),";
						$sql3 .= "(1,$maxid,$creator,$assign_time,'$memo'),";
						$usql .= "update {{users}} set cust_num=cust_num+1 where eno='$eno';";	
						$i++;
						$maxid++;
					}	
	        	}
				if(!$i){
					exit("<script>alert(\"表格中的数据已存在，请勿重复导入（电话号码已存在）。\");javascript:history.go(-1);</script>");
				}
				$sql = trim($sql, ',');
				$sql3 = trim($sql3,',');
				///////////////////开启事务///////////////////
				$transaction = Yii::app()->db->beginTransaction();
				try {
					$num = Yii::app()->db->createCommand($sql)->execute();
					Yii::app()->db->createCommand($sql3)->execute();
					$res2 = Yii::app()->db->createCommand($usql)->execute();
					$tip_info = CustomerInfo::model()->findAll( array('select'=>array('id','eno'),
																		'condition' => 'create_time=:create_time', 
																		'params' => array(':create_time'=>$create_time)
																	)
																);
					$tipSql = "insert into {{tip_info}} value ";
					$sqlstr = '';
					foreach($tip_info as $k2=>$v2){
						$sqlstr .= '('."$v2[id]".','."'$v2[eno]'".')'.',';
					}
					$sqlstr = trim($sqlstr, ',');
					$tsql = $tipSql.$sqlstr;
					Yii::app()->db->createCommand($tsql)->execute();/////新分资源弹窗提示用户
					$transaction->commit();
					exit("<script>alert(\"恭喜你, 成功导入".$num."条数据。未导入的数据行号：".$repetaInfo."\");javascript:history.go(-1);</script>");	
				} catch (Exception $exc) {
					$transaction->rollBack();//事务回滚
					exit("<script>alert(\"对不起, 由于未知的错误, 本次操作失败, 请重新操作。\");javascript:history.go(-1);</script>");
				}
				
	        }
		}
		$this->renderPartial('batchCustomer', array('model'=>$model));
	}
        public function actionContact(){
            $model = new DialDetail('search');
            $model->unsetAttributes();
            //部门组别人员三级联动
	    $uInfo = Userinfo::secondlevel();
            if(isset($_GET['DialDetail'])){
                $model->attributes= $_GET['DialDetail'];
                $model->searchtype= $_GET['DialDetail']['searchtype'];
                $model->keyword= $_GET['DialDetail']['keyword'];
                $model->timetype = $_GET['DialDetail']['timetype'];
                $model->stime = $_GET['DialDetail']['stime'];
                $model->etime = $_GET['DialDetail']['etime'];
                $model->dept=$_GET['search']['dept'];
                $model->group=$_GET['search']['group'];
            }
            $this->render("contact",
                    array(
                        'model'=>$model,
                        'deptArr'=>$uInfo['deptArr'],
			'groupArr'=>$uInfo['groupArr'],
                        'user_info'=>$uInfo['user_info'],
                        'infoArr'=>$uInfo['infoArr'],
                    ));
        }
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CustomerInfo the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CustomerInfo::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CustomerInfo $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='customer-info-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function  get_eno_text($data)
	{
		$val = $data->eno;
		$enoArr = $this->getEnoArr($val);
		$res = isset($enoArr[$val])? $enoArr[$val]:$val;
		return $res;
	}

	public function getEnoArr($eno){
		return CHtml::listData(Users::model()->findAll('eno=:eno', array(':eno'=>$eno)), 'eno', 'name');
	}
	
	public function get_assign_text($data){
		$val = $data->assign_eno;
		$assignArr = $this->getAssignArr($val);
		$res = isset($assignArr[$val]) ? $assignArr[$val] : $val;
		return $res;
	}
	
	public function getAssignArr($assign){
		return Chtml::listData(Users::model()->findAll('eno=:eno', array(':eno'=>$assign)), 'eno', 'name');
	}
	
	public function get_category_text($data){
		$val = $data->category;
		$categoryArr = $this->getCategory();
		$res = isset($categoryArr[$val]) ? $categoryArr[$val] : $val;
		return $res ? $res : '未分类';
	}
	
	public function getCategory(){
		return Userinfo::getCategory();
	}
	
	public function get_assign_time($data){
		return $data->assign_time ? date("Y-m-d H:i:s",$data->assign_time) : '未分配';
	}
	public function get_next_time($data){
		return $data->next_time ? date("Y-m-d H:i:s",$data->next_time) : '未安排';
	}
	/**
	 *批量导入EXCEL模板文件下载
	 */
	public function actionGetTemplate(){
		/////header("Content-type:text/html;charset=utf-8"); 
		$file_name="customerInfo.xlsx"; 
		//用以解决中文不能显示出来的问题 
		/////$file_name=iconv("utf-8","gb2312",$file_name); 
		$file_sub_path=$_SERVER['DOCUMENT_ROOT']."/document/"; 
		$file_path=$file_sub_path.$file_name; 
		//首先要判断给定的文件存在与否 
		if(!file_exists($file_path)){ 
			echo "没有该文件文件"; 
			return ; 
		} 
		/////$fp=fopen($file_path,"r"); 
		/////$file_size=filesize($file_path); 
		//下载文件需要用到的头 
		Header("Content-type: application/octet-stream"); 
		Header("Accept-Ranges: bytes"); 
		/////Header("Accept-Length:".$file_size); 
		/////Header("Content-Disposition: attachment; filename=".$file_name); 
		/////$buffer=1024; 
		/////$file_count=0; 
		//向浏览器返回数据 
		/*while(!feof($fp) && $file_count<$file_size){ 
			$file_con=fread($fp,$buffer); 
			$file_count+=$buffer; 
			echo $file_con; 
		} 
		fclose($fp); */
		header("Content-Disposition: attachment; filename=".basename($file_path));
		readfile($file_path);
	}
	
	public function actionDelTipInfo(){
		$custId = Yii::app()->request->getParam('custId');
		Yii::app()->db->createCommand()->delete('{{tip_info}}',"id in( $custId )"); 
	}
	
	public function actionBatchDel(){
		$ids = Yii::app()->request->getParam('ids');
		$enoArr =  CustomerInfo::model()->findAll("id in($ids)");
		$upsql = '';
		foreach ($enoArr as $key => $value) {
			$upsql .= "update {{users}} set cust_num=cust_num-1 where eno='$value[eno]';";
		}
		$sql = "update {{customer_info}} set `status`=2 where id in($ids)";
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$res = Yii::app()->db->createCommand($sql)->execute();
			$res2 = Yii::app()->db->createCommand($upsql)->execute();
			$transaction->commit();
			echo 1;
		} catch (Exception $exc) {
			$transaction->rollBack();//事务回滚
			echo 0;
		}
	}
    public function get_diallong($data) {
        $val = $data->dial_long;
        /*
        if ($val == 0&&!empty($data->uid)&&$data->isok==1) {
            $dt = date("Y_n_j", $data->dial_time);
            $table = "cdro_" . $dt;
            $calldate = date("Y-m-d H", $data->dial_time);
            $sql = "select uniqueid,billsec,userfield from $table where src=:ext and dst=:phone and DATE_FORMAT(calldate,'%Y-%m-%d %H')=:calldate order by calldate desc limit 1";
            $result = Yii::app()->db3->createCommand($sql)->queryRow(true, array(":ext" => $data->extend_no, ":phone" => $data->phone, ":calldate" => $calldate));
            if (!empty($result) && is_array($result)) {
                $val = $result['billsec'];
                $sql = "update {{dial_detail}} set dial_long=:dial_long,record_path=:path,uid=:uid where id=:id";
                Yii::app()->db->createCommand($sql)->execute(array(":dial_long" => $result['billsec'], ":path" => $result['userfield'],":uid"=>$result['uniqueid'], ":id" => $data->id));
            }
        }*/ 
        return $val;
    }
	
	public function actionUpdatenum(){
		$userArr = array();
		$u_info = Users::model()->findAll();
		foreach ($u_info as $v) {
			$userArr[$v->eno] = $v->cust_num;
		}
		foreach ($userArr as $k1 => $v1) {
			$cust_num = CustomerInfo::model()->findAllBySql("select count(*) as id from c_customer_info where eno='$k1' and `status`=0 and cust_type<>30");
			$num = $cust_num[0]['id'];
			if($v1 != $num){
				Users::model()->updateAll(array('cust_num'=>$num),'eno=:eno',array(':eno'=>"$k1"));
			}
		}	
		echo '更新成功';
	}
	
	/**
	 * 用户选择了第页显示多少条后会触发AJAX请求此方法
	 */
	public function actionPageSizeShow(){
		$pageSizeId = Yii::app()->request->getParam('pageSizeId');
		$sourceUrl = Yii::app()->request->getParam('sourceUrl');
		switch ($pageSizeId) {
			case 50:
				$_SESSION['uPageSize'] = 50;
				break;
			case 100:
				$_SESSION['uPageSize'] = 100;
				break;
			case 200:
				$_SESSION['uPageSize'] = 200;
				break;
			default:
				$_SESSION['uPageSize'] = 10;
				break;
		}
		//echo "<script>window.location.href=".$sourceUrl."</script>";
		echo 1;
	}
}
