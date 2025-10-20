# Link Church Management System to GitHub

## Step 1: Install Git (If Not Installed)

### Download Git:
1. Go to: https://git-scm.com/download/win
2. Download "64-bit Git for Windows Setup"
3. Run the installer
4. Use default settings (just keep clicking "Next")
5. Restart your terminal/IDE after installation

## Step 2: Configure Git (First Time Only)

Open Command Prompt or PowerShell and run:

```bash
git config --global user.name "Your Name"
git config --global user.email "your-email@example.com"
```

Replace with your actual name and email.

## Step 3: Initialize Git Repository

In your project folder (f:\xampp\htdocs\churchmang), run:

```bash
git init
```

## Step 4: Create .gitignore File

I'll create this for you - it tells Git which files to ignore.

## Step 5: Make First Commit

```bash
git add .
git commit -m "Initial commit: Church Management System"
```

## Step 6: Create GitHub Repository

1. Go to: https://github.com
2. Log in (or create account if needed)
3. Click the "+" icon (top right)
4. Click "New repository"
5. Repository name: `church-management-system`
6. Description: "Church Management System with Pastor Portal, Member Management, and more"
7. Choose: **Private** (recommended) or Public
8. **DO NOT** check "Initialize with README"
9. Click "Create repository"

## Step 7: Link to GitHub

GitHub will show you commands. Use these:

```bash
git remote add origin https://github.com/YOUR-USERNAME/church-management-system.git
git branch -M main
git push -u origin main
```

Replace `YOUR-USERNAME` with your actual GitHub username.

## Step 8: Push Your Code

```bash
git push
```

## Future Updates

After making changes to your code:

```bash
git add .
git commit -m "Description of changes"
git push
```

## Quick Commands Reference

| Command | Purpose |
|---------|---------|
| `git status` | Check what files changed |
| `git add .` | Stage all changes |
| `git add filename` | Stage specific file |
| `git commit -m "message"` | Commit with message |
| `git push` | Push to GitHub |
| `git pull` | Get latest from GitHub |
| `git log` | View commit history |

## Important Files to Ignore

The .gitignore file I'll create will exclude:
- `/vendor/` - Composer dependencies
- `/node_modules/` - NPM dependencies
- `.env` - Environment secrets (API keys, passwords)
- `/storage/` - Cache and logs
- `/public/uploads/` - User uploaded files
- Database files

## Security Note

**NEVER commit:**
- `.env` file (contains passwords and secrets)
- Database files with real user data
- API keys or credentials
- User uploaded content with sensitive info

The .gitignore will protect you from accidentally committing these.

## Collaboration

To work with others:

1. They clone your repo: `git clone https://github.com/YOUR-USERNAME/church-management-system.git`
2. They run: `composer install`
3. They copy `.env.example` to `.env`
4. They run: `php artisan key:generate`
5. They set up their database

## Repository Settings (Recommended)

On GitHub, go to Settings:
- ✅ Make repository **Private** (if handling church data)
- ✅ Enable "Issues" for bug tracking
- ✅ Add description and topics
- ✅ Add a README.md file

## Backup Strategy

With GitHub:
- ✅ Your code is backed up in the cloud
- ✅ You can restore any previous version
- ✅ Multiple people can work on it
- ✅ Track who changed what and when

## Need Help?

- Git documentation: https://git-scm.com/doc
- GitHub guides: https://guides.github.com
- Git cheat sheet: https://education.github.com/git-cheat-sheet-education.pdf
