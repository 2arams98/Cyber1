document.addEventListener('DOMContentLoaded', function() {
    const profile = document.querySelector('.profile');
    const profileDropdown = document.getElementById('profileDropdown');
    const adminName = document.getElementById('adminName');
    const logoutButton = document.getElementById('logoutButton');

    // Fetch admin details
    fetch('fetch_admin_details.php')
        .then(response => response.json())
        .then(data => {
            adminName.textContent = `${data.first_name} ${data.last_name}`;
            document.getElementById('firstName').textContent = data.first_name;
            document.getElementById('lastName').textContent = data.last_name;
            document.getElementById('indexNo').textContent = data.index_no;
            document.getElementById('dob').textContent = data.date_of_birth;
        });

    // Fetch courses
    fetch('../courses/fetch_courses.php')
        .then(response => response.json())
        .then(courses => {
            const courseSelect = document.getElementById('course');
            const quizCourseSelect = document.getElementById('quizCourse');
            courses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.id;
                option.textContent = course.name;
                courseSelect.appendChild(option);

                const quizOption = option.cloneNode(true);
                quizCourseSelect.appendChild(quizOption);
            });
        });

    // Fetch uploaded materials
    fetchMaterials();

    profile.addEventListener('click', () => {
        profileDropdown.classList.toggle('show');
    });

    // Handle material upload form submission using AJAX
    const uploadForm = document.getElementById('uploadForm');
    uploadForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(uploadForm);
        fetch('upload_material.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('message').textContent = result;
            fetchMaterials();  // Refresh the materials list
        })
        .catch(error => {
            document.getElementById('message').textContent = 'Error uploading material';
            console.error('Error:', error);
        });
    });

    // Handle quiz creation form submission using AJAX
    const createQuizForm = document.getElementById('createQuizForm');
    createQuizForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(createQuizForm);
        fetch('create_quiz.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('quizMessage').textContent = result;
        })
        .catch(error => {
            document.getElementById('quizMessage').textContent = 'Error creating quiz';
            console.error('Error:', error);
        });
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

function fetchMaterials() {
    fetch('fetch_materials.php')
        .then(response => response.json())
        .then(materials => {
            const materialsList = document.getElementById('materialsList');
            materialsList.innerHTML = '';  // Clear the existing list
            materials.forEach(material => {
                const materialBox = document.createElement('div');
                materialBox.className = 'material-box';
                materialBox.innerHTML = `
                    <h3>${material.course_name}</h3>
                    <p>Type: ${material.type}</p>
                    <a href="../uploads/${material.file_path}" target="_blank">View Material</a>
                    <button onclick="deleteMaterial(${material.id})">Delete</button>
                `;
                materialsList.appendChild(materialBox);
            });
        });
}

function deleteMaterial(materialId) {
    if (confirm('Are you sure you want to delete this material?')) {
        fetch(`delete_material.php`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${materialId}`
        })
        .then(response => response.text())
        .then(result => {
            console.log(result);  // Log the response for debugging
            if (result === 'success') {
                fetchMaterials();  // Refresh the materials list
            } else {
                alert('Error deleting material: ' + result);  // Show detailed error message
            }
        })
        .catch(error => {
            alert('Error deleting material');
            console.error('Error:', error);  // Log the error for debugging
        });
    }
}
