# Railway Deployment Guide for Symfony Pharmacy Management System

## Prerequisites
1. Railway account: https://railway.app
2. GitHub repository with your code
3. MySQL database (Aiven or Railway-provided)

## Deployment Steps

### 1. Create New Project on Railway
```bash
# Go to https://railway.app/new
# Click "Deploy from GitHub repo"
# Select your repository
```

### 2. Add MySQL Database
```bash
# Option A: Use existing Aiven MySQL
# Set DATABASE_URL in Railway environment variables

# Option B: Provision Railway MySQL
# Click "+ New" → "Database" → "Add MySQL"
# Railway will automatically set DATABASE_URL
```

### 3. Configure Environment Variables
Go to your project → Variables tab and add:

```env
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=your-generated-secret-here
DATABASE_URL=mysql://user:pass@host:port/dbname?serverVersion=8.0&charset=utf8mb4

# If using Aiven MySQL with SSL:
DATABASE_URL=mysql://user:password@host.aivencloud.com:port/dbname?serverVersion=8.0&charset=utf8mb4&sslmode=verify-ca&sslrootcert=/app/ca.pem
```

### 4. Upload SSL Certificate (if using Aiven)
```bash
# In Railway project settings → Files
# Upload ca.pem to /app/ca.pem
```

### 5. Deploy
```bash
# Railway will automatically:
# 1. Install dependencies (composer install)
# 2. Run migrations (doctrine:migrations:migrate)
# 3. Clear cache (cache:clear --env=prod)
# 4. Start PHP server
```

### 6. Access Your Application
```bash
# Railway will provide a public URL:
# https://your-app.up.railway.app
```

## Post-Deployment

### Load Initial Data (Optional)
```bash
# Connect to Railway CLI
railway login
railway link

# Run fixtures
railway run php bin/console doctrine:fixtures:load --no-interaction
```

### Create Admin User
```bash
railway run php bin/console app:create-admin admin@pharmacy.tn Admin123!
```

## Monitoring

### View Logs
```bash
railway logs
```

### Access Database
```bash
railway connect MySQL
```

## Troubleshooting

### Migration Errors
```bash
# Force migrations
railway run php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
```

### Cache Issues
```bash
railway run php bin/console cache:clear --env=prod
railway run php bin/console cache:warmup --env=prod
```

### SSL Certificate Issues
- Ensure ca.pem is uploaded to /app/ca.pem
- Verify DATABASE_URL includes sslrootcert path
- Check sslmode is set to verify-ca

## Files Added for Railway Deployment

1. **railway.json** - Railway-specific configuration
2. **nixpacks.toml** - Build and deployment instructions
3. **Procfile** - Process configuration
4. **.env.production** - Production environment template

## Default Credentials

After loading fixtures:

- **Admin**: admin1@pharmacy.tn / admin123
- **Pharmacy**: pharmacy1@pharmacy.tn / pharmacy123
- **Supplier**: supplier1@pharmacy.tn / supplier123
- **Delivery**: delivery1@pharmacy.tn / delivery123

## Support

For issues:
1. Check Railway logs
2. Verify environment variables
3. Ensure database is accessible
4. Check migration status
