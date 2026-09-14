# LaraNotes

LaraNotes is an app for creating projects with documents, think of minimal version of `Notion`.

## Database Schema

### Tables:

- `users`: id, name, email, email_verified_at, password, remeber_token and timestamps
- `projects`: id, user_id, title (varchar(255)), description (varchar(1000), nullable) and timestamps
- `pages`: id, project_id, content (text) and timestamps

## Business Rules

- Each user shall only have 1 project
- Each project shall only have 5 pages (documents)
- A user can't see, modify or delete other users projects & pages
- Each project tile shall have minimum 3 characters and maximum 255
- Each project description (which is optional) shall have minimum 3 characters and maximum 100

### Premium Suscrption

- Each premium user shall have unlimited projects
- Each project of a premium user shall have unlimited pages
