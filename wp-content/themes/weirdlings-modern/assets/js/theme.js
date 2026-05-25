(function () {
  const header = document.querySelector('.wl-site-header');
  const toggle = document.querySelector('[data-menu-toggle]');
  const mobileMenu = document.querySelector('[data-mobile-menu]');

  if (header) {
    const onScroll = () => {
      header.classList.toggle('is-scrolled', window.scrollY > 12);
    };

    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  if (toggle && mobileMenu) {
    toggle.addEventListener('click', () => {
      const isOpen = mobileMenu.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(isOpen));
    });
  }

  document.addEventListener('click', (event) => {
    const control = event.target.closest('.wl-qty-btn');

    if (!control) {
      return;
    }

    const cartControls = control.closest('.wl-cart-controls');
    const quantityField = cartControls ? cartControls.querySelector('input.qty') : null;

    if (!quantityField) {
      return;
    }

    event.preventDefault();

    const currentValue = Number.parseFloat(quantityField.value || '0');
    const step = Number.parseFloat(quantityField.step || '1') || 1;
    const min = quantityField.min !== '' ? Number.parseFloat(quantityField.min) : step;
    const max = quantityField.max !== '' ? Number.parseFloat(quantityField.max) : Number.POSITIVE_INFINITY;
    const nextValue = control.classList.contains('wl-qty-btn--minus')
      ? Math.max(min, currentValue - step)
      : Math.min(max, currentValue + step);

    quantityField.value = Number.isFinite(nextValue) ? String(nextValue) : String(min);
    quantityField.dispatchEvent(new Event('input', { bubbles: true }));
    quantityField.dispatchEvent(new Event('change', { bubbles: true }));
  });
})();

/* Inicializar Select2 (o SelectWoo) en selects del checkout si está disponible */
(function () {
  function initCheckoutSelects($) {
    var selectors = '#billing_country, #shipping_country, #billing_state, #shipping_state, select.country_to_state, select.state_select';
    var fn = $.fn.select2 ? 'select2' : ($.fn.selectWoo ? 'selectWoo' : null);
    if (!fn) {
      return;
    }

    $(selectors).each(function () {
      var $s = $(this);
      if ($s.next('.select2-container').length || $s.data('select2')) {
        return;
      }
      try {
        $s[fn]({ width: 'resolve' });
      } catch (e) {
        // ignore
      }
    });
  }

  if (window.jQuery) {
    window.jQuery(function ($) {
      initCheckoutSelects($);
      // Re-run after a short delays in case WC initializes later
      setTimeout(function () { initCheckoutSelects(window.jQuery); }, 800);
      setTimeout(function () { initCheckoutSelects(window.jQuery); }, 1600);

      // Re-initialize when WooCommerce updates the checkout via AJAX
      $(document.body).on('updated_checkout', function () {
        initCheckoutSelects(window.jQuery);
      });
    });
  }
})();

/* Fallback: crear dropdowns estilizados si el select nativo presenta problemas de contraste */
(function () {
  var selectors = ['#billing_country', '#shipping_country', '#billing_state', '#shipping_state'];

  function createFakeSelect(select) {
    if (!select || select.dataset.wlFake) return;
    // If Select2/SelectWoo is available globally, prefer it over fake select
    if (window.jQuery && (window.jQuery.fn.select2 || window.jQuery.fn.selectWoo)) {
      return;
    }
      // don't create fake if select is already enhanced (Select2/SelectWoo) or hidden
      if (select.nextElementSibling && (select.nextElementSibling.classList && select.nextElementSibling.classList.contains('select2-container'))) {
        return;
      }
      if (select.style.display === 'none' || select.offsetParent === null) {
        return;
      }
    var wrapper = document.createElement('div');
    wrapper.className = 'wl-fake-select';
    var button = document.createElement('button');
    button.type = 'button';
    button.className = 'wl-fake-select__button';
    var display = document.createElement('span');
    display.className = 'wl-fake-select__value';
    var dropdown = document.createElement('ul');
    dropdown.className = 'wl-fake-select__dropdown';

    Array.prototype.forEach.call(select.options, function (opt) {
      var li = document.createElement('li');
      li.className = 'wl-fake-select__option';
      li.textContent = opt.textContent || opt.innerText;
      li.dataset.value = opt.value;
      if (opt.selected) {
        display.textContent = li.textContent;
        li.classList.add('is-selected');
      }
      dropdown.appendChild(li);
      li.addEventListener('click', function () {
        select.value = this.dataset.value;
        // trigger change for dependent state updates
        var ev = new Event('change', { bubbles: true });
        select.dispatchEvent(ev);
        display.textContent = this.textContent;
        var prev = dropdown.querySelector('.is-selected');
        if (prev) prev.classList.remove('is-selected');
        this.classList.add('is-selected');
        dropdown.classList.remove('is-open');
        button.classList.remove('is-focused');
      });
    });

    button.appendChild(display);
    wrapper.appendChild(button);
    // add search input for long lists (placed inside dropdown)
    var search = document.createElement('input');
    search.type = 'search';
    search.className = 'wl-fake-select__search';
    search.placeholder = 'Buscar…';
    dropdown.appendChild(search);
    dropdown.appendChild(document.createElement('hr'));
    wrapper.appendChild(dropdown);
    select.parentNode.insertBefore(wrapper, select);
    // hide original select accessibly
    select.classList.add('wl-hidden-select');
    select.dataset.wlFake = '1';
    select.dataset.wlFake = '1';

    button.addEventListener('click', function (e) {
      e.preventDefault();
      var open = dropdown.classList.toggle('is-open');
      button.classList.toggle('is-focused', open);
      if (open) {
        // focus search input for quick filtering
        setTimeout(function () { try { search.focus(); } catch (err) {} }, 10);
      }
    });

    document.addEventListener('click', function (e) {
      if (!wrapper.contains(e.target)) {
        dropdown.classList.remove('is-open');
        button.classList.remove('is-focused');
      }
    });

    // filter options when typing in search
    search.addEventListener('input', function () {
      var q = (this.value || '').toLowerCase();
      Array.prototype.forEach.call(dropdown.children, function (li) {
        if (!li.classList || !li.dataset) return;
        var text = (li.textContent || '').toLowerCase();
        li.style.display = text.indexOf(q) !== -1 ? '' : 'none';
      });
    });

    // Observe select class changes to mirror validation state
    try {
      var obs = new MutationObserver(function (mutations) {
        mutations.forEach(function (m) {
          if (m.attributeName === 'class') {
            var cls = select.className || '';
            var invalid = cls.indexOf('woocommerce-invalid') !== -1 || cls.indexOf('is-invalid') !== -1 || select.getAttribute('aria-invalid') === 'true';
            button.classList.toggle('is-invalid', invalid);
          }
        });
      });
      obs.observe(select, { attributes: true, attributeFilter: ['class', 'aria-invalid'] });
    } catch (e) {
      // ignore
    }
  }

  function initFakeSelects() {
    selectors.forEach(function (sel) {
      var el = document.querySelector(sel);
      if (el) {
        // only create if browser displays native dropdown white-on-white
        createFakeSelect(el);
      }
    });
  }

  // Run after DOM ready and after other retries
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () { setTimeout(initFakeSelects, 500); });
  } else {
    setTimeout(initFakeSelects, 500);
  }
})();

(function () {
  var tablist = document.querySelector('[data-account-tabs]');
  if (!tablist) {
    return;
  }

  var tabButtons = Array.prototype.slice.call(tablist.querySelectorAll('[data-account-tab]'));
  var actionButtons = Array.prototype.slice.call(document.querySelectorAll('[data-account-tab-target]'));
  var logoutButton = document.querySelector('[data-account-logout-url]');
  var panels = Array.prototype.slice.call(document.querySelectorAll('[data-account-panel]'));

  function setActiveTab(tabId, updateHash) {
    if (!tabId) {
      tabId = tabButtons.length ? tabButtons[0].dataset.accountTab : 'dashboard';
    }

    tabButtons.forEach(function (button) {
      var isActive = button.dataset.accountTab === tabId;
      button.classList.toggle('is-active', isActive);
      button.setAttribute('aria-selected', String(isActive));
    });

    panels.forEach(function (panel) {
      var isActive = panel.dataset.accountPanel === tabId;
      panel.classList.toggle('is-active', isActive);
      panel.hidden = !isActive;
    });

    if (updateHash && window.history && window.history.replaceState) {
      window.history.replaceState(null, '', '#' + tabId);
    }
  }

  tablist.addEventListener('click', function (event) {
    var button = event.target.closest('[data-account-tab]');
    if (!button) {
      return;
    }

    event.preventDefault();
    setActiveTab(button.dataset.accountTab, true);
  });

  actionButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      setActiveTab(button.dataset.accountTabTarget, true);
    });
  });

  if (logoutButton) {
    logoutButton.addEventListener('click', function () {
      window.location.href = logoutButton.dataset.accountLogoutUrl;
    });
  }

  var initialTab = window.location.hash ? window.location.hash.replace('#', '') : '';
  if (!initialTab || !document.querySelector('[data-account-panel="' + initialTab + '"]')) {
    initialTab = tabButtons.length ? tabButtons[0].dataset.accountTab : 'dashboard';
  }

  setActiveTab(initialTab, false);
})();

(function () {
  var widget = document.querySelector('[data-chatbot-widget]');
  if (!widget) {
    return;
  }

  var toggleButton = widget.querySelector('[data-chatbot-toggle]');
  var panel = widget.querySelector('[data-chatbot-panel]');
  var closeButton = widget.querySelector('[data-chatbot-close]');
  var messages = widget.querySelector('[data-chatbot-messages]');
  var optionsWrap = widget.querySelector('[data-chatbot-options]');
  var form = widget.querySelector('[data-chatbot-form]');
  var input = widget.querySelector('[data-chatbot-input]');
  var fileInput = widget.querySelector('[data-chatbot-file]');
  var attachments = widget.querySelector('[data-chatbot-attachments]');
  var status = widget.querySelector('[data-chatbot-status]');

  if (!toggleButton || !panel || !form || !input) {
    return;
  }

  function setState(nextState, message) {
    toggleButton.dataset.chatbotState = nextState;
    if (status) {
      if (message) {
        status.hidden = false;
        status.textContent = message;
      } else {
        status.hidden = true;
        status.textContent = '';
      }
    }
  }

  function appendMessage(text, type) {
    if (!messages) {
      return;
    }
    var item = document.createElement('article');
    item.className = 'wl-chatbot-message wl-chatbot-message--' + type;
    var paragraph = document.createElement('p');
    paragraph.textContent = text;
    item.appendChild(paragraph);
    messages.appendChild(item);
    messages.scrollTop = messages.scrollHeight;
  }

  function openPanel() {
    panel.hidden = false;
    panel.classList.add('is-open');
    toggleButton.setAttribute('aria-expanded', 'true');
    window.setTimeout(function () {
      input.focus();
    }, 80);
  }

  function closePanel() {
    panel.classList.remove('is-open');
    panel.hidden = true;
    toggleButton.setAttribute('aria-expanded', 'false');
  }

  function updateAttachmentList() {
    if (!attachments || !fileInput) {
      return;
    }

    attachments.innerHTML = '';

    if (!fileInput.files || !fileInput.files.length) {
      attachments.hidden = true;
      return;
    }

    Array.prototype.forEach.call(fileInput.files, function (file) {
      var li = document.createElement('li');
      li.textContent = file.name;
      attachments.appendChild(li);
    });

    attachments.hidden = false;
  }

  function buildPayload(messageText) {
    return {
      event: 'chatbot_message',
      source: 'front_page',
      message: messageText,
      page_url: window.location.href,
      page_title: document.title,
      timestamp: new Date().toISOString(),
      user_agent: navigator.userAgent
    };
  }

  function submitFallbackPost(url, payload, files) {
    var frameName = 'wl-chatbot-n8n-target';
    var frame = document.querySelector('iframe[name="' + frameName + '"]');
    if (!frame) {
      frame = document.createElement('iframe');
      frame.name = frameName;
      frame.hidden = true;
      document.body.appendChild(frame);
    }

    var fallbackForm = document.createElement('form');
    fallbackForm.method = 'POST';
    fallbackForm.action = url;
    fallbackForm.target = frameName;
    fallbackForm.enctype = 'multipart/form-data';
    fallbackForm.hidden = true;

    var payloadInput = document.createElement('input');
    payloadInput.type = 'hidden';
    payloadInput.name = 'payload';
    payloadInput.value = JSON.stringify(payload);
    fallbackForm.appendChild(payloadInput);

    if (files && files.length) {
      var mediaNames = document.createElement('input');
      mediaNames.type = 'hidden';
      mediaNames.name = 'media_names';
      mediaNames.value = JSON.stringify(Array.prototype.map.call(files, function (file) { return file.name; }));
      fallbackForm.appendChild(mediaNames);
    }

    document.body.appendChild(fallbackForm);
    fallbackForm.submit();
    fallbackForm.remove();
  }

  function sendMessage(messageText, files) {
    var webhookUrl = toggleButton.dataset.chatbotWebhook;
    if (!webhookUrl || toggleButton.dataset.chatbotState === 'loading') {
      return;
    }

    setState('loading', 'Enviando a la manada...');

    var payload = buildPayload(messageText);
    var formData = new FormData();
    formData.append('payload', JSON.stringify(payload));

    if (files && files.length) {
      Array.prototype.forEach.call(files, function (file) {
        formData.append('media[]', file, file.name);
      });
    }

    fetch(webhookUrl, {
      method: 'POST',
      mode: 'no-cors',
      body: formData
    }).then(function () {
      setState('done', 'Mensaje enviado.');
      window.setTimeout(function () {
        setState('idle', '');
      }, 2400);
    }).catch(function () {
      try {
        submitFallbackPost(webhookUrl, payload, files || []);
        setState('done', 'Mensaje enviado.');
        window.setTimeout(function () {
          setState('idle', '');
        }, 2400);
      } catch (error) {
        setState('error', 'No se pudo enviar. Intenta de nuevo.');
      }
    });
  }

  toggleButton.addEventListener('click', function () {
    if (panel.hidden) {
      openPanel();
    } else {
      closePanel();
    }
  });

  if (closeButton) {
    closeButton.addEventListener('click', closePanel);
  }

  if (optionsWrap) {
    optionsWrap.addEventListener('click', function (event) {
      var optionButton = event.target.closest('[data-chatbot-option]');
      if (!optionButton) {
        return;
      }
      var value = (optionButton.dataset.chatbotOption || '').trim();
      if (!value) {
        return;
      }
      input.value = value;
      input.focus();
    });
  }

  if (fileInput) {
    fileInput.addEventListener('change', updateAttachmentList);
  }

  form.addEventListener('submit', function (event) {
    event.preventDefault();

    var text = (input.value || '').trim();
    var files = fileInput && fileInput.files ? fileInput.files : [];
    var fallbackMessage = 'Hola, criatura. ¿En qué podemos ayudarte hoy?';
    var finalMessage = text || fallbackMessage;

    appendMessage(finalMessage, 'user');
    if (files.length) {
      appendMessage('Adjuntaste ' + files.length + ' archivo(s).', 'user');
    }

    sendMessage(finalMessage, files);

    input.value = '';
    if (fileInput) {
      fileInput.value = '';
      updateAttachmentList();
    }

    window.setTimeout(function () {
      appendMessage('Gracias. Nuestra manada revisa tu mensaje y te responde pronto.', 'bot');
    }, 220);
  });
})();
