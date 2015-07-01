# social-calendar
Social calendar web app written in PHP
Author: Alex Konetchy
Date: 06-01-2013

This is one of the first dynamic web applications that I ever wrote. The website functioned with a smoother user interface, and allowed users to:
    --Signup via email
    --Signup via facebook
    --Login
    --Save their session
    --Create a custom calendar for their needs
    --Upload images, vines, and text to a calendar
    --Switch between a timeline view of a calendar and a calendar view
    --Edit their profile with custom profile pictures and a short description
    --Share their calenars on social media
    --Add friends and follow other peoples calendars
    --View the most popular posts on the website
    
The user interface was quite simple but allowed for most tasks that users like to be able to use.

The site is written in PHP, HTML, CSS, JavaScript, and utilizes a MySQL database to handle the sites important information. The website utilizes classes to create SQL commands to pull information from the database and return the information in readable and relevant ways. The 'classes' folder handles most of these requests, while the main pages initialize the classes and call their methods.

THINGS THAT COULD BE DONE DIFFERENTLY:

If I were to redo the creation of this site I would try to make the code more dynamic. I think it would be a good idea to use less includes of other files within the pages as this slows down the load times substantially. I also think classes could be condensced and structured in a more dynamic and real-life way. For example the AddComment and the Comment classes could be combined into a single Comment class with AddComment being a method within.

OVERALL

The social calendar web app is a demonstration of what I learned by myself practicing with PHP and MySQL. Thank you for looking at my work!!
