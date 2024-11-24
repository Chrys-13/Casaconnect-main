// Dummy data for demonstration purposes
const estates = [
    { id: 1, name: 'Sunset Villa', location: 'New York', price: '$500,000' },
    { id: 2, name: 'Ocean Breeze Apartment', location: 'Miami', price: '$300,000' },
    { id: 3, name: 'Mountain Retreat', location: 'Denver', price: '$700,000' }
];

function displayEstates() {
    const estateList = document.getElementById('estate-list');
    estates.forEach(estate => {
        const estateCard = document.createElement('div');
        estateCard.className = 'estate-card';
        estateCard.innerHTML = `
            <p><strong>Name:</strong> ${estate.name}</p>
            <p><strong>Location:</strong> ${estate.location}</p>
            <p><strong>Price:</strong> ${estate.price}</p>
        `;
        estateList.appendChild(estateCard);
    });
}

document.addEventListener('DOMContentLoaded', displayEstates);
