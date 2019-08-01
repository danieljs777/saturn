<!--// 


function add_OpenerSelect( Elem, Text, Value) {
		var op=opener;
		if (op) {
			if (eval(Elem)) {
				var vObj = eval(Elem); 
				var i = vObj.length++
				vObj[i].text=Text; 
				vObj[i].value=Value;
			}
		}
}

/*
##############################################################################################################
*/

function transfer_Selects(fBox, tBox) {
    var arrFBox = new Array();
    var arrTBox = new Array();
    var arrLookup = new Array();
    var i;
    for (i = 0; i < tBox.options.length; i++) {
        arrLookup[tBox.options[i].text] = tBox.options[i].value;
        arrTBox[i] = tBox.options[i].text;
    }
    var fLength = 0;
    var tLength = arrTBox.length;
    for(i = 0; i < fBox.options.length; i++) {
        arrLookup[fBox.options[i].text] = fBox.options[i].value;
        if (fBox.options[i].selected && fBox.options[i].value != "") {
            arrTBox[tLength] = fBox.options[i].text;
            tLength++;
        } else {
        arrFBox[fLength] = fBox.options[i].text;
        fLength++;
       }
    }
//    arrFBox.sort();
    arrTBox.sort();
    fBox.length = 0;
    tBox.length = 0;
    var c;
    for(c = 0; c < arrFBox.length; c++) {
        var no = new Option();
        no.value = arrLookup[arrFBox[c]];
        no.text = arrFBox[c];
        fBox[c] = no;
    }
    for(c = 0; c < arrTBox.length; c++) {
        var no = new Option();
        no.value = arrLookup[arrTBox[c]];
        no.text = arrTBox[c];
        tBox[c] = no;
   }
}


//-->