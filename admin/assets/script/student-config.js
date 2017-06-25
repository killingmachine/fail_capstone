var subCode = getId('subCode');
		var studCont = getId('stud-cont');		
		var studId = document.getElementsByName('studId');
		var srchBox = getId('srchBox');
		var srch = getId('srch');
		var slctdBtn =''; 	
		var curr = getId('curr');


function initOnLoad(){
	var srchBtn = getId('srchBtn');
	function runMothafucka(){
		for(var i = 0; i<studId.length;i++){
				studId[i].onclick = function(){
						slctdBtn = this.value;
						srchBox.style.display = 'none';
						srch.style.display = 'none';
						editStudent();

				}
			}
	}
	runMothafucka();
	function editStudent(){
			var ajax = ajaxObj("POST","includes/response.php");
			ajax.onreadystatechange = function(){
				if(ajaxReturn(ajax)){
					studCont.innerHTML = ajax.responseText;
				}
			}
			studCont.innerHTML = 'wait....';
			ajax.send("slctdStud="+slctdBtn);	
			
	}
	function srchStudent(){
		// if(srchBox.value == ''){
		// 	alert('no value');
		// }
		// else{
			var ajax = ajaxObj("POST","includes/response.php");
			ajax.onreadystatechange = function(){
				if(ajaxReturn(ajax)){
					// initOnLoad();
					studCont.innerHTML = ajax.responseText;
					runMothafucka();
				}
			}
			studCont.innerHTML = 'wait....';
			ajax.send("srchStud="+srchBox.value);
		// }		
	}
	srchBtn.addEventListener('click',srchStudent);	
} 
window.addEventListener("load",initOnLoad);