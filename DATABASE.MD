# Side Hunt - Database Relations Documentation

## Overview
Side Hunt is a Laravel-based job marketplace application that connects job creators with workers. This document provides a comprehensive overview of all database tables and their relationships within the system.

## Database Architecture

### Core Tables Structure

#### 1. Users Table
**Primary Table**: `users`
- **Primary Key**: `id` (auto-increment)
- **Purpose**: Stores user information for both job creators and workers

**Fields:**
- `id` - Unique identifier
- `nama` - User's full name
- `email` - Unique email address
- `alamat` - User's address
- `telpon` - Unique phone number
- `dompet` - User's wallet balance (integer, default: 0)
- `isAdmin` - Admin status (boolean, default: false)
- `avatar` - Profile picture path
- `messenger_color` - Chat theme color
- `active_status` - Online status for chat
- `dark_mode` - UI preference
- `email_verified_at` - Email verification timestamp
- `password` - Hashed password
- `remember_token` - Session token
- `created_at`, `updated_at` - Timestamps

#### 2. Side Jobs Table
**Primary Table**: `side_jobs`
- **Primary Key**: `id` (auto-increment)
- **Purpose**: Stores job postings created by users

**Fields:**
- `id` - Unique job identifier
- `nama` - Job title
- `deskripsi` - Job description
- `tanggal_buat` - Job creation date
- `alamat` - Job location address
- `koordinat` - GPS coordinates
- `min_gaji` - Minimum salary
- `max_gaji` - Maximum salary
- `max_pekerja` - Maximum number of workers needed
- `jumlah_pelamar_diterima` - Number of accepted applicants (default: 0)
- `pembuat` - Foreign key to users table (job creator)
- `created_at`, `updated_at` - Timestamps

#### 3. Pelamars Table (Job Applications)
**Pivot Table**: `pelamars`
- **Primary Key**: `id` (auto-increment)
- **Purpose**: Manages job applications and their status

**Fields:**
- `id` - Unique application identifier
- `user_id` - Foreign key to users (applicant)
- `job_id` - Foreign key to side_jobs
- `status` - Application status: ['tunda', 'diterima', 'ditolak'] (default: 'tunda')
- `created_at`, `updated_at` - Timestamps

#### 4. Transaksis Table (Transactions)
**Primary Table**: `transaksis`
- **Primary Key**: `kode` (UUID)
- **Purpose**: Handles financial transactions between users

**Fields:**
- `kode` - UUID primary key
- `pembuat_id` - Foreign key to users (transaction creator)
- `pekerja_id` - Foreign key to users (worker/receiver)
- `jumlah` - Transaction amount
- `status` - Transaction status: ['tertunda', 'sukses', 'gagal'] (default: 'tertunda')
- `dibuat` - Transaction creation datetime
- `created_at`, `updated_at` - Timestamps

#### 5. Payments Table (Payment Gateway)
**Primary Table**: `payments`
- **Primary Key**: `id` (auto-increment)
- **Purpose**: Handles payment gateway transactions for top-up and wallet management

**Fields:**
- `id` - Unique payment identifier
- `user_id` - Foreign key to users (payment owner)
- `checkout_link` - Payment gateway URL
- `external_id` - External payment identifier for tracking
- `status` - Payment status: ['pending', 'paid', 'settled', 'failed', 'expired', 'cancelled'] (default: 'pending')
- `method` - Payment method used (e.g., 'bank_transfer', 'ewallet', etc.)
- `amount` - Payment amount (decimal)
- `description` - Payment description
- `created_at`, `updated_at` - Timestamps

## Database Relationships

### 1. User Relationships

#### User → Side Jobs (One-to-Many)
```php
// User Model
public function pembuat() {
    return $this->hasMany(SideJob::class, 'pembuat');
}

// SideJob Model  
public function pembuat() {
    return $this->belongsTo(User::class, 'pembuat');
}
```
**Relationship**: One user can create multiple jobs, but each job belongs to one creator.

#### User → Job Applications (Many-to-Many through Pelamars)
```php
// User Model
public function pelamar() {
    return $this->belongsToMany(User::class, 'pelamars');
}

// Pelamar Model
public function user() {
    return $this->belongsTo(User::class);
}
```
**Relationship**: Users can apply to multiple jobs, and jobs can have multiple applicants.

#### User → Transactions (One-to-Many as Creator)
```php
// Transaksi Model
public function pembuat() {
    return $this->belongsTo(User::class, 'pembuat_id');
}
```

#### User → Transactions (One-to-Many as Worker)
```php
// Transaksi Model
public function pekerja() {
    return $this->belongsTo(User::class, 'pekerja_id');
}
```
**Relationship**: Users can be involved in multiple transactions as either creator or worker.

#### User → Payments (One-to-Many)
```php
// User Model
public function payments() {
    return $this->hasMany(Payment::class);
}

// Payment Model
public function user() {
    return $this->belongsTo(User::class);
}
```
**Relationship**: Users can have multiple payment transactions for top-up and wallet management.

### 2. Side Job Relationships

#### Side Job → Applications (One-to-Many)
```php
// SideJob Model
public function pelamar() {
    return $this->belongsToMany(User::class, 'pelamars', 'job_id', 'user_id');
}

// Pelamar Model
public function sidejob() {
    return $this->belongsTo(SideJob::class, 'job_id');
}
```
**Relationship**: Each job can have multiple applications through the pelamars pivot table.

#### Side Job → Transactions (One-to-Many)
```php
// Transaksi Model  
public function job() {
    return $this->belongsTo(SideJob::class, 'job_id');
}
```
**Relationship**: Jobs can generate multiple transactions for payments.

### 3. Application (Pelamar) Relationships

#### Application → User (Many-to-One)
**Relationship**: Each application belongs to one user (applicant).

#### Application → Side Job (Many-to-One) 
**Relationship**: Each application is for one specific job.

### 4. Transaction Relationships

#### Transaction → Users (Many-to-One for Creator and Worker)
**Relationship**: Each transaction involves two users - one as creator and one as worker.

### 5. Payment Relationships

#### Payment → User (Many-to-One)
```php
// Payment Model
public function user() {
    return $this->belongsTo(User::class);
}
```
**Relationship**: Each payment belongs to one user.

#### Payment Status Methods
```php
// Payment Model Helper Methods
public function isPaid() {
    return $this->status === 'paid' || $this->status === 'settled';
}

public function isPending() {
    return $this->status === 'pending';
}

public function isExpired() {
    return $this->isPending() && $this->created_at->diffInHours(now()) > 24;
}
```
**Helper Methods**: Payment model includes useful status checking methods for business logic.

## Chat System Tables

### Chat Messages (`ch_messages`)
- **Primary Key**: `id` (UUID)
- **Purpose**: Stores chat messages between users

**Fields:**
- `id` - UUID primary key
- `from_id` - Sender user ID
- `to_id` - Receiver user ID  
- `body` - Message content (max 5000 chars)
- `attachment` - File attachment path
- `seen` - Read status (boolean)
- `created_at`, `updated_at` - Timestamps

### Chat Favorites (`ch_favorites`)
- **Primary Key**: `id` (UUID)
- **Purpose**: Stores user's favorite contacts

**Fields:**
- `id` - UUID primary key
- `user_id` - User who favorited
- `favorite_id` - Favorited user ID
- `created_at`, `updated_at` - Timestamps

## Foreign Key Constraints

The system implements the following foreign key constraints for data integrity:

```sql
-- Side Jobs Table
ALTER TABLE side_jobs ADD CONSTRAINT 
FOREIGN KEY (pembuat) REFERENCES users(id) ON DELETE CASCADE;

-- Pelamars Table
ALTER TABLE pelamars ADD CONSTRAINT 
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE pelamars ADD CONSTRAINT 
FOREIGN KEY (job_id) REFERENCES side_jobs(id) ON DELETE CASCADE;

-- Transaksis Table  
ALTER TABLE transaksis ADD CONSTRAINT 
FOREIGN KEY (pembuat_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE transaksis ADD CONSTRAINT 
FOREIGN KEY (pekerja_id) REFERENCES users(id) ON DELETE CASCADE;

-- Payments Table
ALTER TABLE payments ADD CONSTRAINT 
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
```

## Laravel System Tables

### Framework Tables
- `jobs` - Laravel queue jobs
- `job_batches` - Job batch processing
- `failed_jobs` - Failed queue jobs
- `sessions` - User sessions
- `password_reset_tokens` - Password reset tokens
- `cache` & `cache_locks` - Application caching
- `migrations` - Migration tracking

## Entity Relationship Summary

```
Users (1) ──────────────────(M) Side Jobs
  │                              │
  │                              │
  │         Pelamars             │
  │    (M)──────────(M)          │
  │                              │
  │                              │
  └──(M) Transactions (M)────────┘
  │    (pembuat_id/pekerja_id)
  │
  └──(1) Payments (M)

Users (M)──────────(M) Chat Messages
  │                    (from_id/to_id)
  │
  └──(M) Chat Favorites (M)────Users
```

## Application Flow

1. **User Registration**: Users register and are stored in the `users` table
2. **Job Creation**: Users create jobs stored in `side_jobs` with foreign key to creator
3. **Job Application**: Other users apply through `pelamars` pivot table
4. **Application Management**: Job creators can accept/reject applications
5. **Transaction Processing**: Accepted jobs generate transactions in `transaksis`
6. **Payment Gateway Integration**: Users can top-up wallet balance through `payments` table using Xendit gateway
7. **Wallet Management**: Enhanced wallet system with payment tracking and transaction history
8. **Communication**: Users communicate via chat system (`ch_messages`)

## Payment Gateway Integration

### Xendit Integration
The application integrates with Xendit payment gateway for secure payment processing:

- **TopUp Functionality**: Users can add funds to their wallet through various payment methods
- **Real-time Status Tracking**: Automatic payment status updates and synchronization
- **Multiple Payment Methods**: Support for bank transfers, e-wallets, and other payment channels
- **Security Features**: Secure external ID generation and payment validation
- **Automatic Wallet Updates**: Successful payments automatically update user wallet balance

### Payment States
- `pending`: Payment created, waiting for user action
- `paid`/`settled`: Payment completed successfully
- `failed`: Payment processing failed
- `expired`: Payment expired (24-hour timeout)
- `cancelled`: Payment cancelled by user

## Enhanced Features

### Management Dashboard
- **Financial Management**: Top-up, transaction history, and wallet management
- **Job Management**: Enhanced job creation and application tracking
- **Reporting System**: Comprehensive reporting and analytics
- **Admin Controls**: Administrative functions for system management

### Security Improvements
- **Payment Validation**: Secure payment processing with external ID verification
- **User Authentication**: Enhanced authentication with admin role management
- **Data Integrity**: Comprehensive foreign key constraints and validation
- **Transaction Locking**: Database locking for concurrent payment processing

## Data Integrity Features

- **Cascading Deletes**: When users are deleted, their jobs, applications, transactions, and payments are automatically removed
- **Unique Constraints**: Email and phone numbers are unique across users
- **Default Values**: Sensible defaults for wallet balance, admin status, and application status
- **Enumerated Values**: Controlled vocabulary for application, transaction, and payment statuses
- **UUID Primary Keys**: Transactions use UUIDs for better security and distribution
- **Payment Security**: External ID validation and secure payment processing
- **Database Locking**: Prevents race conditions in payment processing and wallet updates

## API Endpoints

### Payment API
- `POST /management/Top-Up` - Create new payment invoice
- `GET /management/Top-Up/{external_id}` - View payment status page
- `POST /management/Top-Up/check-status` - Check payment status via AJAX
- `POST /management/Top-Up/cancel/{external_id}` - Cancel pending payment
- `POST /api/payment/cleanup-expired` - Cleanup expired payments (scheduled task)

### Management API
- `GET /management/riwayat-transaksi/data` - Fetch transaction history data
- Various management endpoints for financial and administrative functions

## Recent Updates (June 2025)

### Payment Gateway Implementation
- **New Payments Table**: Complete payment gateway integration using Xendit
- **TopUpController**: Comprehensive payment processing controller
- **Real-time Status Updates**: AJAX-based payment status monitoring
- **Multiple Payment Methods**: Support for various Indonesian payment channels

### Enhanced User Experience
- **Management Dashboard**: Centralized management interface for users
- **Financial Controls**: Advanced wallet and transaction management
- **Improved Security**: Enhanced payment validation and user authentication
- **Responsive Design**: Mobile-friendly payment interfaces

### Model Corrections
- **Fixed SideJob Relationships**: Corrected pelamar relationship mapping
- **Enhanced Payment Model**: Added status checking helper methods
- **Improved Transaction Handling**: Better error handling and logging

## Technical Implementation

### Key Controllers

#### TopUpController
Handles payment gateway integration and wallet management:
```php
// Key features:
- Payment invoice creation with Xendit API
- Real-time payment status checking
- Automatic wallet balance updates
- Payment cancellation and expiry handling
- Database transaction locking for consistency
```

#### ManagementPageController
Provides centralized management functionality:
```php
// Key features:
- Dashboard analytics and statistics
- Financial transaction history with AJAX pagination
- Payment management and reporting
- User activity monitoring
```

#### TransaksiController
Manages job-related financial transactions:
```php
// Key features:
- Transaction creation between job creators and workers
- Transaction status management (tertunda, sukses, gagal)
- Admin approval workflow for transactions
- Integration with user wallet system
```

### Database Architecture Principles

#### ACID Compliance
- **Atomicity**: Payment processing uses database transactions
- **Consistency**: Foreign key constraints maintain data integrity
- **Isolation**: Row-level locking prevents concurrent update conflicts
- **Durability**: All financial transactions are permanently logged

#### Scalability Considerations
- **UUID Primary Keys**: Used for transactions to support distributed systems
- **Indexed Columns**: Payment status and creation timestamps are indexed
- **Pagination**: Large data sets use Laravel pagination for performance
- **Caching Strategy**: Session-based caching for user data

#### Security Architecture
- **Input Validation**: Comprehensive validation for all financial operations
- **CSRF Protection**: All forms include CSRF tokens
- **Authentication Middleware**: Protected routes require authentication
- **Admin Authorization**: Administrative functions require admin privileges
- **Payment Verification**: External payment validation through Xendit API
