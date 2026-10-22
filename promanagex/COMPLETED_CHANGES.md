# ProManageX — Completion Notes

This pass completes the project against the SRS (FR-01 to FR-47, FR-S01-S03, FR-E01-E05).
Your original files were kept; only gaps were filled in.

## Database
- `database.sql` — added `phone`, `department` to `users`; added new tables:
  `team_members`, `time_logs`, `performance_records`, `task_comments`.
- `upgrade.sql` — **run this only if you already imported the old database.sql**
  before this update, so you don't lose existing data.

## New / Completed Pages

**Admin**
- `admin/employees.php` — now full Add/Edit/Delete (was view-only). FR-09–FR-12.
- `admin/projects.php` — added Edit. FR-14.
- `admin/tasks.php` — added Edit (status/progress too). 
- `admin/teams.php` — added member assignment (add/remove employees to a team). FR-24–FR-26.
- `admin/performance.php` — new page: live performance table + "Generate Snapshot"
  which stores a row per employee in `performance_records`. FR-38–FR-40.
- `admin/reports.php` — task report now shows hours logged per task (uses `time_logs`).

**Manager** (all scoped to the manager's own projects only)
- `manager/teams.php` — new: create teams + manage members. FR-23–FR-26.
- `manager/files.php` — new: upload/download/delete files for own projects. FR-27–FR-30.
- `manager/notifications.php` — new: send to employees on own projects + view own. FR-31–FR-34.
- `manager/reports.php` — new: project/task/performance reports scoped to own projects. FR-41–FR-44.
- `manager/profile.php` — new: update profile + change password. FR-45–FR-46.

**Employee**
- `employee/files.php` — new: upload files for projects the employee has tasks in. FR-27–FR-30.
- `employee/notifications.php` — new: view + mark-as-read. FR-34.
- `employee/profile.php` — new: update profile + change password. FR-45–FR-46.
- `employee/timelog.php` — new: log hours against assigned tasks. Supplementary FR-S01–FR-S03.

## Sidebar
`includes/sidebar.php` updated with links to all the new pages for each role.

## Optional Financial/Client Features (FR-E01–FR-E05) — now added
- `admin/financials.php` — record project income/expenses, running totals. FR-E01.
- `admin/invoices.php` — create client invoices, update status (Draft/Sent/Paid/Overdue). FR-E02.
- `admin/feedback.php` + public `feedback.php` — admin generates a no-login shareable
  link per project; client opens it and submits a star rating + comment. FR-E03.
- `admin/calendar.php` — project or general calendar events with date/time. FR-E04.
  Read-only views for managers/employees at `manager/announcements.php` and
  `employee/announcements.php` (also show calendar events scoped to their projects).
- `admin/announcements.php` — post announcements targeted to Everyone / Managers / Employees. FR-E05.

New tables: `financial_records`, `client_invoices`, `client_feedback`,
`calendar_events`, `announcements` — all included in `database.sql` and `upgrade.sql`.

## Setup
1. Import `database.sql` fresh (or run `upgrade.sql` on an existing DB).
2. Run `install.php` once to (re)set demo passwords, or use the defaults noted there.
3. Login as `admin@promanagex.com` and explore each sidebar item, or as
   `manager@promanagex.com` / `employee@promanagex.com`.
