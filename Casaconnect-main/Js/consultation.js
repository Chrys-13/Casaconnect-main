$(document).ready(function () {
    const slotsTable = $("#available-slots tbody");

    // Show loading spinner while fetching slots
    slotsTable.html("<tr><td colspan='3'>Loading available slots...</td></tr>");

    // Fetch available slots for consultation
    $.getJSON("../actions/get_slots.php?type=consultation", function (data) {
        slotsTable.empty(); // Clear previous rows

        if (data.length > 0) {
            data.forEach(slot => {
                slotsTable.append(`
                    <tr>
                        <td>${slot.date}</td>
                        <td>${slot.time}</td>
                        <td>
                            <button onclick="bookSlot(${slot.id})">Book</button>
                        </td>
                    </tr>
                `);
            });
        } else {
            slotsTable.append("<tr><td colspan='3'>No slots available</td></tr>");
        }
    }).fail(function () {
        slotsTable.html("<tr><td colspan='3'>Error loading slots. Please try again.</td></tr>");
    });

    // Book a slot
    window.bookSlot = function (slotId) {
        const userId = 1; // Replace with session user ID if logged in

        $.post("../actions/book_slot.php", { slot_id: slotId, user_id: userId }, function (response) {
            alert(response);
            location.reload(); // Refresh the page to show updated slots
        }).fail(function () {
            alert("Error booking slot. Please try again.");
        });
    };
});
