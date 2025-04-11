// Window scroll sticky class add
function windowScroll() {
  const navbar = document.getElementById("navbar");
  if (document.body.scrollTop >= 50 || document.documentElement.scrollTop >= 50) {
    navbar.classList.add("nav-sticky");
  } else {
    navbar.classList.remove("nav-sticky");
  }
}

window.addEventListener("scroll", (ev) => {
  windowScroll();
});

// Scroll suave + clase activa en navbar
document.querySelectorAll('#navbar-navlist a[href^="#"]').forEach((link) => {
  link.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) {
      target.scrollIntoView({ behavior: "smooth", block: "start" });
    }

    document.querySelectorAll("#navbar-navlist li").forEach((li) => li.classList.remove("active"));
    this.parentElement.classList.add("active");
  });
});

window.addEventListener("scroll", () => {
  const sections = document.querySelectorAll("section[id]");
  const scrollPos = window.scrollY + 80;

  sections.forEach((section) => {
    const top = section.offsetTop;
    const height = section.offsetHeight;
    const id = section.getAttribute("id");

    if (scrollPos >= top && scrollPos < top + height) {
      document.querySelectorAll("#navbar-navlist li").forEach((li) => li.classList.remove("active"));
      const activeLink = document.querySelector(`#navbar-navlist a[href="#${id}"]`);
      if (activeLink && activeLink.parentElement) {
        activeLink.parentElement.classList.add("active");
      }
    }
  });
});

// Preloader
function preloader() {
  setTimeout(() => {
    document.getElementById("preloader").style.visibility = "hidden";
    document.getElementById("preloader").style.opacity = "0";
  }, 500);
}

// Fade in error
function fadeIn() {
  var fade = document.getElementById("error-msg");
  var opacity = 0;
  var intervalID = setInterval(function () {
    if (opacity < 1) {
      opacity += 0.5;
      fade.style.opacity = opacity;
    } else {
      clearInterval(intervalID);
    }
  }, 200);
}

// Tema claro/oscuro
if (sessionStorage.getItem("theme") === "light") {
  document.body.setAttribute("data-bs-theme", "light");
} else if (sessionStorage.getItem("theme") === "dark") {
  document.body.setAttribute("data-bs-theme", "dark");
}

if (document.body.getAttribute("data-bs-theme") === "light") {
  sessionStorage.setItem("theme", "light");
} else if (document.body.getAttribute("data-bs-theme") === "dark") {
  sessionStorage.setItem("theme", "dark");
}

const btn = document.getElementById("mode");
btn.addEventListener("click", () => {
  let theme = sessionStorage.getItem("theme");
  if (theme === "light" || theme === "") {
    document.body.setAttribute("data-bs-theme", "dark");
    sessionStorage.setItem("theme", "dark");
  } else {
    document.body.removeAttribute("data-bs-theme");
    sessionStorage.setItem("theme", "light");
  }
});

// Splide vertical
/* new Splide(".splide", {
  direction: "ttb",
  height: "30rem",
  wheel: true,
}).mount();
 */
preloader();
