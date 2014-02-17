// JavaScript Document
// HP_functions.js
// Author: Ron Fredericks, LectureMaker LLC
// Last Updated: 7/10/2013

function count_down(msgTag, msgSpan)
{
    // display the count down of seconds within the text when time is greater than 0
    var sec = $(msgTag+" "+msgSpan).text() || 0;
    var timer = setInterval(function() { 
       $(msgTag+" "+msgSpan).text(--sec);
       if (sec <= 0) {
          $(msgTag).fadeOut('fast');
          clearInterval(timer);
       } 
    }, 1000);
}

function load_login(user)
// stuff Login Form with valid user credentials
{
    if (user == "Ron Fredericks") {
        $('#username').focus(function() {
        this.value="Ron Fredericks";
        });
        $('#username').focus();
        $('#password').focus(function() {
        this.value="rf";
        });
        $('#password').focus();    
    } 
    else if (user == "Tommy Tuba") {
        $('#username').focus(function() {
        this.value="Tommy Tuba";
        });
        $('#username').focus();
        $('#password').focus(function() {
        this.value="tt";
        });
        $('#password').focus();            
    }
    else if (user == "Admin") {
        $('#username').focus(function() {
        this.value="Admin";
        });
        $('#username').focus();
        $('#password').focus(function() {
        this.value="aStrongPassword";
        });
        $('#password').focus();    
    }
}

function login_form(form)
{
    var x=form.username;
    if (!x.value) {
        alert("Username must be filled out");
        x.focus();
        return false;
    }
    var y=form.password;
    if (!y.value) {
        alert("Password must be filled out");
        y.focus();
        return false;
    }    
    return true;
}

function open_window(url, target)
// Open a browser window
//      url is web address
//      target specified the attribute or name of the window
//          _blank - URL is loaded into a new window. This is default
//          _parent - URL is loaded into the parent frame
//          _self - URL replaces the current page
//          _top - URL replaces any framesets that may be loaded
//          name - The name of the window
{
    window.open(url, target);
}

function update_background_image(imgLink)
// update css body background image
{
        $("body").css("background", "url('" + imgLink + "')");
}

function update_id_text(id, text)
// update css id text
{
        //$(div).css(id, text);
        $("#"+id).text(text);
}
