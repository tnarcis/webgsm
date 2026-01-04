# WebGSM Project Status

> **CITEȘTE ACEST FIȘIER LA ÎNCEPUT DE SESIUNE!**
> **ACTUALIZEAZĂ-L LA FINAL!**

---

## Ultima actualizare
- **Data:** 4 Ianuarie 2025
- **Programator:** Narcis

---

## ✅ FINALIZAT

### Checkout Pro (plugin)
- PF/PJ toggle
- ANAF autocomplete
- Persoane fizice salvate
- Firme salvate
- Adrese livrare salvate
- Dropdown județe România
- Validare vizuală câmpuri
- Thank you page custom
- HPOS compatibilitate (compatibilitate tema cuwoocommerce )xxxx
- Mobile responsive

### GitHub + Deploy
- Repo: github.com/tnarcis/webgsm
- Auto-deploy la Hostico configurat
- 2 programatori cu acces

---

## 🚧 ÎN LUCRU

(nimic momentan)

---

## 📋 DE FĂCUT

- [ ] Integrare curieri (Fan Courier, Sameday)
- [ ] Easybox selector
- [ ] AWB în comenzi
- [ ] Optimizare My Account

---

## 📁 Structura
```
plugins/webgsm-checkout-pro/  ← Checkout
themes/martfury-child/        ← Child theme + module
```

---

## 📝 Jurnal

### 4 Ian 2025 - Narcis
- Finalizat checkout PF/PJ
- Setup GitHub + deploy Hostico
- Adăugat programator 2



DE FACUT : 
📘 BLUEPRINT COMPLET – webgsm-checkout-pro + Contul Meu
1. Probleme identificate în webgsm-checkout-pro
A. Probleme funcționale
Funcția de salvare PJ nu funcționează la adăugarea unei firme noi
Butonul „lupă ANAF” trebuie redenumit în ceva mai intuitiv (ex: „Autocompletare”)
După adăugarea unei firme sau închiderea popup‑ului, selecția revine automat pe „Persoană Fizică” în loc să rămână pe „Persoană Juridică”

✔️ 2.2. Modificare text buton ANAF
În UI:
înlocuire text „Lupă ANAF” cu:
Autocompletare
Caută firmă
Verifică CUI
✔️ 2.3. Persistență selecție Persoană Juridică nu reamane selectat pj sare pe persoana fizica 
La închiderea popup‑ului, trebuie păstrată valoarea selectată în:


Testare:
adăugare firmă → rămâne pe PJ
închidere popup → rămâne pe PJ

1. Structura pe Categorii (Grupare Logică)
În loc de o listă simplă, îți recomand să folosești un panou lateral (Sidebar) sau un grid de iconițe organizat astfel:

A. Activitate Comercială

Comenzi: Istoricul achizițiilor și statusul curent.

Retururi & Garanție: (Grupate împreună) Tot ce ține de post-vânzare. Utilizatorul vrea să știe „cum dau înapoi” sau „cum repar”, deci stau bine împreună.

Descărcări: Produse digitale (dacă este cazul).

B. Date de Livrare și Facturare

Adrese: Aici poți include un submeniu sau o pagină unică pentru:

Adrese personale (Acasă/Birou).

Firme Salvate: O secțiune dedicată pentru B2B (foarte utilă în România).

Date Facturare: Detaliile cardului sau datele fiscale implicite.

C. Setări Profil

Detalii Cont: Schimbare parolă, nume, email.

Dezautentificare: Plasat mereu ultimul, eventual cu un stil vizual diferit (ex: text roșu sau link discret).

2. Optimizarea pentru "Eleganță" (UI/UX)
Tabloul de bord (Dashboard) Visual

În loc să trimiți utilizatorul direct pe „Comenzi”, prima pagină (Dashboard) ar trebui să conțină scurtături vizuale (Carduri):

Un card mare: „Urmărește ultima comandă”.

3-4 butoane rapide cu iconițe: Comenzi recente, Adrese, Garanție.

Terminologie mai prietenoasă

Cuvintele folosite contează enorm pentru tonul site-ului:

În loc de „Dezautentificare”, folosește „Ieșire din cont”.

În loc de „Detalii cont”, poți folosi „Securitate și Profil”.

În loc de „Firme salvate”, poți folosi „Profiluri Business”.

3. Implementare Tehnică în WooCommerce
Dacă nu vrei să scrii cod de la zero, există două modalități eficiente de a realiza asta:

Elementor / Divi / Producători de pagini: Majoritatea au widget-uri de „My Account” pe care le poți stiliza complet, eliminând tab-urile standard și creând un layout personalizat.

Plugin-uri dedicate: * IconicWP Information Architecture sau Custom My Account Page for WooCommerce. Acestea îți permit să adaugi „Endpoints” (puncte finale) noi, cum ar fi secțiunea de Firme Salvate sau Garanții, fără să strici funcționalitatea de bază.

Exemplu de meniu final (Navigation Flow):

Secțiune	Ce include?
Punctul de control	 |Sumar comenzi recente și mesaje suport.
Achizițiile mele	 |Comenzi, Descărcări, Retururi & Garanție.
Date Salvate	     |Adrese de livrare, Firme (CUI/CIF), Metode plată.
Setări Profil	     |Email, Parolă, Preferințe notări.
Ieșire	             |Logout.
Sfat: Pentru secțiunea de Garanții, asigură-te că utilizatorul poate vedea clar cât timp mai este validă garanția lângă fiecare produs din lista de comenzi. Aceasta este o trăsătură de „magazin premium”.

Apropo de implementare, ai deja un plugin pentru gestionarea garanțiilor sau vrei să creăm o pagină de formular personalizat pentru asta?

