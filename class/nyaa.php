<?php
	class nyaa{
		public $db;
		public function __construct(){
			$this->db= new medoo([
    				'database_type' => 'mysql',
 				    'database_name' => 'nyaa',
 				    'server' => 'localhost',
 			        'username' => 'root',
  				    'password' => '',
			        'charset' => 'utf8',
   			 		'port' => 3306,
    				'prefix' => 'info_',
			]);
		}
		public function nyaa(){
			$page=$this->count('cn');
			$url="https://nyaa.si/?p=".$page;
			phpQuery::newDocumentFile($url); 
			$info=pq(".table tbody")->find('tr'); 
			$pre=$this->achieve($info);
			$this->insert($pre,'nyaa');
			
		}
		public function sukebei(){
			$page=$this->count('cs');
			$url="https://sukebei.nyaa.si/?p=".$page;
			phpQuery::newDocumentFile($url); 
			$info=pq(".table tbody")->find('tr'); 
			$pre=$this->achieve($info);
			$this->insert($pre,'sukebei');
		}
		public function search($info){
			$table=$info['table'];
			$where['name[~]']=$info['name'];
			$where['type']=$info['type'];
			$info=$this->db->select($table,"*",$where);
			return $info;

		}
		public function s($info,$num,$size){//nyaa
			$where['name[~]']=$info;
			$info=$this->db->select('nyaa',"*",$where);
			$re['total']=count($info);
			
			if($num<=0){
            	$num=0;
        	}else{
               	$num=(int)$num;//
        	}
			$list=array_slice($info, $size*$num,$size);

			$re['list']=$list;
			
			$this->json($re);
		}
		protected function achieve($info){
			foreach ($info as $key => $value) {
				$ar=[];
				$ar[]=pq($value)->find("td:eq(0)")->find("img")->attr("alt");//0
				$ar[]=pq($value)->find("td:eq(1)")->text();
				//$ar[]=pq($value)->find("td:eq(2)")->find("a:eq(0)")->attr("href");//torrent
				//$ar[]=pq($value)->find("td:eq(2)")->find("a:eq(1)")->attr("href");//magnet 3
				$torrent=pq($value)->find("td:eq(2)")->find(".fa-download")->parent()->attr("href");
				$magnet=pq($value)->find("td:eq(2)")->find(".fa-magnet")->parent()->attr("href");
				$ar[]=$torrent?$torrent:"";
				$ar[]=$magnet?$magnet:"";
				$ar[]=pq($value)->find("td:eq(3)")->text();//size
				$ar[]=pq($value)->find("td:eq(4)")->text();//date
				$rem[]=$ar;
			}
			return $rem;
		}
		protected function insert($info,$table){
			foreach ($info as $key => $value) {
				if(!$this->jugg($value[3],$table)){
					$add['type']=$value[0];
					$add['name']=$value[1];
					$add['torrent']=$value[2];
					$add['magnet']=$value[3];
					$add['size']=$value[4];
					$add['date']=$value[5];
					$add['time']=time();
					$this->db->insert($table,$add);
				}
			}
		}
		protected function jugg($magnet,$table){
			$where['magnet']=$magnet;
			return $this->db->has($table,$where);
		}
		protected function count($table){
			$insert['time']=time();
			$this->db->insert($table,$insert);
			$where['id[>]']=0;
			return $this->db->count($table,$where);
		}
		protected function json($info){
			header('Content-type:text/json');
			echo json_encode($info);
		}
	}