
---

description: "Task list for Bus Ticket Management System MVP implementation"
---

# Tasks: Bus Ticket Management System

**Input**: Design documents from `/specs/bus-ticket-system/`
**Prerequisites**: plan.md (required), spec.md (required for user stories), research.md, data-model.md, contracts/

**Tests**: Include unit tests for models/services, integration tests for user journeys, contract tests for APIs.

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

## Path Conventions

- **Laravel App**: `app/Http/Controllers/`, `app/Models/`, `resources/views/`, `public/assets/`, `tests/` at repository root
- Paths shown below assume Laravel project - adjust based on plan.md structure

## Phase 1: Foundational Setup (Shared Infrastructure)

**Purpose**: Project initialization and basic structure

- [ ] T001 Create project structure per implementation plan (app/, resources/, tests/, etc.)
- [ ] T002 Initialize Laravel project with required dependencies (Google Maps API, Stripe, etc.)
- [ ] T003 [P] Configure linting and formatting tools (PHPStan, ESLint)
- [ ] T004 Setup database schema and migrations for core entities (Users, Roles, Stations, Routes, DriverApplications, Subscriptions)
- [ ] T005 [P] Implement RBAC middleware and policies for role-based access
- [ ] T006 [P] Setup API routing and middleware structure in Laravel
- [ ] T007 Create base models (User, Role, Station, Route) using Eloquent ORM
- [ ] T008 Configure error handling and logging infrastructure
- [ ] T009 Setup environment configuration management
- [ ] T010 Setup MVC structure with Controllers, Models, Views separation

---

## Phase 2: Core Features (Blocking Prerequisites)

**Purpose**: Core infrastructure that MUST be complete before ANY user story can be implemented

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [ ] T011 Setup Google Maps API integration for coordinates and route calculations
- [ ] T012 [P] Implement authentication/authorization framework using Laravel Sanctum
- [ ] T013 [P] Setup billing system with payment gateways (Stripe/PayPal)
- [ ] T014 Create base services for business logic (UserService, RouteService, BillingService)
- [ ] T015 Configure caching with Redis for performance optimization
- [ ] T016 Setup Tailwind CSS and responsive design framework
- [ ] T017 Integrate SweetAlert2 for notifications and GSAP for animations
- [ ] T018 Setup RESTful API structure with validation and error handling

**Checkpoint**: Foundation ready - user story implementation can now begin in parallel

---

## Phase 3: User Story 1 - Driver Registration and Approval (Priority: P1) 🎯 MVP

**Goal**: Implement Driver registration and approval process with RBAC.

**Independent Test**: Test registration and approval independently, verify role changes and notifications.

### Tests for User Story 1 (OPTIONAL - only if tests requested) ⚠️

> **NOTE: Write these tests FIRST, ensure they FAIL before implementation**

- [ ] T019 [P] [US1] Contract test for driver registration API in tests/Feature/DriverRegistrationTest.php
- [ ] T020 [P] [US1] Integration test for approval process in tests/Feature/DriverApprovalTest.php

### Implementation for User Story 1

- [ ] T021 [P] [US1] Create DriverApplication model in app/Models/DriverApplication.php
- [ ] T022 [US1] Implement DriverRegistrationController in app/Http/Controllers/DriverRegistrationController.php
- [ ] T023 [US1] Create registration form view in resources/views/driver/register.blade.php
- [ ] T024 [US1] Implement BusOwnerApprovalController in app/Http/Controllers/BusOwnerApprovalController.php
- [ ] T025 [US1] Create approval dashboard view in resources/views/bus-owner/approvals.blade.php
- [ ] T026 [US1] Add RBAC middleware for Driver and Bus_Owner roles
- [ ] T027 [US1] Integrate SweetAlert2 for approval notifications
- [ ] T028 [US1] Add validation and error handling for registration
- [ ] T029 [US1] Add logging for approval actions

**Checkpoint**: At this point, Driver registration and approval should be fully functional and testable independently

---

## Phase 4: User Story 2 - Route and Location Management (Priority: P1)

**Goal**: Build route management with Google Maps integration.

**Independent Test**: Create and view routes on map independently.

### Tests for User Story 2 (OPTIONAL - only if tests requested) ⚠️

- [ ] T030 [P] [US2] Contract test for route creation API in tests/Feature/RouteManagementTest.php
- [ ] T031 [P] [US2] Integration test for map display in tests/Feature/MapDisplayTest.php

### Implementation for User Story 2

- [ ] T032 [P] [US2] Create Station model in app/Models/Station.php
- [ ] T033 [P] [US2] Create Route model in app/Models/Route.php
- [ ] T034 [US2] Implement RouteController in app/Http/Controllers/RouteController.php
- [ ] T035 [US2] Create route management view in resources/views/admin/routes.blade.php
- [ ] T036 [US2] Integrate Google Maps API for coordinate storage and route calculation
- [ ] T037 [US2] Add map visualization in resources/views/user/route-map.blade.php
- [ ] T038 [US2] Implement GSAP animations for map interactions
- [ ] T039 [US2] Add validation for GPS coordinates and route data
- [ ] T040 [US2] Add logging for route changes

**Checkpoint**: Route management and map display should be independently functional

---

## Phase 5: User Story 3 - Ticket Booking System (Priority: P1)

**Goal**: Implement ticket booking with billing and reporting.

**Independent Test**: Book tickets and view reports independently.

### Tests for User Story 3 (OPTIONAL - only if tests requested) ⚠️

- [ ] T041 [P] [US3] Contract test for booking API in tests/Feature/TicketBookingTest.php
- [ ] T042 [P] [US3] Integration test for billing process in tests/Feature/BillingTest.php

### Implementation for User Story 3

- [ ] T043 [P] [US3] Create Ticket model in app/Models/Ticket.php
- [ ] T044 [US3] Implement BookingController in app/Http/Controllers/BookingController.php
- [ ] T045 [US3] Create booking form view in resources/views/user/book.blade.php
- [ ] T046 [US3] Implement BillingController in app/Http/Controllers/BillingController.php
- [ ] T047 [US3] Create billing dashboard view in resources/views/admin/billing.blade.php
- [ ] T048 [US3] Integrate payment gateway (Stripe/PayPal) for transactions
- [ ] T049 [US3] Implement reporting service for financial analytics
- [ ] T050 [US3] Add SweetAlert2 for payment confirmations
- [ ] T051 [US3] Add validation and error handling for bookings
- [ ] T052 [US3] Add logging for billing actions

**Checkpoint**: Ticket booking and billing should be independently functional

---

## Phase 6: User Story 4 - Staff Support Role (Priority: P2)

**Goal**: Implement Staff dashboard for reporting and user management.

**Independent Test**: Access reports and reset passwords independently.

### Tests for User Story 4 (OPTIONAL - only if tests requested) ⚠️

- [ ] T053 [P] [US4] Contract test for staff reports API in tests/Feature/StaffReportsTest.php
- [ ] T054 [P] [US4] Integration test for password reset in tests/Feature/PasswordResetTest.php

### Implementation for User Story 4

- [ ] T055 [P] [US4] Create StaffController in app/Http/Controllers/StaffController.php
- [ ] T056 [US4] Create staff dashboard view in resources/views/staff/dashboard.blade.php
- [ ] T057 [US4] Implement report generation service in app/Services/ReportService.php
- [ ] T058 [US4] Add password reset functionality in app/Http/Controllers/Auth/PasswordResetController.php
- [ ] T059 [US4] Add RBAC middleware for Staff role limitations
- [ ] T060 [US4] Integrate SweetAlert2 for reset confirmations
- [ ] T061 [US4] Add validation and logging for staff actions

**Checkpoint**: Staff support features should be independently functional

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Improvements that affect multiple user stories

- [ ] T062 [P] Documentation updates in docs/
- [ ] T063 Code cleanup and refactoring
- [ ] T064 Performance optimization across all stories (caching, query optimization)
- [ ] T065 [P] Additional unit tests in tests/Unit/ for models and services
- [ ] T066 Security hardening (input validation, CSRF protection, rate limiting)
- [ ] T067 Run quickstart.md validation
- [ ] T068 Implement PWA with service workers for offline functionality
- [ ] T069 Optimize assets with minification, bundling, versioning, lazy loading, and CDN integration
- [ ] T070 Ensure accessibility compliance (WCAG 2.1 AA) and monitor Core Web Vitals
- [ ] T071 Setup monitoring and alerting with New Relic or Sentry
- [ ] T072 Ensure scalability with horizontal scaling and database sharding
- [ ] T073 Validate dark mode and responsive design across all screens
- [ ] T074 Validate Google Maps integration for accuracy
- [ ] T075 Test billing and reporting modules for completeness
- [ ] T076 Validate Staff role limitations and reporting access
- [ ] T077 Test Driver approval process and notifications

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies - can start immediately
- **Foundational (Phase 2)**: Depends on Setup completion - BLOCKS all user stories
- **User Stories (Phase 3-6)**: All depend on Foundational phase completion
  - User stories can then proceed in parallel (if staffed)
  - Or sequentially in priority order (P1 → P2)
- **Polish (Final Phase)**: Depends on all desired user stories being complete

### User Story Dependencies

- **User Story 1 (P1)**: Can start after Foundational (Phase 2) - No dependencies on other stories
- **User Story 2 (P1)**: Can start after Foundational (Phase 2) - May integrate with US3 but should be independently testable
- **User Story 3 (P1)**: Can start after Foundational (Phase 2) - May integrate with US2 but should be independently testable
- **User Story 4 (P2)**: Can start after Foundational (Phase 2) - No dependencies on other stories

### Within Each User Story

- Tests (if included) MUST be written and FAIL before implementation
- Models before services
- Services before controllers
- Controllers before views
- Core implementation before integration
- Story complete before moving to next priority

### Parallel Opportunities

- All Setup tasks marked [P] can run in parallel
- All Foundational tasks marked [P] can run in parallel (within Phase 2)
- Once Foundational phase completes, all user stories can start in parallel (if team capacity allows)
- All tests for a user story marked [P] can run in parallel
- Models within a story marked [P] can run in parallel
- Different user stories can be worked on in parallel by different team members

---

## Parallel Example: User Story 1

```bash
# Launch all tests for User Story 1 together (if tests requested):
Task: "Contract test for driver registration API in tests/Feature/DriverRegistrationTest.php"
Task: "Integration test for approval process in tests/Feature/DriverApprovalTest.php"

# Launch all models for User Story 1 together:
Task: "Create DriverApplication model in app/Models/DriverApplication.php"
```

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1: Setup
2. Complete Phase 2: Foundational (CRITICAL - blocks all stories)
3. Complete Phase 3: User Story 1
4. **STOP and VALIDATE**: Test User Story 1 independently
5. Deploy/demo if ready

### Incremental Delivery

1. Complete Setup + Foundational → Foundation ready
2. Add User Story 1 → Test independently → Deploy/Demo (MVP!)
3. Add User Story 2 → Test independently → Deploy/Demo
4. Add User Story 3 → Test independently → Deploy/Demo
5. Each story adds value without breaking previous stories

### Parallel Team Strategy

With multiple developers:

1. Team completes Setup + Foundational together
2. Once Foundational is done:
    - Developer A: User Story 1 (Driver Registration)
    - Developer B: User Story 2 (Route Management)
    - Developer C: User Story 3 (Ticket Booking)
3. Stories complete and integrate independently

---

## Notes

- [P] tasks = different files, no dependencies
- [Story] label maps task to specific user story for traceability
- Each user story should be independently completable and testable
- Verify tests fail before implementing
- Commit after each task or logical group
- Stop at any checkpoint to validate story independently
- Avoid: vague tasks, same file conflicts, cross-story dependencies that break independence
