document.addEventListener('DOMContentLoaded', function() {
    const currentLocation = location.href;
    const menuItem = document.querySelectorAll('nav ul li a');
    const menuLength = menuItem.length;
    for (let i = 0; i < menuLength; i++) {
        if (menuItem[i].href === currentLocation) {
            menuItem[i].className = "active";
        }
    }

    fetch('fetch_courses.php')
        .then(response => response.json())
        .then(courses => {
            const courseList = document.getElementById('course-list');
            courses.forEach(course => {
                const courseBox = document.createElement('div');
                courseBox.className = 'course-box';
                
                const courseTitle = document.createElement('h3');
                courseTitle.textContent = course.name;
                courseBox.appendChild(courseTitle);
                
                const courseImage = document.createElement('img');
                courseImage.src = `../assets/images/${course.image}`;
                courseImage.alt = course.name;
                courseBox.appendChild(courseImage);
                
                const courseDescription = document.createElement('p');
                courseDescription.textContent = course.description;
                courseBox.appendChild(courseDescription);
                
                const accessButton = document.createElement('a');
                accessButton.href = '../login/login.html';
                accessButton.className = 'access-btn';
                accessButton.textContent = 'Access the Course';
                courseBox.appendChild(accessButton);
                
                courseList.appendChild(courseBox);
            });
        })
        .catch(error => console.error('Error fetching courses:', error));
});
