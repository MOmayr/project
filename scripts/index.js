
var app = angular.module('App', ['ngMaterial', 'ui.router']);

app.config(function ($mdThemingProvider) {
    $mdThemingProvider.theme('default')
        .primaryPalette('light-blue')
        .accentPalette('orange')
        .backgroundPalette('grey')
    // .dark()
});

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("scrollToTopBtn").style.display = "block";
    } else {
        document.getElementById("scrollToTopBtn").style.display = "none";
        document.documentElement.classList.remove("scroll");
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
    // console.log(document.documentElement.classList);
    // document.documentElement.classList.add("scroll");
}

function loadScript(url, entity, callback) {
    // if(executeCallbackFirst){
    //     callback();
    // }
    if(entity){
        callback();
        return;
    }
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = url;
    script.onload = callback;
    document.body.appendChild(script);
}

function loadStyle(href, entity, callback) {
    if(entity){
        return;
    }
    var link = document.createElement("link");
    link.rel = "stylesheet";
    link.type = "text/css";
    link.href = href;
    link.onload = callback;
    document.body.appendChild(link);
}

// red
// pink
// purple
// deep-purple
// indigo
// blue
// light-blue
// cyan
// teal
// green
// light-green
// lime
// yellow
// amber
// orange
// deep-orange
// brown
// grey
// blue-grey