document.addEventListener('DOMContentLoaded', function() {
    const profile = document.querySelector('.profile');
    const profileDropdown = document.getElementById('profileDropdown');
    const studentName = document.getElementById('studentName');
    const logoutButton = document.getElementById('logoutButton');

    // Fetch student details
    fetch('fetch_student_details.php')
        .then(response => response.json())
        .then(data => {
            studentName.textContent = `${data.first_name} ${data.last_name}`;
            document.getElementById('firstName').textContent = data.first_name;
            document.getElementById('lastName').textContent = data.last_name;
            document.getElementById('indexNo').textContent = data.index_no;
            document.getElementById('dob').textContent = data.date_of_birth;
        });

    // Fetch courses
    fetch('fetch_courses.php')
        .then(response => response.json())
        .then(courses => {
            const coursesList = document.getElementById('coursesList');
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
                
                const viewButton = document.createElement('a');
                viewButton.href = `view_course.html?course_id=${course.id}`;
                viewButton.textContent = 'View Materials';
                courseBox.appendChild(viewButton);
                
                coursesList.appendChild(courseBox);
            });
        });

    profile.addEventListener('click', () => {
        profileDropdown.classList.toggle('show');
    });

    // Handle logout button click
    logoutButton.addEventListener('click', function() {
        fetch('logout.php')
            .then(() => {
                window.location.href = '../login/login.html';
            })
            .catch(error => console.error('Error:', error));
    });
});
