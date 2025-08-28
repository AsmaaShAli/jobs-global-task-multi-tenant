# 🏗 Multi-Tenant Laravel Application

This project is a **multi-tenant Laravel 12 application** with authentication, multi-database support, queue processing, and Elasticsearch integration.  
It was developed as part of a recruitment task and demonstrates **multi-tenancy, Passport authentication, Horizon queue management, and asynchronous Elasticsearch syncing**.

---

## 🚀 Features
- **Laravel Sail with Docker Compose** (PHP, MySQL, Redis, Elasticsearch)
- **Laravel Passport** for authentication
- **Multi-tenancy** with dynamic DB connections per tenant
- **Separate tenant databases** managed via `Tenant` model
- **Custom Artisan Command** `tenants:migrate` to run migrations for all tenants
- **Job postings** synced asynchronously with **Elasticsearch**
- **Laravel Horizon** for queue management
- **Events dispatched after response** for better performance
- API endpoints for tenant creation and job posting management

---

## 🛠 Setup Instructions

### 1. Clone the repository
```bash
git clone https://github.com/AsmaaShAli/jobs-global-task-multi-tenant.git
cd jobs-global-task-multi-tenant
```

### 2. Start Docker services
```bash
./vendor/bin/sail up -d
```

### 3. Install dependencies
```bash
./vendor/bin/sail composer install
./vendor/bin/sail artisan key:generate
```

### 4. Run migrations for central database
```bash
./vendor/bin/sail artisan migrate
```


### 5. Create tenants
```bash
./vendor/bin/sail artisan tinker

>>> \App\Models\Tenant::create([
    'name' => 'Tenant A',
    'slug' => 'tenant-a',
    'db_database' => 'tenant_a_db',
    'db_host' => 'mysql',
    'db_username' => 'sail',
    'db_password' => 'password',
]);
```

### Run tenant migrations
```bash
./vendor/bin/sail artisan tenants:migrate
```
## 🗃 Queue & Horizon
```bash
./vendor/bin/sail artisan horizon
```
Jobs  are dispatched after response using queueable events.

### 🧪 Testing
Run tests with:
```bash
./vendor/bin/sail artisan test
```
> **P.S: it should work, but it didn't for some problem with elastic search, but when I ran the tests manually with phpstorm, each ran successfully.**


##  📡 API Endpoints
### Tenant Management

- ``` POST /api/tenants```  → Create a new tenant

- ``` GET /api/tenants```  → List tenants (not implemented)

### Job Management

- ``` POST /api/jobs```  → Create a job (auto-sync to Elasticsearch via queue)

- ``` GET /api/jobs/search?query=developer```  → Search jobs from Elasticsearch (not implemented)

Tenant context is determined via a custom header:
``` X-Tenant: tenant-slug```


## ⚡ Custom Artisan Commands

- Run migrations for all tenants:
```bash
./vendor/bin/sail artisan tenants:migrate

```
- Run fresh migrations with seeding:
```bash
./vendor/bin/sail artisan tenants:migrate --fresh --seed

```
## 📖 Notes

- Tenant creation bypasses middleware (since no tenant exists yet).

- All other endpoints require X-Tenant header.

- Elasticsearch syncing is async (check Horizon dashboard).

