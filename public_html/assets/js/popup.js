

function signin_Button_onClick() {
    var popup = document.getElementById("signin-popup");
    var clsL_popup = popup.classList;
    if(clsL_popup.contains("hidden_popup")){
	clsL_popup.remove("hidden_popup");
	clsL_popup.add("display_popup");
    } else if (clsL_popup.contains("display_popup")){
	clsL_popup.remove("display_popup");
	clsL_popup.add("hidden_popup");
    }
	
}