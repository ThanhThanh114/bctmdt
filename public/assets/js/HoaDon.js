// ===============================
// Hóa đơn FUTA - JS xử lý
// ===============================

document.addEventListener("DOMContentLoaded", () => {
  const printBtn = document.getElementById("printBtn");
  const pdfBtn = document.getElementById("pdfBtn");
  const downloadBtn = document.getElementById("downloadBtn");
  const emailBtn = document.getElementById("emailBtn");
  const applyBtn = document.getElementById("applyBtn");

  const invoiceCard = document.getElementById("invoiceCard");

  // ===============================
  // IN HÓA ĐƠN
  // ===============================
  printBtn?.addEventListener("click", () => {
    window.print();
  });

  // ===============================
  // TẢI PDF
  // ===============================
  pdfBtn?.addEventListener("click", async () => {
    const { jsPDF } = window.jspdf;
    const canvas = await html2canvas(invoiceCard);
    const imgData = canvas.toDataURL("image/png");

    const pdf = new jsPDF("p", "mm", "a4");
    const width = pdf.internal.pageSize.getWidth();
    const height = (canvas.height * width) / canvas.width;

    pdf.addImage(imgData, "PNG", 0, 0, width, height);
    pdf.save("HoaDon.pdf");
  });

  // ===============================
  // TẢI ẢNH
  // ===============================
  downloadBtn?.addEventListener("click", async () => {
    const canvas = await html2canvas(invoiceCard);
    const link = document.createElement("a");
    link.download = "HoaDon.png";
    link.href = canvas.toDataURL("image/png");
    link.click();
  });

  // ===============================
  // GỬI EMAIL (demo)
  // ===============================
  emailBtn?.addEventListener("click", () => {
    alert("📧 Tính năng gửi email chỉ là demo!");
  });

  // ===============================
  // PANEL CHỈNH SỬA
  // ===============================
  applyBtn?.addEventListener("click", () => {
    document.getElementById("custName").innerText = document.getElementById("inpName").value;
    document.getElementById("custPhone").innerText = document.getElementById("inpPhone").value;
    document.getElementById("custEmail").innerText = document.getElementById("inpEmail").value;
    document.getElementById("route").innerText = document.getElementById("inpRoute").value;
    document.getElementById("tripDate").innerText = document.getElementById("inpDate").value;
    document.getElementById("tripTime").innerText = document.getElementById("inpTime").value;
    document.getElementById("seatNo").innerText = document.getElementById("inpSeat").value;

    // Tính toán tiền vé
    const price = parseInt(document.getElementById("inpPrice").value) || 0;
    const fee = parseInt(document.getElementById("inpFee").value) || 0;
    const vat = parseInt(document.getElementById("inpVat").value) || 0;
    const discount = parseInt(document.getElementById("inpDiscount").value) || 0;

    const subTotal = price + fee;
    const tax = Math.round((subTotal * vat) / 100);
    const grandTotal = subTotal + tax - discount;

    // Update UI
    document.getElementById("subTotal").innerText = subTotal.toLocaleString("vi-VN") + "₫";
    document.getElementById("tax").innerText = tax.toLocaleString("vi-VN") + "₫";
    document.getElementById("discount").innerText = discount.toLocaleString("vi-VN") + "₫";
    document.getElementById("grandTotal").innerText = grandTotal.toLocaleString("vi-VN") + "₫";
  });
});
