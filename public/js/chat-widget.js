/**
 * Chat Widget - Fixed Version
 */

class ChatWidget {
    constructor() {
        this.isOpen = false;
        this.isTyping = false;
        this.sessionId = 'chat_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        this.chatHistory = this.loadChatHistory();
        this.currentTrip = null;

        if (window.chatWidget) {
            return window.chatWidget;
        }

        this.init();
        window.chatWidget = this;
    }

init() {
    console.log('🚀 Chat Widget khởi tạo...');
    this.createWidget();
    this.bindEvents();
}

bindEvents() {
    // Tìm giá vé
    const priceMatch = message.match(/(?:giá[^:]*:\s*|giá\s+rổ:\s*)?(\d{2,3})k?(?:\/vé|\/người|k)?/gi);

    // Tìm giờ khởi hành
    const timeMatch = message.match(/(?:lúc\s+|chạy\s+lúc\s+)?(\d{1,2})\s*(?:h|giờ)\s*(?:sáng|chiều|tối|trưa)?/i);

    console.log("Price:", priceMatch);
    console.log("Time:", timeMatch);
}

createWidget() {
    this.addStyles();
}

createWidget() {

        const widget = document.createElement('div');
        widget.className = 'chat-widget';
        widget.innerHTML = this.getWidgetHTML();

        document.body.appendChild(widget);

        this.chatButton = document.querySelector('.chat-button');
        this.chatBox = document.querySelector('.chat-box');
        this.chatClose = document.querySelector('.chat-close');
        this.chatMessages = document.querySelector('.chat-messages');
        this.chatInput = document.querySelector('.chat-input');
        this.chatSend = document.querySelector('.chat-send');

        console.log('✅ Widget HTML created');

        // Load chat history sau khi tạo widget
        this.loadChatMessages();
    }

    addStyles() {
        if (document.getElementById('chat-styles')) return;

        const style = document.createElement('style');
        style.id = 'chat-styles';
        style.textContent = `
            .chat-widget {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 999999;
                font-family: -apple-system, BlinkMacSystemFont, 'Segui UI', Roboto, sans-serif;
            }
            .chat-button {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                color: white;
                font-size: 28px;
            }
            .chat-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 25px rgba(102, 126, 234, 0.6);
            }
            .chat-box {
                position: absolute;
                bottom: 80px;
                right: 0;
                width: 380px;
                height: 500px;
                background: white;
                border-radius: 15px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
                display: none;
                flex-direction: column;
                overflow: hidden;
                opacity: 0;
                transform: scale(0.8) translateY(20px);
                transition: all 0.3s ease;
            }
            .chat-box.active {
                display: flex;
                opacity: 1;
                transform: scale(1) translateY(0);
            }
            .chat-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 20px;
                text-align: center;
                position: relative;
            }
            .chat-close {
                position: absolute;
                top: 15px;
                right: 15px;
                background: none;
                border: none;
                color: white;
                font-size: 24px;
                cursor: pointer;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background 0.2s ease;
            }
            .chat-close:hover {
                background: rgba(255, 255, 255, 0.1);
            }
            .chat-header h3 {
                margin: 0;
                font-size: 18px;
                font-weight: 600;
            }
            .chat-header p {
                margin: 5px 0 0 0;
                font-size: 14px;
                opacity: 0.9;
            }
            .chat-messages {
                flex: 1;
                padding: 20px;
                overflow-y: auto;
                background: #f8f9fa;
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            .message {
                max-width: 85%;
                word-wrap: break-word;
                line-height: 1.5;
                font-size: 14px;
                margin-bottom: 10px;
            }
            .message.user {
                align-self: flex-end;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 12px 16px;
                border-radius: 20px 20px 5px 20px;
                margin-left: auto;
            }
            .message.bot {
                align-self: flex-start;
                background: white;
                color: #333;
                padding: 12px 16px;
                border-radius: 20px 20px 20px 5px;
                border: 1px solid #e9ecef;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                margin-right: auto;
                white-space: pre-wrap;
            }
            .typing {
                display: flex;
                gap: 4px;
                align-items: center;
                padding: 8px 0;
            }
            .typing span {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background-color: #999;
                animation: typing 1.4s infinite ease-in-out;
            }
            .typing span:nth-child(2) { animation-delay: -0.32s; }
            .typing span:nth-child(3) { animation-delay: -0.16s; }
            @keyframes typing {
                0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
                40% { transform: scale(1); opacity: 1; }
            }
            .chat-input-area {
                padding: 20px;
                background: white;
                border-top: 1px solid #e9ecef;
                display: flex;
                gap: 10px;
                align-items: flex-end;
            }
            .chat-input {
                flex: 1;
                resize: none;
                border: 2px solid #e9ecef;
                border-radius: 20px;
                padding: 12px 16px;
                font-size: 14px;
                outline: none;
                max-height: 80px;
                min-height: 20px;
                background: white;
                color: #333;
                font-family: inherit;
                line-height: 1.4;
                transition: border-color 0.2s ease;
            }
            .chat-input:focus {
                border-color: #667eea;
            }
            .chat-send {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
                width: 45px;
                height: 45px;
                border-radius: 50%;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                outline: none;
                flex-shrink: 0;
                transition: transform 0.2s ease;
            }
            .chat-send:hover {
                transform: scale(1.05);
            }
            .chat-send:disabled {
                opacity: 0.6;
                cursor: not-allowed;
                transform: none;
            }
            .welcome {
                text-align: center;
                color: #666;
                font-size: 14px;
                padding: 15px;
                background: #f8f9fa;
                border-radius: 10px;
                border: 1px dashed #dee2e6;
                line-height: 1.5;
            }
            .booking-button {
                background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                color: white;
                border: none;
                padding: 12px 20px;
                border-radius: 25px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                margin: 10px 5px;
                transition: all 0.3s ease;
                box-shadow: 0 2px 10px rgba(40, 167, 69, 0.3);
            }
            .booking-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
            }
            .trip-info {
                background: #e3f2fd;
                padding: 15px;
                border-radius: 10px;
                border-left: 4px solid #2196f3;
                margin: 10px 0;
                font-size: 14px;
            }
            .trip-info strong {
                color: #1976d2;
            }
            .booking-button {
                background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                color: white;
                border: none;
                padding: 12px 20px;
                border-radius: 25px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                margin: 10px 5px;
                transition: all 0.3s ease;
                box-shadow: 0 2px 10px rgba(40, 167, 69, 0.3);
            }
            .booking-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
            }
            .trip-info {
                background: #e3f2fd;
                padding: 15px;
                border-radius: 10px;
                border-left: 4px solid #2196f3;
                margin: 10px 0;
                font-size: 14px;
            }
            .trip-info strong {
                color: #1976d2;
            }
            .clear-chat {
                position: absolute;
                top: 15px;
                left: 15px;
                background: none;
                border: none;
                color: white;
                font-size: 16px;
                cursor: pointer;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background 0.2s ease;
            }
            .clear-chat:hover {
                background: rgba(255, 255, 255, 0.1);
            }
        `;
        document.head.appendChild(style);
    }

    getWidgetHTML() {
        return `
            <button class="chat-button" title="Chat với Minh - Tư vấn viên FUTA">
                💬
            </button>
            <div class="chat-box">
                <div class="chat-header">
                    <button class="clear-chat" onclick="window.chatWidget.clearAllChat()" title="Xóa toàn bộ chat">🗑️</button>
                    <button class="chat-close">×</button>
                    <h3>🤖 Minh - Tư vấn viên AI</h3>
                    <p>Hỗ trợ đặt vé xe 24/7</p>
                </div>
                <div class="chat-messages">
                    <div class="welcome">
                        👋 Chào bạn! Mình là <strong>Minh</strong> - tư vấn viên tại đây<br><br>
                        Mình có thể giúp bạn:<br>
                        💬 Tư vấn chuyến xe phù hợp<br>
                        🎫 Hướng dẫn đặt vé online<br>
                        💰 Tư vấn giá vé và khuyến mãi<br>
                        ❓ Giải đáp các thắc mắc khác<br><br>
                        <strong style="color: #667eea;">Thử hỏi mình:</strong><br>
                        "Mình muốn đi từ TP.HCM đến Đà Lạt"<br>
                        "Giá vé từ Hà Nội về Vinh bao nhiêu?"<br><br>
                        Cứ thoải mái hỏi nhé! 😊
                    </div>
                </div>
                <div class="chat-input-area">
                    <textarea class="chat-input" placeholder="Nhập câu hỏi của bạn..." rows="1"></textarea>
                    <button class="chat-send" title="Gửi tin nhắn">➤</button>
                </div>
            </div>
        `;
    }

    bindEvents() {
        this.chatButton.addEventListener('click', () => this.toggleChat());
        this.chatClose.addEventListener('click', () => this.closeChat());
        this.chatSend.addEventListener('click', () => this.sendMessage());

        this.chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });

        this.chatInput.addEventListener('input', () => {
            this.chatInput.style.height = 'auto';
            this.chatInput.style.height = this.chatInput.scrollHeight + 'px';
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.chat-widget') && this.isOpen) {
                this.closeChat();
            }
        });

        console.log('✅ Events bound successfully');
    }

    toggleChat() {
        if (this.isOpen) {
            this.closeChat();
        } else {
            this.openChat();
        }
    }

    openChat() {
        console.log('📖 Opening chat');
        this.isOpen = true;
        this.chatBox.classList.add('active');
        this.chatButton.style.transform = 'rotate(180deg)';

        setTimeout(() => {
            this.chatInput.focus();
            this.scrollToBottom();
        }, 300);
    }

    closeChat() {
        console.log('📕 Closing chat');
        this.isOpen = false;
        this.chatBox.classList.remove('active');
        this.chatButton.style.transform = 'rotate(0deg)';
    }

    async sendMessage() {
        const message = this.chatInput.value.trim();
        if (!message || this.isTyping) return;

        console.log('📤 Sending message:', message);

        this.addMessage(message, 'user');
        this.chatInput.value = '';
        this.chatInput.style.height = 'auto';
        this.showTyping();

        // Phát hiện điểm đi và điểm đến
        this.detectTripInfo(message);

        try {
            const response = await fetch('/BC_TMDT/api/chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    message: message,
                    session_id: this.sessionId
                })
            });

            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }

            const data = await response.json();
            console.log('📨 API response:', data);

            this.hideTyping();

            if (data.error) {
                this.addMessage('Ôi, có lỗi gì đó rồi 😅 Bạn thử lại sau nhé!', 'bot');
            } else {
                const botMessage = data.content || 'Xin lỗi, mình không hiểu lắm. Bạn có thể nói rõ hơn được không? 🤔';
                this.addMessage(botMessage, 'bot');

                // Kiểm tra nếu có danh sách chuyến xe từ API
                if (data.type === 'trip_list' && data.trips && data.trips.length > 0) {
                    this.showTripButtons(data.trips);
                } else {
                    // Kiểm tra nếu bot message chứa thông tin chuyến xe cụ thể
                    this.checkAndShowBookingButtonFromMessage(botMessage);
                }
            }

        } catch (error) {
            console.error('❌ Chat API Error:', error);
            this.hideTyping();
            this.addMessage('Ôi, mạng hình như bị chậm. Bạn thử lại sau vài giây nhé! 📶', 'bot');
        }
    }

    addMessage(content, sender, isBookingButton = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message ' + sender;

        if (isBookingButton) {
            messageDiv.innerHTML = content;
        } else {
            messageDiv.textContent = content;
        }

        this.chatMessages.appendChild(messageDiv);
        this.scrollToBottom();

        // Lưu vào chat history (chỉ lưu text message, không lưu booking button)
        if (!isBookingButton) {
            this.saveChatMessage(content, sender);
        }
    }

    showTyping() {
        if (this.isTyping) return;

        this.isTyping = true;
        this.chatSend.disabled = true;

        const typingDiv = document.createElement('div');
        typingDiv.className = 'message bot';
        typingDiv.id = 'typing-indicator';
        typingDiv.innerHTML = 'Minh đang suy nghĩ <span style="animation: typing 1.4s infinite;">...</span>';

        this.chatMessages.appendChild(typingDiv);
        this.scrollToBottom();
    }

    hideTyping() {
        this.isTyping = false;
        this.chatSend.disabled = false;

        const typingDiv = document.getElementById('typing-indicator');
        if (typingDiv) {
            typingDiv.remove();
        }
    }

    scrollToBottom() {
        this.chatMessages.scrollTop = this.chatMessages.scrollHeight;
    }

    // Lưu chat message vào localStorage
    saveChatMessage(content, sender) {
        this.chatHistory.push({ content, sender, timestamp: Date.now() });
        localStorage.setItem('chatHistory', JSON.stringify(this.chatHistory));
    }

    // Load chat history từ localStorage
    loadChatHistory() {
        const saved = localStorage.getItem('chatHistory');
        return saved ? JSON.parse(saved) : [];
    }

    // Load chat messages từ history
    loadChatMessages() {
        if (this.chatHistory.length === 0) {
            // Hiển thị welcome message nếu chưa có chat
            return;
        }

        // Xóa welcome message
        const welcome = this.chatMessages.querySelector('.welcome');
        if (welcome) welcome.remove();

        // Load các message từ history
        this.chatHistory.forEach(msg => {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message ' + msg.sender;
            messageDiv.textContent = msg.content;
            this.chatMessages.appendChild(messageDiv);
        });

        this.scrollToBottom();
    }

    // Phát hiện thông tin chuyến xe từ tin nhắn
    detectTripInfo(message) {
        const cities = ['hà nội', 'hồ chí minh', 'tp hcm', 'sài gòn', 'đà nẵng', 'cần thơ', 'hải phòng', 'đà lạt', 'nha trang', 'huế', 'quảng ninh', 'vũng tàu', 'phan thiết', 'quy nhon', 'pleiku', 'buôn ma thuột', 'cà mau', 'an giang', 'kiên giang', 'tiền giang', 'long an', 'đồng nai', 'bình dương', 'tây ninh', 'bình phước', 'lâm đồng', 'ninh thuận', 'bình thuận', 'khánh hòa', 'phú yên', 'gia lai', 'đắk lắk', 'đắk nông', 'kon tum', 'quảng nam', 'quảng ngãi', 'bình định', 'thừa thiên huế', 'quảng bình', 'quảng trị', 'hà tĩnh', 'nghệ an', 'thanh hóa', 'ninh bình', 'nam định', 'thái bình', 'hưng yên', 'hà nam', 'vĩnh phúc', 'bắc ninh', 'bắc giang', 'lạng sơn', 'cao bằng', 'hà giang', 'lào cai', 'yên bái', 'sơn la', 'điện biên', 'lai châu', 'hòa bình', 'phú thọ', 'tuyên quang', 'thái nguyên', 'bắc kạn', 'vinh', 'huế', 'đông hà'];

        let detectedCities = [];
        const lowerMessage = message.toLowerCase();

        cities.forEach(city => {
            if (lowerMessage.includes(city)) {
                detectedCities.push(city);
            }
        });

        if (detectedCities.length >= 2) {
            this.currentTrip = {
                from: detectedCities[0],
                to: detectedCities[1]
            };
        }
    }

    // Kiểm tra và hiển thị nút đặt vé từ message
    checkAndShowBookingButtonFromMessage(botMessage) {
        console.log('🎫 Checking for booking button from message:', botMessage);

        // Phân tích thông tin chuyến xe từ bot message
        const tripInfo = this.extractTripInfoFromMessage(botMessage);

        if (tripInfo) {
            console.log('🎯 Will show specific trip booking button in 1s');
            setTimeout(() => {
                this.showSpecificTripBookingButton(tripInfo);
            }, 1000);
        } else {
            console.log('🔄 Falling back to old method');
            // Fallback về method cũ
            this.checkAndShowBookingButton(botMessage);
        }
    }

        // Trích xuất thông tin chuyến xe từ message
        extractTripInfoFromMessage(message) {
            console.log('🔍 Extracting trip info from:', message);
            const info = {};
            
            // Tìm giá vé (Giá vé: 370k/vé, 370k/người, Giá rổ: 370k)
            const priceMatch = message.match(/(?:giá[^:]*:\s*|giá\s+rổ:\s*)?(\d{2,3})k?(?:\/vé|\/người|k)?/gi);
            if (priceMatch) {
                // Extract số từ match string
                const priceStr = priceMatch[0];
                const priceNumber = priceStr.match(/(\d{2,3})/)[1];
                let price = priceNumber;
                if (price.length <= 3) {
                    price = price + '000';
                }
                info.gia_ve = parseInt(price);
                console.log('💰 Found price:', info.gia_ve, 'from:', priceStr);
            }
            
            // Tìm giờ khởi hành (lúc 7 giờ sáng, 7h sáng, chạy lúc 7 giờ sáng)
            const timeMatch = message.match(/(?:lúc\s+|chạy\s+lúc\s+)?(\d{1,2})\s*(?:h|giờ)\s*(?:sáng|chiều|tối|trưa)?/i);
            if (timeMatch) {
                const hour = timeMatch[1];
                info.gio_di = hour.padStart(2, '0') + ':00';
                console.log('🕐 Found time:', info.gio_di, 'from:', timeMatch[0]);
            }
            
            // Tìm ngày (26/09/2025, ngày mai, hôm nay)
            const dateMatch = message.match(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);
            if (dateMatch) {
                const day = dateMatch[1].padStart(2, '0');
                const month = dateMatch[2].padStart(2, '0');
                const year = dateMatch[3];
                info.ngay_di = year + '-' + month + '-' + day;
                console.log('📅 Found date:', info.ngay_di);
            }
            
            // Tìm loại xe
            if (message.includes('giường nằm')) {
                info.loai_xe = 'Giường nằm';
                console.log('🚌 Found bus type:', info.loai_xe);
            } else if (message.includes('limousine')) {
                info.loai_xe = 'Limousine';
                console.log('🚌 Found bus type:', info.loai_xe);
            } else if (message.includes('ghế ngồi')) {
                info.loai_xe = 'Ghế ngồi';
                console.log('🚌 Found bus type:', info.loai_xe);
            }
            
            // Tìm số chỗ còn lại (Còn 10 ghế thôi, còn khoảng 10 ghế)
            const seatMatch = message.match(/còn\s+(?:khoảng\s+)?(\d+)\s+ghế(?:\s+thôi)?/i);
            if (seatMatch) {
                info.so_ve = parseInt(seatMatch[1]);
                console.log('🪑 Found seats:', info.so_ve);
            }
            
            // Tìm tên nhà xe (nhà xe Tây Đô)
            const companyMatch = message.match(/nhà xe\s+([A-ZÀ-Ỹ][a-zA-ZÀ-ỹ\s]*Đô)/i);
            if (companyMatch) {
                info.nha_xe = companyMatch[1].trim();
                console.log('🏢 Found company:', info.nha_xe);
            }
            
            console.log('📊 Extracted info:', info);
            
            // Nếu có ít nhất giá hoặc giờ thì coi như có thông tin
            if (info.gia_ve || info.gio_di) {
                // Thêm thông tin điểm đi/đến từ context nếu có
                if (this.currentTrip) {
                    info.diem_di = this.formatCityName(this.currentTrip.from);
                    info.diem_den = this.formatCityName(this.currentTrip.to);
                }
                
                console.log('✅ Trip info found:', info);
                return info;
            }
            
            console.log('❌ No trip info found in message');
            return null;
        }

    // Hiển thị nút đặt vé với thông tin chuyến cụ thể
    showSpecificTripBookingButton(tripInfo) {
        const now = new Date();
        const defaultDate = now.toISOString().split('T')[0];

        const bookingHTML = `
                <div class="trip-info">
                    <strong>🎫 Thông tin chuyến xe tìm thấy:</strong><br>
                    ${tripInfo.diem_di ? `📍 Từ: ${tripInfo.diem_di}<br>` : ''}
                    ${tripInfo.diem_den ? `📍 Đến: ${tripInfo.diem_den}<br>` : ''}
                    ${tripInfo.ngay_di ? `📅 Ngày: ${tripInfo.ngay_di}<br>` : ''}
                    ${tripInfo.gio_di ? `🕐 Giờ: ${tripInfo.gio_di}<br>` : ''}
                    ${tripInfo.loai_xe ? `🚌 Loại xe: ${tripInfo.loai_xe}<br>` : ''}
                    ${tripInfo.gia_ve ? `💰 Giá vé: ${this.formatPrice(tripInfo.gia_ve)} VND<br>` : ''}
                    ${tripInfo.so_ve ? `🪑 Còn: ${tripInfo.so_ve} ghế<br>` : ''}
                    ${tripInfo.nha_xe ? `🏢 Nhà xe: ${tripInfo.nha_xe}<br>` : ''}
                    <br>
                    <button class="booking-button" onclick="window.chatWidget.bookSpecificTrip(${JSON.stringify(tripInfo).replace(/"/g, '&quot;')})">
                        🎫 Đặt vé chuyến này ngay
                    </button>
                </div>
            `;

        this.addMessage(bookingHTML, 'bot', true);
    }

    // Đặt vé với thông tin cụ thể
    bookSpecificTrip(tripInfo) {
        const params = new URLSearchParams();

        if (tripInfo.diem_di) params.set('diem_di', tripInfo.diem_di);
        if (tripInfo.diem_den) params.set('diem_den', tripInfo.diem_den);
        if (tripInfo.ngay_di) params.set('ngay_di', tripInfo.ngay_di);
        if (tripInfo.gio_di) params.set('gio_di', tripInfo.gio_di);
        if (tripInfo.gia_ve) params.set('gia_ve', tripInfo.gia_ve);
        if (tripInfo.loai_xe) params.set('loai_xe', tripInfo.loai_xe);
        if (tripInfo.nha_xe) params.set('nha_xe', tripInfo.nha_xe);
        if (tripInfo.so_ve) params.set('so_ve', tripInfo.so_ve);

        window.location.href = `/BC_TMDT/DatVe.php?${params.toString()}`;
    }

    // Kiểm tra và hiển thị nút đặt vé (method cũ)
    checkAndShowBookingButton(botMessage) {
        if (this.currentTrip && (botMessage.includes('chuyến') || botMessage.includes('tuyến') || botMessage.includes('xe'))) {
            setTimeout(() => {
                this.showBookingButton();
            }, 1000);
        }
    }

    // Hiển thị nút đặt vé
    showBookingButton() {
        if (!this.currentTrip) return;

        const bookingHTML = `
                <div class="trip-info">
                    <strong>🚌 Thông tin chuyến xe:</strong><br>
                    📍 Từ: ${this.formatCityName(this.currentTrip.from)}<br>
                    📍 Đến: ${this.formatCityName(this.currentTrip.to)}<br>
                    <button class="booking-button" onclick="window.chatWidget.goToBooking()">
                        🎫 Đặt vé ngay
                    </button>
                </div>
            `;

        this.addMessage(bookingHTML, 'bot', true);
    }

    // Hiển thị danh sách chuyến xe từ database
    showTripButtons(trips) {
        let tripHTML = '<div class="trip-info"><strong>🎫 Các chuyến xe tìm thấy:</strong><br><br>';

        trips.forEach((trip, index) => {
            const tripId = trip.trip_id;
            const tripInfo = trip.trip_info;

            tripHTML += `
                    <div style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 8px; background: #f9f9f9;">
                        <strong>Chuyến ${index + 1}: ${tripInfo.ten_xe}</strong><br>
                        📍 ${tripInfo.diem_di} → ${tripInfo.diem_den}<br>
                        🕒 ${tripInfo.ngay_di} - ${tripInfo.gio_di}<br>
                        💰 ${this.formatPrice(tripInfo.gia_ve)} VND<br>
                        <button class="booking-button" onclick="window.chatWidget.bookTrip(${tripId}, '${tripInfo.ten_xe}', '${tripInfo.diem_di}', '${tripInfo.diem_den}', '${tripInfo.ngay_di}', '${tripInfo.gio_di}', ${tripInfo.gia_ve})" 
                                style="margin-top: 8px; font-size: 12px; padding: 8px 15px;">
                            🎫 Đặt vé chuyến này
                        </button>
                    </div>
                `;
        });

        tripHTML += '</div>';
        this.addMessage(tripHTML, 'bot', true);
    }

    // Format tên thành phố
    formatCityName(city) {
        return city.replace(/\b\w/g, l => l.toUpperCase());
    }

    // Format giá tiền
    formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }

    // Chuyển đến trang đặt vé cho chuyến cụ thể
    bookTrip(tripId, tenXe, diemDi, diemDen, ngayDi, gioDi, giaVe) {
        const params = new URLSearchParams({
            trip_id: tripId,
            ten_xe: tenXe,
            diem_di: diemDi,
            diem_den: diemDen,
            ngay_di: ngayDi,
            gio_di: gioDi,
            gia_ve: giaVe
        });

        window.location.href = `/BC_TMDT/DatVe.php?${params.toString()}`;
    }

    // Chuyển đến trang đặt vé
    goToBooking() {
        if (this.currentTrip) {
            const from = encodeURIComponent(this.currentTrip.from);
            const to = encodeURIComponent(this.currentTrip.to);
            window.location.href = `/BC_TMDT/DatVe.php?from=${from}&to=${to}`;
        }
    }

    // Xóa chat history (chỉ khi F5)
    clearChatHistory() {
        this.chatHistory = [];
        localStorage.removeItem('chatHistory');
    }

    // Xóa tất cả chat và reload welcome message
    clearAllChat() {
        if (confirm('Bạn có chắc muốn xóa toàn bộ lịch sử chat?')) {
            this.chatHistory = [];
            localStorage.removeItem('chatHistory');
            this.currentTrip = null;

            // Xóa tất cả messages
            this.chatMessages.innerHTML = '';

            // Thêm lại welcome message
            const welcomeDiv = document.createElement('div');
            welcomeDiv.className = 'welcome';
            welcomeDiv.innerHTML = `
                    👋 Chào bạn! Mình là <strong>Minh</strong> - tư vấn viên tại đây<br><br>
                    Mình có thể giúp bạn:<br>
                    💬 Tư vấn chuyến xe phù hợp<br>
                    🎫 Hướng dẫn đặt vé online<br>
                    💰 Tư vấn giá vé và khuyến mãi<br>
                    ❓ Giải đáp các thắc mắc khác<br><br>
                    <strong style="color: #667eea;">Thử hỏi mình:</strong><br>
                    "Mình muốn đi từ TP.HCM đến Đà Lạt"<br>
                    "Giá vé từ Hà Nội về Vinh bao nhiêu?"<br><br>
                    Cứ thoải mái hỏi nhé! 😊
                `;
            this.chatMessages.appendChild(welcomeDiv);
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    console.log('🌟 DOM ready, initializing FUTA Chat Widget...');

    if (document.body.classList.contains('no-chat-widget') ||
        document.body.hasAttribute('data-no-chat-widget')) {
        console.log('⚠️ Chat widget disabled on this page');
        return;
    }

    try {
        window.chatWidget = new ChatWidget();
        console.log('✅ Chat Widget initialized successfully!');

        // Xóa chat history khi trang được tải lại (F5)
        window.addEventListener('beforeunload', () => {
            // Không xóa chat history ở đây, để nó tồn tại
        });
    } catch (error) {
        console.error('❌ Failed to initialize Chat Widget:', error);
    }
});

window.ChatWidget = ChatWidget;
console.log('🚀 Chat Widget script loaded');