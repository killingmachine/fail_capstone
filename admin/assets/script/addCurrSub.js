// function quer(q){
// 	var ajax = ajaxObj("POST","includes/wew.php");
// 	ajax.onreadystatechange = function(){
// 		if(ajaxReturn(ajax)){
// 			avail.innerHTML = ajax.responseText;
// 			runMothaFucka();
// 		}
// 	}
// 	avail.innerHTML = 'wait....';
// 	ajax.send("fill="+q.value);
// }
function initOnload(){
		
		var it = getId('it');
		var cs = getId('cs');
		var all = getId('all');
		var min = getId('min');
		var currId = getId('currId');
		var errMsg = getId('errMsg');
		var subCode = getId('subCode'); 
		var availSub = getId('availSub');
		var avail = getId('avail');
		var srchBox = getId('srchBox');
		var srchBtn = getId('srchBtn');
		var srch = getId('srch');
				// var addBtn = getId('');			
		var selCode = document.getElementsByName('selCode');
		var slctdBtn ="";
				////var for edit
		var currSub = getId('currSub');

		/////
		// var kainis = getId('kainis');
		// var fYfSpS = [];
	 // 	for(var i = 0;i<kainis.rows.length;i++){
	 // 		if(kainis.rows[i].cells[3].innerHTML != ''){
	 // 			fYfSpS[i]=kainis.rows[i].cells[3].innerHTML;  
	 // 			//alert(fYfSpS[i]);	
	 // 		}
	 		
		// }
		// var kainis1 = getId('kainis1');
		// var fYsSpS = [];
		// for(var i = 0;i<kainis1.rows.length;i++){
		// 	if(kainis.rows[i].cells[3].innerHTML != ''){
		// 		fYsSpS[i] = kainis1.rows[i].cells[3].innerHTML;	
		// 	}
	 		
		// }
		// for(var i = 0;i<kainis2.rows.length;i++){
		// 	if(kainis2.rows[i].cells[3].innerHTML != ''){
		// 		fYsumPs[i] = kainis2.rows[i].cells[3].innerHTML;
	 				
		// 	}	
		// }
		var btnDel1 = document.getElementsByClassName('btnDel1');
		var btnDel1Sec = document.getElementsByClassName('btnDel1Sec');
		var btnDel1Sum = document.getElementsByClassName('btnDel1Sum');
		var btnDel2 = document.getElementsByClassName('btnDel2');
		var btnDel2Sec = document.getElementsByClassName('btnDel2Sec');
		var btnDel2Sum = document.getElementsByClassName('btnDel2Sum');
		var btnDel3 = document.getElementsByClassName('btnDel3');
		var btnDel3Sec = document.getElementsByClassName('btnDel3Sec');
		var btnDel3Sum = document.getElementsByClassName('btnDel3Sum');
		var btnDel4 = document.getElementsByClassName('btnDel4');
		var btnDel4Sec = document.getElementsByClassName('btnDel4Sec');
		function pesti10(){
			for(var i=0;i<btnDel4Sec.length;i++){
				btnDel4Sec[i].onclick = function a(){
					var ajax = ajaxObj("POST","includes/response.php");
					ajax.onreadystatechange = function(){
						if(ajaxReturn(ajax)){
							currSub.innerHTML = ajax.responseText;
							shit();
							pesti();
							pesti1();
							pesti2();
							pesti3();
							pesti4();
							pesti5();
							pesti6();
							pesti7();
							pesti8();
							pesti9();
							pesti10();
						}
					}
					currSub.innerHTML = 'wait....';
					ajax.send("delFth="+this.getAttribute("data-value"));
				}
			}	
		}
		pesti10();
		function pesti(){
			for(var i=0;i<btnDel1.length;i++){
				btnDel1[i].onclick = function a(){
					if((fYsSpS.indexOf(this.value) >= 0 || CfYsSpS.indexOf(this.value) >= 0) || (fYsumPs.indexOf(this.value) >= 0 || CfYsumPs.indexOf(this.value) >=0 ) || (sYfSPs.indexOf(this.value) >= 0 || CsYfSPs.indexOf(this.value) >= 0) || (sYsSPs.indexOf(this.value) >= 0 || CsYsSPs.indexOf(this.value) >= 0) || (sYsumPs.indexOf(this.value) >= 0 || CsYsumPs.indexOf(this.value) >= 0 ) || (tYfSPs.indexOf(this.value) >= 0 || CtYfSPs.indexOf(this.value) >= 0) || (tYsSPs.indexOf(this.value) >= 0 || CtYsSPs.indexOf(this.value) >= 0) || (tYsumPs.indexOf(this.value) >= 0 || CtYsumPs.indexOf(this.value) >= 0) || (fthYfSPs.indexOf(this.value) >= 0 || CfthYfSPs.indexOf(this.value) >= 0) || (fthYsSPs.indexOf(this.value) >= 0 || CfthYsSPs.indexOf(this.value) >= 0)){//delete

						alert("delete first subject containing " + this.value + " as a PreRequisite or coRequisite");
					}
					else{/// dont delete
						var ajax = ajaxObj("POST","includes/response.php");
						ajax.onreadystatechange = function(){
							if(ajaxReturn(ajax)){
								currSub.innerHTML = ajax.responseText;
								shit();
								pesti();
								pesti1();
								pesti2();
								pesti3();
								pesti4();
								pesti5();
								pesti6();
								pesti7();
								pesti8();
								pesti9();
								pesti10();
							}
						}
						currSub.innerHTML = 'wait....';
						ajax.send("delF="+this.getAttribute("data-value"));
					}
							
				}	
					
			}
		}
		pesti();	
		function pesti1(){
			for(var i=0;i<btnDel1Sec.length;i++){
				btnDel1Sec[i].onclick = function a(){
					if((fYsumPs.indexOf(this.value) >= 0 || CfYsumPs.indexOf(this.value) >= 0) || (sYfSPs.indexOf(this.value) >= 0 || CsYfSPs.indexOf(this.value) >= 0) || (sYsSPs.indexOf(this.value) >= 0 || CsYsSPs.indexOf(this.value) >= 0) || (sYsumPs.indexOf(this.value) >= 0 || CsYsumPs.indexOf(this.value) >= 0) || (tYfSPs.indexOf(this.value) >= 0 || CtYfSPs.indexOf(this.value) >= 0) || (tYsSPs.indexOf(this.value) >= 0 || CtYsSPs.indexOf(this.value) >= 0) || (tYsumPs.indexOf(this.value) >= 0 || CtYsumPs.indexOf(this.value) >= 0) || (fthYfSPs.indexOf(this.value) >= 0 || CfthYfSPs.indexOf(this.value) >= 0) || (fthYsSPs.indexOf(this.value) >= 0 || CfthYsSPs.indexOf(this.value) >= 0)){//delete
							alert("delete first subject containing " + this.value + " as a PreRequisite or coRequisite");			
					}
					else{/// dont delete
						var ajax = ajaxObj("POST","includes/response.php");
						ajax.onreadystatechange = function(){
							if(ajaxReturn(ajax)){
								currSub.innerHTML = ajax.responseText;
								shit();
								pesti();
								pesti1();
								pesti2();
								pesti3();
								pesti4();
								pesti5();
								pesti6();
								pesti7();
								pesti8();
								pesti9();
								pesti10();
							}
						}
						currSub.innerHTML = 'wait....';
						ajax.send("delFsec="+this.getAttribute("data-value"));
					}	
					
				}	
			}	
		}
		pesti1();
		function pesti2(){
			for(var i=0;i<btnDel1Sum.length;i++){
				btnDel1Sum[i].onclick = function a(){
					if((sYfSPs.indexOf(this.value) >= 0 || CsYfSPs.indexOf(this.value) >= 0) || (sYsSPs.indexOf(this.value) >= 0 || CsYsSPs.indexOf(this.value) >= 0) || (sYsumPs.indexOf(this.value) >= 0 || CsYsumPs.indexOf(this.value) >= 0) || (tYfSPs.indexOf(this.value) >= 0 || CtYfSPs.indexOf(this.value) >= 0) || (tYsSPs.indexOf(this.value) >= 0 || CtYsSPs.indexOf(this.value) >= 0) || (tYsumPs.indexOf(this.value) >= 0 || CtYsumPs.indexOf(this.value) >= 0) || (fthYfSPs.indexOf(this.value) >= 0 || CfthYfSPs.indexOf(this.value) >= 0) || (fthYsSPs.indexOf(this.value) >= 0 || CfthYsSPs.indexOf(this.value) >= 0)){//delete
							alert("delete first subject containing " + this.value + " as a PreRequisite or coRequisite");	
					}
					else{/// dont delete
						var ajax = ajaxObj("POST","includes/response.php");
						ajax.onreadystatechange = function(){
							if(ajaxReturn(ajax)){
								currSub.innerHTML = ajax.responseText;
								shit();
								pesti();
								pesti1();
								pesti2();
								pesti3();
								pesti4();
								pesti5();
								pesti6();
								pesti7();
								pesti8();
								pesti9();
								pesti10();


							}
						}
						currSub.innerHTML = 'wait....';
						ajax.send("delFsum="+this.getAttribute("data-value"));
					}	
					
				}	
			}	
		}
		pesti2();
		function pesti3(){
			for(var i=0;i<btnDel2.length;i++){
				btnDel2[i].onclick = function a(){
					if((sYsSPs.indexOf(this.value) >= 0 || CsYsSPs.indexOf(this.value) >= 0) || (sYsumPs.indexOf(this.value) >= 0 || CsYsumPs.indexOf(this.value) >= 0) || (tYfSPs.indexOf(this.value) >= 0 || CtYfSPs.indexOf(this.value) >= 0) || (tYsSPs.indexOf(this.value) >= 0 || CtYsSPs.indexOf(this.value) >= 0) || (tYsumPs.indexOf(this.value) >= 0 || CtYsumPs.indexOf(this.value) >= 0) || (fthYfSPs.indexOf(this.value) >= 0 || CfthYfSPs.indexOf(this.value) >= 0) || (fthYsSPs.indexOf(this.value) >= 0 || CfthYsSPs.indexOf(this.value) >= 0)){//delete
							alert("delete first subject containing " + this.value + " as a PreRequisite or coRequisite");	
					}
					else{/// dont delete
						var ajax = ajaxObj("POST","includes/response.php");
						ajax.onreadystatechange = function(){
							if(ajaxReturn(ajax)){
								currSub.innerHTML = ajax.responseText;
								shit();
								pesti();
								pesti1();
								pesti2();
								pesti3();
								pesti4();
								pesti5();
								pesti6();
								pesti7();
								pesti8();
								pesti9();
								pesti10();
							}
						}
						currSub.innerHTML = 'wait....';
						ajax.send("delS="+this.getAttribute("data-value"));
					}	
					
				}	
			}
		}
		pesti3();
		function pesti4(){
			for(var i=0;i<btnDel2Sec.length;i++){
				btnDel2Sec[i].onclick = function a(){
					if((sYsumPs.indexOf(this.value) >= 0 || CsYsumPs.indexOf(this.value) >= 0) || (tYfSPs.indexOf(this.value) >= 0 || CtYfSPs.indexOf(this.value) >= 0) || (tYsSPs.indexOf(this.value) >= 0 || CtYsSPs.indexOf(this.value) >= 0) || (tYsumPs.indexOf(this.value) >= 0 || CtYsumPs.indexOf(this.value) >= 0) || (fthYfSPs.indexOf(this.value) >= 0 || CfthYfSPs.indexOf(this.value) >= 0) || (fthYsSPs.indexOf(this.value) >= 0 || CfthYsSPs.indexOf(this.value) >= 0)){//delete
							alert("delete first subject containing " + this.value + " as a PreRequisite or coRequisite");
					}
					else{/// dont delete
						var ajax = ajaxObj("POST","includes/response.php");
						ajax.onreadystatechange = function(){
							if(ajaxReturn(ajax)){
								currSub.innerHTML = ajax.responseText;
								shit();
								pesti();
								pesti1();
								pesti2();
								pesti3();
								pesti4();
								pesti5();
								pesti6();
								pesti7();
								pesti8();
								pesti9();
								pesti10();
							}
						}
						currSub.innerHTML = 'wait....';
						ajax.send("delSs="+this.getAttribute("data-value"));
					}	
					
				}	
			}	
		}
		pesti4();
		function pesti5(){
			for(var i=0;i<btnDel2Sum.length;i++){
				btnDel2Sum[i].onclick = function a(){
					if((tYfSPs.indexOf(this.value) >= 0 || CtYfSPs.indexOf(this.value) >= 0) || (tYsSPs.indexOf(this.value) >= 0 || CtYsSPs.indexOf(this.value) >= 0) || (tYsumPs.indexOf(this.value) >= 0 || CtYsumPs.indexOf(this.value) >= 0) || (fthYfSPs.indexOf(this.value) >= 0 || CfthYfSPs.indexOf(this.value) >= 0) || (fthYsSPs.indexOf(this.value) >= 0 || CfthYsSPs.indexOf(this.value) >= 0)){//delete
							alert("delete first subject containing " + this.value + " as a PreRequisite or coRequisite");	
					}
					else{/// dont delete
						var ajax = ajaxObj("POST","includes/response.php");
						ajax.onreadystatechange = function(){
							if(ajaxReturn(ajax)){
								currSub.innerHTML = ajax.responseText;
								shit();
								pesti();
								pesti1();
								pesti2();
								pesti3();
								pesti4();
								pesti5();
								pesti6();
								pesti7();
								pesti8();
								pesti9();
								pesti10();
							}
						}
						currSub.innerHTML = 'wait....';
						ajax.send("delSsum="+this.getAttribute("data-value"));
					}	
					
				}	
			}	
		}
		pesti5();
		function pesti6(){
			for(var i=0;i<btnDel3.length;i++){
				btnDel3[i].onclick = function a(){
					if((tYsSPs.indexOf(this.value) >= 0 || CtYsSPs.indexOf(this.value) >= 0) || (tYsumPs.indexOf(this.value) >= 0 || CtYsumPs.indexOf(this.value) >= 0) || (fthYfSPs.indexOf(this.value) >= 0 || CfthYfSPs.indexOf(this.value) >= 0) || (fthYsSPs.indexOf(this.value) >= 0 || CfthYsSPs.indexOf(this.value) >= 0)){//delete
						alert("delete first subject containing " + this.value + " as a PreRequisite or coRequisite");	
					}
					else{/// dont delete
						var ajax = ajaxObj("POST","includes/response.php");
						ajax.onreadystatechange = function(){
							if(ajaxReturn(ajax)){
								currSub.innerHTML = ajax.responseText;
								shit();
								pesti();
								pesti1();
								pesti2();
								pesti3();
								pesti4();
								pesti5();
								pesti6();
								pesti7();
								pesti8();
								pesti9();
								pesti10();
							}
						}
						currSub.innerHTML = 'wait....';
						ajax.send("delT="+this.getAttribute("data-value"));
					}	
					
				}	
			}	
		}
		pesti6();
		function pesti7(){
			for(var i=0;i<btnDel3Sec.length;i++){
				btnDel3Sec[i].onclick = function a(){
					if((tYsumPs.indexOf(this.value) >= 0 || CtYsumPs.indexOf(this.value) >= 0) || (fthYfSPs.indexOf(this.value) >= 0 || CfthYfSPs.indexOf(this.value) >= 0) || (fthYsSPs.indexOf(this.value) >= 0 || CfthYsSPs.indexOf(this.value) >= 0)){//delete
						alert("delete first subject containing " + this.value + " as a PreRequisite or coRequisite");	
					}
					else{/// dont delete
						var ajax = ajaxObj("POST","includes/response.php");
						ajax.onreadystatechange = function(){
							if(ajaxReturn(ajax)){
								currSub.innerHTML = ajax.responseText;
								shit();
								pesti();
								pesti();
								pesti1();
								pesti2();
								pesti3();
								pesti4();
								pesti5();
								pesti6();
								pesti7();
								pesti8();
								pesti9();
								pesti10();
							}
						}
						currSub.innerHTML = 'wait....';
						ajax.send("delTs="+this.getAttribute("data-value"));
					}	
					
				}	
			}	
		}
		pesti7();
		function pesti8(){
			for(var i=0;i<btnDel3Sum.length;i++){
				btnDel3Sum[i].onclick = function a(){
					if((fthYfSPs.indexOf(this.value) >= 0 || CfthYfSPs.indexOf(this.value) >= 0) || (fthYsSPs.indexOf(this.value) >= 0 || CfthYsSPs.indexOf(this.value) >= 0)){//delete
						alert("delete first subject containing " + this.value + " as a PreRequisite or coRequisite");	
					}
					else{/// dont delete
						var ajax = ajaxObj("POST","includes/response.php");
						ajax.onreadystatechange = function(){
							if(ajaxReturn(ajax)){
								currSub.innerHTML = ajax.responseText;
								shit();
								pesti();
								pesti1();
								pesti2();
								pesti3();
								pesti4();
								pesti5();
								pesti6();
								pesti7();
								pesti8();
								pesti9();
								pesti10();
							}
						}
						currSub.innerHTML = 'wait....';
						ajax.send("delTsum="+this.getAttribute("data-value"));
					}	
					
				}	
			}	
		}
		pesti8();
		function pesti9(){
			for(var i=0;i<btnDel4.length;i++){
				btnDel4[i].onclick = function a(){
					if(fthYsSPs.indexOf(this.value) >= 0 || CfthYsSPs.indexOf(this.value) >= 0){//delete
						alert("delete first subject containing " + this.value + " as a PreRequisite or coRequisite");	
					}
					else{/// dont delete
						var ajax = ajaxObj("POST","includes/response.php");
						ajax.onreadystatechange = function(){
							if(ajaxReturn(ajax)){
								currSub.innerHTML = ajax.responseText;
								shit();
								pesti();
								pesti1();
								pesti2();
								pesti3();
								pesti4();
								pesti5();
								pesti6();
								pesti7();
								pesti8();
								pesti9();
								pesti10();
							}
						}
						currSub.innerHTML = 'wait....';
						ajax.send("delLast="+this.getAttribute("data-value"));
					}		
				}	
			}
		}
		pesti9()
		//////
		var slctdBtn1 ="";
		var edCurSub = document.getElementsByName('edCurSub');
		function shit(){
			for(var i = 0; i<edCurSub.length;i++){
			edCurSub[i].onclick = function(){
				slctdBtn1 = this.value;
					var ajax = ajaxObj("POST","includes/response.php");
					ajax.onreadystatechange = function(){
						if(ajaxReturn(ajax)){
							currSub.innerHTML = ajax.responseText;
						}
					}
					currSub.innerHTML = 'wait....';
					ajax.send("currSub="+slctdBtn1);	
				}

			}
		}
		shit();

		////addd
		function runMothaFucka(){
			for(var i = 0; i<selCode.length;i++){
			selCode[i].onclick = function(){
				slctdBtn = this.value;
				subCode.value = slctdBtn;
					var ajax = ajaxObj("POST","includes/response.php");
					ajax.onreadystatechange = function(){
						if(ajaxReturn(ajax)){
							errMsg.innerHTML = ajax.responseText;
						}
					}
					errMsg.innerHTML = 'wait....';
					ajax.send("subCode="+subCode.value+"&currId="+currId.value);	
				}

			}
		}
		runMothaFucka();
		function srchSub(){
				var ajax = ajaxObj("POST","includes/response.php");
				ajax.onreadystatechange = function(){
					if(ajaxReturn(ajax)){
						avail.innerHTML = ajax.responseText;
						runMothaFucka();
					}
				}
				avail.innerHTML = 'wait....';
				ajax.send("srchCurSub="+srchBox.value);
					
		}
		srchBtn.addEventListener('click',srchSub);
		////filter
		it.onclick = function quer(){
			var ajax = ajaxObj("POST","includes/response.php");
			ajax.onreadystatechange = function(){
				if(ajaxReturn(ajax)){
					avail.innerHTML = ajax.responseText;
					runMothaFucka();
				}
			}
			avail.innerHTML = 'wait....';
			ajax.send("fill="+this.value);
		}
		cs.onclick = function quer(){
			var ajax = ajaxObj("POST","includes/response.php");
			ajax.onreadystatechange = function(){
				if(ajaxReturn(ajax)){
					avail.innerHTML = ajax.responseText;
					runMothaFucka();
				}
			}
			avail.innerHTML = 'wait....';
			ajax.send("fill="+this.value);
		}
		all.onclick = function quer(){
			var ajax = ajaxObj("POST","includes/response.php");
			ajax.onreadystatechange = function(){
				if(ajaxReturn(ajax)){
					avail.innerHTML = ajax.responseText;
					runMothaFucka();
				}
			}
			avail.innerHTML = 'wait....';
			ajax.send("fill="+this.value);
		}
		min.onclick = function quer(){
			var ajax = ajaxObj("POST","includes/response.php");
			ajax.onreadystatechange = function(){
				if(ajaxReturn(ajax)){
					avail.innerHTML = ajax.responseText;
					runMothaFucka();
				}
			}
			avail.innerHTML = 'wait....';
			ajax.send("fill="+this.value);
		}
		
			
		
		
		
}
window.addEventListener('load',initOnload);