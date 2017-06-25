function initOnload(){		
		var studHehe = getId('studHehe');

		var grd1 = document.getElementsByName('grd1');
		var slctdBtn ="";
		var slctdBtn1 ="";
		var msg11 = getId('msg11');
		var rem = '';
		var btn = getId('bE1');
		var e1 = getId('e1');
		var btn1 = getId('bE2');
		var e2 = getId('e2');
		var btn2 = getId('bE3');
		var e3 = getId('e3');
		///auto evaluate
		btn.onclick = function(){
			var ajax = ajaxObj("POST","includes/response.php");
					ajax.onreadystatechange = function(){
						if(ajaxReturn(ajax)){
							e1.innerHTML = ajax.responseText;
						}
					}
			e1.innerHTML = 'wait....';
			ajax.send("xyz="+2+"&studid="+this.value);
		}
		btn1.onclick = function(){
			var ajax = ajaxObj("POST","includes/response.php");
					ajax.onreadystatechange = function(){
						if(ajaxReturn(ajax)){
							e2.innerHTML = ajax.responseText;
						}
					}
			e2.innerHTML = 'wait....';
			ajax.send("xyz1="+3+"&studid="+this.value);
		}
		btn2.onclick = function(){
			var ajax = ajaxObj("POST","includes/response.php");
					ajax.onreadystatechange = function(){
						if(ajaxReturn(ajax)){
							e3.innerHTML = ajax.responseText;
						}
					}
			e3.innerHTML = 'wait....';
			ajax.send("xyz2="+4+"&studid="+this.value);
		}
		///////
		////var for edit
		function pOrF(chk){
			if(chk>74){
					return 'passed';
			}
			else if(chk<=50){
				return 'N/A';
			}
			else{
					return  'failed';	
			}
		}

		for(var i = 0; i<grd1.length;i++){
			// grd1[i].onchange = function(){
			// 	slctdBtn1 = this.getAttribute('placeholder');
			// 	slctdBtn = this.value;
			// 	rem = pOrF(slctdBtn);
			// 	/////
			// 		var ajax = ajaxObj("POST","includes/wew.php");
			// 		ajax.onreadystatechange = function(){
			// 			if(ajaxReturn(ajax)){
			// 				msg11.innerHTML = ajax.responseText;
			// 			}
			// 		}
			// 		msg11.innerHTML = 'wait....';
			// 		ajax.send("grade="+slctdBtn+"&rem="+rem+"&gId="+slctdBtn1);
					
			// 	}
			grd1[i].onkeypress = function(evt) {
   				 evt = (evt) ? evt : window.event;
    			var charCode = (evt.which) ? evt.which : evt.keyCode;
   	 			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        			return false;
    			}
    			return true;
			}
			grd1[i].onchange = function(){
				slctdBtn1 = this.getAttribute('placeholder');
				slctdBtn = this.value;
				rem = pOrF(slctdBtn);
				/////
					var ajax = ajaxObj("POST","includes/response.php");
					ajax.onreadystatechange = function(){
						if(ajaxReturn(ajax)){
							msg11.innerHTML = ajax.responseText;
						}
					}
					msg11.innerHTML = 'wait....';
					ajax.send("grade="+slctdBtn+"&rem="+rem+"&gId="+slctdBtn1);
					
				}

		}
		var content_holder = getId("content_holder");
		var pre = getId("prev");
	function PrintPreview() {
        
        var popupWin = window.open('', '_blank', 'width=950,height=900,location=no');
        popupWin.document.write('<style>'+ 'button{display:none;}' +'</style>');  
         popupWin.document.write('<html><title>Print Preview</title><link rel="stylesheet" type="text/css" href="../admin/assets/style/bootstrap/css/bootstrap.min.css"/></head><body">')
        // popupWin.document.write('<script src="assets/script/edit-checklist.js"></script>');
        popupWin.document.write('<style>'+ 'button{display:none;}' +'</style>');
        // popupWin.document.write('<style>'+ '#myTable>thead{background-color: #323232;color:#f6f6f6;}' +'</style>');
        popupWin.document.write(content_holder.innerHTML);
        // popupWin.document.write(sub.innerHTML);
        // popupWin.document.write('<button>Print</button>');
         // popupWin.document.write('</body>');
        // popupWin.document.write('</html>');
        
    }
    pre.addEventListener('click',PrintPreview);
}
window.addEventListener('load',initOnload);