<?php
// includes/footer.php

declare(strict_types=1);
?>
</main>
<footer class="bg-white border-top mt-4">
  <div class="container-xl py-4">
    <div class="row g-3">
      <div class="col-md-4">
        <div class="fw-semibold mb-2"><?= e(APP_NAME) ?></div>
        <div class="small text-muted"><?= e(lang('footer.about_text', "Mahalla onlayn murojaat tizimi – fuqarolarning ariza, shikoyat va takliflarini tez va qulay qabul qilish platformasi.")) ?></div>
      </div>
      <div class="col-md-4">
        <div class="fw-semibold mb-2"><?= e(lang('footer.links_title','Foydali havolalar')) ?></div>
        <ul class="list-unstyled small mb-0">
          <li class="mb-1"><a class="text-decoration-none" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/index.php"><?= e(lang('nav.home','Bosh sahifa')) ?></a></li>
          <li class="mb-1"><a class="text-decoration-none" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/track.php"><?= e(lang('nav.track','Holat tekshirish')) ?></a></li>
          <li class="mb-1"><a class="text-decoration-none" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/create_application.php"><?= e(lang('cta.apply','Murojaat yuborish')) ?></a></li>
          <li class="mb-1"><a class="text-decoration-none" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/faq.php"><?= e(lang('nav.faq','FAQ')) ?></a></li>
          <li class="mb-1"><a class="text-decoration-none" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/about.php"><?= e(lang('nav.about','Haqida')) ?></a></li>
          <li class="mb-1"><a class="text-decoration-none" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/contact.php"><?= e(lang('nav.contact','Aloqa')) ?></a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <div class="fw-semibold mb-2"><?= e(lang('footer.contact_title','Aloqa')) ?></div>
        <ul class="list-unstyled small text-muted mb-2">
          <li class="mb-1"><i class="fa-solid fa-phone me-1 text-primary"></i>+998334064444</li>
          <li class="mb-1"><i class="fa-solid fa-envelope me-1 text-primary"></i>yoqub707@gmail.com</li>
          <li class="mb-1"><i class="fa-solid fa-location-dot me-1 text-primary"></i>Toshkent, O'zbekiston</li>
        </ul>
        <div class="d-flex gap-2">
          <a class="btn btn-outline-primary btn-sm" href="#" aria-label="Telegram"><i class="fa-brands fa-telegram"></i></a>
          <a class="btn btn-outline-primary btn-sm" href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
          <a class="btn btn-outline-primary btn-sm" href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
        </div>
      </div>
    </div>
  </div>
  <div class="border-top py-3 small text-center text-muted">
    © <?= date('Y') ?> Mahalla. <?= e(lang('footer.rights','Barcha huquqlar himoyalangan.')) ?>
  </div>
</footer>

<!-- Chatbot HTML Widget -->
<div id="chatbot-widget" class="chatbot-widget">
  <button id="chatbot-toggle" class="chatbot-toggle shadow-lg" aria-label="Open Chatbot">
    <span class="chatbot-pulse"></span>
    <i class="fa-solid fa-comments"></i>
    <span id="chatbot-badge" class="badge bg-danger chatbot-badge">1</span>
  </button>
  
  <div id="chatbot-window" class="chatbot-window shadow-lg hidden">
    <div class="chatbot-header d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <div class="chatbot-avatar-container">
          <span class="chatbot-avatar-status"></span>
          <i class="fa-solid fa-robot text-white"></i>
        </div>
        <div>
          <div class="chatbot-title">Mahalla Assistant</div>
          <div class="chatbot-status">Online</div>
        </div>
      </div>
      <button id="chatbot-close" class="btn btn-link text-white p-0" style="font-size: 1.2rem; outline: none; border: none; box-shadow: none;" aria-label="Close Chatbot">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    
    <div id="chatbot-messages" class="chatbot-messages"></div>
    
    <div id="chatbot-quick-replies" class="chatbot-quick-replies d-flex gap-1 p-2 border-top"></div>
    
    <form id="chatbot-form" class="chatbot-input-area p-2 border-top d-flex gap-1 align-items-center">
      <input type="text" id="chatbot-input" class="form-control form-control-sm" placeholder="Savol yozing..." required autocomplete="off">
      <button type="submit" class="btn btn-primary btn-sm chatbot-send-btn" aria-label="Send Message">
        <i class="fa-solid fa-paper-plane"></i>
      </button>
    </form>
  </div>
</div>

<style>
  /* Chatbot Styles */
  .chatbot-widget {
    position: fixed;
    bottom: 25px;
    right: 25px;
    z-index: 1050;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
  }
  .chatbot-toggle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: #fff;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    position: relative;
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease;
    outline: none;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
  }
  .chatbot-toggle:hover {
    transform: scale(1.08);
    box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4), 0 8px 10px -6px rgba(59, 130, 246, 0.4);
  }
  .chatbot-pulse {
    position: absolute;
    inset: -2px;
    border-radius: 50%;
    border: 2px solid #3b82f6;
    animation: chatbot-ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
    pointer-events: none;
  }
  @keyframes chatbot-ping {
    75%, 100% {
      transform: scale(1.3);
      opacity: 0;
    }
  }
  .chatbot-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    padding: 0;
    border: 2px solid #fff;
  }
  .chatbot-window {
    position: absolute;
    bottom: 75px;
    right: 0;
    width: 350px;
    height: 480px;
    border-radius: 1.25rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: opacity 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    transform-origin: bottom right;
  }
  .dark-mode .chatbot-window {
    background: rgba(30, 41, 59, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.1);
  }
  .chatbot-window.hidden {
    opacity: 0;
    transform: scale(0.8) translateY(20px);
    pointer-events: none;
  }
  .chatbot-header {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: #fff;
    padding: 12px 16px;
  }
  .chatbot-avatar-container {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    position: relative;
  }
  .chatbot-avatar-status {
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 9px;
    height: 9px;
    background: #10b981;
    border-radius: 50%;
    border: 1.5px solid #3b82f6;
  }
  .chatbot-title {
    font-weight: 600;
    font-size: 0.95rem;
    line-height: 1.2;
  }
  .chatbot-status {
    font-size: 0.75rem;
    opacity: 0.85;
  }
  .chatbot-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    scroll-behavior: smooth;
  }
  .chatbot-message {
    max-width: 80%;
    padding: 10px 14px;
    border-radius: 1rem;
    font-size: 0.88rem;
    line-height: 1.4;
    word-break: break-word;
  }
  .chatbot-message.bot {
    background: #e9ecef;
    color: #212529;
    align-self: flex-start;
    border-top-left-radius: 0.25rem;
  }
  .dark-mode .chatbot-message.bot {
    background: #334155;
    color: #f8fafc;
  }
  .chatbot-message.user {
    background: #3b82f6;
    color: #fff;
    align-self: flex-end;
    border-top-right-radius: 0.25rem;
  }
  .chatbot-quick-replies {
    background: rgba(248, 249, 250, 0.8);
    overflow-x: auto;
    white-space: nowrap;
    scrollbar-width: none;
  }
  .dark-mode .chatbot-quick-replies {
    background: rgba(15, 23, 42, 0.6);
  }
  .chatbot-quick-replies::-webkit-scrollbar {
    display: none;
  }
  .chatbot-quick-btn {
    border: 1px solid #3b82f6;
    background: transparent;
    color: #3b82f6;
    font-size: 0.78rem;
    padding: 4px 10px;
    border-radius: 12px;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
  }
  .chatbot-quick-btn:hover {
    background: #3b82f6;
    color: #fff;
  }
  .dark-mode .chatbot-quick-btn {
    color: #60a5fa;
    border-color: #60a5fa;
  }
  .dark-mode .chatbot-quick-btn:hover {
    background: #60a5fa;
    color: #0f172a;
  }
  .chatbot-input-area {
    background: #fff;
  }
  .dark-mode .chatbot-input-area {
    background: #1e293b;
    border-color: #334155 !important;
  }
  .chatbot-input-area input {
    border-radius: 12px;
  }
  .chatbot-send-btn {
    border-radius: 12px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
  }
  .chatbot-typing {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 12px;
  }
  .chatbot-typing span {
    width: 6px;
    height: 6px;
    background: #6c757d;
    border-radius: 50%;
    animation: chatbot-bounce 1.4s infinite ease-in-out both;
  }
  .dark-mode .chatbot-typing span {
    background: #94a3b8;
  }
  .chatbot-typing span:nth-child(1) { animation-delay: -0.32s; }
  .chatbot-typing span:nth-child(2) { animation-delay: -0.16s; }
  @keyframes chatbot-bounce {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1.0); }
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Hide preloader as soon as DOM is ready or after a timeout fallback
  function hidePreloader() {
    var el = document.getElementById('preloader');
    if (el && !el.classList.contains('hidden')) {
      el.classList.add('hidden');
    }
  }
  
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', hidePreloader);
  } else {
    hidePreloader();
  }
  
  window.addEventListener('load', hidePreloader);
  
  // Safeguard: hide after at most 800ms
  setTimeout(hidePreloader, 800);
  
  // Theme Toggle Logic
  (function(){
    var toggleBtn = document.getElementById('theme-toggle');
    if (!toggleBtn) return;
    var icon = toggleBtn.querySelector('i');
    
    function updateIcon() {
      if (document.documentElement.classList.contains('dark-mode')) {
        icon.className = 'fa-solid fa-sun text-warning';
      } else {
        icon.className = 'fa-solid fa-moon text-secondary';
      }
    }
    
    updateIcon();
    
    toggleBtn.addEventListener('click', function() {
      if (document.documentElement.classList.contains('dark-mode')) {
        document.documentElement.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
      } else {
        document.documentElement.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
      }
      updateIcon();
    });
  })();

  // Chatbot logic
  (function(){
    const chatToggle = document.getElementById('chatbot-toggle');
    const chatClose = document.getElementById('chatbot-close');
    const chatWindow = document.getElementById('chatbot-window');
    const chatMessages = document.getElementById('chatbot-messages');
    const chatQuickReplies = document.getElementById('chatbot-quick-replies');
    const chatForm = document.getElementById('chatbot-form');
    const chatInput = document.getElementById('chatbot-input');
    const chatBadge = document.getElementById('chatbot-badge');
    
    if (!chatToggle) return;
    
    const lang = document.documentElement.lang || 'uz';
    
    // Dictionary of answers and patterns
    const replies = {
      uz: {
        welcome: "Assalomu alaykum! Mahalla onlayn yordamchisiga xush kelibsiz. Sizga qanday yordam bera olaman?",
        placeholder: "Savolingizni yozing...",
        typing: "Yozmoqda...",
        quick: [
          { text: "Murojaat yuborish", label: "Murojaat yozish qanday bo'ladi?" },
          { text: "Holatni tekshirish", label: "Murojaat holatini tekshirish" },
          { text: "Kerakli hujjatlar", label: "Qanday hujjatlar yuklash mumkin?" },
          { text: "Buxoro mahallalari", label: "Buxoro mahallalari ro'yxati" },
          { text: "Admin bilan bog'lanish", label: "Admin kontaktlari" }
        ],
        intents: [
          {
            keywords: ['salom', 'assalom', 'yaxshimisiz', 'qandaysiz', 'tuzikmisiz'],
            reply: "Assalomu alaykum! Mahalla onlayn yordamchisiga xush kelibsiz. Sizga qanday yordam bera olaman?"
          },
          {
            keywords: ['murojaat', 'ariza', 'shikoyat', 'yuborish', 'yozish', 'yangi'],
            reply: "Murojaat yuborish uchun tizimga kiring, so'ngra **'Yangi murojaat'** menyusi orqali shaklni to'ldiring. Unda turi, mahallangiz, sarlavha va matnni kiritishingiz kerak. 10MB gacha rasm yoki PDF biriktirish ham mumkin."
          },
          {
            keywords: ['holat', 'tekshir', 'kuzatish', 'kod', 'id', 'qidir'],
            reply: "Murojaat holatini tekshirish uchun sizga berilgan **10 xonali tekshirish kodi** yoki **ID raqami** kerak bo'ladi. Ularni **'Holatni tekshirish'** sahifasida kiritib ko'ring."
          },
          {
            keywords: ['hujjat', 'fayl', 'pdf', 'rasm', 'hajm', 'biriktir'],
            reply: "Murojaatingizga ariza yoki shikoyatga aloqador PDF hujjatlar, JPEG yoki PNG rasmlarini yuklashingiz mumkin. Maksimal fayl hajmi: **10 MB**."
          },
          {
            keywords: ['mahalla', 'ro\'yxat', 'buxoro', 'nechta', 'nomlari'],
            reply: "Tizimimizda Buxoro shahridagi **65 ta mahalla** ro'yxatga olingan (masalan: Nizomiy, M.Ashrafiy, A.Temur, Kukaldosh, Navro'z va h.k.). Yangi murojaat yozganda ularni tanlash imkoni bor."
          },
          {
            keywords: ['admin', 'telefon', 'aloqa', 'bog\'lan', 'email', 'info', 'tel'],
            reply: "Tizim ma'muriyati bilan bog'lanish uchun telefon: **+998334064444** yoki elektron pochta: **yoqub707@gmail.com** manzillariga murojaat qiling."
          },
          {
            keywords: ['rahmat', 'tashakkur', 'yordam', 'xursand'],
            reply: "Arziydi! Mahalla tizimidan foydalanganingiz uchun rahmat. Kuningiz xayrli o'tsin!"
          },
          {
            keywords: ['haqida', 'nima bu', 'tizim'],
            reply: "Ushbu tizim fuqarolarning murojaatlarini tezkor va shaffof qabul qilish hamda ko'rib chiqilishini onlayn nazorat qilish uchun mo'ljallangan platformadir."
          }
        ],
        fallback: "Kechirasiz, savolingizni to'liq tushuna olmadim. Murojaat yuborish, uni tekshirish, Buxoro mahallalari yoki admin kontaktlari haqida so'rashingiz mumkin. Quyidagi tayyor tugmalardan foydalanib ko'ring."
      },
      ru: {
        welcome: "Здравствуйте! Добро пожаловать в онлайн-помощник Махалли. Чем я могу вам помочь?",
        placeholder: "Напишите ваш вопрос...",
        typing: "Печатает...",
        quick: [
          { text: "Отправка обращения", label: "Как отправить обращение?" },
          { text: "Проверка статуса", label: "Как проверить статус?" },
          { text: "Нужные документы", label: "Какие документы можно загрузить?" },
          { text: "Махалли Бухары", label: "Список махаллей Бухары" },
          { text: "Связь с админом", label: "Контакты администрации" }
        ],
        intents: [
          {
            keywords: ['привет', 'здравствуй', 'добрый', 'здравствуйте'],
            reply: "Здравствуйте! Добро пожаловать в онлайн-помощник Махалли. Чем я могу помочь?"
          },
          {
            keywords: ['обращен', 'заявлен', 'жалоб', 'отправ', 'подать', 'создать', 'новое'],
            reply: "Чтобы отправить обращение, войдите в систему, откройте раздел **'Новое обращение'** и заполните форму. Выберите тип, махаллю, укажите тему и текст. Также можно прикрепить файл PDF/JPG/PNG до 10 МБ."
          },
          {
            keywords: ['статус', 'провер', 'код', 'id', 'отслед', 'найти'],
            reply: "Для проверки статуса вам потребуется **10-значный проверочный код** или **ID обращения**. Введите их на странице **'Проверить статус'**."
          },
          {
            keywords: ['документ', 'файл', 'pdf', 'фото', 'размер', 'загруз'],
            reply: "К обращению можно прикрепить файлы документов PDF или изображения JPEG/PNG. Максимальный размер файла составляет **10 МБ**."
          },
          {
            keywords: ['махалл', 'список', 'бухар', 'сколько', 'назван'],
            reply: "В нашей системе зарегистрировано **65 махаллей** Бухары (включая Низомий, М.Ашрафий, А.Темур, Кукалдош и т.д.). Вы можете выбрать свою махаллю при подаче обращения."
          },
          {
            keywords: ['админ', 'телефон', 'связь', 'написать', 'email', 'почта', 'тел'],
            reply: "Связаться с администрацией системы можно по телефону: **+998334064444** или по электронной почте: **yoqub707@gmail.com**."
          },
          {
            keywords: ['спасибо', 'благодар', 'помог', 'отлично'],
            reply: "Пожалуйста! Спасибо, что пользуетесь системой Махалли. Хорошего дня!"
          },
          {
            keywords: ['о нас', 'платформ', 'система', 'что это'],
            reply: "Эта система является платформой для быстрой, удобной и прозрачной подачи и отслеживания обращений граждан в режиме онлайн."
          }
        ],
        fallback: "Извините, я не совсем понял ваш вопрос. Вы можете спросить о подаче обращений, проверке статуса, махаллях Бухары или контактах администрации. Или воспользуйтесь кнопками ниже."
      }
    };
    
    const config = replies[lang] || replies['uz'];
    chatInput.placeholder = config.placeholder;
    
    // Load quick replies
    config.quick.forEach(q => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'chatbot-quick-btn';
      btn.textContent = q.text;
      btn.addEventListener('click', () => {
        handleUserInput(q.label);
      });
      chatQuickReplies.appendChild(btn);
    });
    
    let chatOpened = false;
    
    function toggleChat() {
      chatOpened = !chatOpened;
      if (chatOpened) {
        chatWindow.classList.remove('hidden');
        chatBadge.style.display = 'none';
        if (chatMessages.children.length === 0) {
          showBotResponse(config.welcome);
        }
        setTimeout(() => chatInput.focus(), 150);
      } else {
        chatWindow.classList.add('hidden');
      }
    }
    
    chatToggle.addEventListener('click', toggleChat);
    chatClose.addEventListener('click', toggleChat);
    
    function appendMessage(text, sender) {
      const msg = document.createElement('div');
      msg.className = 'chatbot-message ' + sender;
      let htmlText = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
      msg.innerHTML = htmlText;
      chatMessages.appendChild(msg);
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function showBotResponse(text) {
      const typingIndicator = document.createElement('div');
      typingIndicator.className = 'chatbot-message bot chatbot-typing';
      typingIndicator.innerHTML = '<span></span><span></span><span></span>';
      chatMessages.appendChild(typingIndicator);
      chatMessages.scrollTop = chatMessages.scrollHeight;
      
      setTimeout(() => {
        typingIndicator.remove();
        appendMessage(text, 'bot');
      }, 600 + Math.random() * 400);
    }
    
    function getBotReply(input) {
      const cleaned = input.toLowerCase().trim();
      for (const intent of config.intents) {
        for (const keyword of intent.keywords) {
          if (cleaned.includes(keyword)) {
            return intent.reply;
          }
        }
      }
      return config.fallback;
    }
    
    function handleUserInput(text) {
      if (!text.trim()) return;
      appendMessage(text, 'user');
      const reply = getBotReply(text);
      showBotResponse(reply);
    }
    
    chatForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const txt = chatInput.value;
      if (!txt.trim()) return;
      chatInput.value = '';
      handleUserInput(txt);
    });
  })();

  (function(){
    var fallbackPhotos = [
      'https://images.unsplash.com/photo-1583776855229-5b8f9a54c645?q=80&w=1600&auto=format&fit=crop',
      'https://images.unsplash.com/photo-1583777065027-cb1e5c9961c3?q=80&w=1600&auto=format&fit=crop',
      'https://images.unsplash.com/photo-1603451952566-9b6a26c6f1d0?q=80&w=1600&auto=format&fit=crop',
      'https://images.unsplash.com/photo-1517502884422-41eaead166d4?q=80&w=1600&auto=format&fit=crop',
      'https://images.unsplash.com/photo-1601315379737-0aa2c65e9b03?q=80&w=1600&auto=format&fit=crop',
      'https://images.unsplash.com/photo-1600461609108-39d163a5d7c2?q=80&w=1600&auto=format&fit=crop'
    ];
    function pickFallback(){
      var i = Math.floor(Math.random() * fallbackPhotos.length);
      return fallbackPhotos[i];
    }
    document.querySelectorAll('img').forEach(function(img){
      if (!img.closest('.carousel') && !img.closest('.cover')) {
        if (!img.getAttribute('loading')) img.setAttribute('loading','lazy');
      }
      if (!img.getAttribute('decoding')) img.setAttribute('decoding','async');
      var handler = function(){
        if (img.dataset.fallbackApplied === '1') return;
        img.dataset.fallbackApplied = '1';
        if (img.src && img.src.includes('/public/assets/')) {
          img.src = img.src.replace('/public/assets/', '/assets/');
          return;
        }
        img.src = pickFallback();
      };
      img.addEventListener('error', handler);
      if (img.complete && img.naturalWidth === 0 && img.src) {
        handler();
      }
    });
  })();
  // Copy-to-clipboard for [data-copy]
  (function(){
    var copiedText = <?= json_encode(lang('common.copied','Nusxa olindi')) ?>;
    document.addEventListener('click', function(e){
      var t = e.target;
      if (t && t.matches('[data-copy]')){
        var txt = t.getAttribute('data-copy') || '';
        if (!txt) return;
        (navigator.clipboard ? navigator.clipboard.writeText(txt) : Promise.reject()).then(function(){
          var orig = t.textContent;
          t.textContent = copiedText;
          setTimeout(function(){ t.textContent = orig; }, 1200);
        }).catch(function(){
          try {
            var ta = document.createElement('textarea');
            ta.value = txt; document.body.appendChild(ta); ta.select();
            document.execCommand('copy'); document.body.removeChild(ta);
            var orig = t.textContent; t.textContent = copiedText;
            setTimeout(function(){ t.textContent = orig; }, 1200);
          } catch(_){}
        });
      }
    });
  })();
</script>
</body>
</html>
