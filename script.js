// JavaScript code for the Robotics Club website
// This file can be used to add interactivity and dynamic features

console.log("script.js loaded");

document.addEventListener('DOMContentLoaded', function() {
    const logo = document.getElementById('club-logo');
    if (logo) {
        const img = new Image();
        // Check if the logo element has a src attribute before assigning it
        if (logo.src) {
            img.src = logo.src;
            img.onload = function() {
                logo.style.display = 'inline'; // Or 'block' depending on layout needs
                // Adjust header h1 margin if logo is displayed to prevent overlap or ensure spacing
                const headerTitle = document.querySelector('header h1');
                if (headerTitle) {
                    // This assumes the logo is to the left of the title.
                    // If the logo can be very wide, this might need more dynamic calculation
                    // or the CSS handles potential overlap adequately with flex wrap.
                    // For now, the existing CSS margin-left on h1 should work when logo appears.
                }
            };
            img.onerror = function() {
                logo.style.display = 'none';
                console.log('logo.png not found or failed to load, logo will not be displayed.');
                // Optionally, adjust layout if logo fails to load, e.g., remove margin from h1
                const headerTitle = document.querySelector('header h1');
                if (headerTitle) {
                    // If logo fails, h1 might not need the left margin anymore.
                    // However, the CSS for h1 has margin-left: 15px which might be desired
                    // even without the logo for general spacing from the edge of the header.
                    // For now, we'll leave it to CSS. A more complex scenario might clear it:
                    // headerTitle.style.marginLeft = '0';
                }
            };
        } else {
            console.log('Logo element does not have a src attribute.');
            logo.style.display = 'none';
        }
    } else {
        console.log('Logo element with ID "club-logo" not found.');
    }
});
