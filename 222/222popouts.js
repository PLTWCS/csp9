
/**
 * CSP Activity 2.2.2 IntroducingPHP
 * 
 * 222indexB.php models use of PHP in conjunction with MySQL, JavaScript, and CSS
 * @copyright 2014 Project Lead The Way, Inc.
 * 
 */
 
var popping = "";
function hideDetailedView() {
	var unpopped = document.getElementById("popout");
    unpopped.innerHTML = "";
    unpopped.id = popping;
}

function showDetailedView(currentDiv, firstname) {
	var popframe = document.getElementById(currentDiv);
    popping = popframe.id;
	popframe.id = "popout";
	popframe.innerHTML = "<br /><TABLE><TR><TH rowspan='3'><img src='http://" + window.location.hostname + 
							"/aprilla/pic1.jpg' width='250'><TH align='left'>Artist's Name: <TH align='left'>" + 
							firstname + "<TR><TH align='left'>File Name: <TH align='left'>pic1.jpg<TR></TABLE> ";
    var nameholder = "popout";
	var unpopped = document.getElementById(nameholder);
    unpopped.onmouseout = hideDetailedView;
}
/*
    
    
*/