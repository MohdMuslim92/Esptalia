<img src="[link_to_your_logo_image](https://drive.google.com/file/d/1IlrhYWSIkJpRLAqtTM3qiKXWg3F5BWcm/view?usp=sharing)" alt="Your Site Logo" width="100%">
# Esptalia
Esptalia is a healthcare platform that revolutionizes Sudan's healthcare system by connecting patients and providers seamlessly. Users can book appointments, access real-time information, and enhance healthcare accessibility through digital innovation.
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

[Dashboard]([link_to_your_logo_image](https://drive.google.com/file/d/1sEhC5BJPCsJPW-HIjAYFomZNn7jaMJqq/view?usp=sharing))

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
