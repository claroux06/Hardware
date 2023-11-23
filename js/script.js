function darkmode() {
    $("body").toggleClass("dark-mode")
    $("#button").toggleClass("dark-button")
    $(".card").toggleClass("dark-card")


    if ($('body').hasClass('dark-mode')){
        $(".light-title").css('display', 'none');
        $(".dark-title").css('display', 'block');
        setCookie("darkmode", "on", 365); // set cookie to expire in 365 days
    } else{
        $(".light-title").css('display', 'block');
        $(".dark-title").css('display', 'none');
        setCookie("darkmode", "off", 365); // set cookie to expire in 365 days
    }

}

// fonction pour créer un cookie
  function setCookie(name, value, days) {
    var expires = "";
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
  }

  // fonction pour lire un cookie
  function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  }
  
  // initialisation du dark mode selon la valeur du cookie
  var darkmodeCookie = getCookie("darkmode");
  if (darkmodeCookie != null) {
    if (darkmodeCookie == "on") {
      darkmode();
    }
  }