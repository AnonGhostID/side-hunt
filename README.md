# Side Hunt

**Side Hunt** is a comprehensive web-based job marketplace application built with Laravel that connects job creators (Mitra) with skilled workers for various side jobs and freelance opportunities. The platform provides a complete ecosystem for job management, secure financial transactions, real-time communication, and comprehensive reporting systems.

## 🚀 Features

### For Job Creators (Mitra)
- **Advanced Job Posting**: Create detailed job listings with GPS coordinates, salary ranges, worker criteria, and scheduling
- **Application Management Dashboard**: Review, accept, or reject job applications with detailed applicant profiles
- **Real-time Communication**: Built-in Chatify messaging system for seamless worker communication
- **Financial Management**: Integrated wallet system with Xendit payment processing
- **Job Progress Tracking**: Monitor job status, completion reports, and work verification
- **Worker Rating System**: Bidirectional rating system for quality assurance
- **Analytics Dashboard**: Track hiring metrics, completion rates, and performance statistics

### For Job Seekers (Workers)
- **Smart Job Discovery**: Browse jobs with advanced filtering, location-based search, and preference matching
- **Application Portfolio**: Comprehensive application tracking with status updates and history
- **Direct Messaging**: Real-time chat with job creators through integrated chat system
- **Secure Wallet System**: Receive payments, manage balance, and request withdrawals via multiple payment methods
- **Work Documentation**: Upload completion reports with photo documentation and self-verification
- **Performance Tracking**: View acceptance rates, completion statistics, and received ratings
- **Notification System**: Real-time updates for applications, messages, and job opportunities

### Administrative Features
- **Comprehensive Admin Panel**: Full platform oversight with user management and system monitoring
- **Advanced Support System**: Ticketing system for help requests and fraud reporting with conversation threads
- **Financial Oversight**: Transaction monitoring, payout management, and financial reporting
- **User Management**: Profile oversight, role management, and account administration
- **Platform Analytics**: Comprehensive statistics, user behavior analysis, and system performance metrics
- **Content Moderation**: Report management, fraud prevention, and platform safety measures

### Platform Infrastructure
- **Multi-role Authentication**: Secure role-based access control (User, Mitra, Admin)
- **Responsive Design**: Mobile-first approach with Bootstrap and custom styling
- **Real-time Systems**: Live notifications, chat messaging, and status updates
- **Location Services**: GPS integration for job locations with coordinate mapping
- **File Management**: Secure upload and storage system for documentation and profiles
- **Payment Integration**: Xendit payment gateway for top-ups and withdrawals

## 🛠️ Technology Stack

- **Backend Framework**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade Templates with Bootstrap 5 and custom SCSS
- **Build System**: Vite for modern asset compilation and hot reloading
- **Database**: MySQL with comprehensive Eloquent ORM relationships
- **Real-time Chat**: Chatify package for instant messaging capabilities
- **Payment Processing**: Xendit PHP SDK for secure financial transactions
- **Authentication**: Laravel's built-in authentication with custom role-based middleware
- **Styling**: Bootstrap 5, Tailwind CSS, and Font Awesome icons
- **Development Tools**: Laravel Pint for code formatting, PHPUnit for testing

## 📊 Project Statistics

- **Primary Language**: PHP (27.0%)
- **Frontend Templates**: Blade (51.8%)
- **Client-side Logic**: JavaScript (13.9%)
- **Styling**: CSS/SCSS (7.3%)
- **Total Features**: 18+ comprehensive management modules
- **Database Tables**: 15+ core entities with complex relationships

## 🗄️ Database Architecture

The application features a sophisticated relational database design optimized for job marketplace operations:

### Core Entities
- **Users**: Multi-role user management (Admin, Mitra, User) with integrated wallet system
- **Pekerjaans (Jobs)**: Comprehensive job postings with location coordinates and criteria matching
- **Pelamars (Applications)**: Application management with status tracking and relationship mapping
- **FinancialTransactions**: Unified payment/payout system supporting multiple transaction types
- **Ratings**: Bidirectional rating system between employers and workers
- **Notifications**: Real-time notification system with read/unread status tracking

### Advanced Features
- **Chat System**: Chatify integration with `ch_messages` and `ch_favorites` tables
- **Support System**: `TiketBantuan` and `TicketMessage` tables for comprehensive help desk functionality
- **Reporting System**: `Laporans` table for work completion documentation with photo verification
- **Criteria Matching**: `KriteriaJob` system for intelligent job-worker matching
- **Financial Tracking**: Comprehensive transaction logging with Xendit integration

### Database Relationships
- **One-to-Many**: Users → Jobs, Jobs → Applications, Users → Notifications
- **Many-to-Many**: Users ↔ Jobs (through Pelamars), Complex rating relationships
- **Polymorphic**: Flexible notification system supporting multiple entity types
- **Foreign Keys**: Comprehensive referential integrity with cascade delete protection

For detailed database schema, relationships, and migration history, refer to the `/database/migrations/` directory containing 15+ migration files with complete table structures.

## 🏗️ Installation & Setup

### Prerequisites
- **PHP**: Version 8.2 or higher with required extensions
- **Composer**: Latest version for dependency management
- **Database**: MySQL 8.0+ or MariaDB 10.4+
- **Node.js**: Version 18+ and NPM for asset compilation
- **Web Server**: Apache/Nginx (or use Laravel's built-in server)

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/AnonGhostID/side-hunt.git
   cd side-hunt
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database configuration**
   - Update `.env` file with your database credentials and Xendit API keys
   - Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
   - For fresh installation:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Create storage symbolic link**
   ```bash
   php artisan storage:link
   ```
   *Required for file uploads including work documentation, fraud report evidence, and user avatars.*

7. **Compile assets**
   ```bash
   # Development with hot reload
   npm run dev
   
   # Production build
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

### Additional Configuration

**Xendit Integration**: Add your Xendit API keys to `.env`:
```env
XENDIT_KEY=your_xendit_secret_key
```

**File Storage**: Ensure proper permissions for storage directories:
```bash
chmod -R 775 storage bootstrap/cache
```

## 🖥️ Server Management Commands

### Production Server Commands (Port 8899)

**Start the server:**
```bash
nohup php artisan serve --host=127.0.0.1 --port=8899 > storage/logs/laravel-server.log 2>&1 &
```

**Check server status:**
```bash
ps aux | grep "php artisan serve"
```

**Stop the server:**
```bash
pkill -f "php artisan serve"
```

**Restart the server:**
```bash
pkill -f "php artisan serve" && sleep 2 && nohup php artisan serve --host=127.0.0.1 --port=8899 > storage/logs/laravel-server.log 2>&1 &
```

**View server logs:**
```bash
tail -f storage/logs/laravel-server.log
```

**Test server connection:**
```bash
curl -I http://127.0.0.1:8899
```

### Asset Management

**Rebuild assets after changes:**
```bash
npm run build
```

**Watch for asset changes (development):**
```bash
npm run dev
```

## 👥 Default Users & Testing

The application comes with pre-seeded users for comprehensive testing across all roles:

### Administrator Access
- **Email**: admin@example.com
- **Password**: admin1234
- **Capabilities**: Full platform access, user management, support system.

### Job Creator (Mitra) Testing
- **Email**: owner1@example.com  (another account: owner2+owner3 + @example.com)
- **Password**: owner1234
- **Features**: Job posting, applicant management, payment processing, worker communication

### Worker (User) Testing
- **Email**: user1@example.com (another account: user2+user3 + @example.com)
- **Password**: user1234
- **Features**: Job browsing, application submission, chat system, work reporting, payment reception

### Additional Test Data
- **Seeded Jobs**: Multiple sample job postings with various criteria and locations
- **Sample Applications**: Pre-created applications for testing workflow
- **Financial Transactions**: Example payment and payout records
- **Rating System**: Sample ratings between users for testing review functionality

## 🔧 Key Components & Architecture

### Models & Advanced Relationships
- **Users Model**: Multi-role authentication with wallet integration and complex relationship mapping
- **Pekerjaan Model**: Job management with GPS coordinates, criteria matching, and status tracking
- **Pelamar Model**: Application lifecycle management with status progression and notifications
- **FinancialTransaction Model**: Unified payment/payout system supporting Xendit integration
- **Rating Model**: Bidirectional rating system with employer-worker feedback mechanisms
- **Notification Model**: Real-time notification system with read/unread tracking
- **TiketBantuan Model**: Comprehensive support ticket system with conversation threads

### Controllers & Business Logic
- **PekerjaanController**: Complete job lifecycle management from posting to completion
- **ManagementPageController**: Centralized dashboard system with role-specific analytics
- **TopUpController**: Xendit-integrated payment processing for wallet top-ups
- **PayoutController**: Multi-method withdrawal system supporting banks and e-wallets
- **NotificationController**: Real-time notification system with polling capabilities
- **UsersController**: User management with profile editing and role-based access

### Services & External Integration
- **TarikSaldoService**: Xendit payout integration with comprehensive error handling
- **Role-based Middleware**: Custom middleware for granular access control
- **Asset Helper**: Custom helper functions for dynamic asset management

### Security & Middleware
- **Multi-role Authentication**: Granular role-based access (User, Mitra, Admin)
- **CSRF Protection**: Complete form security implementation
- **Route Protection**: Comprehensive middleware groups for secure access control
- **Password Security**: Bcrypt hashing with strength validation
- **File Upload Security**: Validated file uploads with type checking and storage isolation

## 📱 User Journey & Feature Flow

### 1. **Registration & Onboarding**
Users register with role selection (Worker/Mitra) → Email verification → Profile completion with preferences → Role-specific dashboard access

### 2. **Job Lifecycle (Mitra Perspective)**
Job creation with criteria and location → Application reception and review → Worker selection and communication → Progress monitoring → Work verification → Payment processing → Rating and feedback

### 3. **Application Workflow (Worker Perspective)**  
Job discovery with filtering → Application submission → Status tracking → Communication with employer → Work execution and documentation → Payment reception → Rating submission

### 4. **Financial Operations**
Wallet top-up via Xendit → Secure transaction processing → Work completion payments → Withdrawal requests → Multi-method payout processing → Transaction history tracking

### 5. **Communication & Support**
Real-time chat system → Notification management → Support ticket creation → Admin intervention → Issue resolution → Feedback collection

### 6. **Quality Assurance**
Bidirectional rating system → Work documentation requirements → Fraud reporting mechanisms → Admin moderation → Platform safety measures

## 🔄 Management Features (18 Core Modules)

1. **Active Job Management** - Real-time job status tracking and applicant management
2. **Financial Transaction Oversight** - Comprehensive payment and payout monitoring  
3. **User Communication Hub** - Integrated chat system with conversation management
4. **Application Processing** - Streamlined application review and decision workflows
5. **Work Verification System** - Documentation review and approval processes
6. **Payment Processing** - Secure transaction handling with Xendit integration
7. **Withdrawal Management** - Multi-method payout system for workers
8. **Notification Center** - Real-time updates and communication system
9. **Rating & Review System** - Quality assurance through bidirectional feedback
10. **Support Ticket System** - Comprehensive help desk with conversation threads
11. **Fraud Reporting** - Safety mechanisms with evidence collection
12. **User Profile Management** - Role-based profile editing and oversight
13. **Analytics Dashboard** - Performance metrics and platform statistics
14. **Content Moderation** - Platform safety and content oversight
15. **Geographic Services** - Location-based job matching and mapping
16. **Criteria Matching** - Intelligent job-worker compatibility system
17. **File Management** - Secure upload and storage for documentation
18. **System Administration** - Platform configuration and user management

Each module includes comprehensive activity diagrams detailing workflows between Users, Mitra, Admins, Payment Gateways, and Database systems.

## 📚 Documentation & Resources

### Technical Documentation
- **Database Migrations**: Complete schema documentation in `/database/migrations/`
- **API Documentation**: Route definitions and middleware in `/routes/web.php`

### Development Resources
- **Code Guidelines**: Laravel best practices with custom helper implementations
- **Security Patterns**: Role-based access control and CSRF protection strategies  
- **Payment Integration**: Xendit SDK implementation examples and error handling
- **Testing Framework**: PHPUnit setup with seeded test data for comprehensive testing

### Architecture Insights
- **Service Layer**: Custom services for external API integration (Xendit, Chatify)
- **Middleware Design**: Role-based route protection and session management
- **Database Optimization**: Indexed queries and relationship optimization strategies
- **Frontend Patterns**: Blade template organization with component reusability

## 🤝 Contributing

We welcome contributions to enhance Side Hunt's functionality and user experience:

### How to Contribute
- **Bug Reports**: Submit detailed issue reports with reproduction steps
- **Feature Requests**: Propose new features with use case documentation
- **Code Contributions**: Follow Laravel conventions and include comprehensive tests
- **Documentation**: Improve guides, add examples, or clarify existing content

### Development Standards
- **Code Quality**: Follow PSR-12 coding standards using Laravel Pint
- **Testing**: Include unit and feature tests for new functionality
- **Security**: Ensure all user inputs are validated and sanitized
- **Performance**: Optimize database queries and implement proper caching strategies

### Getting Started
1. Fork the repository and create a feature branch
2. Follow the installation instructions above
3. Review existing code patterns and architecture decisions
4. Submit pull requests with clear descriptions and test coverage

## 📄 License

This project is open-source and available under the [MIT License](LICENSE). This permissive license allows for:

- **Commercial Use**: Use the software commercially without restrictions
- **Modification**: Modify and adapt the code for your specific needs  
- **Distribution**: Distribute the software freely with proper attribution
- **Private Use**: Use privately without disclosure requirements

## 📞 Support & Contact

### Technical Support
- **GitHub Issues**: Report bugs and request features through our issue tracker
- **Documentation**: Comprehensive guides available in the repository documentation
- **Code Review**: Community code review and feedback through pull requests

### Development Team
- **Lead Developer**: AnonGhostID
- **Repository**: [github.com/AnonGhostID/side-hunt](https://github.com/AnonGhostID/side-hunt)
- **Version**: Laravel 11 with ongoing feature development
- **Status**: Active development with regular updates and maintenance

### Community Guidelines
- Be respectful and constructive in all interactions
- Provide detailed information when reporting issues
- Follow established coding standards and conventions
- Contribute to improving documentation and code quality

---

**Side Hunt** - Revolutionizing the gig economy through comprehensive job marketplace technology! 🎯

*Built with Laravel 11, powered by modern PHP practices, and designed for scalable job marketplace operations.*