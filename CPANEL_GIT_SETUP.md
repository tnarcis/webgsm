# 🎯 cPanel Git Deployment - Setup Complet

> **Setup Git deployment automat din cPanel cu `.cpanel.yml`**

---

## 📋 **CE FACE `.cpanel.yml`:**

✅ **Pull automat** din GitHub când apeși butonul în cPanel  
✅ **Backup automat** wp-content înainte de deploy  
✅ **Deploy DOAR** plugins + theme (nu WordPress core!)  
✅ **Cleanup** documentație (*.md), .git, node_modules  
✅ **Permissions** corecte (755 folders, 644 files)  
✅ **Cache flush** (dacă ai WP-CLI)  

---

## 🚀 **SETUP INIȚIAL (10 minute):**

### **Pasul 1: Conectează GitHub la cPanel**

#### **În cPanel:**

1. **Login** la cPanel (https://your-domain.com:2083)

2. **Git Version Control** (caută în search bar)

3. **Create** (sau "Clone a repository")

4. **Completează**:
   ```
   Clone URL: https://github.com/tnarcis/webgsm.git
   
   Repository Path: /home/USERNAME/repositories/webgsm
   (cPanel completează automat USERNAME)
   
   Repository Name: webgsm
   ```

5. **Clone** (așteptă 1-2 minute)

6. **✅ GATA!** Repo cloned în `/home/USERNAME/repositories/webgsm/`

---

### **Pasul 2: Verifică `.cpanel.yml`**

#### **Verificare în cPanel File Manager:**

1. **File Manager** → Navighează la:
   ```
   /home/USERNAME/repositories/webgsm/
   ```

2. **Verifică** că există fișierul `.cpanel.yml` (show hidden files)

3. **Deschide** `.cpanel.yml` și verifică paths:
   ```yaml
   # Ar trebui să conțină:
   $HOME/repositories/webgsm/app/public/wp-content/plugins/...
   $HOME/public_html/wp-content/plugins/...
   ```

4. **Modifică** dacă paths sunt diferite (ex: `public_html` vs. `public_html/webgsm`)

---

### **Pasul 3: Test Deploy**

#### **În cPanel → Git Version Control:**

1. **Manage** repository-ul `webgsm`

2. **Pull or Deploy** → Alege `HEAD`

3. **Update**

4. **Vezi log-urile** în interfață:
   ```
   💾 Creating backup...
   ✅ Backup saved: wp-content-backup-20260113_143022.tar.gz
   🔄 Deploying plugins...
   ✅ Plugins deployed!
   🔄 Deploying theme...
   ✅ Theme deployed!
   🧹 Cleaning up...
   ✅ Cleanup done!
   🔒 Setting permissions...
   ✅ Permissions set!
   ✅ DEPLOYMENT COMPLETE!
   ```

5. **Verifică site-ul**: https://your-domain.com

6. **✅ MERGE?** Perfect! Deploy complet!

---

## 🔄 **UPDATE WORKFLOW (30 secunde):**

### **Când faci modificări local:**

```bash
# 1. Modifici cod local (plugins/theme)

# 2. Commit & Push
git add .
git commit -m "Update feature X"
git push origin main

# 3. În cPanel Git Version Control:
#    → Manage → Pull or Deploy → HEAD → Update

# 4. Verifică site live
#    https://your-domain.com

# GATA! 🎉
```

---

## 📊 **CE SE DEPLOY vs. CE NU:**

| Fișier/Folder | Deploy? | De ce |
|---------------|---------|-------|
| **plugins/webgsm-b2b-pricing/** | ✅ DA | Plugin custom |
| **plugins/webgsm-checkout-pro/** | ✅ DA | Plugin custom |
| **plugins/webgsm-customer-tiers/** | ✅ DA | Plugin custom |
| **themes/martfury-child/** | ✅ DA | Tema custom |
| **uploads/** | ❌ NU | Imagini server live (nu se atinge!) |
| **README.md, *.md** | ❌ NU | Documentație (ștearsă în cleanup) |
| **.git/** | ❌ NU | Metadata Git (ștearsă în cleanup) |
| **node_modules/** | ❌ NU | Dependencies (ștearsă în cleanup) |
| **/app/public/** (restul) | ❌ NU | WordPress core (ignorat) |
| **/conf/, /logs/** | ❌ NU | Local by Flywheel (ignorat) |

---

## 🛡️ **SAFETY: Backup & Rollback**

### **Backup automat:**

`.cpanel.yml` face backup automat în:
```
/home/USERNAME/backups/wp-content-backup-YYYYMMDD_HHMMSS.tar.gz
```

### **Rollback manual (dacă ceva nu merge):**

#### **În cPanel File Manager:**

1. Navighează la `/home/USERNAME/backups/`

2. Găsește ultimul backup (ex: `wp-content-backup-20260113_143022.tar.gz`)

3. **Extract** în `/home/USERNAME/public_html/`

4. **Overwrite** fișierele existente

5. **✅ GATA!** Site revenit la starea dinaintea deploy-ului

#### **SAU via SSH:**

```bash
ssh user@your-domain.com

cd ~/public_html
tar -xzf ~/backups/wp-content-backup-YYYYMMDD_HHMMSS.tar.gz

# Site restored! ✅
```

---

## ⚙️ **CUSTOMIZARE `.cpanel.yml`**

### **Modifică paths (dacă sunt diferite):**

```yaml
# Dacă WordPress e în subfolder:
$HOME/public_html/subfolder/wp-content/plugins/
```

### **Adaugă plugin nou:**

```yaml
# În secțiunea TASK 2:
- /bin/cp -rf $HOME/repositories/webgsm/app/public/wp-content/plugins/NEW-PLUGIN $HOME/public_html/wp-content/plugins/
```

### **Adaugă notificări email (opțional):**

```yaml
# La final:
- echo "Deploy complete!" | mail -s "Site Deployed" your-email@domain.com
```

### **Skip backup (NU recomandat!):**

```yaml
# Comentează liniile TASK 1:
# - export TIMESTAMP=$(date +%Y%m%d_%H%M%S)
# ...
```

---

## 🐛 **TROUBLESHOOTING**

### **Problemă 1: "Permission denied"**

**Cauză**: cPanel nu are permisiuni să scrie în `public_html/`

**Soluție**:
```bash
# În cPanel Terminal sau SSH:
chmod 755 ~/public_html/wp-content/
chmod 755 ~/public_html/wp-content/plugins/
chmod 755 ~/public_html/wp-content/themes/
```

---

### **Problemă 2: "cp: cannot create directory"**

**Cauză**: Path-ul din `.cpanel.yml` e greșit

**Soluție**:
1. Verifică în cPanel File Manager care e path-ul EXACT la `public_html/`
2. Modifică `.cpanel.yml`:
   ```yaml
   # GREȘIT:
   $HOME/public_html/wp-content/...
   
   # CORECT (dacă WordPress e în subfolder):
   $HOME/public_html/webgsm/wp-content/...
   ```
3. Commit & push modificarea

---

### **Problemă 3: Deploy OK, dar modificările nu apar**

**Cauză 1**: Cache WordPress

**Soluție**:
```bash
# cPanel Terminal:
cd ~/public_html
wp cache flush

# SAU în WordPress admin:
# Plugins → WP Super Cache / W3 Total Cache → Clear All Cache
```

**Cauză 2**: Cache browser

**Soluție**: Hard refresh (`Cmd+Shift+R` / `Ctrl+F5`)

---

### **Problemă 4: ".cpanel.yml not found"**

**Cauză**: Fișierul nu e în root-ul repository-ului

**Soluție**:
```bash
# Local:
git add .cpanel.yml
git commit -m "Add .cpanel.yml"
git push

# Apoi în cPanel: Pull or Deploy
```

---

## 📝 **STRUCTURĂ `.cpanel.yml` EXPLICATĂ:**

```yaml
deployment:
  tasks:
    # Fiecare linie = comandă bash executată pe server
    
    # Comenzi utile:
    - export VAR=value          # Setează variabilă
    - mkdir -p /path/           # Creează director
    - /bin/cp -rf source dest   # Copiază recursiv
    - tar -czf file.tar.gz dir  # Arhivează cu gzip
    - find /path -name "*.md"   # Găsește fișiere
    - chmod 755 /path           # Schimbă permissions
    - echo "message"            # Afișează mesaj în log
```

---

## 🎯 **BEST PRACTICES:**

### **✅ DO:**

1. **Testează local** înainte de push
2. **Commit messages clare** (ex: "Fix login bug")
3. **Verifică site-ul** după fiecare deploy
4. **Păstrează backupurile** (cel puțin ultimele 5)
5. **Deploy în ore low-traffic** (dacă modificări majore)

### **❌ DON'T:**

1. **Nu modifica direct pe server** (folosește Git!)
2. **Nu șterge backupurile** (safety net!)
3. **Nu deploy fără test** (măcar un quick check)
4. **Nu commit parole/API keys** (folosește .gitignore!)

---

## 📊 **COMPARAȚIE: Script vs. cPanel Git**

| Feature | Script deploy.sh | cPanel Git (.cpanel.yml) |
|---------|-----------------|--------------------------|
| **Setup** | SSH manual | UI cPanel (ușor) |
| **Deploy** | `./deploy.sh` | Click buton în UI |
| **Backup** | ✅ Automat | ✅ Automat |
| **Cleanup** | ✅ Da | ✅ Da |
| **Permissions** | Manual | ✅ Automat |
| **Log-uri** | Terminal | ✅ UI cPanel (vizual) |
| **Rollback** | Manual | Manual (din backups) |
| **Recomandat** | SSH users | ✅ **UI users** |

**→ CONCLUZIE**: Dacă ai cPanel UI, folosește **cPanel Git (.cpanel.yml)** ✅

---

## 🎊 **REZULTAT FINAL:**

### **CE AI ACUM:**

✅ **Git deployment** automat din cPanel UI  
✅ **`.cpanel.yml`** configurat corect  
✅ **Backup** automat la fiecare deploy  
✅ **Deploy DOAR** plugins + theme (nu core)  
✅ **Cleanup** documentație și fișiere temporare  
✅ **Permissions** sigure (755/644)  

### **WORKFLOW:**

1. **Modifici** local (VSCode/Cursor)
2. **Commit & Push** la GitHub
3. **cPanel UI** → Git → Pull or Deploy → Click
4. **✅ GATA!** Site live actualizat în 30 secunde!

---

## 📞 **NEXT STEPS:**

1. ✅ **Verifică** că `.cpanel.yml` e în repo (e deja!)
2. ✅ **Push** la GitHub (commit următor)
3. ✅ **Setup** cPanel Git (10 min)
4. ✅ **Test** deploy (Pull or Deploy)
5. ✅ **Enjoy** deployment automat! 🎉

---

**Última actualizare**: 2026-01-13

**Autor**: WebGSM Team
