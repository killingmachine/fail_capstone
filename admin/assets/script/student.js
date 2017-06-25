function initOnLoad(){
		var srchBox = getId('anSrch');
		var srchCont = getId('tblStud');
		var btnSrch = getId('btnSrch');
		var srchBtn	=getId('srchBtn');		
	function srchStudent(){
			var ajax = ajaxObj("POST","includes/response.php");
			ajax.onreadystatechange = function(){
				if(ajaxReturn(ajax)){
					srchCont.innerHTML = ajax.responseText;
				}
			}
			srchCont.innerHTML = 'wait....';
			ajax.send("srchAstud="+srchBox.value);	
			
	}
	srchBtn.addEventListener('click',srchStudent);
} 
window.addEventListener("load",initOnLoad);