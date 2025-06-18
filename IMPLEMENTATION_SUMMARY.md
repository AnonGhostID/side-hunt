# Lowongan Terdaftar Implementation Summary

## Overview
This implementation creates a complete job application system under the `dewa/mitra` section, allowing mitra users to manage their job postings and applications.

## Database Structure

### Updated Pelamar Table
- `id` (Primary Key)
- `user_id` (Foreign Key to users table)
- `job_id` (Foreign Key to pekerjaans table)
- `status` (ENUM: 'pending', 'diterima', 'ditolak') - Default: 'pending'
- `created_at` (Timestamp - when user applied)
- `updated_at` (Timestamp - when status was last updated)

## Routes Created

### Job Application Routes
- `POST /kerja/lamar/{id}` - Apply for a job (users only)
- `GET /kerja/{id}` - View job details

### Mitra Management Routes
- `GET /dewa/mitra/lowongan-terdaftar` - View registered jobs with applicants (mitra/admin only)
- `PATCH /dewa/mitra/pelamar/{pelamar}/terima` - Accept applicant (mitra/admin only)
- `PATCH /dewa/mitra/pelamar/{pelamar}/tolak` - Reject applicant (mitra/admin only)

## Controller Methods

### PekerjaanController
1. `lamarPekerjaan($id)` - Handle job application
   - Validates user login
   - Prevents self-application
   - Prevents duplicate applications
   - Creates application record with 'pending' status

2. `lowonganTerdaftar()` - Display mitra's jobs with applicants
   - Shows jobs created by logged-in mitra
   - Includes applicant details and status
   - Paginated results

3. `terima(Pelamar $pelamar)` - Accept job applicant
4. `tolak(Pelamar $pelamar)` - Reject job applicant

## Models Updated

### Pelamar Model
- Added datetime casting for timestamps
- Relationships: belongsTo Users, belongsTo Pekerjaan

### Pekerjaan Model
- Updated relationship: hasMany Pelamar instead of belongsToMany

## Views Created

### 1. Job Detail Page (`pekerjaan-detail.blade.php`)
- Enhanced with application status checking
- Shows different UI states:
  - Owner of job: "This is your job"
  - Already applied: Shows status (pending/accepted/rejected) with timestamp
  - Can apply: "Apply" button
  - Not logged in: "Login to apply" button

### 2. Lowongan Terdaftar Page (`lowongan-terdaftar.blade.php`)
- Lists all jobs created by the mitra
- Expandable cards showing job details
- Tabbed interface for applicant filtering:
  - All applicants
  - Pending applications
  - Accepted applications
  - Rejected applications
- Actions: Accept/Reject applicants

### 3. Applicant Card Partial (`partials/applicant-card.blade.php`)
- Displays applicant information
- Shows application timestamp
- Status badges with appropriate colors
- Action buttons (Accept/Reject for pending applications)
- Link to applicant profile

## Features

### Job Application Process
1. User views job detail page
2. If eligible, user can click "Apply" button
3. Confirmation dialog appears
4. Application is submitted with POST request
5. Record created in database with status 'pending'
6. User sees updated status on job detail page

### Mitra Job Management
1. Mitra can access `/dewa/mitra/lowongan-terdaftar`
2. View all their posted jobs
3. See applicant counts and details
4. Filter applicants by status
5. Accept or reject applications
6. View applicant profiles

### Status Management
- **pending**: Initial status when user applies
- **diterima**: When mitra accepts the application
- **ditolak**: When mitra rejects the application

## Security Features
- Role-based access control (mitra/admin only for management)
- CSRF protection on all forms
- User validation (can't apply to own jobs)
- Duplicate application prevention

## UI/UX Features
- Responsive design
- Interactive confirmations using SweetAlert
- Status badges with color coding
- Tabbed interface for easy filtering
- Loading states and transitions
- Bootstrap icons for better UX

## Database Relationships
```
Users (1) ----< Pelamar (Many)
Pekerjaan (1) ----< Pelamar (Many)
Users (1) ----< Pekerjaan (Many) [as pembuat/creator]
```

## Access Control
- Job application: Only users with role 'user'
- Lowongan management: Only users with role 'mitra' or 'admin'
- Status updates: Only job creator (mitra) or admin

This implementation provides a complete job application and management system integrated with the existing Laravel application structure.
