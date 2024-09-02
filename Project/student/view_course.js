document.addEventListener('DOMContentLoaded', function() {
    const profile = document.querySelector('.profile');
    const profileDropdown = document.getElementById('profileDropdown');
    const studentName = document.getElementById('studentName');
    const logoutButton = document.getElementById('logoutButton');
    const urlParams = new URLSearchParams(window.location.search);
    const courseId = urlParams.get('course_id');

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

    // Fetch materials for the course
    fetch(`fetch_materials.php?course_id=${courseId}`)
        .then(response => response.json())
        .then(materials => {
            const materialsList = document.getElementById('materialsList');
            materials.forEach(material => {
                const materialBox = document.createElement('div');
                materialBox.className = 'material-box';
                
                const materialType = document.createElement('p');
                materialType.textContent = `Type: ${material.type}`;
                materialBox.appendChild(materialType);
                
                const materialLink = document.createElement('a');
                materialLink.href = `../uploads/${material.file_path}`;
                materialLink.textContent = 'View Material';
                materialLink.target = '_blank';
                materialBox.appendChild(materialLink);
                
                materialsList.appendChild(materialBox);
            });
        });

    // Fetch quizzes for the course
    fetch(`fetch_quizzes.php?course_id=${courseId}`)
        .then(response => response.json())
        .then(quizzes => {
            const quizzesList = document.getElementById('quizzesList');
            quizzes.forEach(quiz => {
                const quizBox = document.createElement('div');
                quizBox.className = 'quiz-box';
                
                const quizName = document.createElement('h3');
                quizName.textContent = quiz.quiz_name;
                quizBox.appendChild(quizName);
                
                const quizDescription = document.createElement('p');
                quizDescription.textContent = quiz.quiz_description;
                quizBox.appendChild(quizDescription);
                
                const takeQuizButton = document.createElement('a');
                takeQuizButton.href = `take_quiz.html?quiz_id=${quiz.id}`;
                takeQuizButton.textContent = 'Take Quiz';
                quizBox.appendChild(takeQuizButton);
                
                quizzesList.appendChild(quizBox);
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
