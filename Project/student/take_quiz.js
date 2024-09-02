document.addEventListener('DOMContentLoaded', function() {
    const profile = document.querySelector('.profile');
    const profileDropdown = document.getElementById('profileDropdown');
    const studentName = document.getElementById('studentName');
    const logoutButton = document.getElementById('logoutButton');
    const urlParams = new URLSearchParams(window.location.search);
    const quizId = urlParams.get('quiz_id');

    // Fetch student details
    fetch('fetch_student_details.php')
        .then(response => response.json())
        .then(data => {
            studentName.textContent = `${data.first_name} ${data.last_name}`;
            document.getElementById('firstName').textContent = data.first_name;
            document.getElementById('lastName').textContent = data.last_name;
            document.getElementById('indexNo').textContent = data.index_no;
            document.getElementById('dob').textContent = data.date_of_birth;

            // Pre-fill the name and index number fields in the form
            document.getElementById('name').value = `${data.first_name} ${data.last_name}`;
            document.getElementById('indexNo').value = data.index_no;
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

    // Handle quiz form submission
    const quizForm = document.getElementById('quizForm');
    quizForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(quizForm);
        fetch('submit_quiz.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            alert('Quiz submitted successfully!');
            window.location.href = `student_dashboard.html`;
        })
        .catch(error => {
            alert('Error submitting quiz');
            console.error('Error:', error);
        });
    });
});
