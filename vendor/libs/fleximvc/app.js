function get_meta(metaName) {
  try {
    return document.getElementsByName(metaName)[0].content;
  } catch {
    return;
  }
}
function izitost(title = "", type = "", msg = "") {
  if (type == "success") iziToast.success({ title: title, message: msg });
  else if (type == "info") iziToast.info({ title: title, message: msg });
  else if (type == "warning") iziToast.warning({ title: title, message: msg });
  else if (type == "danger") iziToast.error({ title: title, message: msg });
  else iziToast.show({ title: title, message: msg });
}
function stoastr2(msg, type) {
  Swal.fire({
    position: "top-end",
    icon: type,
    title: msg,
    showConfirmButton: false,
    timer: 3000,
  });
}
function toastr(msg = "", type = "bg-info", duration = 3000) {
  let gravity = "bottom";
  let position = "center";
  switch (type) {
    case "bg-danger":
      gravity = "top";
      break;
    case "bg-warning":
      gravity = "top";
      break;
  }

  Toastify({
    text: msg,
    className: type,
    duration: duration,
    gravity: gravity,
    position: position,
  }).showToast();
}
function stoastr(msg, type) {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener("mouseenter", Swal.stopTimer);
      toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
  });

  Toast.fire({
    icon: type,
    title: msg,
  });
}
function redirect(msg, url) {
  // toastr(msg + ". Se redirecciona en 10 segundos", "bg-info");
  stoastr(msg + ". Se redirecciona en 3 segundos", "danger");
  setTimeout(function () {
    window.location.href = url;
  }, 3000);
}
function calculate_age(birth) {
  var today = new Date();
  var birthday = new Date(birth);
  var age = today.getFullYear() - birthday.getFullYear();
  var m = today.getMonth() - birthday.getMonth();

  if (m < 0 || (m === 0 && today.getDate() < birthday.getDate())) {
    age--;
  }

  return age;
}
function get_action(code, msg, url = null) {
  switch (code) {
    case 550:
      redirect(msg, url);
      break;
    default:
      toastr(`${msg}. Code= ${code}`, "bg-danger");
      break;
  }
}
function pad_left(value, length) {
  return value.toString().length < length ? this.padLeft("0" + value, length) : value;
}
function set_time(fecha, time, minutes = 30) {
  let fecha_temp = `${fecha} ${time}`,
    currentDateObj = new Date(fecha_temp),
    numberOfMlSeconds = currentDateObj.getTime(),
    addMlSeconds = minutes * 60000,
    newDateObj = new Date(numberOfMlSeconds + addMlSeconds),
    h = newDateObj.getHours().toString().padStart(2, "0"),
    m = newDateObj.getMinutes().toString().padStart(2, "0"),
    time_ok = `${h}:${m}`;
  return time_ok;
}
function comprobar_nan(number) {
  if (number == NaN || number == "undefined" || number == "") {
    return 0;
  } else {
    return parseFloat(number);
  }
}
function profitability(a, p) {
  let x100 = p / 100;
  return a * x100;
}
function x100(a, p) {
  let x100 = p / 100;
  return a * x100;
}

var uri = get_meta("uri");
var csrf = get_meta("csrf");
var urlimages = get_meta("urlimages");
var date_js = get_meta("date_js");

// Visor de password
document.addEventListener("DOMContentLoaded", function () {
  Array.from(document.querySelectorAll("form .auth-pass-inputgroup")).forEach(function (e) {
    Array.from(e.querySelectorAll(".password-addon")).forEach(function (r) {
      r.addEventListener("click", function () {
        var o = e.querySelector(".password-input");
        o.type = o.type === "password" ? "text" : "password";
      });
    });
  });
});
