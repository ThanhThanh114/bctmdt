/**
 * FUTA Chat Widget - Clean Version
 */

class FUTAChatWidget {
    constructor() {
        this.isOpen = false;
        this.isTyping = false;
        this.sessionId = 'chat_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

        if (window.futaChatWidget) {
            return window.futaChatWidget;
        }

        this.init();
        window.futaChatWidget = this;
    }

    init() {
        console.log('🚀 FUTA Chat Widget khởi tạo...');
        this.createWidget();
        this.bindEvents();
    }

    createWidget() {
        this.addStyles();

        const widget = document.createElement('div');
        widget.className = 'futa-chat-widget';
        widget.innerHTML = this.getWidgetHTML();

        document.body.appendChild(widget);

        this.chatButton = document.querySelector('.futa-chat-button');
        this.chatBox = document.querySelector('.futa-chat-box');
        this.chatClose = document.querySelector('.futa-chat-close');
        this.chatMessages = document.querySelector('.futa-chat-messages');
        this.chatInput = document.querySelector('.futa-chat-input');
        this.chatSend = document.querySelector('.futa-chat-send');

        console.log('✅ Widget HTML created');
    }

    addStyles() {
        if (document.getElementById('futa-chat-styles')) return;

        const style = document.createElement('style');
        style.id = 'futa-chat-styles';
        style.textContent = `
            .futa-chat-widget {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 999999;
                font-family: -apple-system, BlinkMacSystemFont, 'Segui UI', Roboto, sans-serif;
            }
            .futa-chat-button {
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
            .futa-chat-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 25px rgba(102, 126, 234, 0.6);
            }
            .futa-chat-box {
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
            .futa-chat-box.active {
                display: flex;
                opacity: 1;
                transform: scale(1) translateY(0);
            }
            .futa-chat-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 20px;
                text-align: center;
                position: relative;
            }
            .futa-chat-close {
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
            .futa-chat-close:hover {
                background: rgba(255, 255, 255, 0.1);
            }
            .futa-chat-header h3 {
                margin: 0;
                font-size: 18px;
                font-weight: 600;
            }
            .futa-chat-header p {
                margin: 5px 0 0 0;
                font-size: 14px;
                opacity: 0.9;
            }
            .futa-chat-messages {
                flex: 1;
                padding: 20px;
                overflow-y: auto;
                background: #f8f9fa;
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            .futa-message {
                max-width: 85%;
                word-wrap: break-word;
                line-height: 1.5;
                font-size: 14px;
                margin-bottom: 10px;
            }
            .futa-message.user {
                align-self: flex-end;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 12px 16px;
                border-radius: 20px 20px 5px 20px;
                margin-left: auto;
            }
            .futa-message.bot {
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
            .futa-typing {
                display: flex;
                gap: 4px;
                align-items: center;
                padding: 8px 0;
            }
            .futa-typing span {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background-color: #999;
                animation: typing 1.4s infinite ease-in-out;
            }
            .futa-typing span:nth-child(2) { animation-delay: -0.32s; }
            .futa-typing span:nth-child(3) { animation-delay: -0.16s; }
            @keyframes typing {
                0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
                40% { transform: scale(1); opacity: 1; }
            }
            .futa-chat-input-area {
                padding: 20px;
                background: white;
                border-top: 1px solid #e9ecef;
                display: flex;
                gap: 10px;
                align-items: flex-end;
            }
            .futa-chat-input {
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
            .futa-chat-input:focus {
                border-color: #667eea;
            }
            .futa-chat-send {
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
            .futa-chat-send:hover {
                transform: scale(1.05);
            }
            .futa-chat-send:disabled {
                opacity: 0.6;
                cursor: not-allowed;
                transform: none;
            }
            .futa-welcome {
                text-align: center;
                color: #666;
                font-size: 14px;
                padding: 15px;
                background: #f8f9fa;
                border-radius: 10px;
                border: 1px dashed #dee2e6;
                line-height: 1.5;
            }
        `;
        document.head.appendChild(style);
    }

    getWidgetHTML() {
        return `
            <button class="futa-chat-button" title="Chat với Minh - Tư vấn viên FUTA">
                💬
            </button>
            <div class="futa-chat-box">
                <div class="futa-chat-header">
                    <button class="futa-chat-close">×</button>
                    <h3>🤖 Minh - Tư vấn viên AI</h3>
                    <p>Hỗ trợ đặt vé xe 24/7</p>
                </div>
                <div class="futa-chat-messages">
                    <div class="futa-welcome">
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
                <div class="futa-chat-input-area">
                    <textarea class="futa-chat-input" placeholder="Nhập câu hỏi của bạn..." rows="1"></textarea>
                    <button class="futa-chat-send" title="Gửi tin nhắn">➤</button>
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
            if (!e.target.closest('.futa-chat-widget') && this.isOpen) {
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

        try {
            // Get base URL from current page
            const baseUrl = window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/');
            const apiUrl = window.location.origin + '/api/chat.php';

            console.log('🔗 Calling API:', apiUrl);

            // Call PHP backend API
            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    message: message,
                    session_id: this.sessionId
                })
            });

            console.log('📡 Response status:', response.status);

            if (!response.ok) {
                const errorText = await response.text();
                console.error('❌ API Error:', errorText);
                throw new Error('HTTP ' + response.status);
            }

            const data = await response.json();
            console.log('📨 API response:', data);

            this.hideTyping();

            if (data.error) {
                this.addMessage('Ôi, có lỗi gì đó rồi 😅 Bạn thử lại sau nhé! Hoặc liên hệ hotline: 1900 6067', 'bot');
            } else {
                this.addMessage(data.content || 'Xin lỗi, mình không hiểu lắm. Bạn có thể nói rõ hơn được không? 🤔', 'bot');
            }

        } catch (error) {
            console.error('❌ Chat API Error:', error);
            this.hideTyping();
            this.addMessage('Ôi, mạng hình như bị chậm. Bạn thử lại sau vài giây nhé! 📶 (Hoặc gọi hotline: 1900 6067)', 'bot');
        }
    }

    addMessage(content, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'futa-message ' + sender;
        messageDiv.textContent = content;

        this.chatMessages.appendChild(messageDiv);
        this.scrollToBottom();
    }

    showTyping() {
        if (this.isTyping) return;

        this.isTyping = true;
        this.chatSend.disabled = true;

        const typingDiv = document.createElement('div');
        typingDiv.className = 'futa-message bot';
        typingDiv.id = 'futa-typing';
        typingDiv.innerHTML = 'Minh đang suy nghĩ <span style="animation: typing 1.4s infinite;">...</span>';

        this.chatMessages.appendChild(typingDiv);
        this.scrollToBottom();
    }

    hideTyping() {
        this.isTyping = false;
        this.chatSend.disabled = false;

        const typingDiv = document.getElementById('futa-typing');
        if (typingDiv) {
            typingDiv.remove();
        }
    }

    scrollToBottom() {
        this.chatMessages.scrollTop = this.chatMessages.scrollHeight;
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
        window.futaChatWidget = new FUTAChatWidget();
        console.log('✅ FUTA Chat Widget initialized successfully!');
    } catch (error) {
        console.error('❌ Failed to initialize FUTA Chat Widget:', error);
    }
});

window.FUTAChatWidget = FUTAChatWidget;
console.log('🚀 FUTA Chat Widget script loaded');