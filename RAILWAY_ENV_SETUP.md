# 🚀 Railway Environment Setup

## 📝 **What to Change in Your `.env` File**

When deploying to Railway, you only need to update these specific values in your existing `.env` file:

### **Required Changes:**

```env
# Change these values for production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.railway.app

# Database - Railway will provide these values
DB_CONNECTION=mysql
DB_HOST=${MYSQLHOST}
DB_PORT=${MYSQLPORT}
DB_DATABASE=${MYSQLDATABASE}
DB_USERNAME=${MYSQLUSER}
DB_PASSWORD=${MYSQLPASSWORD}

# Session & Cache (keep these as is)
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### **Optional but Recommended:**

```env
# Email configuration (update with your email service)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="Task Management"
```

### **Generate New APP_KEY:**

Run this command locally to generate a new key:
```bash
php artisan key:generate --show
```

Copy the output and set it as your `APP_KEY` in Railway.

## 🎯 **That's It!**

Everything else in your `.env` file can stay the same. Railway will automatically handle the database connection using their environment variables.

## 📋 **Railway Environment Variables to Set:**

In Railway dashboard, add these variables:
- `APP_KEY` (from the command above)
- `APP_URL` (your Railway app URL)
- All the database variables (Railway auto-populates these) 