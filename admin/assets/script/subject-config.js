var subCode = getId('subCode');
var desc = getId('desc');
var unit = getId('unit');
var errMsg = getId('errMsg');
var warn = getId('warn');
var warn1 = getId('warn1');
var lecHrs = getId('lecHrs');
var labHrs = getId('labHrs');
var conHrs = getId('conHrs');
var addBtn = getId('add');
var subCont = getId('subCont');
var srchBox = getId('srchBox');
var srch = getId('srch');
var srchBtn = getId('srchBtn');
var selCode = document.getElementsByName('selCode');
var slctdBtn;

function initOnLoad(){
		function runMothafucka(){
			for(var i = 0; i<selCode.length;i++){
				selCode[i].onclick = function(){
						slctdBtn = this.value;
						srchBox.style.display = 'none';
						srch.style.display = 'none';
						editSubject();
				}
			}
		} 	
		conHrs.value = parseInt(lecHrs.value) + parseInt(labHrs.value);
		function editSubject(){
			var ajax = ajaxObj("POST","includes/response.php");
			ajax.onreadystatechange = function(){
				if(ajaxReturn(ajax)){
						subCont.innerHTML = ajax.responseText;
				}
			}
			subCont.innerHTML = 'wait....';
			ajax.send("selected="+slctdBtn);	
					
		}
		runMothafucka();
			function srchSub(){
				// if(srchBox.value == ''){
				// 	alert('no value');
				// }
				// else{
					var ajax = ajaxObj("POST","includes/response.php");
					ajax.onreadystatechange = function(){
						if(ajaxReturn(ajax)){
							subCont.innerHTML = ajax.responseText;
							runMothafucka();
						}
					}
					subCont.innerHTML = 'wait....';
					ajax.send("srchSub="+srchBox.value);
				//}		
			}
			srchBtn.addEventListener('click',srchSub);


			
			function addSub(){
					if(subCode.value == '' || desc.value == '' || unit.value == '' || lecHrs.value == ''  || conHrs.value == ''){
						errMsg.innerHTML = 'Fill up all textbox';
						
						
					}
					else{
						var ajax = ajaxObj("POST","includes/response.php");
						ajax.onreadystatechange = function(){
							if(ajaxReturn(ajax)){
								errMsg.innerHTML = ajax.responseText;
							}
						}
						errMsg.innerHTML = 'wait....';
						ajax.send("subcode="+subCode.value+"&desc="+desc.value+"&unit="+unit.value+"&lecHrs="+lecHrs.value+"&labHrs="+labHrs.value+"&conHrs="+conHrs.value);	
					}
				}
			 function nospace(val){
			 		var rx = new RegExp;
					rx = /[^a-z0-9]/gi;
					val.value = val.value.replace(rx, "");	
			 }	
			function getContact(){
				conHrs.value = parseInt(lecHrs.value) + parseInt(labHrs.value);2
			}
				subCode.addEventListener('keyup',function(){nospace(this);})
				// desc.addEventListener('keyup',function(){nospace(this);})
				addBtn.addEventListener('click',addSub);
				lecHrs.addEventListener('change',getContact);
				labHrs.addEventListener('change',getContact);
				lecHrs.addEventListener('keyup',getContact);
				labHrs.addEventListener('keyup',getContact);
} 
window.addEventListener("load",initOnLoad);


	
