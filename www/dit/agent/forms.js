function formhash(form, password) {
   // Create a new element input, this will be out hashed password field.
   var p = document.createElement("input");
   // Add the new element to our form.
   form.appendChild(p);
   p.name = "p";
   p.type = "hidden"
   p.value = hex_sha512(password.value);
   // Make sure the plaintext password doesn't get sent.
   password.value = "";
   // Finally submit the form.
   form.submit();
}

function reghash(form, password, rpassword, email, uname) {
   var atpos=email.value.indexOf("@");
   var dotpos=email.value.lastIndexOf(".");
   if(password.value != rpassword.value){
        alert("password and confirmation password do not match.");
   }else if(atpos<1 || dotpos<atpos+2 || dotpos+2>=email.value.length){
        alert("Not a valid e-mail address.");
   }else if(uname.value == ""){
        alert("Empty Username!");
   }else if(password.value.length < 6 && password.value.length > 0){
        alert("Password must be not less than 6 characters!");
   }else{

   // Create a new element input, this will be out hashed password field.
   var p = document.createElement("input");
   // Add the new element to our form.
   form.appendChild(p);
   p.name = "p";
   p.type = "hidden"
   p.value = password.value;
   // Make sure the plaintext password doesn't get sent.
   password.value = "";
   // Finally submit the form.
   form.submit();
   }
}

function userhash(form, password, rpassword, email, uname ,type, subagent) {
   var atpos=email.value.indexOf("@");
   var dotpos=email.value.lastIndexOf(".");
   if(password.value != rpassword.value){
        alert("password and confirmation password do not match.");
   }else if(atpos<1 || dotpos<atpos+2 || dotpos+2>=email.value.length){
        alert("Not a valid e-mail address.");
   }else if(uname.value == ""){
        alert("Empty Username!");
   }else if(password.value.length < 6){
        alert("Password must be not less than 6 characters!");
   }else{

   // Create a new element input, this will be out hashed password field.
   var p = document.createElement("input");
   // Add the new element to our form.
   form.appendChild(p);
   p.name = "p";
   p.type = "hidden"
   p.value = password.value;
   // Make sure the plaintext password doesn't get sent.
   password.value = "";
   // Finally submit the form.
   form.submit();
   }
}
