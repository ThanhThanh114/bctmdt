<!-- Sync Impact Report
Version change: 1.1.0 -> 1.2.0
Modified principles: II, III, VI (added details for Staff role, Driver approval, Google Maps, billing)
Added sections: Business Processes
Removed sections: none
Templates requiring updates: .specify/templates/plan-template.md, .specify/templates/spec-template.md, .specify/templates/tasks-template.md
Follow-up TODOs: none
-->

# Bus Ticket Management System Constitution

## Core Principles

### I. Codebase Architecture
The application must be built on Laravel, strictly adhering to the standard MVC (Model-View-Controller) directory structure, ensuring clear separation of Controllers, Models, and Views. All compiled CSS and JavaScript files must be placed in the public directory. Apply SOLID principles and Dependency Injection for modularity, maintainability, and extensibility. Use Repository Pattern for data access and Service Layer for business logic, integrating Eloquent ORM with caching (Redis/Memcached) to optimize database query performance.

### II. Role-Based Access Control (RBAC)
Apply RBAC for 5 roles (Admin, Staff, Bus_Owner, Driver, User) with customizable detailed permissions. Dashboards and business logic for Bus_Owner, Driver, Admin, and Staff must be completely separated to ensure safety and clear functional distinction. Staff role is limited to website support and reporting for Admin, with read-only access to summary reports and low-level user management (e.g., password resets). Use middleware and policies in Laravel to control access and prevent unauthorized access.

### III. Routing and Location Services
All features related to routes, stations, and stops must use a reliable Map API (e.g., Google Maps). Route data displayed to Users must include origin, destination, transit stations, and stops. Integrate API to calculate distance, travel time, and display interactive maps. Admin/Bus Owner can define stations and rest stops/transfer points with accurate GPS coordinates.

### IV. Security & Scalability
Design must be compatible with Load Balancer (LB) and Web Application Firewall (WAF). Apply security measures such as HTTPS, input validation, CSRF protection, rate limiting, and logging to prevent attacks. Ensure the system can handle high loads with caching and database optimization.

### V. UX/UI and Technology Experience
Interface must be fully responsive (Tailwind CSS). Use SweetAlert2 for all notifications and GSAP for complex motion effects. Ensure smooth experience on desktop, mobile, and tablet, with page load times under 2 seconds.

### VI. Billing and Reporting
System must include a maintenance fee management module and ability to generate detailed administrative and financial reports. Include automated monthly fee calculations for Bus Owner and Driver, payment management (integrate Stripe or PayPal), and reports on revenue, tickets sold, user statistics.

### VII. Database & API
Use MySQL/PostgreSQL with Laravel migrations and seeders, supporting full-text search and indexing for performance. Build RESTful APIs with validation, pagination, and error handling, integrating Swagger/OpenAPI for documentation and testing.

### VIII. Testing & Deployment
Write unit tests (PHPUnit), feature tests, and integration tests for at least 80% coverage. Use CI/CD pipeline with GitHub Actions or Jenkins for automated testing, linting (PHPStan, ESLint), and deployment to cloud (AWS/Heroku) with zero-downtime strategies.

## Business Processes

### Staff Role Definition
Staff is limited to supporting website operations and reporting for Admin. Staff cannot access or interfere with core business operations like vehicle management, routes, drivers, or trips of Bus Owner/Driver. Staff can access summary reports (read-only) and low-level user management tools (e.g., password resets).

### Driver Registration and Approval Process
New Users/Drivers can select a Bus Owner to work for and submit registration requests (including driver's license info). Bus Owners review and approve/reject pending requests in their dashboard. Upon approval, the user's role changes to 'Driver' and is linked to the Bus Owner.

### Location and Route Management
Admin/Bus Owner define stations and rest stops/transfer points with GPS coordinates. System displays full routes (origin, destination, stops, transfers) on Google Maps for Users, with interactive features like zoom and pan.

### Maintenance Fees and Administrative Reports
System automatically calculates monthly maintenance fees for Bus Owner and Driver. Admin generates detailed administrative and financial reports on fees, payment status, and revenue, exportable in PDF/CSV.

## Technology Stack and Standards
Adhere to the specified technology stacks in the principles, ensuring compliance with security, performance, and accessibility standards.

## Development and Deployment Workflow
Follow testing and deployment practices as outlined in Principle VIII. All changes must undergo code review, automated testing, and approval before deployment.

## Governance
The constitution supersedes all other practices. Amendments require documentation, approval, and a migration plan. All PRs and reviews must verify compliance with these principles. Use project documentation for runtime guidance.

**Version**: 1.2.0 | **Ratified**: 2025-10-23 | **Last Amended**: 2025-10-23
