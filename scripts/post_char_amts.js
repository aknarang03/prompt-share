function checkPrompt(form) {
    const pattern = /^[\s\S]{10,300}$/.test(form.prompt.value) 
    // match any whitespace and non whitespace char 10-300 times
    var result = (pattern) ? "Valid" : "Invalid"
    if (result == "Valid") { return true }
    else { 
        alert ("Please enter 10-300 characters")
        return false 
    }
}

function checkWritingResponse(form) {
    const pattern = /^[\s\S]{10,7500}$/.test(form.response.value)
    // match any whitespace and non whitespace char 10-7500 times
    var result = (pattern) ? "Valid" : "Invalid"
    if (result == "Valid") { return true }
    else { 
        alert ("Please enter 10-7500 characters")
        return false 
    }
}

function checkVote(form) {
    const pattern = /^[\s\S]{0,50}$/.test(form.feedback.value)
    // match any whitespace and non whitespace char 0-50 times
    var result = (pattern) ? "Valid" : "Invalid"
    if (result == "Valid") { return true }
    else { 
        alert ("Please enter 0-50 characters")
        return false 
    }
}