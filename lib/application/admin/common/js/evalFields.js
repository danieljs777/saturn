﻿function ValidNumber(myfield,e)
{
	var keycode;
	if (window.event) 
		keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (((keycode>47) && (keycode<58) )  || (keycode==8)  || (keycode==9) || (keycode==0)) { return true; }
	else return false;
}
function ValidFloatNumber(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (((keycode>47) && (keycode<58) )  || (keycode==8) || (keycode==9) || (keycode==46) || (keycode==44)) { return true; }
	else return false;
}
function fZIP(fld, milSep, decSep, e) {
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? e.which : e.keyCode;
	if (whichCode == 13 || whichCode == 8 || whichCode == 0 ) return true;  
	key = String.fromCharCode(whichCode);  // Get key value from key code
	if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
	len = fld.value.length;
	for(i = 0; i < len; i++)
	if ((fld.value.charAt(i) != decSep)) break;
	aux = '';
	for(; i < len; i++)
	if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
	//aux += key;
	len = aux.length;
	if (len == 5) fld.value = aux+decSep;
	
}
function fDate(fld, milSep, decSep, e) {
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789/';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? e.which : e.keyCode;
	if (whichCode == 13 || whichCode == 8 || whichCode == 0 ) return true;  
	key = String.fromCharCode(whichCode);  // Get key value from key code
	if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
	len = fld.value.length;
	for(i = 0; i < len; i++)
	if ((fld.value.charAt(i) != decSep)) break;
	aux = '';
	for(; i < len; i++)
	if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
	//aux += key;
	len = aux.length;
	if (len == 1) fld.value = aux;
	if (len == 2) fld.value = aux+decSep;
	if (len == 3) fld.value = aux;
	if (len == 4) fld.value = aux;
	if (len == 5) fld.value = aux+decSep;
	if (len > 5) fld.value = aux;
	
}


function fCurrency(fld, milSep, decSep, e) { 
	var sep = 0; 
	var key = ''; 
	var i = j = 0; 
	var len = len2 = 0; 
	var strCheck = '0123456789'; 
	var aux = aux2 = ''; 
	var whichCode = (window.Event) ? e.which : e.keyCode; 
	if (fld.value.length>9) { // Max character
		return false;
		}
	if (whichCode == 13 || whichCode == 8 || whichCode == 0 ) return true;  
	key = String.fromCharCode(whichCode); // Get key value from key code 
	if (strCheck.indexOf(key) == -1)  return false; // Not a valid key 
	len = fld.value.length; 
	for(i = 0; i < len; i++) 
	if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
	aux = ''; 
	for(; i < len; i++) 
	if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
	aux += key; 
	len = aux.length; 
	if (len == 0) fld.value = ''; 
	if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
	if (len == 2) fld.value = '0'+ decSep + aux; 
	if (len > 2) { 
	aux2 = ''; 
	for (j = 0, i = len - 3; i >= 0; i--) { 
	if (j == 3) { 
	aux2 += milSep; 
	j = 0; 
	} 
	aux2 += aux.charAt(i); 
	j++; 
	} 
	fld.value = ''; 
	len2 = aux2.length; 
	for (i = len2 - 1; i >= 0; i--) 
	fld.value += aux2.charAt(i); 
	fld.value += decSep + aux.substr(len - 2, len); 
	} 
	return false; 
} 

function isEmpty(pStrText){
	var	len = pStrText.length;
	var pos;
	var vStrnewtext = "";

	for (pos=0; pos<len; pos++){
		if (pStrText.substring(pos, (pos+1)) != " "){
			vStrnewtext = vStrnewtext + pStrText.substring(pos, (pos+1));
		}
	}

	if (vStrnewtext.length > 0)
		return false;
	else
		return true;
}

