document.addEventListener("DOMContentLoaded", () => {
  const togglePass = document.getElementById("togglePass");
  const password = document.getElementById("password");
  const form = document.getElementById("loginForm");
  const btn = document.getElementById("loginBtn");

  // Ẩn / hiện mật khẩu
  if (togglePass && password) {
    togglePass.addEventListener("click", () => {
      const isHidden = password.type === "password";
      password.type = isHidden ? "text" : "password";
      togglePass.textContent = isHidden ? "🙈" : "👁";
    });
  }

  // Hiệu ứng khi đang đăng nhập
  if (form && btn) {
    form.addEventListener("submit", () => {
      // Thêm class loading
      btn.classList.add("loading");
      // Disable nút
      btn.disabled = true;
      // Đổi nội dung nút
      btn.textContent = "Đang đăng nhập...";
    });
  }
});
