	<?php
	class DbOperations{
		private $con;

		function __construct(){
			require_once dirname(__FILE__).'/DbConnect.php';
			$db = new DbConnect();
			$this->con = $db->connect();
		}

		public function userLogin($username,$pass){
			$password=md5($pass);
			$stmt=$this->con->prepare("SELECT userId FROM tbl_users WHERE userName = ? AND password=?");
			$stmt->bind_param("ss",$username,$password);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows>0;
		}
		public function getUserByUsername($username){
			$stmt=$this->con->prepare("SELECT * FROM tbl_users WHERE userName=?");
			$stmt->bind_param("s",$username);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}
		
		private function getData($search_quarry){
			$sql="SELECT * FROM (select issref,cmpdname,cmpdrefno,'0' as isexternal,(dr.currrec - ifnull(mq.receiptqty,0)) as currrec,di.cmpdid,dr.sno, dr.defrecdate, DATE_FORMAT(dr.defrecdate,'%d-%b-%Y') as defrecdatef
			from tbl_deflash_reciept dr
			inner join tbl_deflash_issue di on dr.defissref = di.sno
			inner join tbl_component tc on di.cmpdid=tc.cmpdId
			left join ( select mdlrref, sum(receiptqty) as receiptqty from (select mdlrref, receiptqty from tbl_moulding_quality where status > 0 and isExternal = 0 group by qualityref)tmq group by mdlrref) mq on mq.mdlrref = dr.sno
			where dr.status = 1 and (dr.currrec - ifnull(mq.receiptqty,0)) > 0  
			UNION ALL
			select cr.planId as issref,cmpdname,cmpdrefno,'1' as isexternal,(cr.recvqty - ifnull(mq.receiptqty,0)) as currrec,cr.cmpdid,cr.sno, cr.invdate as defrecdate, DATE_FORMAT(cr.invdate,'%d-%b-%Y') as defrecdatef
			from tbl_component_recv cr
			inner join tbl_component tc on cr.cmpdid=tc.cmpdId
			left join ( select mdlrref, sum(receiptqty) as receiptqty from (select mdlrref, receiptqty from tbl_moulding_quality where status > 0 and isExternal = 1 group by qualityref)tmq group by mdlrref) mq on mq.mdlrref = cr.sno
			where cr.status = 1 and (cr.recvqty - ifnull(mq.receiptqty,0)) > 0							
			order by defrecdate desc,issref)AS qurry_result WHERE issref='$search_quarry'";

			return $this->con->query($sql);

		}

		public function dtaReturn($search_quarry){

			$response_cmpdid=array();
			$user=$this->getData($search_quarry);
			$user=$user->fetch_assoc();
			$response_cmpdid['cmpdId']=$user['cmpdid'];
			$response=array();

				//get cmpdId 
			$data=$response_cmpdid['cmpdId'];

			$result=$this->con->query("select distinct t1.sno, t1.rej_type, t1.rej_short_name
				from tbl_rejection t1, tbl_component_rejection t2
				where t1.sno=t2.cmpdRejNo and t2.cmpdId='$data'");

			if($result->num_rows>0){
				echo "[";
				$count=1;
				while($row=$result->fetch_assoc()){
					$response["error"]=false;
					$response['message']=$row['rej_type'];
					$response['short_name']=$row['rej_short_name'];
					echo json_encode($response);
					if($count<$result->num_rows){
						echo ",";	
					}
					$count++;
				}

				echo "]";
			}else{
				$response_cmpdid['error']=true;
				$response_cmpdid['message']="search fields not found";
				echo "[";
				echo json_encode($response_cmpdid);
				echo "]";
			}
			
		}
		
		public function fetchData($search_quarry){					

			$result=$this->getData($search_quarry);			

			if($result->num_rows>0){
				echo "[";
				$count=1;
				while($row=$result->fetch_assoc()){
					$response["error"]=false;
					$response['isExternal']=$row['isexternal'];
					$response['currrec']=$row['currrec'];
					$response['cmpdid']=$row['cmpdid'];
					$response['sno']=$row['sno'];

					echo json_encode($response);
					if($count<$result->num_rows){
						echo ",";	
					}
					$count++;
				}
				echo "]";
			}
			else{
				$response_cmpdid['error']=true;
				$response_cmpdid['message']="search fields not found";
				echo "[";
				echo json_encode($response_cmpdid);
				echo "]";
			}
			
		}
	}





	?>