# 📝 CHANGELOG - Martfury Child Theme

Toate modificările notabile vor fi documentate aici.

Format: `[Data] - Modul - Descriere - Fișiere modificate`

---

## [2026-01-13] - RESTRUCTURARE MAJORĂ

### 🎯 **Organizare modulară**
- **Creat**: Structură nouă modulară
- **Creat**: `README.md` principal cu documentație completă
- **Creat**: `CHANGELOG.md` (acest fișier)
- **Status**: 🔄 În curs de refactoring

---

## [2026-01-12] - Cart Popup

### ✅ **Ascundere buton "Vezi coș" din popup**
- **Modul**: Cart / UI
- **Ce**: Ascuns butonul mare "Vezi coș" din popup-ul "Produs adăugat în coș"
- **Păstrat**: Butonul "Vezi coș" în mini-cart (hover pe icon)
- **Fișiere**:
  - `includes/webgsm-design-system.php` (CSS)
  - `functions.php` (JavaScript)
- **CSS**: `.message-box .btn-button { display: none; }`
- **JS**: `hideViewCartButton()` - țintire precisă `.message-box`

---

## [2026-01-12] - SmartBill TVA

### ✅ **TVA automat din WooCommerce**
- **Modul**: Invoices / SmartBill
- **Ce**: TVA se ia automat din prețurile WooCommerce (nu mai e hardcodat)
- **Calcul**: `(item_total_tax / item_total) * 100`
- **Fallback**: Setare admin "Cotă TVA Fallback" (19% default)
- **Fișiere**:
  - `includes/facturi.php` (funcția `genereaza_factura_smartbill`)
- **Instrucțiuni**: WooCommerce → Setări → Taxe → Taxe standard → 19%

---

## [2026-01-12] - SmartBill SKU

### ✅ **SKU în facturi**
- **Modul**: Invoices / SmartBill
- **Ce**: SKU-ul produselor apare în facturi SmartBill
- **Auto-generare**: Produse fără SKU primesc `WEBGSM-{ID}`
- **Tool bulk**: Buton admin pentru generare SKU în masă
- **Fișiere**:
  - `includes/facturi.php`
- **Hook**: `save_post_product` → `webgsm_auto_generate_sku()`
- **Funcții**:
  - `webgsm_auto_generate_sku()` - Auto SKU la salvare produs
  - `webgsm_bulk_generate_skus()` - Tool admin pentru bulk
- **Logging**: `error_log('SmartBill Product: ... | SKU: ...')`
- **Setare SmartBill**: Setări → Setări Facturi → ☑ Afișează codul produsului

---

## [2026-01-12] - B2B Pricing Plugin

### ✅ **Plugin webgsm-b2b-pricing integrat**
- **Modul**: B2B / Pricing
- **Ce**: Prețuri B2B automate pentru clienți PJ
- **Features**:
  - Discount ierarhic (produs → categorie → global)
  - Sistem tiers (Bronze/Silver/Gold/Platinum)
  - Cache management inteligent
  - Afișare economie B2B în cart/checkout
- **Fișiere plugin**:
  - `plugins/webgsm-b2b-pricing/webgsm-b2b-pricing.php` (1,397 linii)
- **Detectare PJ**: Compatibil cu formularul din `registration-enhanced.php`

---

## [2026-01-12] - Formular Înregistrare LINE-ART

### ✅ **Design LINE-ART pentru înregistrare PF/PJ**
- **Modul**: Registration / UI
- **Ce**: Toggle PF/PJ cu iconițe SVG elegante, gradient albastru
- **Features**:
  - Toggle PF/PJ cu line-art icons
  - Formular PJ cu gradient albastru (nu galben)
  - Buton "Autocompletare" ANAF stilizat
  - Hover effects cu border albastru
  - Badge "PREȚURI B2B" animat
- **Fișiere**:
  - `includes/registration-enhanced.php`
- **CSS Classes**:
  - `.webgsm-account-toggle` - Container toggle
  - `.toggle-icon` - SVG icons
  - `#campuri-firma-register` - Formular firmă (gradient albastru)
  - `#btn_cauta_cui_register` - Buton ANAF
- **Integrare B2B**: Câmpurile `tip_facturare`, `firma_cui`, `firma_nume` → detectate de B2B plugin

---

## [2026-01-12] - Detectare PJ la Înregistrare

### ✅ **Auto-detectare clienți B2B**
- **Modul**: Registration / B2B Integration
- **Ce**: User-ii PJ sunt detectați automat și primesc prețuri B2B
- **Hook**: `woocommerce_created_customer` (prioritate 20)
- **Funcție**: `detect_pj_on_registration()` în `webgsm-b2b-pricing.php`
- **Detectare**:
  - Verifică `tip_facturare` === 'pj'
  - Verifică prezența `firma_cui` sau `billing_cui`
  - Verifică `firma_nume` sau `billing_company`
- **Setări user meta**:
  - `_is_pj` = 'yes'
  - `_tip_client` = 'pj'
  - `billing_cui`, `billing_company`, `billing_nr_reg_com`
- **Adrese**: Copiază datele firmei ca billing & shipping default

---

## [ISTORIC VECHI - Înainte de 2026-01-12]

### Funcționalități existente (fără date exacte):
- ✅ Checkout personalizat PF/PJ (webgsm-checkout-pro)
- ✅ Facturare SmartBill
- ✅ Sistem retururi
- ✅ Sistem garanții
- ✅ AWB tracking
- ✅ N8N webhooks
- ✅ Design system (butoane albastre, rotunjite)
- ✅ My Account styling personalizat

---

## 📋 **TEMPLATE PENTRU MODIFICĂRI NOI**

```markdown
## [YYYY-MM-DD] - Titlu Modificare

### ✅/🔄/❌ **Nume feature**
- **Modul**: {modul} / {submodul}
- **Ce**: Descriere scurtă (1-2 propoziții)
- **De ce**: Motivul modificării
- **Cum**: Implementare tehnică
- **Fișiere**:
  - `path/to/file.php` (linia X-Y)
  - `path/to/style.css` (selector .class-name)
- **Hook-uri**: `hook_name` → `function_name()`
- **Breaking changes**: DA/NU
- **Testing**: Cum se testează
- **Rollback**: Cum se revine (dacă e nevoie)
```

---

## 🔍 **CUM GĂSEȘTI RAPID O MODIFICARE**

### **Caut modificare CSS (butoane, culori):**
```bash
grep -r "button\|color" CHANGELOG.md
```

### **Caut modificare PHP (logică, hook-uri):**
```bash
grep -r "Hook\|Funcție" CHANGELOG.md
```

### **Caut după dată:**
```bash
grep "2026-01-12" CHANGELOG.md
```

### **Caut după modul:**
```bash
grep "Modul: Invoices" CHANGELOG.md
```

---

## 📊 **STATISTICI MODIFICĂRI**

- **Total intrări**: 7
- **Module afectate**: 5 (Cart, Invoices, B2B, Registration, Integration)
- **Fișiere modificate**: 4 principale
- **Linii modificate**: ~500+
- **Hook-uri noi**: 3

---

**Acest fișier se actualizează la FIECARE modificare!**
