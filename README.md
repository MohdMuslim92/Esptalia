![Esptalia logo](https://github.com/MohdMuslim92/Esptalia/assets/122816330/9b15d1a3-3e31-4c99-8af0-d4d62a44dc73)


# Esptalia

Esptalia is a healthcare platform that revolutionizes Sudan's healthcare system by connecting patients and providers seamlessly. Users can book appointments, access real-time information, and enhance healthcare accessibility through digital innovation.

here is a simple flowchart that demonstrate the process of booking an appointment

![esptalia-flowchart](https://github.com/MohdMuslim92/Esptalia/assets/122816330/dc2180ad-14bb-4ba2-abf3-76d79e826131)


## The Vision: Transforming Sudan's Healthcare

Esptalia emerged from a profound desire to tackle critical healthcare challenges in Sudan. The Sudanese healthcare system, fraught with inefficiencies, long queues, and manual administrative processes, prompted the creation of Esptalia. This digital platform aims to revolutionize healthcare accessibility, empowering both patients and healthcare providers.

## The Personal Drive Behind Esptalia

My personal journey has been deeply intertwined with the motivations behind Esptalia. Living with cataracts years ago exposed me to the complexities of the healthcare system. Endless hospital visits, uncertainty in schedules, and procedural inefficiencies highlighted the need for change. This personal experience fueled the vision for Esptalia: to streamline the healthcare journey, offering patients a system that respects their time and provides swift access to care.

## The Technical Odyssey: Overcoming Vue.js Challenges

One of the most formidable technical challenges during Esptalia's development involved integrating Vue.js into the frontend. The mission was to enable healthcare providers to intuitively update their working days. 

### Situation:
Creating a user-friendly interface for managing schedules was critical, but initially challenging.

### Task:
The primary goal was to devise an efficient way for healthcare providers to mark their working days.

### Action:
After exploring various methods and experiencing setbacks, I developed a solution. Leveraging Vue.js, I transmitted checked working days as a JSON object from the frontend. On the backend, I transformed this into a comma-separated string for seamless database integration. When retrieving data, the process reversed, ensuring a smooth frontend-backend data flow.

### Result:
This successful implementation bridged the gap between Vue.js and the backend, establishing a robust methodology for complex interactions. Esptalia's frontend now empowers healthcare providers, streamlining their schedules effortlessly.

## The Technical Choices: HTML5, CSS3, JavaScript (Vue.js), and Laravel

Esptalia's frontend boasts a combination of HTML5, CSS3, and JavaScript (Vue.js) for its lightweight and responsive interface. Laravel was chosen for the backend due to its robust framework, enabling efficient data handling and API integration through Axios.

## Key Features:

### Appointment System:
Esptalia’s heart lies in its seamless appointment system, providing patients with a user-friendly interface for booking appointments.

### Notification System for Healthcare Providers:
Instant notifications empower healthcare providers to manage schedules effectively, fostering a collaborative healthcare environment.

### Appointment History:
A comprehensive feature allowing users to access past appointments, promoting transparency in healthcare decision-making.

## Links
- [Deployed Site](http://innovateweb.tech)
- [Project Blog Article](https://medium.com/@mohdmuslim92/healthcare-simplified-building-esptalia-from-scratch-7d4146d483eb)
- [Author's LinkedIn](https://www.linkedin.com/in/mohdmuslim92/)

# Installation
To run Esptalia locally, you'll need to have Laravel installed. Follow these steps:
## Prerequisites

Make sure you have the following installed:

- [PHP](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/download/)
- [Node.js and npm](https://nodejs.org/en/download/)
- Database (e.g., [MySQL](https://www.mysql.com/downloads/), PostgreSQL, SQLite)

## Steps

1. **Install Laravel:**

   ```bash
   composer global require laravel/installer
   ```
   
2. **Clone the repository and configure it:**

    ```git clone https://github.com/MohdMuslim92/Esptalia.git
    cd Esptalia
    composer install
    ```

3. **Install Frontend Dependencies and Vue.js:**

    ```bash
    npm install
    ```

4. **Database Setup:**
    * Create a database for your project.
    * Configure .env with database credentials:

   ```bash
    cp .env.example .env
    ```

    and update the .env file with your config
4. **Run migrations:**

    ```bash
    php artisan migrate
    ```

6. **Start Development Server:**

    ```bash
    php artisan serve
    ```

8. **Compile Assets with Laravel Mix:**

    ```bash
    npm run dev
    ```

# Usage
Now head to the localhost and register as a healthcare provider, fill the form and click register then when got redirected to the dashboard click on doctors like below

![Dashboard](https://github.com/MohdMuslim92/Esptalia/assets/122816330/57aee7f3-bf7b-4475-9a79-accb7792c67c)


add a new doctor, enter new doctor details
then head again to the doctors, choose the doctor you added and update it's working days and hours, availability and the rest of the data
you can add other doctors if you wish

now logout from the healthcare provider account and on the main page click on book appointment, search for doctors using the search options, when the search result shows up choose the doctor you wish, choose the appointment date and click on book, after that enter your details and click book appointment and you have successfully booked an appointment
now if you logged in as a healthcare provider you will see notification telling you that a new appointment booked and you can display it and approve or cancel it



# Contributing
Esptalia is an individual project created to address specific challenges in healthcare. While I developed this project solo, I welcome any suggestions, bug reports, or potential improvements from the community. If you have ideas for enhancements or find any issues, please feel free to:
    - Open an issue in this repository to report bugs or propose new features.
    - Fork the repository and create a pull request with your changes for review and potential inclusion.
    - Contact me through [email/LinkedIn/other contact information] to discuss potential collaborations or improvements.
Your contributions, feedback, and ideas are appreciated, and they can potentially help improve Esptalia and benefit its users. Thank you for considering contributing to this project!

# Related projects
[Charity Organization Website](https://basmaorphans.com)
A comprehensive website built using WordPress, HTML, CSS, JavaScript, AJAX, and PHP. The site serves as a system for the organization, aiding in task management and other essential functionalities.

# Licensing
This project does not currently have a specific license as it was developed as part of an online software engineering course at ALX Africa. Please refer to the policies or licensing of [ALX Africa](https://www.alxafrica.com/) for more information on copyright and usage.
