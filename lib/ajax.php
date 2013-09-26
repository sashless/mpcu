<?php
error_reporting(0);

require_once 'config.php';

class Gateway{
	private $configuration;
	
	public function __construct($config){
		if( isset($config) ){
			$this->configuration = $config;
			$this->initDB();
		}else{
			die("Died. Config not properly set.");
		}
	
		if(isset($_GET['action'])){
			if(isset($_GET['action']['get_next']) && isset($_GET['action']['time'])){
				$this->getNextActions($_GET['action']['time']);
			}else if(isset($_GET['action']['save_action']) &&
					 isset($_GET['action']['time']) &&
					 isset($_GET['action']['handling']) && 
					 isset($_GET['action']['type'])){
				$this->saveAction(array('time' => $_GET['action']['time'] ,
						 				'handling' => $_GET['action']['handling'],
										'type' => $_GET['action']['type']) );
			}
		}
	}
	private function initDB(){
		try {
			$this->conn = new PDO('mysql:host='.$this->configuration['host'].';dbname='.$this->configuration['database'], $this->configuration['username'], $this->configuration['password']);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	}
	private function saveAction($action){
		$this->filelog("save action: ");
		$this->filelog($action);
		try {
			$stmt = $this->conn->prepare('INSERT INTO T_Actions(p_action_time, action_handling, action_type) VALUES(:p_action_time, :action_handling, :action_type)');
			$stmt->bindParam(':p_action_time', $action['time'], PDO::PARAM_STR);
			$stmt->bindParam(':action_handling', $action['handling'], PDO::PARAM_STR);
			$stmt->bindParam(':action_type', $action['type'], PDO::PARAM_STR);
			$stmt->execute();
			$this->submitAction(array('status' => 'saved'));
		} catch(PDOException $e) {
			$this->filelog($e->getMessage());
			$this->submitAction(array('status' => 'error' , 'msg' => $e->getMessage()));
		}
	}
	private function getNextActions($last_action_time){
		$this->filelog("get next action: ");
		$this->filelog($last_action_time);
		try {
			$stmt = $this->conn->prepare('SELECT action_handling as handling, p_action_time as time, action_type as type FROM T_Actions WHERE p_action_time > :last_action_time ORDER BY time ASC');			
			$stmt->bindParam(':last_action_time', $last_action_time, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll();
			$this->filelog("result :");
			$this->filelog($result);
			if(count($result)){
				$this->submitAction($result);
			}else{
				$this->filelog("action not found - nothing to perform");
			}
		} catch(PDOException $e) {
			$this->filelog($e->getMessage());
		}	
	}
	private function submitAction($action){
		echo json_encode($action);
		exit;
	}
	private function filelog($msg){
		$file = __DIR__.'/gateway_log.txt';
		if (!file_exists($file)) file_put_contents($file, "");
	
		$current = file_get_contents($file);
		$current .= "\n";
		$current .= print_r($msg,true);
		file_put_contents($file, $current);
	
	}
}

new Gateway($config);
