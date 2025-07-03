# Bake House Inventory Management System (BHIMS)
## Comprehensive Development Guide

Welcome to the BHIMS Development Guide. This document serves as the central reference for developers working on the Bake House Inventory Management System. It provides a detailed overview of the system architecture, setup instructions, coding standards, and best practices to ensure consistency and quality across the development lifecycle.

### Table of Contents
1. [Project Overview](#project-overview)
2. [Technology Stack](#technology-stack)
3. [System Requirements](#system-requirements)
4. [Setup Instructions](#setup-instructions)
5. [Project Structure](#project-structure)
6. [Key Features](#key-features)
7. [Development Workflow](#development-workflow)
8. [Database Schema](#database-schema)
9. [API Documentation](#api-documentation)
10. [Testing](#testing)
11. [Deployment](#deployment)
12. [Troubleshooting](#troubleshooting)

## Project Overview
BHIMS is a comprehensive inventory management solution designed specifically for bake houses. It streamlines operations by managing ingredients, recipes, suppliers, and sales while providing valuable insights through analytics and reporting.

## Technology Stack

### Backend
- **Framework**: Laravel 10.x
  - Eloquent ORM for database operations
  - Blade templating engine
  - Artisan command-line interface
  - Built-in authentication and authorization
  - Task scheduling and queues
  - Event broadcasting

### Frontend
- **Core Technologies**
  - HTML5 for semantic markup
  - CSS3 with Tailwind CSS for utility-first styling
  - JavaScript (ES6+) for client-side interactivity
  - Livewire for dynamic interfaces without leaving Laravel
  - Alpine.js for lightweight JavaScript interactions

### Database
- **MySQL 8.0+**
  - InnoDB storage engine with row-level locking
  - Proper indexing strategy for performance
  - Transaction support for data integrity
  - JSON data type support
  - Full-text search capabilities

### Development Tools
- **Version Control**: Git with GitHub/GitLab
- **Package Managers**:
  - Composer for PHP dependencies
  - NPM for frontend dependencies
- **Development Environment**:
  - Laravel Sail (Docker) for containerized development
  - XAMPP/Laragon for local server environments
  - PHP 8.1+ with necessary extensions

### Testing & Quality
- PHPUnit for unit and feature tests
- Laravel Dusk for browser testing
- PHP_CodeSniffer for code style
- PHPStan for static analysis
- GitHub Actions for CI/CD

### Production Environment
- Web Server: Nginx/Apache
- Queue Worker: Supervisor
- Caching: Redis/Memcached
- File Storage: Local/S3-compatible storage
- Monitoring: Laravel Telescope

## System Requirements
- PHP 8.1 or higher
- Composer
- Node.js 16+ and NPM
- MySQL 8.0+
- Web server (Apache/Nginx)
- Git

## Setup Instructions

### 1. Clone the Repository
```bash
git clone [repository-url]
cd bhims
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
1. Copy `.env.example` to `.env`
2. Generate application key:
   ```bash
   php artisan key:generate
   ```
3. Configure your database in `.env`
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bhims
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

### 4. Run Migrations and Seeders
```bash
php artisan migrate --seed
```

### 5. Compile Assets
```bash
npm run dev
# or for production
npm run build
```

### 6. Start Development Server
```bash
php artisan serve
```

## Project Structure

### Core Application (`app/`)
- `Console/`: Custom Artisan commands
  - `Commands/`: Custom CLI commands
  - `Kernel.php`: Scheduled tasks configuration
- `Exceptions/`: Global exception handling
  - `Handler.php`: Global exception handler
- `Http/`: HTTP request handling
  - `Controllers/`: Request handlers
    - `API/`: API controllers
    - `Auth/`: Authentication controllers
    - `Controller.php`: Base controller
  - `Middleware/`: HTTP middleware
    - `Authenticate.php`: Authentication checks
    - `VerifyCsrfToken.php`: CSRF protection
  - `Requests/`: Form request validation
    - `BaseRequest.php`: Common request logic
  - `Resources/`: API resources
- `Models/`: Eloquent models
  - `Traits/`: Reusable model traits
  - `Scopes/`: Query scopes
  - `Concerns/`: Model concerns
- `Policies/`: Authorization policies
- `Providers/`: Service providers
  - `AppServiceProvider.php`: Core service provider
  - `AuthServiceProvider.php`: Auth services
  - `EventServiceProvider.php`: Event listeners
- `Services/`: Business logic
  - `Inventory/`: Inventory management
  - `Reporting/`: Report generation
  - `Notifications/`: Notification handling

### Configuration (`config/`)
- `app.php`: Core application settings
- `auth.php`: Authentication configuration
- `database.php`: Database connections
- `filesystems.php`: File storage
- `logging.php`: Logging configuration
- `queue.php`: Queue configuration
- `services.php`: Third-party services

### Database (`database/`)
- `factories/`: Model factories for testing
- `migrations/`: Database schema migrations
  - `YYYY_MM_DD_HHMMSS_*_create_tables.php`: Schema definitions
- `seeders/`: Database seeders
  - `DatabaseSeeder.php`: Main seeder
  - `*TableSeeder.php`: Table-specific seeders

### Public Files (`public/`)
- `css/`: Compiled CSS
- `js/`: Compiled JavaScript
- `images/`: Public images
- `storage/`: Symbolic link to storage
- `index.php`: Entry point
- `robots.txt`: Search engine instructions
- `.htaccess`: Apache configuration

### Resources (`resources/`)
- `css/`: Source CSS/Sass
- `js/`: Source JavaScript
  - `Components/`: Vue/React components
  - `Layouts/`: Layout components
  - `Pages/`: Page components
- `views/`: Blade templates
  - `layouts/`: Base layouts
  - `components/`: Reusable components
  - `auth/`: Authentication views
  - `partials/`: View partials
- `lang/`: Language files
  - `en/`: English translations
  - `es/`: Spanish translations

### Routes (`routes/`)
- `api.php`: API route definitions
- `channels.php`: Broadcasting channels
- `console.php`: Artisan commands
- `web.php`: Web routes

### Tests (`tests/`)
- `Feature/`: Feature tests
  - `Api/`: API endpoint tests
  - `Auth/`: Authentication tests
  - `Http/`: HTTP request tests
- `Unit/`: Unit tests
  - `Models/`: Model tests
  - `Services/`: Service tests
- `Browser/`: Dusk browser tests
- `TestCase.php`: Base test case
- `CreatesApplication.php`: Test application setup

## Comprehensive Feature Set

### 1. User Management System
#### Authentication & Security
- Multi-factor authentication (MFA) with TOTP
- Session management with device tracking
- Brute force protection
- Password complexity requirements
- Automatic session timeout
- IP whitelisting/blacklisting
- Failed login attempt tracking
- Security question setup

#### Role-Based Access Control (RBAC)
- Hierarchical role structure
- Fine-grained permissions
- Department-based access rules
- Time-based access restrictions
- Temporary access grants
- Audit logging for all access
- Role templates
- Permission inheritance

#### User Profiles
- Personal information management
- Document uploads (ID, certificates)
- Signature capture
- Notification preferences
- Two-factor authentication setup
- Session management
- Activity history
- Skills and qualifications tracking

### 2. Inventory Management System
#### Stock Control
- Real-time inventory tracking
- Multi-location inventory
- Barcode/QR code generation and scanning
- Batch and lot tracking
- Serial number tracking
- Expiration date tracking
- Minimum/maximum stock levels
- Reorder point automation

#### Warehouse Management
- Bin location management
- Zone-based storage
- Stock movement tracking
- Cross-docking support
- Cycle counting
- Stock adjustments
- Inventory valuation (FIFO, LIFO, Average)
- Damaged goods tracking

#### Alerts & Notifications
- Customizable threshold alerts
- Expiry date notifications
- Automated reorder points
- Email/SMS notifications
- Push notifications
- Escalation workflows
- Custom alert templates
- Acknowledgment tracking

### 3. Recipe & Production Management
#### Recipe Development
- Visual recipe builder
- Ingredient substitution suggestions
- Nutritional information calculator
- Cost analysis
- Version control
- Approval workflows
- Recipe scaling
- Batch size adjustment

#### Production Planning
- Production scheduling
- Resource allocation
- Batch tracking
- Yield calculations
- Waste management
- Production costing
- Bill of Materials (BOM)
- Work order management

#### Quality Control
- Quality checkpoints
- Compliance tracking
- Test results recording
- Non-conformance reporting
- Corrective actions
- Quality certificates
- Audit trails
- Supplier quality metrics

### 4. Procurement & Supplier Management
#### Supplier Portal
- Self-service portal
- RFQ management
- Bid comparison
- Performance dashboards
- Document exchange
- Communication logs
- Contract management
- Compliance tracking

#### Purchase Management
- Purchase requisitions
- Multi-level approvals
- Purchase order generation
- GRN processing
- Three-way matching
- Invoice processing
- Payment tracking
- Return to vendor (RTV)

### 5. Sales & Customer Management
#### Point of Sale (POS)
- Quick product lookup
- Barcode scanning
- Multiple payment methods
- Split payments
- Receipt generation
- Returns processing
- Discount management
- Loyalty program integration

#### Customer Relationship
- 360° customer view
- Purchase history
- Communication logs
- Service requests
- Feedback collection
- Loyalty programs
- Credit management
- Customer segmentation

### 6. Financial Management
#### Accounting Integration
- General ledger
- Accounts payable
- Accounts receivable
- Bank reconciliation
- Tax calculations
- Financial reporting
- Budgeting
- Cost center tracking

#### Billing & Invoicing
- Invoice generation
- Recurring billing
- Payment processing
- Credit notes
- Debit notes
- Payment reminders
- Aging reports
- Collection management

### 7. Analytics & Business Intelligence
#### Reporting Engine
- Custom report builder
- Scheduled reports
- Export to multiple formats
- Dashboard widgets
- KPI tracking
- Ad-hoc reporting
- Report scheduling
- Email distribution

#### Advanced Analytics
- Sales trend analysis
- Inventory turnover rates
- Profit margin analysis
- Demand forecasting
- What-if scenario planning
- Customer buying patterns
- Supplier performance
- Production efficiency

### 8. Integration & API
#### Third-Party Integrations
- Accounting software (QuickBooks, Xero)
- E-commerce platforms
- Payment gateways
- Shipping providers
- Email/SMS services
- ERP systems
- CRM systems
- HRMS systems

#### API Access
- RESTful API
- Webhook support
- API documentation
- Rate limiting
- Authentication (OAuth2, API keys)
- WebSocket support
- Bulk operations
- Webhook management

### 9. System Administration
#### Configuration
- Business settings
- Tax configuration
- Currency and localization
- Email templates
- System backups
- Print templates
- Barcode configuration
- Approval workflows

#### Maintenance
- System logs
- Cache management
- Queue monitoring
- Database maintenance
- System updates
- Backup management
- System health checks
- Performance monitoring

### 10. Mobile & Remote Access
#### Mobile Applications
- Inventory management
- Sales processing
- Stock counting
- Approval workflows
- Notifications
- Offline capability
- Photo capture
- GPS tracking

#### Web Portal
- Self-service portal
- Dashboard access
- Report viewing
- Approval workflows
- Document access
- Communication center
- Knowledge base
- Support ticketing

### 11. Compliance & Security
#### Data Protection
- Data encryption
- Access controls
- Audit trails
- Data retention policies
- Backup and recovery
- Vulnerability scanning
- Penetration testing
- Compliance reporting

#### Regulatory Compliance
- Food safety standards
- Industry regulations
- Tax compliance
- Export/import controls
- Health and safety
- Environmental regulations
- Labor laws
- Quality standards

### 12. Advanced Features
#### AI & Machine Learning
- Demand prediction
- Optimal pricing
- Anomaly detection
- Automated categorization
- Smart reordering
- Customer churn prediction
- Sentiment analysis
- Image recognition

#### IoT Integration
- Smart scales
- Temperature monitoring
- Equipment sensors
- RFID tracking
- Automated data capture
- Predictive maintenance
- Energy monitoring
- Environmental controls

### 1. User Management System
#### Authentication & Security
- Multi-factor authentication (MFA) support
- Session management
- Failed login attempt tracking
- Password policy enforcement
- Activity logging and audit trails

#### Role-Based Access Control (RBAC)
- Predefined roles: Super Admin, Manager, Staff
- Granular permission system
- Department-based access control
- Time-based access restrictions
- Permission inheritance

#### User Profiles
- Personal information management
- Avatar and cover photo uploads
- Notification preferences
- Two-factor authentication setup
- Session management

### 2. Inventory Management
#### Stock Control
- Real-time inventory tracking
- Multi-location inventory management
- Barcode/QR code support
- Batch and lot tracking
- Serial number tracking

#### Alerts & Notifications
- Customizable threshold alerts
- Expiry date notifications
- Automated reorder points
- Email/SMS notifications
- Push notifications

#### Inventory Operations
- Stock adjustments
- Inventory transfers
- Cycle counting
- Inventory valuation
- Cost tracking

### 3. Recipe & Production Management
#### Recipe Development
- Recipe creation wizard
- Ingredient substitution suggestions
- Nutritional information calculator
- Cost analysis
- Version control

#### Production Planning
- Production scheduling
- Resource allocation
- Batch tracking
- Yield calculations
- Waste management

### 4. Supplier & Purchasing
#### Supplier Management
- Supplier database
- Performance metrics
- Rating system
- Contract management
- Communication history

#### Purchase Orders
- PO generation
- Approval workflows
- GRN (Goods Received Note) processing
- Three-way matching
- Payment tracking

### 5. Sales & Point of Sale
#### POS System
- Quick product lookup
- Barcode scanning
- Multiple payment methods
- Receipt generation
- Returns processing

#### Customer Management
- Customer profiles
- Purchase history
- Loyalty programs
- Credit management
- Communication logs

### 6. Analytics & Business Intelligence
#### Reporting
- Custom report builder
- Scheduled reports
- Export to multiple formats (PDF, Excel, CSV)
- Dashboard widgets
- KPI tracking

#### Advanced Analytics
- Sales trend analysis
- Inventory turnover rates
- Profit margin analysis
- Demand forecasting
- What-if scenario planning

### 7. Integration & API
#### Third-Party Integrations
- Accounting software (QuickBooks, Xero)
- E-commerce platforms
- Payment gateways
- Shipping providers
- Email/SMS services

#### API Access
- RESTful API
- Webhook support
- API documentation
- Rate limiting
- Authentication (OAuth2, API keys)

### 8. System Administration
#### Configuration
- Business settings
- Tax configuration
- Currency and localization
- Email templates
- System backups

#### Maintenance
- System logs
- Cache management
- Queue monitoring
- Database maintenance
- System updates

## Development Workflow

### Branching Strategy
- `main` - Production-ready code
- `develop` - Integration branch
- `feature/*` - New features
- `bugfix/*` - Bug fixes
- `hotfix/*` - Critical production fixes

### Code Style
- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Add PHPDoc blocks for all methods
- Keep methods small and focused

### Commit Messages
Use conventional commit messages:
- `feat:` for new features
- `fix:` for bug fixes
- `docs:` for documentation changes
- `style:` for code style changes
- `refactor:` for code refactoring
- `test:` for test related changes
- `chore:` for maintenance tasks

## Database Schema

### Core Tables

#### `users`
- `id`: Primary key (UUID)
- `name`: Full name
- `email`: Unique email address
- `password`: Hashed password
- `role_id`: Foreign key to roles
- `status`: Active/Inactive/Suspended
- `last_login_at`: Timestamp
- `created_at`, `updated_at`: Timestamps
- `deleted_at`: Soft delete timestamp

#### `ingredients`
- `id`: Primary key (UUID)
- `name`: Ingredient name
- `code`: Unique identifier
- `category_id`: Foreign key to categories
- `unit_of_measure`: Base unit (g, kg, ml, L, etc.)
- `current_stock`: Decimal
- `minimum_stock`: Decimal
- `cost_per_unit`: Decimal
- `supplier_id`: Foreign key to suppliers
- `location_id`: Storage location
- `expiry_alert_days`: Integer
- `barcode`: Barcode data
- `notes`: Additional information
- `is_active`: Boolean

### Transaction Tables

#### `inventory_transactions`
- `id`: Primary key (UUID)
- `transaction_type`: ENUM('purchase', 'sale', 'adjustment', 'transfer')
- `reference_id`: Related document ID
- `reference_type`: Related document type
- `item_id`: Foreign key to items
- `quantity`: Decimal
- `unit_price`: Decimal
- `total_price`: Decimal
- `location_id`: Source location
- `destination_id`: Target location (for transfers)
- `notes`: Transaction notes
- `created_by`: User ID
- `approved_by`: User ID (for approvals)
- `transaction_date`: Timestamp

### Relationship Tables

#### `recipe_ingredients`
- `id`: Primary key
- `recipe_id`: Foreign key to recipes
- `ingredient_id`: Foreign key to ingredients
- `quantity`: Decimal
- `unit_of_measure`: Unit for this recipe
- `notes`: Special instructions
- `is_optional`: Boolean
- `sort_order`: Integer

### Supporting Tables

#### `suppliers`
- `id`: Primary key (UUID)
- `name`: Supplier name
- `contact_person`: Contact name
- `email`: Contact email
- `phone`: Contact number
- `address`: Physical address
- `tax_id`: Tax identification
- `payment_terms`: Terms of payment
- `lead_time`: Days for delivery
- `rating`: Performance rating
- `is_active`: Boolean

#### `purchase_orders`
- `id`: Primary key (UUID)
- `po_number`: Unique PO number
- `supplier_id`: Foreign key to suppliers
- `order_date`: Date of order
- `expected_delivery`: Expected date
- `status`: Draft/Ordered/Received/Partial/Cancelled
- `subtotal`: Subtotal amount
- `tax_amount`: Tax amount
- `discount_amount`: Discount amount
- `total_amount`: Total amount
- `notes`: Additional notes
- `created_by`: User ID
- `approved_by`: User ID
- `approved_at`: Timestamp

### Reporting Views

#### `inventory_summary_view`
- Aggregates current stock levels
- Calculates stock value
- Identifies items needing reorder
- Shows stock movement trends

#### `sales_performance_view`
- Daily/Monthly/Yearly sales
- Top selling items
- Sales by category
- Customer purchase history

### Indexing Strategy
- Primary keys on all tables
- Foreign key indexes
- Composite indexes for common queries
- Full-text search on product names/descriptions
- Date-based indexes for reporting

### Data Retention Policy
- Transaction history: 5 years
- Audit logs: 7 years
- User activity: 1 year
- System logs: 6 months
- Backups: 30 days (daily), 12 months (monthly)

## API Documentation
API endpoints are documented using OpenAPI/Swagger. Access the documentation at:
```
/api/documentation
```

## Testing
Run the test suite with:
```bash
php artisan test
```

## Deployment
### Production Requirements
- PHP 8.1+
- MySQL 8.0+
- Web server (Apache/Nginx)
- SSL certificate

### Deployment Steps
1. Clone the repository
2. Install dependencies with `--no-dev` flag
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install --production
   ```
3. Set up environment variables
4. Generate application key
5. Run migrations
6. Optimize the application
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
7. Set up queue workers (if using queues)
8. Configure web server
9. Set up scheduled tasks

## Troubleshooting

### Common Issues
1. **Permission Issues**
   ```bash
   chmod -R 775 storage/
   chmod -R 775 bootstrap/cache/
   ```

2. **Composer Dependencies**
   ```bash
   composer install
   composer dump-autoload
   ```

3. **NPM/Asset Compilation**
   ```bash
   npm install
   npm run dev
   ```

For additional support, please contact the development team.

---
*Last Updated: July 3, 2024*
