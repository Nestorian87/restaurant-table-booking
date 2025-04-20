# TablyTime – Restaurant Booking System

TablyTime is a modular restaurant booking platform developed as part of a university project for the *Scripting Languages* course. It includes a frontend built with Laravel Livewire and a backend composed of multiple Laravel-based microservices.

<img width="1335" alt="image" src="https://github.com/user-attachments/assets/5a5466a1-e19b-4b08-aeee-a33ddf536e78" />

---

## Features

- Backend and frontend separation
- Multilingual support (Ukrainian & English)
- User authentication with role-based access control (RBAC)
- User profile editing
- Laravel Blade templating
- Full microservice backend architecture
- API Gateway between services and frontend
- Frontend built with Livewire components and reactive updates
- Modern, intuitive UI for real restaurant booking workflows

---

## Architecture Overview


Each service uses its own database. Services communicate via API Gateway and RabbitMQ for async events.

![Microservice Architecture Diagram (4)](https://github.com/user-attachments/assets/36003284-f6e6-4648-9ed3-59378093eb8e)


---

## Tech Stack

| Layer             | Technology                     |
|------------------|--------------------------------|
| Frontend         | Laravel Livewire + Blade       |
| API Gateway      | NGINX                |
| Backend Services | Laravel (PHP)                  |
| Messaging Queue  | RabbitMQ                       |
| Databases        | SQlite per service |

---

## Requirements Covered

| Requirement                                                                 | Implemented |
|------------------------------------------------------------------------------|-------------|
| System consists of backend and frontend parts                                | Yes         |
| Multilingual support (Ukrainian / English)                                   | Yes         |
| Authentication with multiple access levels (RBAC)                            | Yes         |
| User profile editing                                                         | Yes         |
| Use of template engine (Laravel Blade)                                       | Yes         |
| Framework-based development (Laravel used throughout)                        | Yes         |
| Backend follows microservice architecture                                    | Yes         |
| API Gateway mediates between services and frontend                           | Yes         |
| Frontend is modular, reactive and state-preserving                           | Yes         |
| UI reflects project theme and real-world use                                 | Yes         |
