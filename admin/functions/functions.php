<?php
	
	function random_pass(){
		$seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789');
		shuffle($seed);
		$rand = '';
		foreach (array_rand($seed, 5) as $k){$rand .= $seed[$k];}
		return $rand; 
	}
	function escape($str){
		$fck = preg_replace('/[^A-Za-z0-9\-]/', '', $str);
		return htmlentities(trim($fck), ENT_QUOTES, 'UTF-8');
	}
	function yrLevel($yr){
		if($yr == 1){
			return 'st year';
		}
		else if($yr == 2){
			return 'nd year';
		}
		else if($yr == 3){
			return 'rd year';
		}
		else{
			return 'th year';	
		}
	}
	function getStanding($lvl){
	if($lvl == 1){
			return 'freshman standing';
		}
		else if($lvl == 2){
			return 'sophomore standing';
		}
		else if($lvl == 3){
			return 'junior standing';
		}
		else if($lvl == 4){
			return 'senior standing';
		}
		else{
			return '';	
		}
	}
?>