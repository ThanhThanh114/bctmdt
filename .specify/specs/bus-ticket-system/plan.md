# Implementation Plan: Bus Ticket Management System

**Branch**: `main` | **Date**: 2025-10-23 | **Spec**: [link to spec]
**Input**: Feature specification from `/specs/bus-ticket-system/spec.md`

**Note**: This template is filled in by the `/speckit.plan` command. See `.specify/templates/commands/plan.md` for the execution workflow.

## Summary

Implement a comprehensive Bus Ticket Management System using Laravel with MVC architecture, RBAC, Google Maps integration, billing, and reporting. The system will support multiple user roles with separate dashboards, automated fee management, and interactive route mapping.

## Technical Context

**Language/Version**: PHP 8.1+ with Laravel 10+  
**Primary Dependencies**: Laravel Framework, Google Maps API, Tailwind CSS, SweetAlert2, GSAP, Stripe/PayPal for payments  
**Storage**: MySQL/PostgreSQL  
**Testing**: PHPUnit, Laravel Dusk for integration tests  
**Target Platform**: Web (responsive for desktop, mobile, tablet)  
**Project Type**: Web application  
**Performance Goals**: <2s page load, 1000 concurrent users, 90+ Lighthouse score  
**Constraints**: <1s response time, secure RBAC, accurate map calculations  
**Scale/Scope**: Multi-role system for 10k+ users, 1000+ routes, real-time notifications

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

- Must use Laravel framework for the application.
- Must adhere to MVC (Model-View-Controller) directory structure with clear separation of Controllers, Models, and Views.
- All compiled CSS and JavaScript must be placed in the public directory.
- Must apply SOLID principles and Dependency Injection for modularity.
- Must implement RBAC for secure access.
- Must integrate Google Maps API for location-based features.
- Must handle billing and subscriptions securely.
- Must generate reports for financial and operational insights.

## Project Structure

### Documentation (this feature)

```text
specs/bus-ticket-system/
├── plan.md              # This file (/speckit.plan command output)
├── research.md          # Phase 0 output (/speckit.plan command)
├── data-model.md        # Phase 1 output (/speckit.plan command)
├── quickstart.md        # Phase 1 output (/speckit.plan command)
├── contracts/           # Phase 1 output (/speckit.plan command)
└── tasks.md             # Phase 2 output (/speckit.tasks command - NOT created by /speckit.plan)
```

### Source Code (repository root)

```text
app/
├── Http/Controllers/    # MVC Controllers for each role and feature
├── Models/              # Eloquent Models for Users, Routes, Stations, etc.
├── Services/            # Business logic services
├── Repositories/        # Data access repositories
└── Policies/            # RBAC policies

resources/
├── views/               # Blade templates for dashboards and UI
└── css/                 # Tailwind CSS (compiled to public)

public/
├── assets/              # Compiled CSS/JS, images
└── index.php            # Laravel entry point

tests/
├── Feature/             # Integration tests
├── Unit/                # Unit tests
└── Browser/             # E2E tests

routes/
├── web.php              # Web routes
└── api.php              # API routes
```

**Structure Decision**: Laravel web application with MVC separation, RBAC middleware, and integrated APIs for maps and payments.

## Complexity Tracking

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|-------------------------------------|
| RBAC with 5 roles | Multi-tenant system requires strict access control | Single role system insufficient for business separation |
| Google Maps API | Accurate route visualization and calculations | Static maps lack interactivity and precision |
| Automated billing | Revenue management for subscriptions | Manual billing prone to errors and delays |

## Phase 0: Research & Planning

**Purpose**: Gather requirements, review existing database, and plan integrations.

- [ ] T001 [P] Review existing database schema and adjust for new features (Stations, RouteStops with GPS, DriverApplications, Subscriptions/Fees).
- [ ] T002 [P] Plan integration of Google Maps API for coordinates, route calculation, and dynamic map display.
- [ ] T003 [P] Design RBAC middleware for role-based access (Admin/Staff vs. Bus Owner/Driver).
- [ ] T004 [P] Outline billing system for subscriptions and fees.
- [ ] T005 [P] Plan financial and revenue reporting features.

## Phase 1: Core Implementation

**Purpose**: Build foundational features and dashboards.

1. **Dashboard and Frontend Design**:
   - Design 4 independent layouts (Admin, Staff, Bus_Owner, Driver) using Tailwind CSS with responsive design.
   - Admin/Staff dashboard for system management (users, roles, reports, analytics).
   - Bus_Owner dashboard for asset management (buses, drivers, routes, revenue, billing).
   - Driver dashboard for personal schedules, application status, notifications.
   - Use GSAP for smooth transitions between pages/modals (fade-in, slide, loading animations).

2. **Data Models and Business Logic**:
   - Design `Stations` and `RouteStops` tables with GPS coordinates for Google Maps.
   - Design `DriverApplications` table for Driver registration with Bus Owner (driver_id, bus_owner_id, status, dates).
   - Design `Subscriptions`/`Fees` table for monthly maintenance fees (user_id, type, amount, due_date, status).
   - Integrate Google Maps API for storing coordinates, calculating routes, and displaying dynamic maps.

3. **Authentication and Authorization**:
   - Setup Laravel middleware for RBAC (Admin/Staff vs. Bus Owner/Driver).
   - Ensure Driver approval process uses safe transactions with rollback.
   - Implement Laravel Sanctum/Passport for secure API access.

4. **Error Handling and Notifications**:
   - Integrate SweetAlert2 for notifications (role upgrade errors, driver application statuses, overdue fee warnings).
   - Ensure MVC structure: Controllers for logic, Models for data, Views for UI.
   - Add global error handling middleware.

## Phase 2: Advanced Features

**Purpose**: Implement integrations and enhancements.

- [ ] T010 Implement billing system with payment gateways for subscriptions and fees.
- [ ] T011 Add financial and revenue reporting features (charts, exports, filters).
- [ ] T012 Enhance UI with GSAP animations and SweetAlert2 notifications across all dashboards.
- [ ] T013 Optimize database queries for Google Maps API calls and route calculations.
- [ ] T014 Add real-time notifications for driver applications and fee alerts.

## Phase 3: Testing & Deployment

**Purpose**: Ensure quality and readiness.

- [ ] T020 Unit and integration tests for all new features (RBAC, Google Maps, billing).
- [ ] T021 Performance optimization (caching for maps, lazy loading for dashboards).
- [ ] T022 Security hardening (input validation, CSRF protection, rate limiting).
- [ ] T023 Documentation updates in docs/ (API endpoints, user guides).
- [ ] T024 Run quickstart.md validation and deploy to staging.

## Dependencies & Execution Order

### Phase Dependencies

- **Phase 0**: No dependencies - can start immediately.
- **Phase 1**: Depends on Phase 0 completion - blocks advanced features.
- **Phase 2**: Depends on Phase 1 - adds integrations.
- **Phase 3**: Depends on Phase 2 - ensures quality.

### User Story Dependencies

- **Core Dashboards (Phase 1)**: Independent, can be developed in parallel.
- **RBAC and Maps (Phase 1)**: Must complete before billing (Phase 2).
- **Billing and Reports (Phase 2)**: Can start after core implementation.

### Within Each Phase

- Research tasks in Phase 0 can run in parallel.
- Models before controllers in Phase 1.
- Tests before deployment in Phase 3.

### Parallel Opportunities

- All Phase 0 tasks marked [P] can run in parallel.
- Dashboard designs for different roles can be developed in parallel.
- Unit tests for different features can run in parallel.

## Implementation Strategy

### MVP First (Core Dashboards Only)

1. Complete Phase 0: Research.
2. Complete Phase 1: Core Implementation (dashboards and basic RBAC).
3. **STOP and VALIDATE**: Test dashboards independently.
4. Deploy/demo if ready.

### Incremental Delivery

1. Phase 0 + Phase 1 → Core system ready.
2. Add Phase 2 → Integrations complete → Deploy/Demo.
3. Add Phase 3 → Quality assured → Final Deploy.

### Parallel Team Strategy

With multiple developers:

1. Team completes Phase 0 together.
2. Once Phase 0 done:
    - Developer A: Admin/Staff dashboards.
    - Developer B: Bus_Owner dashboard.
    - Developer C: Driver dashboard.
3. Integrate in Phase 2 and test in Phase 3.

## Notes

- [P] tasks = different files, no dependencies.
- Each phase should be independently completable and testable.
- Verify tests fail before implementing.
- Commit after each task or logical group.
- Stop at any checkpoint to validate independently.
- Avoid: vague tasks, same file conflicts, cross-phase dependencies that break independence.
