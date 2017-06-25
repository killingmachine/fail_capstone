function InitOnLod(){
    var fSubBtn = getId('fSub');
    var myTable = getId("myTable");
    var addTable = getId("addTable");
    var chkBox = getId('addSub').getElementsByTagName('input');
    var allBtn = getId('sub').getElementsByTagName('button');
    var btnL = allBtn.length;
    var rowL = myTable.rows.length - 1;
    var chkLen = chkBox.length;
    var totUnits = getId('totUnits');
    var total = 0;
  	var pre = getId('prev');
  	var sub = getId('sub');
  	var studDesc = getId('studDesc');
    var newL;



    for(i=1; i <= rowL ; i ++){
        total+=parseInt(myTable.rows[i].cells[2].innerHTML, 10);
    }
    ////chkbox
    for(c = 0; c<chkLen;c++){
        chkBox[c].onclick = function(){
             var str = this.value.split(".");
             newL = myTable.rows.length - 1;
             
            if(this.checked){
              if(total + parseInt(str[2],10) > 29){
                alert('maximum units aquired');
                this.checked = false;
              }
              else{
                newL++;
                var ins = myTable.insertRow(newL);
                var code = ins.insertCell(0);
                var title = ins.insertCell(1);
                var unt = ins.insertCell(2);
                code.innerHTML = str[0];
                title.innerHTML = str[1];
                unt.innerHTML = str[2];
                total+=parseInt(str[2],10);
                totUnits.innerHTML = "Total Units: " + total;
              }
                
                  
            }
            else{
                newL--;
                total= total - parseInt(str[2],10);
                totUnits.innerHTML = "Total Units: " + total;
                myTable.deleteRow(newL + 1);
            } 
        }       
    }
    for(bA = 0 ; bA < btnL ; bA++){
      allBtn[bA].onclick = function(){
        if(confirm('Do you really want to delete this subject?')){
           var row = this.parentNode.parentNode;
           row.parentNode.removeChild(row);
           var shit = row.innerHTML.split("<td>");
           newL--;
           var u = shit[3].substring(0,1);
           total = total - parseInt(u,10);
           totUnits.innerHTML = "Total Units: " + total;
           
        }
      }  
    }
    totUnits.innerHTML = "Total Units: " + total;




	function PrintPreview() {
        
        var popupWin = window.open('', '_blank', 'width=950,height=700,location=no');  
        popupWin.document.write('<html><title>Print Preview</title><link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css"/></head><body">')
        popupWin.document.write('<script src="assets/script/edit-checklist.js"></script>');
        popupWin.document.write('<style>'+ '#shit{display:none;}' +'</style>')
        popupWin.document.write(studDesc.innerHTML);
        popupWin.document.write(sub.innerHTML);
        // popupWin.document.write('<button>Print</button>');
         popupWin.document.write('</body>');
        popupWin.document.write('</html>');
        
    }
  function seeFsub(){
    window.open('failed.php');
  }
	pre.addEventListener('click',PrintPreview);
  fSubBtn.addEventListener('click',seeFsub);

}
window.addEventListener('load',InitOnLod);
