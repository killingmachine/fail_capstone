function initOnload(){
	var userName = getId('userName');
	var eMsg = getId('eMsg');
	var add = getId('add');
	var sbtn;
	var subCont = getId('subCont');
	var cId = document.getElementsByName('cId');
	function runMothaFucka(){
		for(var i = 0; i<cId.length;i++){
				cId[i].onclick = function(){
						sbtn = this.value;
						editClient();
				}
			}
	}
	
	function editClient(){
			var ajax = ajaxObj("POST","includes/response.php");
			ajax.onreadystatechange = function(){
				if(ajaxReturn(ajax)){
						subCont.innerHTML = ajax.responseText;
				}
			}
			subCont.innerHTML = 'wait....';
			ajax.send("sbtn="+sbtn);	
					
	}
	runMothaFucka();
	function addClient(){
		if(userName.value == ''){
			eMsg.innerHTML = 'Fill up the textbox';
		}
		else{
			var ajax = ajaxObj("POST","includes/response.php");
				ajax.onreadystatechange = function(){
					if(ajaxReturn(ajax)){
						eMsg.innerHTML = ajax.responseText;
					}
				}
				eMsg.innerHTML = 'wait....';
				ajax.send("ClientName="+userName.value);
		}
	}
	add.addEventListener('click',addClient);
}

window.addEventListener('load',initOnload);
// alert("hello");