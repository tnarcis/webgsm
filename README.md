# 🛍️ WebGSM - WordPress WooCommerce B2B Platform

> Platform WordPress/WooCommerce cu funcționalități B2B avansate pentru România

---

## 📦 **Structura Repository**

```
webgsm/
├── app/public/wp-content/
│   ├── plugins/
│   │   ├── webgsm-b2b-pricing/      ← Plugin prețuri B2B
│   │   └── webgsm-checkout-pro/     ← Checkout personalizat PF/PJ
│   └── themes/
│       └── martfury-child/          ← Temă child customizată
├── .gitignore
└── README.md
```

---

## ✨ **Funcționalități**

### 🏢 **Plugin: WebGSM B2B Pricing**

**Prețuri și discount-uri automate pentru clienți B2B (Persoane Juridice)**

#### Caracteristici:
- ✅ **Detectare automată PJ** - Identifică automat clienții firmă
- ✅ **Discount ierarhic**:
  - La nivel de produs (prioritate 1)
  - La nivel de categorie (prioritate 2)
  - Global/implicit (prioritate 3)
- ✅ **Sistem Tiers** - Bronze → Silver → Gold → Platinum
  - Avansare automată bazată pe valoarea comenzilor
  - Discount-uri suplimentare per tier
- ✅ **Cache management inteligent** - Update instant la modificare discount
- ✅ **Afișare clară economii**:
  - Badge "B2B" pe produs
  - Preț original tăiat + preț B2B
  - Economie calculată în cart/checkout
- ✅ **Admin panel complet**:
  - Rapoarte clienți B2B
  - Setări discount-uri
  - Export date

#### Tehnologii:
- WooCommerce Hooks (price filters, display hooks)
- WordPress User Meta
- Product/Category Meta
- Cache management (WP Object Cache, Transients)
- Console debugging pentru dezvoltatori

---

### 🛒 **Plugin: WebGSM Checkout Pro**

**Checkout personalizat pentru România cu integrare ANAF**

#### Caracteristici:
- ✅ **Toggle PF/PJ** - Alegere tip client la checkout
- ✅ **Integrare ANAF**:
  - Autocompletare date firmă după CUI
  - Verificare plătitor TVA
  - Validare date în timp real
- ✅ **Carduri firmă salvate** - Clienți pot salva multiple firme
- ✅ **Validare completă** - Date obligatorii PF vs PJ
- ✅ **Design responsive** - Optimizat mobile/desktop

---

### 🎨 **Temă: Martfury Child**

**Customizări și funcționalități suplimentare**

#### Module incluse:
- ✅ **Registration Enhanced** (LINE-ART Design):
  - Toggle PF/PJ stilizat cu iconițe SVG
  - Autocompletare ANAF cu gradient albastru
  - Validare în timp real
  - Confirmare email obligatorie
  - Hover effects și animații
- ✅ **My Account** - Dashboard personalizat
- ✅ **Facturare PJ** - Generare facturi pentru firme
- ✅ **Retururi** - Sistem gestiune retururi
- ✅ **Garanții** - Gestiune produse în garanție
- ✅ **AWB Tracking** - Tracking colete
- ✅ **N8N Webhooks** - Integrare automatizări

---

## 🚀 **Setup & Instalare**

### Cerințe:
- WordPress 6.0+
- WooCommerce 8.0+
- PHP 7.4+
- MySQL 5.7+

### Instalare:

1. **Clone repository:**
   ```bash
   git clone [URL_REPO]
   cd webgsm
   ```

2. **Configurare WordPress:**
   - Importă structura în `/wp-content/`
   - Activează tema `martfury-child`
   - Activează plugin-urile:
     - `webgsm-b2b-pricing`
     - `webgsm-checkout-pro`

3. **Configurare plugin-uri:**
   - **B2B Pricing**: `WordPress Admin → B2B Pricing → Setări`
     - Setează discount implicit
     - Configurează tiers (Bronze/Silver/Gold/Platinum)
   - **Checkout Pro**: Verifică integrarea ANAF funcționează

---

## 🔧 **Configurare B2B**

### Setare discount-uri:

1. **La nivel de produs:**
   - Editează produs → Scroll la "Prețuri B2B"
   - Setează `Discount PJ (%)`

2. **La nivel de categorie:**
   - Produse → Categorii → Editează categorie
   - Setează `Discount PJ (%)`

3. **Global/implicit:**
   - B2B Pricing → Setări
   - Setează `Discount Implicit`

### Sistem Tiers:

- **Bronze**: 0 - 10,000 RON → +5% discount
- **Silver**: 10,000 - 50,000 RON → +10% discount
- **Gold**: 50,000 - 100,000 RON → +15% discount
- **Platinum**: 100,000+ RON → +20% discount

---

## 📝 **Workflow Înregistrare PJ**

1. User accesează `/my-account/`
2. Alege **"Persoană Juridică"** din toggle
3. Se deschide formular cu câmpuri firmă (design albastru)
4. Introduce CUI → Click **"Autocompletare"**
5. ANAF completează automat: denumire, reg. com., adresă
6. User finalizează înregistrarea
7. **Plugin B2B detectează automat** că e PJ:
   - Marchează `_is_pj = yes`
   - Salvează date firmă în `billing_*`
   - Prețurile B2B devin active instant

---

## 🐛 **Debugging**

### Console Debugging (B2B Pricing):

În browser console (F12) vei vedea:
```javascript
🔧 WebGSM B2B Pricing - DEBUG
📌 User ID: 6
🏢 Is PJ: true
⭐ Tier: bronze
💰 Discount Implicit: 10%
📦 Produse Afișate
Product #123: {
  'Preț Original': '1000 RON',
  'Discount PJ': '20%',
  '⚠️ SURSA DISCOUNT': 'produs',
  'Preț Final B2B': '800 RON'
}
```

### Verificare detectare PJ:

```php
// În admin, User Edit
- _is_pj: yes/no
- _tip_client: pj/pf
- billing_cui: CUI firmă
- billing_company: Denumire firmă
```

---

## 🎨 **Design System**

### Line-Art Icons:
- Toggle PF/PJ cu iconițe SVG elegante
- Hover effect: border albastru + gradient background
- Badge "PREȚURI B2B" cu fade-in animation

### Color Palette:
- **Primary Blue**: `#2196F3`
- **Dark Blue**: `#1976D2`
- **Success Green**: `#4CAF50`
- **Error Red**: `#F44336`

---

## 📄 **Fișiere Importante**

### Plugin B2B Pricing:
```
webgsm-b2b-pricing/
├── webgsm-b2b-pricing.php        ← Core logic
├── admin/
│   ├── settings-page.php         ← Setări discount
│   ├── customers-page.php        ← Lista clienți B2B
│   └── reports-page.php          ← Rapoarte vânzări
└── assets/
    ├── admin.css                 ← Stiluri admin
    └── admin.js                  ← JavaScript admin
```

### Temă Child:
```
martfury-child/
├── functions.php                 ← Include module
└── includes/
    ├── registration-enhanced.php ← Formular LINE-ART
    ├── facturare-pj.php         ← Facturi PJ
    └── webgsm-myaccount.php     ← Dashboard custom
```

---

## 🔐 **Securitate**

- ✅ Sanitizare input-uri (`sanitize_text_field`, `sanitize_email`)
- ✅ Validare AJAX cu nonce
- ✅ Verificare capabilități user (`current_user_can`)
- ✅ Escape output (`esc_html`, `esc_url`, `esc_attr`)

---

## 📈 **Performance**

- ✅ Cache management inteligent
- ✅ Lazy loading pentru rapoarte
- ✅ Optimizare query-uri DB
- ✅ Minificare CSS/JS în producție

---

## 🤝 **Contribuție**

Pentru modificări:
1. Creează branch nou: `git checkout -b feature/nume-feature`
2. Commit: `git commit -m "✨ Descriere"`
3. Push: `git push origin feature/nume-feature`
4. Creează Pull Request

---

## 📞 **Contact**

**WebGSM Development Team**
- Email: dev@webgsm.ro
- Website: https://webgsm.ro

---

## 📜 **Licență**

Proprietary - © 2025 WebGSM. Toate drepturile rezervate.
