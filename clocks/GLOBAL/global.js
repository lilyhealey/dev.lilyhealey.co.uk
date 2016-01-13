



//  Force getElementById to work

if(!document.getElementById) {
	if(document.all) {
		document.getElementById = function() {
			if(typeof document.all[arguments[0]] != "undefined") {
				return document.all[arguments[0]];
			} else {
				return null;
			}
		}
	} else if(document.layers) {
		document.getElementById = function() {
			if(typeof document[arguments[0]] != "undefined") {
				return document[arguments[0]];
			} else {
				return null;
			}
		}
	}
}




//  Alternative to "voided" links

function Hello() {
	// Empty function
}




//  Show and Hide objects

function objectShow(id) {
	document.getElementById(id).style.visibility = "visible";
}
function objectHide(id) {
	document.getElementById(id).style.visibility = "hidden";
}




//  Popup windows

function windowPop(url, id, width, height) {
	//var x = ((screen.width / 2) - ((width) / 2));
	//var y = ((screen.height / 2) - ((height) / 2));
	var x = (screen.width - width-30);
	var y = (screen.height - height-50);
	windowNew = window.open(url,id,"width="+ width +",height="+ height +",scrollbars=no,resizable=no,left="+ x +",top="+ y);
	windowNew.focus();
}




//  List Box Handler

function listBoxToggle(command) { 

	if (document.getElementById("listBox")) {

		if (command) {

			objectHide('listIndicator');
			objectShow('listBox');

		} else {

			objectHide('listBox');
			objectShow('listIndicator');
		}
	}
}







