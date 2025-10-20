add these features to it;

## ⚙️ 1. Equipment Page

**Purpose:** Manage church assets — sound systems, chairs, instruments, etc.

### 🧱 Table: `equipment`

| Field            | Type                       | Description                              |
| ---------------- | -------------------------- | ---------------------------------------- |
| id               | INT                        | Primary key                              |
| name             | VARCHAR(200)               | Equipment name (e.g., “Microphone”)      |
| category         | VARCHAR(100)               | e.g., Audio, Musical, Electrical         |
| quantity         | INT                        | Number available                         |
| condition        | ENUM('Good','Fair','Poor') | Current state                            |
| location         | VARCHAR(150)               | Where it’s kept (store room, hall, etc.) |
| assigned_to      | VARCHAR(150)               | Optional (staff or ministry using it)    |
| purchase_date    | DATE                       | When it was bought                       |
| last_maintenance | DATE                       | Last service or repair date              |
| notes            | TEXT                       | Any remarks                              |
| created_at       | TIMESTAMP                  | Created timestamp                        |

### 🧭 Page features

* List all equipment (sortable by name, category, or condition).
* Add new equipment item (form).
* Edit/update details (especially quantity/condition).
* Record maintenance logs (optional sub-table later).
* Export to Excel or PDF inventory.
* Search & filter by condition/location.

---

## 🙏🏾 2. Prayer Line Page

**Purpose:** Track prayer requests received through the church (hotline, SMS, or form).

### 🧱 Table: `prayer_line`

| Field          | Type         | Description                      |
| -------------- | ------------ | -------------------------------- |
| id             | INT          | Primary key                      |
| requester_name | VARCHAR(200) | Name of person requesting prayer |
| contact        | VARCHAR(50)  | Phone number or email            |
| request        | TEXT         | Details of the prayer need       |
| category       | VARCHAR(100) | Healing, Family, Financial, etc. |
| urgent         | BOOLEAN      | Flag for urgent requests         |
| prayed         | BOOLEAN      | Whether prayer was offered       |
| date_received  | DATETIME     | Request date                     |
| prayed_by      | VARCHAR(150) | Staff who prayed/responded       |
| notes          | TEXT         | Follow-up notes                  |

### 🧭 Page features

* Show all prayer requests (filter by urgent/unprayed).
* Mark request as “Prayed For”.
* Add new requests manually or via public form.
* Follow-up log or comment section.
* Optional: SMS reply integration (“Your prayer request has been received.”).

---

## 👶🏾 3. Children Ministry Page

**Purpose:** Manage children’s records and parent contacts for Sunday school/ministry.

### 🧱 Table: `children_ministry`

| Field         | Type                  | Description               |
| ------------- | --------------------- | ------------------------- |
| id            | INT                   | Primary key               |
| child_name    | VARCHAR(200)          | Full name of the child    |
| dob           | DATE                  | Date of birth             |
| gender        | ENUM('Male','Female') |                           |
| parent_name   | VARCHAR(200)          | Guardian or parent        |
| contact       | VARCHAR(50)           | Parent’s phone            |
| address       | TEXT                  | Residence info            |
| class_group   | VARCHAR(100)          | Nursery, Teens, Pre-teens |
| allergies     | TEXT                  | Health/safety info        |
| notes         | TEXT                  | Remarks from teacher      |
| registered_on | DATE                  | Date registered           |

### 🧭 Page features

* Register a child (with parent info).
* Assign to ministry class.
* Birthday reminders for children.
* Filter by age or group.
* Export list for teachers.
* Attendance integration (optional later).

---

## 💝 4. Welfare Page

**Purpose:** Manage welfare cases, donations, and disbursements.

### 🧱 Table: `welfare`

| Field            | Type                                              | Description                             |
| ---------------- | ------------------------------------------------- | --------------------------------------- |
| id               | INT                                               | Primary key                             |
| member_id        | INT                                               | Linked member (optional)                |
| case_type        | VARCHAR(100)                                      | e.g., Bereavement, Medical Aid, Support |
| description      | TEXT                                              | Details of the welfare need             |
| amount_requested | DECIMAL(12,2)                                     | Requested support                       |
| amount_disbursed | DECIMAL(12,2)                                     | Given amount                            |
| status           | ENUM('Pending','Approved','Completed','Declined') | Case status                             |
| handled_by       | VARCHAR(150)                                      | Staff name or welfare committee         |
| created_at       | TIMESTAMP                                         | Logged date                             |
| notes            | TEXT                                              | Comments or updates                     |

### 🧭 Page features

* View welfare cases with filters by status.
* Add or update welfare requests.
* Track disbursements.
* Export reports for finance/welfare committee.
* Optionally link welfare to `finance` entries.

---

## 🤝🏾 5. Partnership Page

**Purpose:** Manage relationships with partner organizations, sponsors, and donors.

### 🧱 Table: `partnerships`

| Field          | Type                                | Description                          |
| -------------- | ----------------------------------- | ------------------------------------ |
| id             | INT                                 | Primary key                          |
| organization   | VARCHAR(255)                        | Partner name                         |
| contact_person | VARCHAR(150)                        | Key person                           |
| phone          | VARCHAR(50)                         | Contact phone                        |
| email          | VARCHAR(150)                        | Contact email                        |
| start_date     | DATE                                | Partnership start date               |
| end_date       | DATE                                | (Optional) Expiry date               |
| contribution   | VARCHAR(200)                        | e.g., Financial, Equipment, Services |
| terms          | TEXT                                | Terms of partnership                 |
| status         | ENUM('Active','Inactive','Pending') |                                      |
| notes          | TEXT                                |                                      |

### 🧭 Page features

* Add/edit partner organizations.
* Track contributions or donations.
* Attach agreement documents (upload PDF).
* Mark partnership active/inactive.
* Generate partner reports.

---

## 🎥 6. Media Teams Page

**Purpose:** Manage media department volunteers, their roles, and schedules.

### 🧱 Table: `media_teams`

| Field            | Type         | Description                        |
| ---------------- | ------------ | ---------------------------------- |
| id               | INT          | Primary key                        |
| member_id        | INT          | Linked member                      |
| role             | VARCHAR(100) | Camera, Sound, Livestream, Editing |
| availability     | VARCHAR(50)  | e.g., Sundays only                 |
| assigned_program | VARCHAR(200) | Linked service or program          |
| notes            | TEXT         |                                    |

### 🧭 Page features

* Assign church members to media roles.
* View schedules and team structure.
* Record performance notes or attendance.
* Optional: auto-assign for events.

---

## 💬 7. Counselling Page

**Purpose:** Log counselling sessions between church leaders and members/visitors.

### 🧱 Table: `counselling`

| Field           | Type                              | Description                |
| --------------- | --------------------------------- | -------------------------- |
| id              | INT                               | Primary key                |
| counsellor      | VARCHAR(150)                      | Staff/Pastor name          |
| member_id       | INT                               | Optional link to member    |
| visitor_id      | INT                               | Optional link to visitor   |
| session_date    | DATETIME                          | Date/time of counselling   |
| issues          | TEXT                              | Summary of issue discussed |
| outcome         | TEXT                              | Decision or advice given   |
| follow_up_date  | DATE                              | Optional next session      |
| confidentiality | ENUM('Normal','Private','Strict') | Data access level          |
| notes           | TEXT                              |                            |

### 🧭 Page features

* Add new session log.
* View counselling records by member or counsellor.
* Filter by date or confidentiality.
* Mark follow-up dates and reminders.
* Secure access (limit to authorized users).

---

## 🎂 8. Birthday Page

**Purpose:** Display all upcoming birthdays for members (and children).

### 🧱 Table source: use `members.dob` and `children_ministry.dob`

### 🧭 Page features

* Show birthdays for the current month/week.
* Filter by ministry (Adults / Children).
* Highlight “Today’s Birthdays”.
* Export birthday list or print bulletin.
* Optional: send SMS/email “Happy Birthday” automatically (integrate SMS API later).


## 🔐 Access Control Ideas

| Role              | Access                            |
| ----------------- | --------------------------------- |
| Admin             | All pages                         |
| Finance           | Finance + Welfare only            |
| Pastor            | Counselling, Prayer Line, Members |
| Media Lead        | Media Teams + Programs            |
| Welfare Committee | Welfare Page                      |
| Children Teacher  | Children Ministry Page            |

