function initOnload(){
		var curName = getId('currName');
		var currList = getId('currList');
		var cors = getId('cors');
		var warn = getId('warn');
		var errMsg = getId('errMsg');	
		var addBtn = getId('addBtn');			
		var curId = document.getElementsByName('curId');
		var slctdBtn ="";
		var fudep = "";
		for(var i = 0; i<curId.length;i++){
			curId[i].onclick = function(){
				slctdBtn = this.value;
				fudep = this.getAttribute("data-value");
				editCurri();
			}
		}
		////edit
		function editCurri(){
			var ajax = ajaxObj("POST","includes/response.php");
			ajax.onreadystatechange = function(){
				if(ajaxReturn(ajax)){
				currList.innerHTML = ajax.responseText;
				}
			}
			currList.innerHTML = 'wait....';
			ajax.send("selectedCurr="+slctdBtn+"&fudep="+fudep);						
		}
		//add	
		function addcurr(){
			if(curName.value=='' || cors.value =='' ){
				errMsg.style.color = 'red';
				errMsg.innerHTML = 'Pls name your curriculum';
				warn.style.color = 'red';
				warn.innerHTML = '*';
			}
			else{
				var ajax = ajaxObj("POST","includes/response.php");
				ajax.onreadystatechange = function(){
					if(ajaxReturn(ajax)){
						errMsg.innerHTML = ajax.responseText;
					}
				}
				errMsg.innerHTML = 'wait....';
				ajax.send("currName="+curName.value+"&cors="+cors.value);	
			}
	    }
		addBtn.addEventListener('click',addcurr);

}
window.addEventListener('load',initOnload);