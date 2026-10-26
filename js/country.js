/*function showprovince(){
			var selectBox = document.getElementById('country');
			var userInput = selectBox.options[selectBox.selectedIndex].value;
			if (userInput=='Nigeria') {
				document.getElementById('nlProvinces').style.visibility='visible';
			}else if(userInput=='Foreign') {
				document.getElementById('nlProvinces').style.display='none';
			}
			else{
				document.getElementById('nlProvinces').style.visibility='hidden';
			}
			else{
				document.getElementById('nlProvinces').style.display='none';
			}
			else if(userInput=='Foreign') {
				document.getElementById('nsProvinces').style.visibility='visible';
			}
			else{
				document.getElementById('nsProvinces').style.visibility='hidden';
			}
			if (userInput=='directentry') {
				document.getElementById('nnProvinces').style.visibility='visible';
			}
			else{
				document.getElementById('nnProvinces').style.visibility='hidden';
			}
			return false;
		}*/

function change(obj) {

    var selectBox = obj;
    var selected = selectBox.options[selectBox.selectedIndex].value;
    var retCustDetails = document.getElementById("retCustDetails");
    var tradeCustDetails = document.getElementById("tradeCustDetails");

    if(selected == 'Nigeria'){
        retCustDetails.style.display = "block";
        tradeCustDetails.style.display = "none";
    }else if (selected == 'Foreign') {
    	 tradeCustDetails.style.display = "block";
       retCustDetails.style.display = "none";
    }
    else{
        retCustDetails.style.display = "none";
        tradeCustDetails.style.display = "none";
    }
}

//For Sponsorship Selection
function changes(objs){
	var selectBox = objs;
    var selected = selectBox.options[selectBox.selectedIndex].value;
    var retCustDetails = document.getElementById("retCustDetails");
    var tradeCustDetails = document.getElementById("tradeCustDetails");

    if(selected == 'Private'){
        retCustDetails.style.display = "block";
        tradeCustDetails.style.display = "none";
    }else if (selected == 'Scholarship') {
    	 tradeCustDetails.style.display = "block";
       retCustDetails.style.display = "none";
    }
    else{
        retCustDetails.style.display = "none";
        tradeCustDetails.style.display = "none";
    }
}
//For Qualification Selection
function changed(objs){
    var selectBox = objs;
    var selected = selectBox.options[selectBox.selectedIndex].value;
    var retCustDetails = document.getElementById("retCustDetails");
    var tradeCustDetails = document.getElementById("tradeCustDetails");

    if(selected == 'Yes'){
        retCustDetails.style.display = "block";
        tradeCustDetails.style.display = "none";
    }else if (selected == 'No') {
         tradeCustDetails.style.display = "none";
       retCustDetails.style.display = "none";
    }
    else{
        retCustDetails.style.display = "none";
        tradeCustDetails.style.display = "none";
    }
}
