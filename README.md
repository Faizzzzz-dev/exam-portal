# Online Examination Portal

A comprehensive web-based examination and student management system built with Laravel 11 and Breeze.


## ✨ Features

### Core Features

#### Authentication & Authorization
- Secure user authentication using Laravel Breeze
- Role-based access control (Lecturer and Student roles)
- Protected routes with custom middleware
- Password hashing and security

#### Lecturer Features
- **Class Management**
  - Create, edit, and delete classes
  - Assign students to classes
  - View class details and enrolled students
  - Generate unique class codes

- **Subject Management**
  - Create and manage subjects
  - Link subjects to multiple classes
  - Track subject-class relationships

- **Exam Creation & Management**
  - Create exams with multiple question types
  - Support for Multiple Choice and Open Text questions
  - Set exam duration and time limits
  - Configure passing marks and total marks
  - Schedule exams with start/end times
  - Enable question shuffling
  - Set maximum attempt limits
  - Publish/unpublish exams

- **Question Bank**
  - Add unlimited questions to exams
  - Multiple choice questions with 2+ options
  - Open text questions for essay-type answers
  - Assign marks per question
  - Mark correct answers for auto-grading

- **Results & Analytics**
  - View all exam submissions
  - Auto-grading for multiple choice questions
  - Manual grading interface for open text answers
  - Detailed student performance analytics
  - Export results (future feature)

#### Student Features
- **Dashboard**
  - View available exams
  - Track exam completion status
  - See recent results and scores
  - Resume in-progress exams
  - Performance statistics

- **Exam Taking**
  - Real-time countdown timer
  - Auto-save functionality
  - Progress indicator
  - Auto-submit on time expiration
  - Answer review before submission
  - Prevent accidental page reload

- **Results & Review**
  - View detailed exam results
  - Review correct/incorrect answers
  - See question-by-question breakdown
  - Track attempt history
  - Performance percentage calculation

### Additional Features

#### Security
- CSRF protection on all forms
- SQL injection prevention via Eloquent ORM
- XSS protection
- Role-based route protection
- Secure password hashing

#### User Experience
- Responsive design with Tailwind CSS
- Clean and intuitive interface
- Real-time feedback
- Loading states and transitions
- Error handling and validation

#### System Features
- Automatic time tracking
- Attempt management
- Grade calculation
- Data relationships and integrity
- Scalable architecture

## 🔧 Requirements

- PHP >= 8.2
- Composer
- MySQL >= 5.7 
- Node.js >= 16.x and NPM
- Web server (Apache/Nginx)

## 📦 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Faizzzzz-dev/exam-portal.git
cd exam-portal
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=exam_portal
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed the database with roles and demo data
php artisan db:seed --class=DemoDataSeeder
```

### 6. Build Assets

```bash
npm run build
```

### 7. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 💾 Database Setup

### Database Structure

The application uses the following main tables:

- **users** - User accounts with role assignment
- **roles** - Lecturer and Student roles
- **classes** - Class/group management
- **subjects** - Subject definitions
- **class_subject** - Many-to-many relationship
- **class_user** - Student enrollment
- **exams** - Exam metadata and configuration
- **questions** - Question bank
- **question_options** - Multiple choice options
- **exam_assignments** - Exam-class assignments
- **student_exams** - Exam attempts and results
- **student_answers** - Individual answer records

### Creating Migration Files

All migration files are provided. Run them in order:

```bash
php artisan migrate
```

### Seeding Data

The seeder creates:
- Lecturer and Student roles
- Demo lecturer account
- 5 demo student accounts
- 2 sample classes
- 2 sample subjects
- Student enrollments

```bash
php artisan db:seed
```

## 🚀 Usage

### Access the Application

1. **Homepage**: http://localhost:8000
2. **Login**: http://localhost:8000/login
3. **Register**: http://localhost:8000/register

### For Lecturers

1. **Login** with lecturer credentials
2. **Create Classes** - Navigate to Classes → Create New Class
3. **Create Subjects** - Go to Subjects → Create New Subject
4. **Create Exams**:
   - Go to Exams → Create New Exam
   - Fill in exam details (title, subject, duration, etc.)
   - Add questions (multiple choice or open text)
   - Assign exam to classes
   - Publish when ready
5. **View Results** - Check Results section for student submissions
6. **Grade Open Text Answers** - Manually grade essay-type questions

### For Students

1. **Login** with student credentials
2. **View Available Exams** - See all assigned exams on dashboard
3. **Start Exam**:
   - Click on exam to view details
   - Read instructions
   - Click "Start Exam"
   - Answer questions within time limit
   - Submit when complete
4. **View Results** - Check your scores and review answers


## 🔑 Testing Credentials

### Lecturer Account
- **Email**: lecturer@example.com
- **Password**: password

### Student Accounts
- **Email**: student1@example.com to student5@example.com
- **Password**: password (same for all students)

**Note**: Change these credentials in production!




---

**Developed with ❤️ for YP Technical Assessment**
