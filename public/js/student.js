// Student Booking System JavaScript

let selectedMentor = null;
let availableSlots = [];

// Initialize the form
document.addEventListener('DOMContentLoaded', function() {
    // Enable/disable buttons based on selections
    document.getElementById('specialization').addEventListener('change', function() {
        const nextButton = document.getElementById('nextStep1');
        nextButton.disabled = !this.value;
    });

    document.getElementById('mentor').addEventListener('change', function() {
        const nextButton = document.getElementById('nextStep2');
        nextButton.disabled = !this.value;
        
        if (this.value) {
            showMentorInfo(this.value);
        } else {
            hideMentorInfo();
        }
    });

    document.getElementById('date').addEventListener('change', function() {
        if (this.value && selectedMentor) {
            loadTimeSlots();
        }
    });

    document.getElementById('time').addEventListener('change', function() {
        const bookButton = document.getElementById('bookButton');
        bookButton.disabled = !this.value;
    });
});

// Load mentors for selected specialization
async function loadMentors() {
    const specializationId = document.getElementById('specialization').value;
    if (!specializationId) return;

    const mentorSelect = document.getElementById('mentor');
    const nextButton = document.getElementById('nextStep2');
    
    // Show loading state
    mentorSelect.innerHTML = '<option value="">Loading mentors...</option>';
    nextButton.disabled = true;
    
    try {
        // Pozivamo backend API
        const response = await fetch(`/getMentorBySpecialization/${specializationId}`);
        const data = await response.json();
        
        if (data.success) {
            mentorSelect.innerHTML = '<option value="">-- Choose Mentor --</option>';
            
            if (data.mentors && data.mentors.length > 0) {
                data.mentors.forEach(mentor => {
                    const option = document.createElement('option');
                    option.value = mentor.id;
                    option.textContent = `${mentor.first_name} ${mentor.last_name}`;
                    mentorSelect.appendChild(option);
                });
                
                // Store mentors data for later use
                window.mentorsData = data.mentors;
            } else {
                mentorSelect.innerHTML = '<option value="">-- No mentors available for this specialization --</option>';
            }
            
            // Show step 2
            showStep(2);
        } else {
            // Proveravamo da li je authentication error
            if (data.message && data.message.includes('not authenticated')) {
                window.location.href = '/';
                return;
            }
            
            showError('Failed to load mentors: ' + (data.message || 'Unknown error'));
            mentorSelect.innerHTML = '<option value="">-- Error loading mentors --</option>';
        }
    } catch (error) {
        console.error('Error loading mentors:', error);
        showError('Failed to load mentors. Please try again.');
        mentorSelect.innerHTML = '<option value="">-- Error loading mentors --</option>';
    }
}

// Show mentor information
function showMentorInfo(mentorId) {
    const mentor = window.mentorsData.find(m => m.id == mentorId);
    if (!mentor) return;

    selectedMentor = mentor;
    
    document.getElementById('mentorName').textContent = `${mentor.first_name} ${mentor.last_name}`;
    document.getElementById('mentorBio').textContent = mentor.biography || 'No biography available';
    document.getElementById('mentorPrice').textContent = `$${parseFloat(mentor.price || 0).toFixed(2)}`;
    
    document.getElementById('mentorInfo').style.display = 'block';
}

// Hide mentor information
function hideMentorInfo() {
    selectedMentor = null;
    document.getElementById('mentorInfo').style.display = 'none';
}

// Load available time slots
async function loadTimeSlots() {
    const mentorId = document.getElementById('mentor').value;
    const date = document.getElementById('date').value;
    
    if (!mentorId || !date) return;

    const timeSelect = document.getElementById('time');
    const bookButton = document.getElementById('bookButton');
    
    // Show loading state
    timeSelect.innerHTML = '<option value="">Loading time slots...</option>';
    bookButton.disabled = true;
    
    try {
        // Pozivamo backend API za dostupne termine
        const response = await fetch(`/getAvailableTimeSlots?mentor_id=${mentorId}&date=${date}`);
        const data = await response.json();
        
        if (data.success) {
            timeSelect.innerHTML = '<option value="">-- Choose Time --</option>';
            availableSlots = data.slots || [];
            
            if (data.slots && data.slots.length > 0) {
                data.slots.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.time;
                    option.textContent = slot.display_time;
                    timeSelect.appendChild(option);
                });
            } else {
                timeSelect.innerHTML = '<option value="">-- No available slots for this date --</option>';
            }
        } else {
            // Proveravamo da li je authentication error
            if (data.message && data.message.includes('not authenticated')) {
                window.location.href = '/';
                return;
            }
            
            showError('Failed to load time slots: ' + (data.message || 'Unknown error'));
            timeSelect.innerHTML = '<option value="">-- Error loading time slots --</option>';
        }
    } catch (error) {
        console.error('Error loading time slots:', error);
        showError('Failed to load time slots. Please try again.');
        timeSelect.innerHTML = '<option value="">-- Error loading time slots --</option>';
    }
}

// Function to proceed to step 3 (date and time selection)
function proceedToStep3() {
    const mentorId = document.getElementById('mentor').value;
    if (!mentorId) {
        showError('Please select a mentor first');
        return;
    }
    
    // Show step 3
    showStep(3);
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date').min = today;
    
    // Set maximum date to 1 month from today
    const maxDate = new Date();
    maxDate.setMonth(maxDate.getMonth() + 1);
    const maxDateString = maxDate.toISOString().split('T')[0];
    document.getElementById('date').max = maxDateString;
}

// Book the session
async function bookSession() {
    const mentorId = document.getElementById('mentor').value;
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    
    if (!mentorId || !date || !time) {
        showError('Please fill in all fields');
        return;
    }

    // Get mentor price from selected mentor data
    const mentor = window.mentorsData.find(m => m.id == mentorId);
    const price = mentor ? mentor.price : 0;

    const bookButton = document.getElementById('bookButton');
    const originalText = bookButton.innerHTML;
    
    // Show loading state
    bookButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Booking...';
    bookButton.disabled = true;
    
    try {
        // Pozivamo backend API za rezervisanje - koristimo tvoju rutu
        const formData = new FormData();
        formData.append('mentor_id', mentorId);
        formData.append('date', date);
        formData.append('time', time);
        formData.append('price', price);
        
        const response = await fetch('/bookAppointment', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess(data.message || 'Appointment booked successfully!');
            // Reset form
            resetForm();
        } else {
            // Proveravamo da li je authentication error
            if (data.message && data.message.includes('not authenticated')) {
                window.location.href = '/';
                return;
            }
            
            showError(data.message || 'Failed to book appointment');
        }
    } catch (error) {
        console.error('Error booking appointment:', error);
        showError('Failed to book appointment. Please try again.');
    } finally {
        // Restore button
        bookButton.innerHTML = originalText;
        bookButton.disabled = false;
    }
}

// Navigate to previous step
function previousStep(currentStep) {
    if (currentStep === 2) {
        showStep(1);
        hideMentorInfo();
        document.getElementById('mentor').value = '';
        document.getElementById('nextStep2').disabled = true;
    } else if (currentStep === 3) {
        showStep(2);
        document.getElementById('date').value = '';
        document.getElementById('time').value = '';
        document.getElementById('bookButton').disabled = true;
    }
}

// Show specific step
function showStep(stepNumber) {
    // Hide all steps
    document.querySelectorAll('.form-step').forEach(step => {
        step.style.display = 'none';
    });
    
    // Show the requested step
    document.getElementById(`step${stepNumber}`).style.display = 'block';
}

// Reset the form
function resetForm() {
    document.getElementById('specialization').value = '';
    document.getElementById('mentor').value = '';
    document.getElementById('date').value = '';
    document.getElementById('time').value = '';
    
    document.getElementById('nextStep1').disabled = true;
    document.getElementById('nextStep2').disabled = true;
    document.getElementById('bookButton').disabled = true;
    
    hideMentorInfo();
    showStep(1);
}

// Show success message
function showSuccess(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success';
    alertDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    
    const container = document.querySelector('.dashboard-content');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Show error message
function showError(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger';
    alertDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    
    const container = document.querySelector('.dashboard-content');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Test function to call the getMentorBySpecialization route
function testGetMentorsBySpecialization(specializationId) {
    console.log('Testing getMentorsBySpecialization for specialization ID:', specializationId);
    
    fetch(`/getMentorBySpecialization/${specializationId}`)
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                console.log('Mentors found:', data.mentors);
                alert(`Success! Found ${data.mentors.length} mentors for specialization ID ${specializationId}`);
                
                // Display mentors in console for debugging
                if (data.mentors.length > 0) {
                    console.table(data.mentors);
                } else {
                    console.log('No mentors found for this specialization');
                }
            } else {
                console.error('Error response:', data.message);
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Network error:', error);
            alert('Network error: ' + error.message);
        });
} 