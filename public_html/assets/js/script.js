function getPatient() {
    var list = document.getElementById("listPatient");
    document.getElementById("patient").value = list.options[list.selectedIndex].text;
}


function getDate() {
    var today = new Date();
    var dd = today.getUTCDate();
    var mm = today.getMonth() + 1; // January is 0
    var yyyy = today.getFullYear();

    document.getElementById("date").value = dd + "/" + mm + "/" + yyyy;
}

function validateFormSignIn() {
    var falseField = 0;
    if (!validateFormEmail()) {
        ++falseField;
    }

    if (!validateFormPassword()) {
        ++falseField;
    }
    return falseField <= 0;
}


function validateFormSignUp() {
    var falseField = 0;
    if (!validateFirstname()) {
        ++falseField;
    }
    if (!validateLastname()) {
        ++falseField;
    }
    if (!validateFormEmail()) {
        ++falseField;
    }
    if (!validateFormPassword()) {
        ++falseField;
    }

    return falseField <= 0;
}

function validateFormEmail() {
    var x = document.forms["formSign"]["email"].value;
    var element = document.getElementById('email');
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length) {
        //alert("Not a valid e-mail address");
        element.innerHTML = 'Not a valid e-mail address';
        document.getElementById('emailDiv').className = "has-error";
        return false;
    } else {
        document.getElementById('emailDiv').className = "has-success";
        return true;
    }
}

function validateFormPassword() {
    var element = document.getElementById('password');
    var nb = element.value.length;
    if (nb < 4) {
        element.innerHTML = 'Password must contain at least 4 letters';
        document.getElementById('passwordDiv').className = "has-error";
        return false;
    } else {
        document.getElementById('passwordDiv').className = "has-success";
        return true;
    }
}

function validateLastname() {
    var element = document.getElementById('lastname');
    if (element.value == "") {
        //document.getElementById('message').innerHTML = 'Lastname must be filled out';
        document.getElementById('lastnameDiv').className = "has-error";
        return false;
    } else {
        document.getElementById('lastnameDiv').className = "has-success";
        return true;
    }
}

function validateFirstname() {
    var element = document.getElementById('firstname');
    if (element.value == "") {
        //document.getElementById('message').innerHTML = 'Firstname must be filled out';
        document.getElementById('firstnameDiv').className = "has-error";
        return false;
    } else {
        document.getElementById('firstnameDiv').className = "has-success";
        return true;
    }
}

function showHelp(arg) {
    var objtTo = null;
    var newdiv = null;

    switch (arg) {
        case 'email':
            objtTo = document.getElementById('container');
            newdiv = document.createElement('p');
            newdiv.id = 'child';
            newdiv.innerHTML = "Email as example@ece.fr";
            objtTo.appendChild(newdiv);
            //document.getElementById('help').innerHTML = 'Email as aa@mail.com';
            break;
        case 'password':
            objtTo = document.getElementById('containerP');
            newdiv = document.createElement('p');
            newdiv.id = 'child';
            newdiv.innerHTML = "The password must contains at least 4 characters.";
            objtTo.appendChild(newdiv);
            //document.getElementById('help').innerHTML = 'Must contains at least 4 characters';
            break;
    }
}

function hideHelp(arg) {
    //alert('hide arg');
    //var bool = false;
    switch (arg) {
        case 'email':
            var objTo = document.getElementById('container');
            //bool = validateFormEmail();
            break;
        case 'password':
            var objTo = document.getElementById('containerP');
            //bool = validateFormPassword();
            break;
        default :
            break;
    }

    if(objTo)
        removeNode(objTo);

    //return bool;
}


function insertAfter(newElement, afterElement) {
    var parent = afterElement.parentNode;

    if (parent.lastChild === afterElement) { // Si le dernier élément est le même que l'élément après lequel on veut insérer, il suffit de faire appendChild()
        parent.appendChild(newElement);
    } else { // Dans le cas contraire, on fait un insertBefore() sur l'élément suivant
        parent.insertBefore(newElement, afterElement.nextSibling);
    }
}

function removeNode(element){
    while(element.lastChild){
        element.removeChild(element.lastChild);
    }
}

function loadFunction(){
    $('#update').load("/Profile/vimeoLikes #vimeo");
}

/*function refreshDiv()
 {
 var xmlhttp;

 if (window.XMLHttpRequest)
 {// code for IE7+, Firefox, Chrome, Opera, Safari
 xmlhttp=new XMLHttpRequest();
 }
 else
 {// code for IE6, IE5
 xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
 }
 var url='/profile/vimeoLikes';


 xmlhttp.open('GET',url,false);
 xmlhttp.send();
 document.getElementById('update').innerHTML = xmlhttp.responseText;
 }*/
