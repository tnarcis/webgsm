# 🚀 DEPLOY LA CPANEL - WordPress Site

> **Problemă**: Repo GitHub conține întreaga structură Local by Flywheel (`/app/public/`), dar pe cPanel vrei să tragi **DOAR `wp-content/`** (plugins + teme).

---

## 🎯 **SOLUȚIE RECOMANDATĂ: Git Subtree Pull**

### **✅ OPȚIUNEA 1: Pull doar wp-content (RECOMANDAT)**

#### **Pe server cPanel (via SSH sau File Manager → Terminal):**

```bash
# 1. Conectează-te la cPanel via SSH
ssh user@your-domain.com

# 2. Navighează la directorul WordPress
cd ~/public_html

# 3. Verifică că wp-content există
ls -la wp-content/

# 4. Adaugă remote către GitHub repo
git remote add github https://github.com/tnarcis/webgsm.git

# 5. Trage DOAR wp-content din repo (nu tot!)
git fetch github main
git checkout github/main -- app/public/wp-content/

# 6. Mută fișierele în locația corectă (dacă e nevoie)
# Dacă wp-content e la root:
mv app/public/wp-content/* wp-content/
rm -rf app/

# SAU dacă ai structură diferită:
cp -r app/public/wp-content/* wp-content/
rm -rf app/
```

#### **Următoarele update-uri:**

```bash
cd ~/public_html

# Pull doar wp-content
git fetch github main
git checkout github/main -- app/public/wp-content/
mv app/public/wp-content/* wp-content/
rm -rf app/
```

---

## 🔧 **OPȚIUNEA 2: Git Sparse Checkout (Mai avansat)**

### **Setup inițial pe cPanel:**

```bash
# 1. Conectează SSH
ssh user@your-domain.com

# 2. Navighează
cd ~/public_html

# 3. Inițializează Git (dacă nu există)
git init

# 4. Adaugă remote
git remote add origin https://github.com/tnarcis/webgsm.git

# 5. Activează sparse-checkout
git config core.sparseCheckout true

# 6. Specifică CE vrei să tragi (doar wp-content)
echo "app/public/wp-content/" >> .git/info/sparse-checkout

# 7. Pull DOAR wp-content
git pull origin main
```

### **Următoarele update-uri:**

```bash
cd ~/public_html
git pull origin main

# Fișierele vor merge direct în app/public/wp-content/
# Dacă structura e diferită pe server, mută-le:
cp -r app/public/wp-content/* wp-content/
```

---

## 📦 **OPȚIUNEA 3: Script Automat de Deploy**

### **Creează `deploy.sh` pe cPanel:**

```bash
#!/bin/bash
# deploy.sh - Actualizează DOAR wp-content din GitHub

echo "🚀 Starting deployment..."

# Configurare
REPO_URL="https://github.com/tnarcis/webgsm.git"
BRANCH="main"
WP_ROOT="/home/user/public_html"
TEMP_DIR="/home/user/temp_deploy"

# Cleanup temp
rm -rf $TEMP_DIR
mkdir -p $TEMP_DIR

# Clone repo în temp
echo "📦 Cloning repository..."
git clone --depth 1 --branch $BRANCH $REPO_URL $TEMP_DIR

# Verifică dacă există wp-content
if [ ! -d "$TEMP_DIR/app/public/wp-content" ]; then
    echo "❌ Error: wp-content not found in repo!"
    exit 1
fi

# Backup wp-content actual (safety)
echo "💾 Creating backup..."
tar -czf $WP_ROOT/wp-content-backup-$(date +%Y%m%d_%H%M%S).tar.gz $WP_ROOT/wp-content

# Synch DOAR plugins și themes (nu uploads!)
echo "🔄 Syncing plugins..."
rsync -av --delete \
    $TEMP_DIR/app/public/wp-content/plugins/ \
    $WP_ROOT/wp-content/plugins/ \
    --exclude='.git*'

echo "🔄 Syncing themes..."
rsync -av --delete \
    $TEMP_DIR/app/public/wp-content/themes/martfury-child/ \
    $WP_ROOT/wp-content/themes/martfury-child/ \
    --exclude='.git*'

# Cleanup temp
rm -rf $TEMP_DIR

echo "✅ Deployment complete!"
echo "📊 Check your site: https://your-domain.com"
```

### **Folosire:**

```bash
# Face script executabil (prima dată)
chmod +x deploy.sh

# Rulează deployment
./deploy.sh
```

---

## 🎨 **OPȚIUNEA 4: GitHub Actions → cPanel (Automat la push)**

### **Creează `.github/workflows/deploy-cpanel.yml` în repo:**

```yaml
name: Deploy to cPanel

on:
  push:
    branches: [ main ]
    paths:
      - 'app/public/wp-content/**'

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - name: Checkout code
      uses: actions/checkout@v3
      
    - name: Deploy to cPanel via FTP
      uses: SamKirkland/FTP-Deploy-Action@4.3.0
      with:
        server: ftp.your-domain.com
        username: ${{ secrets.FTP_USERNAME }}
        password: ${{ secrets.FTP_PASSWORD }}
        local-dir: ./app/public/wp-content/
        server-dir: /public_html/wp-content/
        exclude: |
          **/.git*
          **/.git*/**
          **/node_modules/**
          **/*.md
```

### **Setup GitHub Secrets:**

1. GitHub repo → Settings → Secrets → New secret
2. Adaugă:
   - `FTP_USERNAME` = username cPanel FTP
   - `FTP_PASSWORD` = parola cPanel FTP

**Rezultat**: La fiecare push pe `main` → Deploy automat DOAR `wp-content/` pe cPanel! 🚀

---

## 📋 **RECOMANDAREA MEA:**

### **Pentru tine (control manual):**

**FOLOSEȘTE OPȚIUNEA 3 (Script deploy.sh)** ✅

**De ce:**
- ✅ Control complet (rulezi când vrei)
- ✅ Backup automat înainte de deploy
- ✅ Synch DOAR plugins + themes (nu uploads!)
- ✅ Exclude `.git*` și fișiere temporare
- ✅ Ușor de debugat

### **Setup rapid (5 minute):**

```bash
# 1. SSH la cPanel
ssh user@your-domain.com

# 2. Creează script
nano ~/deploy.sh

# 3. Copy/paste scriptul din OPȚIUNEA 3 (modifică paths!)

# 4. Salvează (Ctrl+X, Y, Enter)

# 5. Face executabil
chmod +x ~/deploy.sh

# 6. Test
./deploy.sh

# GATA! 🎉
```

### **Update viitor (30 secunde):**

```bash
ssh user@your-domain.com
./deploy.sh
```

---

## ⚠️ **IMPORTANTE - NU uita:**

### **1. Exclude uploads/ din rsync:**

```bash
# În deploy.sh, adaugă:
--exclude='uploads/'
```

**De ce**: `uploads/` conține imagini/fișiere de pe server live (nu vrei să le suprascrii)

### **2. Exclude fișiere documentație:**

```bash
# Adaugă în rsync:
--exclude='*.md'
--exclude='README.md'
--exclude='CHANGELOG.md'
--exclude='INDEX.md'
--exclude='QUICK_START.md'
--exclude='AI_*.md'
--exclude='SECURITY.md'
--exclude='SUMMARY.md'
```

### **3. Backup ÎNTOTDEAUNA înainte:**

```bash
# Scriptul face asta automat:
tar -czf wp-content-backup-$(date +%Y%m%d_%H%M%S).tar.gz wp-content
```

---

## 🧪 **TESTARE:**

### **Pas 1: Test local (dry-run)**

```bash
# Adaugă --dry-run la rsync pentru a vedea ce se va schimba
rsync -av --dry-run --delete \
    $TEMP_DIR/app/public/wp-content/plugins/ \
    $WP_ROOT/wp-content/plugins/

# Verifică output-ul! Dacă e OK, rulează fără --dry-run
```

### **Pas 2: Deploy pe staging (dacă ai)**

```bash
./deploy.sh  # pe server staging
# Testează site-ul
# Dacă e OK → deploy pe production
```

### **Pas 3: Deploy production**

```bash
./deploy.sh  # pe server live
# Verifică site: https://your-domain.com
# Verifică: plugins active, tema OK, fără erori
```

---

## 🔄 **ROLLBACK (dacă ceva nu merge):**

```bash
# Restaurează backup-ul
cd ~/public_html
tar -xzf wp-content-backup-YYYYMMDD_HHMMSS.tar.gz

# Site-ul revine la starea dinaintea deploy-ului! ✅
```

---

## 📊 **COMPARAȚIE OPȚIUNI:**

| Opțiune | Dificultate | Control | Automat | Recomandat |
|---------|-------------|---------|---------|------------|
| **1. Subtree Pull** | 🟢 Ușor | Manual | ❌ | Pentru deploy rapid |
| **2. Sparse Checkout** | 🟡 Mediu | Manual | ❌ | Pentru Git avansat |
| **3. Script deploy.sh** | 🟢 Ușor | Manual | ❌ | ✅ **BEST** (control + backup) |
| **4. GitHub Actions** | 🔴 Avansat | Automat | ✅ | Pentru CI/CD avansat |

---

## 🎯 **NEXT STEPS:**

1. **Alege**: OPȚIUNEA 3 (Script deploy.sh) ✅
2. **Setup**: SSH → cPanel → Creează script (5 min)
3. **Test**: `./deploy.sh` pe staging (dacă ai)
4. **Deploy**: `./deploy.sh` pe production
5. **Verifică**: Site live funcționează
6. **Done!** 🎉

---

## 📞 **HELP:**

### **Dacă ceva nu merge:**

1. **Verifică paths** în script (WP_ROOT, TEMP_DIR)
2. **Verifică permissions**: `chmod +x deploy.sh`
3. **Verifică Git**: `git --version` (pe server)
4. **Verifică backup**: Există `wp-content-backup-*.tar.gz`?

### **Debug:**

```bash
# Rulează cu verbose
bash -x deploy.sh  # Vezi fiecare comandă
```

---

**Última actualizare**: 2026-01-13

**Autor**: WebGSM Team
