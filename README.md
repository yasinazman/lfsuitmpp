# 🛡️ UiTM Puncak Perdana Lost & Found System (LFSUiTMPP)
**Developed for IMS566: Advanced Web Design Development and Content Management**

LFSUiTMPP is a specialised web-based management system developed for UiTM Puncak Perdana. It was created to solve the issue of scattered information, where students previously relied on disorganised WhatsApp groups to locate missing items. This system provides a centralised digital hub, allowing students and staff to report, track, and manage lost and found items efficiently in one place.

<p align="center">
  <img src="screenshots/lfsuitmpp_logo.png" alt="Dashboard Preview" width="400">
</p>

## 🚀 Key Features
* **Secure Authentication**: Role-based access for administrators with secure login/logout.
* **Full CRUD Lifecycle**: Comprehensive management of Lost and Found reports (Create, Read, Update, Delete).
* **Dynamic UI/UX**: Professional responsive design featuring a persistent Dark Mode toggle.
* **Automated PDF Export**: Professional report generation for case evidence and documentation.
* **Data Visualisation**: Integrated Chart.js for Monthly and Weekly report analysis.
* **Advanced Filtering**: Quick search and status filtering for efficient record management.

## 🛠️ Tech Stack
* **Backend:** CakePHP Framework (PHP 8.1+)
* **Database:** MySQL
* **Frontend:** HTML5, CSS3 (Custom Variables), JavaScript (ES6)
* **Library:** Chart.js, FontAwesome 6.4.0, Google Fonts (Poppins)

## 📂 Installation & Setup
1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/yasinazman/lfsuitmpp.git](https://github.com/yasinazman/lfsuitmpp.git)
    ```
    *Alternatively, you can simply **Download ZIP** from the repository homepage and extract it.* <br>
    <br>
2.  **Install Dependencies:**
    * Run this command in the project terminal to download required libraries:
    ```bash
    composer install
    ```
    <br>
3.  **Database Setup:**
    * Create a new database named `lfsuitmpp`.
    * Import the SQL schema located at `database/lfsuitmpp.sql`.
    <br>
4.  **Environment Configuration:**
    * Open `config/app_local.php` and configure your database credentials (`username`, `password` & `database`).
    <br>
5.  **Browser Requirement:**
    * For the best experience, use **Google Chrome** as per course guidelines.
    <br>
6.  **Admin Access (Login Credentials):**
    * To access the administrator dashboard, use the default login:
        * **Username:** `yasin`
        * **Password:** `123456`

## 👥 Development Team (CDIM2624B)
| Name | Student ID | Role & Contribution |
| :--- | :--- | :--- |
| **Muhammad Yasin Bin Azman** | 2025198311 | **Project Manager** <br> (Database Design, Authentication & System Integration) |
| **Adi Farhan Bin Mohd Faizal** | 2025198493 | **Lost Items Module Lead** <br> (Lost Report CRUD) |
| **Muhamad Nur Zuhair Bin Asmade** | 2025381093 | **Found Items Module Lead** <br> (Found Report CRUD) |
| **Muhammad Adib Fitri Bin Suhaimi** | 2025197205 | **Admin Dashboard Specialist** <br> (Data Monitoring, Statistics & Reporting Logic) |
| **Ryan Iskandar Shah Bin Mohd Shahril Nizam** | 2025148771 | **Quality Assurance (QA)** <br> (System Testing, Documentation & User Manual) |

---
**Lecturer:** Dr. Muhammad Asyraf Bin Wahi Anuar <br>
**Program:** Bachelor of Information Science (Hons.) Information Systems Management <br>
**Faculty:** Faculty of Information Science, College of Computing, Informatics and Mathematics <br>
**Campus:** UiTM Puncak Perdana