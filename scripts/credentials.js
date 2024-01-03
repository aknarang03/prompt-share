function checkReal(field){ // make sure input email fits pattern of an actual email
    const dot = field.indexOf(".") > 0
    const at = field.indexOf("@") > 0
    const pattern = /[a-zA-z0-9._]+@[a-zA-z0-9._]+\.[a-zA-Z]{2,6}/.test(field)
    return (dot && at && pattern) ? "Valid" : "Invalid Email Address"
}

function validateEmail (form) {
    if ((form.email.value) == "") {
        // if user entered nothing and pressed button
        alert ("Please enter an email")
        return false
    }
    var result = checkReal (form.email.value)
    if (result == "Valid") return true
    else { 
        alert (result); 
        return false 
    }
}

function checkChars(field){ // make sure input username contains only valid characters and correct amount
    const pattern = /^[a-zA-z0-9_\.\-]{2,32}$/.test(field)
    return (pattern) ? "Valid" : "Invalid Username"
}

function checkPwChars(field){ // make sure input password contains only valid characters and correct amount
    const pattern = /^[a-zA-z0-9@#$%&*!\-]{6,32}$/.test(field)
    return (pattern) ? "Valid" : "Invalid Password"
}

function validateUsername (form) { // validate username specifically
    if ((form.username.value) == "") {
        alert ("Please enter a username")
        return false
    }
    var result = checkChars (form.username.value)
    if (result == "Valid") return true
    else { 
        alert (result + "\nUsername should contain 2-32 letters, numbers, and/or chars _-."); 
        return false 
    }
}

function validatePassword (form) { // validate password specifically
    if ((form.password.value) == "") {
        alert ("Please enter a password")
        return false
    }
    var result = checkPwChars (form.password.value)
    if (result == "Valid") return true
    else {
        alert (result + "\nPassword should contain 6-32 letters, numbers, and/or chars @#$%&*!-"); 
        return false
    }
}

function validate(form) {
    if ((form.email.value) == "") {
        alert ("Please enter an email")
        return false
    } else if ((form.username.value) == "") {
        alert ("Please enter a username")
        return false
    } else if ((form.password.value) == "") {
        alert ("Please enter a password")
        return false
    }
    var result = checkChars (form.username.value)
    result += checkReal (form.email.value)
    result += checkPwChars (form.password.value)
    if (result == "ValidValidValid") return true
    else {
        alert ("Please check validity of email, username, password\nUsername should contain 2-32 letters, numbers, and/or chars _-.\nPassword should contain 6-32 letters, numbers, and/or chars @#$%&*!-"); 
        return false 
    }
}