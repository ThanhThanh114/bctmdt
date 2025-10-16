// -------------------- GHẾ --------------------
const seatGrid = document.getElementById('seatGrid');
const totalSeats = 34;
const seatPrice = 200000;
let selectedSeats = [];

function renderSeats() {
  for (let i = 1; i <= totalSeats; i++) {
    const seat = document.createElement('div');
    seat.classList.add('seat');
    seat.textContent = "A" + String(i).padStart(2, '0');

    // Random ghế đã bán
    if (Math.random() < 0.2) {
      seat.classList.add('sold');
    }

    seat.addEventListener('click', () => toggleSeat(seat));
    seatGrid.appendChild(seat);
  }
}

function toggleSeat(seat) {
  if (seat.classList.contains('sold')) return;
  seat.classList.toggle('selected');
  updateSelectedInfo();
}

function updateSelectedInfo() {
  selectedSeats = [...document.querySelectorAll('.seat.selected')].map(s => s.textContent);

  document.getElementById('selectedSeatsText').textContent = "Số lượng ghế: " + selectedSeats.length;
  document.getElementById('seatList').textContent = "Số ghế: " + (selectedSeats.length > 0 ? selectedSeats.join(", ") : "-");

  let total = selectedSeats.length * seatPrice;
  document.getElementById('ticketPrice').textContent = total.toLocaleString("vi-VN") + "đ";
  document.getElementById('totalPrice').textContent = total.toLocaleString("vi-VN") + "đ";
  document.getElementById('grandTotal').textContent = total.toLocaleString("vi-VN") + "đ";
}

renderSeats();

// -------------------- DROP SELECT --------------------
document.getElementById('drop').addEventListener('change', e => {
  document.getElementById('dropPoint').textContent = e.target.options[e.target.selectedIndex].text;
});

// -------------------- PAY BTN --------------------
document.getElementById('payBtn').addEventListener('click', () => {
  if (selectedSeats.length === 0) {
    alert("Vui lòng chọn ít nhất 1 ghế!");
    return;
  }
  if (!document.getElementById('fullname').value || !document.getElementById('phone').value || !document.getElementById('email').value) {
    alert("Vui lòng nhập đầy đủ thông tin khách hàng!");
    return;
  }
  if (!document.getElementById('terms').checked) {
    alert("Vui lòng chấp nhận điều khoản!");
    return;
  }
  alert("Đặt vé thành công!\nGhế: " + selectedSeats.join(", ") + "\nTổng tiền: " + (selectedSeats.length * seatPrice).toLocaleString("vi-VN") + "đ");
});
