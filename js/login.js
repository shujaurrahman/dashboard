const form = document.querySelector(".login form"),
  submitBtn = form.querySelector(".submitButton"),
  errorText = form.querySelector(".error-text");

form.onsubmit = (e) => {
  e.preventDefault();
}
submitBtn.onclick = () => {
  submitBtn.disabled = true;
  submitBtn.value = "Processing...";
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./backend/login.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      
      if (xhr.status === 200) {
        let data = xhr.response;
        if (data === "success") {
          location.href = "dashboard.php";
        } else {
          errorText.style.display = "block";
          errorText.textContent = data;
          submitBtn.disabled = false;
          submitBtn.value = "Login";
          setTimeout(() => {
            errorText.style.display = "none";
          }, 5000);
        }
      }

    }
  };
  let formData = new FormData(form);
  xhr.send(formData);
}
