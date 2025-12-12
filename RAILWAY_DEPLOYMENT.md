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

# For Aiven MySQL with SSL
# Note: The application automatically creates the SSL certificate file from DATABASE_SSL_CA
# Replace with your actual Aiven credentials
DATABASE_URL=mysql://user:password@host.aivencloud.com:port/defaultdb?serverVersion=8.0&charset=utf8mb4&driverOptions[1009]=var/ssl/ca.pem&driverOptions[1014]=false

# Aiven SSL Certificate (paste your full certificate content below)
# The Kernel will automatically write this to var/ssl/ca.pem on boot
DATABASE_SSL_CA=-----BEGIN CERTIFICATE-----
MIIEUDCCArigAwIBAgIUMM1iUEUcLWoc566hsYfZPVIOHyowDQYJKoZIhvcNAQEM
BQAwQDE+MDwGA1UEAww1MWJlN2FhOTEtYTcxZS00OGEzLWExMjctYWFiNjcyMmE5
YjNkIEdFTiAxIFByb2plY3QgQ0EwHhcNMjUxMjEwMjIxNDAxWhcNMzUxMjA4MjIx
NDAxWjBAMT4wPAYDVQQDDDUxYmU3YWE5MS1hNzFlLTQ4YTMtYTEyNy1hYWI2NzIy
YTliM2QgR0VOIDEgUHJvamVjdCBDQTCCAaIwDQYJKoZIhvcNAQEBBQADggGPADCC
AYoCggGBALXuPxd6nvaRfLTKM5XaO5Z+5RUmf54vipTk3o2+zHFQA3OviNRHt3yc
epMjk7TdldQP8DmPsRQNy3jVxILukS6wMyCyeYv3ulK+iE00gYatuernrwBVmjxL
l/qSiiXqEgnER0hUqr17buLJb9oIahz4t9oAVHRdmUz2Gq6UvbFNF6SO00qXZdb8
5gTLZfUKHjVG59PKq0qUR9/YUh5IRLKjBO4zdMHBZDBvjNV3Gk1S07bDhcNlMgYC
X/yp5REEpsPYTw+Pv8Bifuna4nPcMxzXy+WAzPm1IGahfCj2ug+kAnEXCx5BUNWF
u55+3LkV0pgT5WIdOIkhk/t1uNxdEfPpdZcb1+ynkxw5DjfEkOkvv9DXld/YuMWf
xBOv5KRAHxEszdi6kDdVHv1hVpEN9wmwebQyJmkTirREjQkEnzGztXyX10u6x+xj
72AfyTrldH0wjDL7f4dKeK+C1zbhtsZ0MgJr7kMakNv6aBFI+OKi2rUHah7jvrAZ
2PBhheGyowIDAQABo0IwQDAdBgNVHQ4EFgQU7L+dr9+Z7SOQf+TsXJaCHJbGInQw
EgYDVR0TAQH/BAgwBgEB/wIBADALBgNVHQ8EBAMCAQYwDQYJKoZIhvcNAQEMBQAD
ggGBAAowLvLykYQ2h+pwO3ysd9Y025iveJ/gdwK0IWsLM6zJFQ0mzmC2ki7P0+iB
5PQhjBTIJuRjDSArcrYpFalowkhtz87t+zAsOwxN0h87bbcjOfXOt4fWpipFeCqY
gcGw9H2weW03HJDd7zrN/s2kV5oABxfFw7BHRQSO9AlO2QjkSTty6uBAcBOK92G7
LZ1UrK1Mxcj7CBMGvqDOe2Roq0hK34QzFkqC4Y59p1cGJFKozRU+j15dCQPRln+T
IlpTGHdGFASsxk5ZPrmAuNKSDrOVpVInfica0KIk7DjE3KwyNOqvXL2rkukpryOL
TVbZzPhqlj2dkeFC9/lGg5rb6cax/acuYgaX4A76/doxnnkJMLIOvYevahcbca2G
QE7pFTnhkgXlnxlmnMkodaoN7ZqzobGWm+zd50BZQWhzB2hajxgr7AsbOIuEvqF/
YGD0jXa6ZYsi8hgIuqDhyo0Rpyagj9ZhFfTU4UELRX88G4uN+1626+U7DSFGGxpK
edBpRw==
-----END CERTIFICATE-----
```

**Note**: The CA certificate is now stored as an environment variable, eliminating the need to upload files to Railway.

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
- Ensure DATABASE_SSL_CA environment variable contains the full certificate (including BEGIN/END lines)
- The application automatically writes the certificate to `var/ssl/ca.pem` on boot
- Verify DATABASE_URL includes `driverOptions[1009]=var/ssl/ca.pem&driverOptions[1014]=false`
- Check that the `var` directory is writable
- No manual file uploads needed - everything is automated via environment variables

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
