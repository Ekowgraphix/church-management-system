# 📧 Email Configuration Guide

## Quick Setup Options

### **Option 1: SendGrid (Recommended)** ⭐

**Why SendGrid?**
- Free tier: 100 emails/day
- Reliable delivery
- Easy setup
- Great for churches

**Setup Steps:**
1. Go to https://sendgrid.com/
2. Sign up for free account
3. Verify your email
4. Create an API Key:
   - Go to Settings → API Keys
   - Click "Create API Key"
   - Name it "Church Management System"
   - Select "Full Access"
   - Copy the API key (you'll only see it once!)

5. Update your `.env` file:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourchurch.com"
MAIL_FROM_NAME="Your Church Name"
```

6. Clear config cache:
```bash
php artisan config:clear
```

**Done!** ✅

---

### **Option 2: Gmail SMTP**

**Good for:** Small churches, testing

**Setup Steps:**
1. Enable 2-Step Verification on your Gmail account
2. Generate App Password:
   - Go to Google Account → Security
   - 2-Step Verification → App passwords
   - Select "Mail" and "Windows Computer"
   - Copy the 16-character password

3. Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="Your Church Name"
```

**Limit:** 500 emails/day

---

### **Option 3: Mailgun**

**Good for:** High volume churches

**Setup Steps:**
1. Sign up at https://www.mailgun.com/
2. Verify your domain
3. Get API credentials from dashboard

4. Update `.env`:
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-api-key
MAIL_FROM_ADDRESS="noreply@yourchurch.com"
MAIL_FROM_NAME="Your Church Name"
```

**Free tier:** 5,000 emails/month for 3 months

---

## Testing Email Configuration

### **Test Command:**
```bash
php artisan tinker
```

Then run:
```php
Mail::raw('Test email from Church Management System', function($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email');
});
```

If successful, you'll see no errors and receive the email!

---

## Using Email Campaigns

### **1. Create Campaign:**
1. Go to `/email-campaigns`
2. Click "New Campaign"
3. Fill in:
   - Campaign name
   - Subject line
   - Email content
   - Select recipients
4. Save as draft or schedule

### **2. Send Campaign:**
1. Open campaign
2. Review recipients
3. Click "Send Campaign"
4. Emails will be sent immediately

### **3. Track Results:**
- View sent count
- See failed emails
- Check delivery status

---

## Email Templates

Located in: `resources/views/emails/campaign.blade.php`

**Customize:**
- Colors
- Logo
- Footer text
- Layout

---

## Troubleshooting

### **Emails not sending?**

1. **Check .env file:**
   - Correct credentials?
   - No extra spaces?
   - Quotes around values?

2. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Check logs:**
   ```
   storage/logs/laravel.log
   ```

4. **Test SMTP connection:**
   - Use telnet or online SMTP tester
   - Verify port not blocked by firewall

### **Emails going to spam?**

1. **Verify sender domain:**
   - Add SPF record
   - Add DKIM record
   - Add DMARC record

2. **Use professional email:**
   - Not @gmail.com for mass emails
   - Use your church domain

3. **Avoid spam words:**
   - "Free", "Click here", "Act now"
   - Too many exclamation marks!!!

---

## Best Practices

### **DO:**
✅ Use church domain email
✅ Include unsubscribe option
✅ Personalize with member names
✅ Test before sending to all
✅ Keep content relevant
✅ Send at appropriate times

### **DON'T:**
❌ Send too frequently
❌ Use ALL CAPS
❌ Include too many links
❌ Send without permission
❌ Forget to proofread

---

## Production Recommendations

### **For Large Churches (1000+ members):**
- Use **SendGrid** or **Mailgun**
- Enable **queue processing**
- Set up **dedicated IP**
- Monitor **bounce rates**

### **Queue Setup (Optional):**

1. Update `.env`:
```env
QUEUE_CONNECTION=database
```

2. Run migration:
```bash
php artisan queue:table
php artisan migrate
```

3. Start queue worker:
```bash
php artisan queue:work
```

This sends emails in background without slowing down the app!

---

## Email Limits

| Provider | Free Tier | Paid |
|----------|-----------|------|
| SendGrid | 100/day | From $15/month |
| Mailgun | 5,000/month (3 months) | From $35/month |
| Gmail | 500/day | N/A |
| Amazon SES | 62,000/month | $0.10/1000 |

---

## Support

**Email not working?**
1. Check configuration
2. Review error logs
3. Test with simple email first
4. Verify credentials

**Need help?**
- Check Laravel documentation
- Contact email provider support
- Review firewall settings

---

## ✅ Quick Checklist

- [ ] Choose email provider
- [ ] Sign up for account
- [ ] Get API credentials
- [ ] Update .env file
- [ ] Clear config cache
- [ ] Send test email
- [ ] Create first campaign
- [ ] Monitor delivery

**Email system ready!** 📧

