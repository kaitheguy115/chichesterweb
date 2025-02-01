let users = [];

function loadUserData() {
    return fetch('user_data.json')
        .then(response => response.json())
        .then(data => {
            users = data.users;
        })
        .catch(error => console.error('Error loading user data:', error));
}

function authenticateUser(username, password) {
    return new Promise((resolve, reject) => {
        const user = users.find(u => u.username === username && u.password === password);
        if (user) {
            resolve({ success: true, isSLT: user.isSLT });
        } else {
            resolve({ success: false });
        }
    });
}

// Load user data and then attach authenticateUser to window
loadUserData().then(() => {
    window.authenticateUser = authenticateUser;
}).catch(error => {
    console.error('Failed to load user data:', error);
});
