// Disclamer dummy data for demonstration purposes 
const users = [
    { id: 1, name: 'Mr. Chrys Karis', role: 'Broker' },
    { id: 2, name: 'Mr. Coleman Franklin', role: 'Agent' },
    { id: 3, name: 'Mrs. Faith de Jane', role: 'Broker' },
    { id: 4, name: 'Mrs. Nellie du Bois', role: 'Agent' },
];

// Escapes HTML to prevent injection attacks 💉 🙏🏾 if Mr. Sampah decide to go that way.
function sanitizeHTML(str) {
    const tempDiv = document.createElement('div');
    tempDiv.textContent = str;
    return tempDiv.innerHTML;
}

function displayUsers() {
    const userList = document.getElementById('user-list');
    
    if (!userList) {
        console.error('User list container not found!');
        return;
    }

    users.forEach(user => {
        const userCard = document.createElement('article'); // Semantic element for user content in the card container element that will be displayed on the screen.
        userCard.className = 'user-card';
        userCard.innerHTML = `
            <p><strong>Name:</strong> ${sanitizeHTML(user.name)}</p>
            <p><strong>Role:</strong> ${sanitizeHTML(user.role)}</p>
        `;
        userList.appendChild(userCard);
    });
}
// Calling the function to display the users list..

document.addEventListener('DOMContentLoaded', displayUsers);