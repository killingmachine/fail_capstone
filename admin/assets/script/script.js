function initOnLoad(){
	var cBtn = getId('cBtn');
	var x = getId('x');
	var sem = getId('sem');
	var msg = getId('msg');
	var oE = getId('q');
	var nE = getId('w');
	var msgSem = getId('msgSem');
	var old = getId('o');
	var n  = getId('n');
	function palit(){
		if(old.value == '' || n.value == ''){
			if(old.value == '' && n.value == ''){
				oE.innerHTML = '*';
				nE.innerHTML = '*';
			}
			else if(old.value == '' && n.value != ''){
				oE.innerHTML = '*';
				nE.innerHTML = '';
			}
			else if(n.value == '' && old.value != ''){
				oE.innerHTML = '';
				nE.innerHTML = '*';	
			}
		}
		else{
			var ajax = ajaxObj("POST","includes/response.php");
			ajax.onreadystatechange = function(){
			if(ajaxReturn(ajax)){
						msg.innerHTML = ajax.responseText;
					}
				}
				msg.innerHTML = 'wait....';
				ajax.send("old="+old.value+"&new="+n.value);	
		}
		
	}
	function palitSem(){
		var ajax = ajaxObj('POST','includes/response.php');
		ajax.onreadystatechange = function(){
		if(ajaxReturn(ajax)){
						msgSem.innerHTML = ajax.responseText;
			}
		}
		msgSem.innerHTML = 'wait....';
		ajax.send('sem='+sem.value);

	}
	x.addEventListener('click',palitSem);
	cBtn.addEventListener('click',palit);
}	
window.addEventListener('load',initOnLoad);