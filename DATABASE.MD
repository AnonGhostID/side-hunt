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

### 2. Side Job Relationships

#### Side Job → Applications (One-to-Many)
```php
// SideJob Model
public function pelamar() {
    return $this->belongsToMany(SideJob::class, 'pelamars', 'user_id', 'job_id');
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
       (pembuat_id/pekerja_id)

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
6. **Communication**: Users communicate via chat system (`ch_messages`)

## Data Integrity Features

- **Cascading Deletes**: When users are deleted, their jobs, applications, and transactions are automatically removed
- **Unique Constraints**: Email and phone numbers are unique across users
- **Default Values**: Sensible defaults for wallet balance, admin status, and application status
- **Enumerated Values**: Controlled vocabulary for application and transaction statuses
- **UUID Primary Keys**: Transactions use UUIDs for better security and distribution
