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
git clone https://github.com/<your-username>/<repo-name>.git
cd <repo-name>

