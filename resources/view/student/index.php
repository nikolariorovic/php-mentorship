<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Book Session</title>
    <link rel="stylesheet" href="../../public/css/login.css">
    <link rel="stylesheet" href="../../public/css/student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.3s;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-title">
            <i class="fas fa-user-graduate"></i>
            Student Dashboard
        </div>
        <div class="header-actions">
            <span class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['user']['first_name'] ?? 'Student'); ?>!</span>
            <a href="/appointments" class="btn btn-secondary" style="margin-right: 10px;">
                <i class="fas fa-calendar-check"></i>
                My Bookings
            </a>
            <a href="/logout" class="logout-button">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </div>

    <div class="dashboard-content">
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']); ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error']); ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="booking-container">
            <div class="booking-card">
                <div class="card-header">
                    <h2><i class="fas fa-calendar-plus"></i> Book a Session</h2>
                    <p>Choose a specialization, mentor, and time slot for your session</p>
                </div>

                <div class="booking-form">
                    <!-- Step 1: Choose Specialization -->
                    <div class="form-step" id="step1">
                        <h3>Step 1: Choose Specialization</h3>
                        <div class="form-group">
                            <label for="specialization">Select Specialization:</label>
                            <select id="specialization" name="specialization" required>
                                <option value="">-- Choose Specialization --</option>
                                <?php if (!empty($specializations)): ?>
                                    <?php foreach ($specializations as $specialization): ?>
                                        <option value="<?php echo $specialization->getId(); ?>">
                                            <?php echo htmlspecialchars($specialization->getName()); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="loadMentors()" disabled id="nextStep1">
                            <i class="fas fa-arrow-right"></i> Next: Choose Mentor
                        </button>
                    </div>

                    <!-- Step 2: Choose Mentor -->
                    <div class="form-step" id="step2" style="display: none;">
                        <h3>Step 2: Choose Mentor</h3>
                        <div class="form-group">
                            <label for="mentor">Select Mentor:</label>
                            <select id="mentor" name="mentor" required>
                                <option value="">-- Choose Mentor --</option>
                                <!-- OVDE TREBA DA SE UČITAJU MENTORS IZ BACK-ENDA -->
                            </select>
                        </div>
                        <div class="mentor-info" id="mentorInfo" style="display: none;">
                            <div class="mentor-card">
                                <div class="mentor-avatar">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="mentor-details">
                                    <h4 id="mentorName"></h4>
                                    <p id="mentorBio"></p>
                                    <div class="mentor-price">
                                        <i class="fas fa-dollar-sign"></i>
                                        <span id="mentorPrice"></span> per session
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step-buttons">
                            <button type="button" class="btn btn-secondary" onclick="previousStep(2)">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                            <button type="button" class="btn btn-primary" onclick="proceedToStep3()" disabled id="nextStep2">
                                <i class="fas fa-arrow-right"></i> Next: Choose Time
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Choose Time Slot -->
                    <div class="form-step" id="step3" style="display: none;">
                        <h3>Step 3: Choose Date & Time</h3>
                        <div class="form-group">
                            <label for="date">Select Date:</label>
                            <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="time">Select Time:</label>
                            <select id="time" name="time" required>
                                <option value="">-- Choose Time --</option>
                                <!-- OVDE TREBA DA SE UČITAJU AVAILABLE SLOTS IZ BACK-ENDA -->
                            </select>
                        </div>
                        <div class="step-buttons">
                            <button type="button" class="btn btn-secondary" onclick="previousStep(3)">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                            <button type="button" class="btn btn-success" onclick="bookSession()" disabled id="bookButton">
                                <i class="fas fa-check"></i> Book Session
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../public/js/student.js"></script>
</body>
</html>
