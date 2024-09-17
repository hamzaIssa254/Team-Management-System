<div align="center">
  <h1>📋 Task Management System</h1>
  <p>An efficient task management system built with <strong>Laravel</strong>, including role-based access control and detailed project-task management.</p>
</div>

---

<div align="center">
  <img src="https://via.placeholder.com/800x300?text=Task+Management+System+Banner" alt="Project Banner" style="width:100%; max-width: 800px;" />
</div>

---

## 🚀 Features

<ul>
    <li>Role-based permission system (Manager, Developer, Tester)</li>
    <li>Task assignment with priority and status management</li>
    <li>Project tracking with task associations</li>
    <li>Custom validation for task and project inputs</li>
    <li>Integration with caching for optimized task queries</li>
</ul>

---

## 🛠️ Installation

```bash
# Clone the repository
git clone https://github.com/hamzaIssa254/Team-Management-System.git

# Navigate into the directory
cd task-management-system

# Install dependencies
composer install

# Setup environment variables
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Start the server
php artisan serve


🧑‍💻 Usage
Creating a Task
POST /api/tasks
{
    "title": "Design new feature",
    "description": "Design the new feature for the upcoming sprint",
    "priority": "high",
    "due_date": "2024-09-30",
    "status": "in_progress",
    "assigned_to": 3,
    "role": "developer"
}

Managing Projects
POST /api/projects
{
    "name": "New Web App",
    "description": "A web app for task management"
}

📚 Documentation
<ul> <li><a href="#routes">API Routes</a></li> <li><a href="#validation">Validation Rules</a></li> <li><a href="#error-handling">Error Handling</a></li> </ul>



📦 API Routes
<table> <thead> <tr> <th>Method</th> <th>Endpoint</th> <th>Description</th> </tr> </thead> <tbody> <tr> <td>POST</td> <td>/api/tasks</td> <td>Create a new task</td> </tr> <tr> <td>GET</td> <td>/api/tasks</td> <td>Get all tasks</td> </tr> <tr> <td>POST</td> <td>/api/projects</td> <td>Create a new project</td> </tr> <tr> <td>GET</td> <td>/api/projects</td> <td>Get all projects</td> </tr> </tbody> </table>

🛡️ Validation Rules
TaskStoreRequest
json
{
    "title": "required|string|max:30",
    "project_id": "required|integer|exists:projects,id",
    "description": "required|string|min:10|max:30",
    "priority": "required|in:low,medium,high",
    "due_date": "required|date",
    "status": "required|in:new,in_progress,done",
    "assigned_to": "required|exists:users,id",
    "role": "required|in:manager,developer,tester"
}


ProjectStoreRequest
json
Copy code
{
    "name": "required|string|min:4",
    "description": "required|string|min:5"
}

📜 License
This project is licensed under the MIT License - see the LICENSE file for details.

### Notes:
- The use of `<div>`, `<ul>`, `<table>`, and other HTML elements is allowed and will be rendered correctly on GitHub.
- You can replace the placeholder image URLs with actual images hosted on your repository or a cloud service.
- Using `<h1>`, `<h2>`, and other semantic HTML tags ensures your markdown has structured headers.

Once you're happy with the design, you can publish it on GitHub and it will render the HTML elements seamlessly.
