# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a static website for Robot Club TO, a robotics club in Toronto. The website serves as:
- An informational landing page about the club
- A membership application system with PHP backend
- A file-based data storage system for applications

## Architecture

### Frontend Structure
- **index.html**: Main landing page with sections for about, members, education, philosophy, events, and contact
- **application.html**: Membership application form with required fields and validation
- **style.css**: Unified stylesheet for all pages with responsive design
- **script.js**: Client-side JavaScript for dynamic logo loading

### Backend Components
- **process_application.php**: PHP script that processes form submissions
  - Validates and sanitizes form data
  - Stores applications in PostgreSQL database
  - Generates HTML response page with submission status
  - Includes conceptual email notification code (commented out)

### Data Storage
- Applications are stored in PostgreSQL database
- Database initialized with `init.sql` script
- Table: `applications` with proper indexing for performance

## Development Commands

### Docker Development (Recommended)
- **Start application**: `docker-compose up -d`
- **Stop application**: `docker-compose down`
- **View logs**: `docker-compose logs -f`
- **Rebuild after changes**: `docker-compose up --build`
- **Access database**: `docker-compose exec db psql -U robotclub -d robotclub`

### Local Development (Alternative)
- Use any local web server (Apache, Nginx, or simple PHP server)
- For PHP testing: `php -S localhost:8000`
- Ensure PHP is available for application form processing
- Requires PostgreSQL database setup

### Deployment
- Docker: Use `docker-compose.yml` with production environment variables
- Traditional: Copy files to web server document root with PostgreSQL database

## Key Technical Details

### Form Processing Flow
1. User submits application via `application.html`
2. Data posted to `process_application.php`
3. PHP script validates required fields and formats
4. Data inserted into PostgreSQL database using prepared statements
5. Success/error status displayed to user

### Security Considerations
- All form data is sanitized using `htmlspecialchars()` and `filter_var()`
- Database queries use prepared statements to prevent SQL injection
- Age confirmation and liability waiver required
- Basic validation prevents empty required fields

### Logo System
- `logo.png` conditionally displayed via JavaScript
- Logo element hidden by default, shown only if image loads successfully
- Responsive design maintains layout with or without logo

## Docker Configuration
- **Dockerfile**: PHP 8.2 Apache with PostgreSQL extensions
- **docker-compose.yml**: Multi-container setup with web and database services
- **init.sql**: Database schema initialization
- **Environment Variables**: Database connection configured via environment

## File Dependencies
- Docker and Docker Compose for containerized development
- PHP 8.2+ with PDO PostgreSQL extension
- PostgreSQL 15+ database
- Logo file (`logo.png`) is optional but referenced in all pages