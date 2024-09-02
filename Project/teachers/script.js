document.addEventListener('DOMContentLoaded', function() {
    const currentLocation = location.href;
    const menuItem = document.querySelectorAll('nav ul li a');
    const menuLength = menuItem.length;
    for (let i = 0; i < menuLength; i++) {
        if (menuItem[i].href === currentLocation) {
            menuItem[i].className = "active";
        }
    }

    fetch('fetch_teachers.php')
        .then(response => response.json())
        .then(teachers => {
            const teacherList = document.getElementById('teacher-list');
            teachers.forEach(teacher => {
                const teacherBox = document.createElement('div');
                teacherBox.className = 'teacher-box';
                
                const teacherTitle = document.createElement('h3');
                teacherTitle.textContent = teacher.name;
                teacherBox.appendChild(teacherTitle);
                
                const teacherImage = document.createElement('img');
                teacherImage.src = `../assets/images/${teacher.image}`;
                teacherImage.alt = teacher.name;
                teacherBox.appendChild(teacherImage);
                
                const teacherDescription = document.createElement('p');
                teacherDescription.textContent = teacher.description;
                teacherBox.appendChild(teacherDescription);
                
                const contactButton = document.createElement('a');
                contactButton.href = '../login/login.html';
                contactButton.className = 'contact-btn';
                contactButton.textContent = 'Contact the Teacher';
                teacherBox.appendChild(contactButton);
                
                teacherList.appendChild(teacherBox);
            });

            const searchBox = document.getElementById('search-box');
            searchBox.addEventListener('input', function() {
                const searchTerm = searchBox.value.toLowerCase();
                const filteredTeachers = teachers.filter(teacher => 
                    teacher.name.toLowerCase().includes(searchTerm) || 
                    teacher.description.toLowerCase().includes(searchTerm)
                );
                
                teacherList.innerHTML = '';
                filteredTeachers.forEach(teacher => {
                    const teacherBox = document.createElement('div');
                    teacherBox.className = 'teacher-box';
                    
                    const teacherTitle = document.createElement('h3');
                    teacherTitle.textContent = teacher.name;
                    teacherBox.appendChild(teacherTitle);
                    
                    const teacherImage = document.createElement('img');
                    teacherImage.src = `../assets/images/${teacher.image}`;
                    teacherImage.alt = teacher.name;
                    teacherBox.appendChild(teacherImage);
                    
                    const teacherDescription = document.createElement('p');
                    teacherDescription.textContent = teacher.description;
                    teacherBox.appendChild(teacherDescription);
                    
                    const contactButton = document.createElement('a');
                    contactButton.href = '../login/login.html';
                    contactButton.className = 'contact-btn';
                    contactButton.textContent = 'Contact the Teacher';
                    teacherBox.appendChild(contactButton);
                    
                    teacherList.appendChild(teacherBox);
                });
            });
        })
        .catch(error => console.error('Error fetching teachers:', error));
});
