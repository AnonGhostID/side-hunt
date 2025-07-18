# Activity Diagrams - Manajemen Features

This document contains activity diagrams for all 18 management features in the Laravel job marketplace application. Each diagram shows the workflow between different actors (User, Mitra, System, Admin, Payment Gateway, Database) for various management operations.

## 1. Job Management Features

### 1.1 Panel Manajemen Pekerjaan Aktif Pengguna Pekerja

```
'Panel Manajemen Pekerjaan Aktif Pengguna Pekerja' {
    |User, System, Database| {
        $User$
        (User) Access pekerjaan berlangsung page;
        (System) Authenticate user session;
        (System) Check user role is 'user';
        (Database) Query Pelamar table for user applications;
        (Database) Load related sidejob and user data;
        (System) Filter applications by status;
        (System) Display active job applications;
        (User) View job details and status;
        <User> Need to update application? {
            -Yes- {
                (User) Click on job for more details;
                (System) Redirect to job management page;
            }
            -No- {
                (User) Continue monitoring status;
            }
        }
        >User<;
        @User@
    }
}
```

### 1.2 Panel Manajemen Pekerjaan Aktif Pembuat Kerja

```
'Panel Manajemen Pekerjaan Aktif Pembuat Kerja' {
    |Mitra, System, Database| {
        $Mitra$
        (Mitra) Access pekerjaan berlangsung page;
        (System) Authenticate mitra session;
        (System) Check user role is 'mitra';
        (Database) Query Pekerjaan table for mitra jobs;
        (Database) Get applicants for each job;
        (System) Load job creators and applicant data;
        (System) Display jobs with applicants;
        (Mitra) View applicant details and job status;
        <Mitra> Need to manage applicants? {
            -Yes- {
                (Mitra) Click manage job;
                (System) Redirect to job management page;
            }
            -No- {
                (Mitra) Continue monitoring applications;
            }
        }
        >Mitra<;
        @Mitra@
    }
}
```

### 1.3 Verifikasi Hasil Pekerjaan oleh Pemberi Kerja

```
'Verifikasi Hasil Pekerjaan oleh Pemberi Kerja' {
    |Mitra, System, Database, User| {
        $Mitra$
        (Mitra) Access job management page;
        (System) Authenticate mitra session;
        (Database) Load job details and reports;
        (System) Display submitted reports;
        (Mitra) Review work reports and documentation;
        <Mitra> Accept work results? {
            -Yes- {
                (Mitra) Click accept results;
                (System) Validate mitra permissions;
                (System) Update job status to 'Selesai';
                (Database) Create successful transaction;
                (Database) Update user wallet balance;
                (System) Send notification to worker;
                (System) Display success message;
            }
            -No- {
                (Mitra) Provide feedback to worker;
                (System) Send feedback notification;
                (Database) Update job status to pending review;
            }
        }
        >System<;
        @Mitra@
    }
}
```

### 1.4 Upload Laporan Hasil Pekerjaan

```
'Upload Laporan Hasil Pekerjaan' {
    |User, System, Database| {
        $User$
        (User) Access upload laporan page;
        (System) Authenticate user session;
        (Database) Query accepted jobs for user;
        (System) Filter jobs that are not yet finished;
        (System) Display available jobs for reporting;
        (User) Select job to report;
        (User) Fill report description;
        (User) Upload selfie photo;
        (User) Upload work documentation;
        (User) Submit report form;
        (System) Validate form data and files;
        <System> Validation successful? {
            -Yes- {
                (System) Check job completion status;
                (System) Verify user assignment to job;
                (System) Check for existing reports;
                (Database) Store report files;
                (Database) Save report record;
                (System) Display success message;
            }
            -No- {
                (System) Display validation errors;
                (User) Correct form errors;
            }
        }
        >System<;
        @User@
    }
}
```

### 1.5 Riwayat Pekerjaan

```
'Riwayat Pekerjaan' {
    |User, System, Database| {
        $User$
        (User) Access riwayat pekerjaan page;
        (System) Authenticate user session;
        (Database) Query completed jobs for user;
        (Database) Load job details and creators;
        (Database) Get rating information;
        (System) Process job history data;
        (System) Display completed jobs with ratings;
        (User) Review job history;
        <User> Want to rate employer? {
            -Yes- {
                (User) Click rating button;
                (System) Open rating modal;
                (User) Submit rating and comment;
                (Database) Store rating record;
                (System) Update display;
            }
            -No- {
                (User) Continue browsing history;
            }
        }
        >User<;
        @User@
    }
}
```

## 2. Financial Management Features

### 2.1 Pengisian Saldo via Payment Gateway

```
'Pengisian Saldo via Payment Gateway' {
    |User, System, Payment Gateway, Database| {
        $User$
        (User) Access top-up page;
        (System) Display top-up form;
        (User) Enter top-up amount;
        (User) Select payment method;
        (User) Submit top-up request;
        (System) Validate amount and method;
        (System) Generate external_id;
        (Database) Create pending transaction;
        (Payment Gateway) Create payment invoice;
        (System) Redirect to payment page;
        (User) Complete payment;
        <Payment Gateway> Payment successful? {
            -Yes- {
                (Payment Gateway) Send webhook notification;
                (System) Process payment callback;
                (Database) Update transaction status;
                (Database) Add amount to user wallet;
                (System) Send success notification;
            }
            -No- {
                (Payment Gateway) Send failure notification;
                (System) Update transaction as failed;
                (System) Display error message;
            }
        }
        >System<;
        @User@
    }
}
```

### 2.2 Penarikan Saldo via Payment Gateway

```
'Penarikan Saldo via Payment Gateway' {
    |User, System, Payment Gateway, Database| {
        $User$
        (User) Access tarik saldo page;
        (System) Display withdrawal form;
        (System) Show supported banks and e-wallets;
        (User) Enter withdrawal amount;
        (User) Select withdrawal method;
        (User) Enter account details;
        (User) Submit withdrawal request;
        (System) Validate amount and account;
        <System> Sufficient balance? {
            -Yes- {
                (Database) Create pending payout;
                (Database) Deduct amount from wallet;
                (Payment Gateway) Create payout request;
                <Payment Gateway> Payout successful? {
                    -Yes- {
                        (Database) Update payout status;
                        (System) Send success notification;
                    }
                    -No- {
                        (Database) Refund wallet balance;
                        (Database) Update payout as failed;
                        (System) Display error message;
                    }
                }
                >Payment Gateway<;
            }
            -No- {
                (System) Display insufficient balance error;
            }
        }
        >System<;
        @User@
    }
}
```

### 2.3 Riwayat Transaksi

```
'Riwayat Transaksi' {
    |User, System, Database| {
        $User$
        (User) Access riwayat transaksi page;
        (System) Authenticate user session;
        (Database) Query FinancialTransaction for user;
        (System) Paginate transaction results;
        (System) Display transaction history;
        (User) Browse transaction list;
        <User> Want to search transactions? {
            -Yes- {
                (User) Enter search criteria;
                (System) Filter transactions by search;
                (Database) Query filtered results;
                (System) Update display with results;
            }
            -No- {
                (User) Continue browsing;
            }
        }
        >User<;
        <User> Want to change page size? {
            -Yes- {
                (User) Select items per page;
                (System) Update pagination;
                (Database) Query new page size;
                (System) Refresh display;
            }
            -No- {
                (User) Continue with current view;
            }
        }
        >User<;
        @User@
    }
}
```

### 2.4 Laporan Keuangan Bulanan

```
'Laporan Keuangan Bulanan' {
    |User, System, Database| {
        $User$
        (User) Access laporan keuangan page;
        (System) Display month/year filter form;
        (User) Select reporting period;
        (User) Submit filter request;
        (System) Process period selection;
        (Database) Query top-up transactions;
        (Database) Query job income transactions;
        (Database) Query job expense transactions;
        (Database) Query withdrawal transactions;
        (System) Combine all transaction types;
        (System) Calculate income and expense totals;
        (System) Calculate net income;
        (System) Calculate transaction statistics;
        (System) Generate financial report;
        (System) Display comprehensive report;
        (User) Review financial summary;
        <User> Want to change period? {
            -Yes- {
                (User) Select different period;
                (System) Regenerate report;
            }
            -No- {
                (User) Continue with current report;
            }
        }
        >User<;
        @User@
    }
}
```

### 2.5 Refund Dana

```
'Refund Dana' {
    |User, System, Database, Admin| {
        $User$
        (User) Access refund dana page;
        (System) Display refund information;
        (User) Review refund policy;
        (User) Submit refund request;
        (System) Create refund ticket;
        (Database) Store refund request;
        (System) Send notification to admin;
        (Admin) Review refund request;
        <Admin> Approve refund? {
            -Yes- {
                (Admin) Process refund approval;
                (Database) Update user wallet;
                (System) Send approval notification;
                (System) Display refund success;
            }
            -No- {
                (Admin) Reject refund request;
                (System) Send rejection notification;
                (System) Display rejection reason;
            }
        }
        >Admin<;
        @User@
    }
}
```

## 3. Notification System Features

### 3.1 Riwayat Notifikasi

```
'Riwayat Notifikasi' {
    |User, System, Database| {
        $User$
        (User) Access notifications page;
        (System) Authenticate user session;
        (Database) Query notifications for user;
        (System) Order by creation date;
        (System) Display notification list;
        (User) Browse notifications;
        <User> Want to mark as read? {
            -Yes- {
                (User) Click notification;
                (System) Mark notification as read;
                (Database) Update read status;
                (System) Update notification display;
            }
            -No- {
                (User) Continue browsing;
            }
        }
        >User<;
        <User> Want to delete notification? {
            -Yes- {
                (User) Click delete button;
                (System) Confirm deletion;
                (Database) Remove notification;
                (System) Update display;
            }
            -No- {
                (User) Keep notification;
            }
        }
        >User<;
        <User> Want to mark all as read? {
            -Yes- {
                (User) Click mark all read;
                (Database) Update all notifications;
                (System) Refresh display;
            }
            -No- {
                (User) Continue with current state;
            }
        }
        >User<;
        @User@
    }
}
```

### 3.2 Notifikasi Status Pelamar Pekerjaan Baru

```
'Notifikasi Status Pelamar Pekerjaan Baru' {
    |User, System, Database, Mitra| {
        $User$
        (User) Apply for job;
        (System) Process job application;
        (Database) Create Pelamar record;
        (System) Send notification to job creator;
        (Mitra) Receive application notification;
        (Mitra) Review application;
        <Mitra> Accept application? {
            -Yes- {
                (Mitra) Accept applicant;
                (System) Update application status;
                (Database) Store status change;
                (System) Create acceptance notification;
                (System) Send notification to applicant;
                (User) Receive acceptance notification;
            }
            -No- {
                (Mitra) Reject applicant;
                (System) Update application status;
                (Database) Store status change;
                (System) Create rejection notification;
                (System) Send notification to applicant;
                (User) Receive rejection notification;
            }
        }
        >System<;
        @User@
    }
}
```

### 3.3 Notifikasi Status Pelamaran Kerja

```
'Notifikasi Status Pelamaran Kerja' {
    |User, System, Database| {
        $User$
        (User) Check application status;
        (System) Query application records;
        (Database) Get application status changes;
        (System) Check for status updates;
        <System> Status changed? {
            -Yes- {
                (System) Create status notification;
                (Database) Store notification;
                (System) Send real-time notification;
                (User) Receive status update;
                (User) View updated status;
            }
            -No- {
                (System) Display current status;
                (User) Continue monitoring;
            }
        }
        >System<;
        <System> Job completed? {
            -Yes- {
                (System) Create completion notification;
                (Database) Store completion record;
                (System) Send completion notification;
                (User) Receive completion notice;
            }
            -No- {
                (System) Continue monitoring;
            }
        }
        >System<;
        @User@
    }
}
```

## 4. Rating System Features

### 4.1 Panel Rating Terhadap Pekerja

```
'Panel Rating Terhadap Pekerja' {
    |Mitra, System, Database| {
        $Mitra$
        (Mitra) Access job management page;
        (System) Display completed jobs;
        (Mitra) Select worker to rate;
        (System) Check rating permissions;
        <System> Can rate worker? {
            -Yes- {
                (System) Display rating form;
                (Mitra) Enter rating score;
                (Mitra) Write comment;
                (Mitra) Submit rating;
                (System) Validate rating data;
                (Database) Create rating record;
                (System) Update worker rating average;
                (System) Display success message;
            }
            -No- {
                (System) Display permission error;
                (System) Show rating restrictions;
            }
        }
        >System<;
        @Mitra@
    }
}
```

### 4.2 Panel Rating Terhadap Pemberi Kerja

```
'Panel Rating Terhadap Pemberi Kerja' {
    |User, System, Database| {
        $User$
        (User) Access riwayat pekerjaan page;
        (System) Display completed jobs;
        (User) Click rate employer button;
        (System) Check rating permissions;
        <System> Can rate employer? {
            -Yes- {
                (System) Open rating modal;
                (User) Enter rating score;
                (User) Write comment;
                (User) Submit rating;
                (System) Validate rating data;
                (Database) Create rating record;
                (System) Update employer rating average;
                (System) Close modal and update display;
            }
            -No- {
                (System) Display permission error;
                (System) Show rating restrictions;
            }
        }
        >System<;
        @User@
    }
}
```

## 5. Support System Features

### 5.1 Panel Bantuan dan Penipuan

```
'Panel Bantuan dan Penipuan' {
    |User, System, Database| {
        $User$
        (User) Access panel bantuan page;
        (System) Display existing tickets;
        (User) Click create new ticket;
        (System) Display ticket form;
        (User) Select ticket type;
        <User> Fraud report? {
            -Yes- {
                (User) Fill fraud report form;
                (User) Enter reported party details;
                (User) Enter incident date;
                (User) Upload evidence files;
                (User) Submit fraud report;
                (System) Validate fraud report;
                (Database) Store fraud ticket;
                (System) Send notification to admin;
            }
            -No- {
                (User) Fill help request form;
                (User) Enter subject and description;
                (User) Upload supporting files;
                (User) Submit help request;
                (System) Validate help request;
                (Database) Store help ticket;
                (System) Send notification to admin;
            }
        }
        >System<;
        (System) Display ticket creation success;
        (User) View ticket in list;
        @User@
    }
}
```

### 5.2 Panel Respon Bantuan dan Penipuan (Admin)

```
'Panel Respon Bantuan dan Penipuan (Admin)' {
    |Admin, System, Database, User| {
        $Admin$
        (Admin) Access panel bantuan page;
        (System) Display all tickets;
        (Admin) Select ticket to respond;
        (System) Display ticket details;
        (Admin) Review ticket information;
        (Admin) Write response message;
        (Admin) Send message to user;
        (System) Validate message;
        (Database) Store admin message;
        (System) Send notification to user;
        (User) Receive admin response;
        <Admin> Close ticket? {
            -Yes- {
                (Admin) Write closing message;
                (Admin) Close ticket;
                (Database) Update ticket status;
                (System) Send closure notification;
                (User) Receive closure notice;
            }
            -No- {
                (Admin) Keep ticket open;
                (System) Continue monitoring;
            }
        }
        >Admin<;
        <User> Reopen ticket? {
            -Yes- {
                (User) Click reopen button;
                (System) Reopen ticket;
                (Database) Update ticket status;
                (System) Send reopen notification;
                (Admin) Receive reopen notice;
            }
            -No- {
                (User) Accept closure;
            }
        }
        >User<;
        @Admin@
    }
}
```

## 6. User Management Features

### 6.1 Panel Track Record Pelamar Pekerjaan

```
'Panel Track Record Pelamar Pekerjaan' {
    |Mitra, System, Database| {
        $Mitra$
        (Mitra) Access track record page;
        (System) Authenticate mitra session;
        (Database) Query mitra's jobs;
        (Database) Get pending applicants;
        (System) Process applicant data;
        (Database) Get job history for each applicant;
        (System) Calculate completion statistics;
        (System) Calculate success rates;
        (System) Display applicant track records;
        (Mitra) Review applicant history;
        (Mitra) View completion rates;
        (Mitra) Check previous ratings;
        <Mitra> Make hiring decision? {
            -Yes- {
                (Mitra) Accept or reject applicant;
                (System) Update application status;
                (Database) Store decision;
                (System) Send notification to applicant;
            }
            -No- {
                (Mitra) Continue reviewing;
            }
        }
        >Mitra<;
        @Mitra@
    }
}
```

---

## Summary

This document contains 18 activity diagrams covering all the management features in the Laravel job marketplace application. Each diagram illustrates the complete workflow from user interaction to system response, including:

- **Authentication and authorization checks**
- **Database operations**
- **Business logic validation**
- **External service integration (Payment Gateway)**
- **Notification system**
- **File handling and storage**
- **Multi-role user interactions**

The diagrams use a consistent swim lane structure with User, Mitra, System, Admin, Payment Gateway, and Database as the primary actors, ensuring clear understanding of responsibilities and data flow throughout the application.