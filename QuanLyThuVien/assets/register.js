document.addEventListener("DOMContentLoaded", function () {
  const emailField = document.getElementById("emailField");
  const phoneField = document.getElementById("phoneField");
  const radios = document.querySelectorAll('input[name="signup_method"]');

  // Chuyển đổi giữa đăng ký bằng email hoặc số điện thoại
  radios.forEach((radio) => {
    radio.addEventListener("change", () => {
      if (radio.value === "email") {
        emailField.style.display = "block";
        emailField.required = true;
        phoneField.style.display = "none";
        phoneField.required = false;
        phoneField.value = "";
      } else {
        phoneField.style.display = "block";
        phoneField.required = true;
        emailField.style.display = "none";
        emailField.required = false;
        emailField.value = "";
      }
    });
  });
});
