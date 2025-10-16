document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formLienHe");

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    alert("✅ Thông tin liên hệ của bạn đã được gửi thành công (Demo).");
    form.reset();
  });
});
